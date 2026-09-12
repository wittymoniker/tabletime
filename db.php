<?php
// PHP 8.1+ enables mysqli strict exceptions by default. Tabletime's legacy
// code expects connection/query failures to be returned and handled, not thrown
// as uncaught exceptions (which become generic HTTP 500 responses on Wasmer).
if (function_exists('mysqli_report')) { @mysqli_report(MYSQLI_REPORT_OFF); }

/**
 * Tabletime database bootstrap.
 *
 * Canonical Wasmer Edge managed-database variables:
 * DB_HOST, DB_PORT, DB_NAME, DB_USERNAME, DB_PASSWORD.
 *
 * This bootstrap also accepts DATABASE_URL / MYSQL_URL and several common
 * MYSQL_* aliases so Tabletime can be moved between independent hosts.
 */

function tt_env_first(array $names, $default = '') {
    foreach ($names as $name) {
        $v = getenv($name);
        if ($v !== false && $v !== '') return $v;
    }
    return $default;
}

function tt_parse_mysql_url(string $url): ?array {
    if ($url === '') return null;
    $p = @parse_url($url);
    if (!is_array($p) || empty($p['host'])) return null;
    $scheme = strtolower((string)($p['scheme'] ?? ''));
    if ($scheme !== '' && !in_array($scheme, ['mysql', 'mariadb'], true)) return null;
    $name = ltrim((string)($p['path'] ?? ''), '/');
    if ($name === '') return null;
    return [
        'host' => (string)$p['host'],
        'port' => (int)($p['port'] ?? 3306),
        'name' => urldecode($name),
        'user' => urldecode((string)($p['user'] ?? '')),
        'pass' => urldecode((string)($p['pass'] ?? '')),
        'source' => 'connection_url',
    ];
}

$ttDb = null;

// Prefer Wasmer's documented managed-database environment variables.
$envHost = (string)tt_env_first(['DB_HOST']);
$envPort = (string)tt_env_first(['DB_PORT']);
$envName = (string)tt_env_first(['DB_NAME']);
$envUser = (string)tt_env_first(['DB_USERNAME']);
$envPass = getenv('DB_PASSWORD');
if ($envHost !== '' && $envName !== '' && $envUser !== '') {
    $ttDb = [
        'host' => $envHost,
        'port' => (int)($envPort !== '' ? $envPort : 3306),
        'name' => $envName,
        'user' => $envUser,
        'pass' => $envPass !== false ? (string)$envPass : '',
        'source' => 'wasmer_db_env',
    ];
}

// Some DB providers / importers expose one connection URL instead.
if ($ttDb === null) {
    $url = (string)tt_env_first(['DATABASE_URL', 'MYSQL_URL', 'MYSQL_DATABASE_URL']);
    $ttDb = tt_parse_mysql_url($url);
}

// Portable aliases used by several MySQL hosting platforms.
if ($ttDb === null) {
    $host = (string)tt_env_first(['MYSQL_HOST', 'MYSQLHOST', 'DATABASE_HOST']);
    $port = (string)tt_env_first(['MYSQL_PORT', 'MYSQLPORT', 'DATABASE_PORT']);
    $name = (string)tt_env_first(['MYSQL_DATABASE', 'MYSQLDATABASE', 'DATABASE_NAME']);
    $user = (string)tt_env_first(['MYSQL_USER', 'MYSQLUSER', 'DATABASE_USER']);
    $pass = tt_env_first(['MYSQL_PASSWORD', 'MYSQLPASSWORD', 'DATABASE_PASSWORD'], '');
    if ($host !== '' && $name !== '' && $user !== '') {
        $ttDb = [
            'host' => $host,
            'port' => (int)($port !== '' ? $port : 3306),
            'name' => $name,
            'user' => $user,
            'pass' => (string)$pass,
            'source' => 'portable_mysql_env',
        ];
    }
}

// Local/shared-host configuration remains available outside Wasmer.
if ($ttDb === null) {
    $configFile = __DIR__ . '/config/db_config.php';
    if (is_file($configFile)) {
        $config = require $configFile;
        if (is_array($config)) {
            $host = (string)($config['host'] ?? '');
            $name = (string)($config['name'] ?? '');
            $user = (string)($config['user'] ?? '');
            if ($host !== '' && $name !== '' && $user !== '') {
                $ttDb = [
                    'host' => $host,
                    'port' => (int)($config['port'] ?? 3306),
                    'name' => $name,
                    'user' => $user,
                    'pass' => (string)($config['pass'] ?? ''),
                    'source' => 'local_config',
                ];
            }
        }
    }
}

$TT_DB_CONFIGURED = is_array($ttDb);
$TT_DB_SOURCE = $TT_DB_CONFIGURED ? (string)$ttDb['source'] : 'none';

if ($TT_DB_CONFIGURED) {
    $DATABASE_HOST = (string)$ttDb['host'];
    $DATABASE_PORT = (int)$ttDb['port'];
    $DATABASE_USER = (string)$ttDb['user'];
    $DATABASE_PASS = (string)$ttDb['pass'];
    $DATABASE_NAME = (string)$ttDb['name'];

    // Existing Tabletime scripts pass four mysqli constructor parameters.
    // Preserve compatibility by encoding a non-default port in the host.
    if ($DATABASE_PORT > 0 && $DATABASE_PORT !== 3306 && strpos($DATABASE_HOST, ':') === false) {
        $DATABASE_HOST .= ':' . $DATABASE_PORT;
    }
} else {
    $DATABASE_HOST = '';
    $DATABASE_PORT = 3306;
    $DATABASE_USER = '';
    $DATABASE_PASS = '';
    $DATABASE_NAME = '';

    // setup.php opts out so it can render useful diagnostics instead of redirecting.
    if (!defined('TT_DB_ALLOW_UNCONFIGURED') || TT_DB_ALLOW_UNCONFIGURED !== true) {
        // Never leave visitors at a dead-end error page. Send them to the installer.
        if (!headers_sent()) {
            header('Location: /tabletime/setup.php?reason=db_unconfigured', true, 302);
            exit;
        }
        http_response_code(503);
        exit('Tabletime needs database setup: /tabletime/setup.php');
    }
}
