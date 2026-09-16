<?php
/** Tabletime TURN / ICE configuration.
 * Supports:
 *   1) Twilio Network Traversal Service ephemeral credentials (recommended)
 *   2) Static/generic TURN credentials
 *   3) STUN-only fallback
 */

function tt_turn_env(string $name): string {
    $v = getenv($name);
    return $v === false ? '' : trim((string)$v);
}

function tt_turn_filter_urls(array $urls): array {
    $out = [];
    foreach ($urls as $u) {
        $u = trim((string)$u);
        if ($u === '') continue;
        if (!preg_match('~^(?:stun|stuns|turn|turns):~i', $u)) continue;
        if (!in_array($u, $out, true)) $out[] = $u;
    }
    return $out;
}

function tt_turn_static_servers(): array {
    $raw = tt_turn_env('TT_TURN_URLS');
    if ($raw === '') $raw = tt_turn_env('TT_TURN_URL');
    if ($raw === '') return [];
    $urls = tt_turn_filter_urls(preg_split('/[\r\n,;]+/', $raw) ?: []);
    if (!$urls) return [];
    $entry = ['urls' => count($urls) === 1 ? $urls[0] : $urls];
    $user = tt_turn_env('TT_TURN_USERNAME');
    $cred = tt_turn_env('TT_TURN_CREDENTIAL');
    if ($user !== '') $entry['username'] = $user;
    if ($cred !== '') $entry['credential'] = $cred;
    return [$entry];
}

function tt_turn_http_post_basic(string $url, string $user, string $password, array $fields, ?string &$error = null): ?array {
    $body = http_build_query($fields, '', '&');
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERPWD => $user . ':' . $password,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'],
        ]);
        $raw = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        if ($raw === false) {
            $error = 'TURN provider request failed.';
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        if ($code < 200 || $code >= 300) {
            $error = 'TURN provider returned HTTP ' . $code . '.';
            return null;
        }
    } else {
        $ctx = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => "Accept: application/json\r\nContent-Type: application/x-www-form-urlencoded\r\nAuthorization: Basic " . base64_encode($user . ':' . $password) . "\r\n",
            'content' => $body,
            'timeout' => 10,
            'ignore_errors' => true,
        ]]);
        $raw = @file_get_contents($url, false, $ctx);
        if ($raw === false) {
            $error = 'TURN provider request failed.';
            return null;
        }
        $status = 0;
        foreach (($http_response_header ?? []) as $line) {
            if (preg_match('~^HTTP/\S+\s+(\d{3})~', $line, $m)) { $status = (int)$m[1]; break; }
        }
        if ($status && ($status < 200 || $status >= 300)) {
            $error = 'TURN provider returned HTTP ' . $status . '.';
            return null;
        }
    }
    $json = json_decode((string)$raw, true);
    if (!is_array($json)) {
        $error = 'TURN provider returned invalid JSON.';
        return null;
    }
    return $json;
}

function tt_turn_twilio_servers(?string &$error = null): array {
    $sid = tt_turn_env('TWILIO_ACCOUNT_SID');
    $token = tt_turn_env('TWILIO_AUTH_TOKEN');
    if ($sid === '' || $token === '') return [];

    $ttl = (int)(tt_turn_env('TT_TURN_TOKEN_TTL') ?: '3600');
    $ttl = max(60, min(86400, $ttl));

    if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['tt_turn_twilio_cache']) && is_array($_SESSION['tt_turn_twilio_cache'])) {
        $c = $_SESSION['tt_turn_twilio_cache'];
        if ((int)($c['expires_at'] ?? 0) > time() + 300 && isset($c['servers']) && is_array($c['servers'])) {
            return $c['servers'];
        }
    }

    $url = 'https://api.twilio.com/2010-04-01/Accounts/' . rawurlencode($sid) . '/Tokens.json';
    $json = tt_turn_http_post_basic($url, $sid, $token, ['Ttl' => $ttl], $error);
    if (!$json) return [];
    $servers = $json['ice_servers'] ?? $json['iceServers'] ?? [];
    if (!is_array($servers)) {
        $error = 'TURN provider did not return ICE servers.';
        return [];
    }
    $clean = [];
    foreach ($servers as $server) {
        if (!is_array($server)) continue;
        $urls = $server['urls'] ?? $server['url'] ?? null;
        $list = is_array($urls) ? $urls : [$urls];
        $list = tt_turn_filter_urls($list);
        if (!$list) continue;
        $entry = ['urls' => count($list) === 1 ? $list[0] : $list];
        if (isset($server['username'])) $entry['username'] = (string)$server['username'];
        if (isset($server['credential'])) $entry['credential'] = (string)$server['credential'];
        $clean[] = $entry;
    }
    if ($clean && session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION['tt_turn_twilio_cache'] = ['expires_at' => time() + $ttl, 'servers' => $clean];
    }
    return $clean;
}

function tt_turn_ice_config(): array {
    $base = [['urls' => 'stun:stun.l.google.com:19302']];
    $provider = strtolower(tt_turn_env('TT_TURN_PROVIDER'));
    $error = null;

    if ($provider === 'twilio' || ($provider === '' && tt_turn_env('TWILIO_ACCOUNT_SID') !== '' && tt_turn_env('TWILIO_AUTH_TOKEN') !== '')) {
        $twilio = tt_turn_twilio_servers($error);
        if ($twilio) return ['servers' => $twilio, 'turn_configured' => true, 'source' => 'twilio', 'error' => null];
    }

    $static = tt_turn_static_servers();
    if ($static) return ['servers' => array_merge($base, $static), 'turn_configured' => true, 'source' => 'static', 'error' => $error];

    return ['servers' => $base, 'turn_configured' => false, 'source' => 'stun-only', 'error' => $error];
}
