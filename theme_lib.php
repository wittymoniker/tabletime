<?php
/**
 * Tabletime user theme helpers.
 *
 * The historical format is kept intact for compatibility:
 * 18 palette colours ; text colour ; font size
 */

function tt_theme_defaults(): array {
    return [
        '#ababab', '#bcbcbc', '#cdcdcd', '#dcdcdc', '#ededed', '#dfdfdf',
        '#0a0a0a', '#1b1b1b', '#2c2c2c', '#3d3d3d', '#4e4e4e', '#5f5f5f',
        '#a3a3a3', '#b2b2b2', '#c1c1c1', '#d1d1d1', '#e2e2e2', '#f3f3f3',
        '#000000', '14'
    ];
}

function tt_theme_hex($value, string $fallback): string {
    $value = strtolower(trim((string)$value));
    return preg_match('/^#[0-9a-f]{6}$/', $value) ? $value : $fallback;
}

function tt_theme_parse(?string $stored): array {
    $defaults = tt_theme_defaults();
    if ($stored === null || trim($stored) === '') return $defaults;

    $parts = explode(';', $stored);
    $theme = $defaults;
    for ($i = 0; $i < 19; $i++) {
        if (isset($parts[$i])) $theme[$i] = tt_theme_hex($parts[$i], $defaults[$i]);
    }
    if (isset($parts[19])) {
        $size = (int)$parts[19];
        $theme[19] = (string)max(3, min(36, $size ?: (int)$defaults[19]));
    }
    return $theme;
}

function tt_theme_from_post(array $post): array {
    $defaults = tt_theme_defaults();
    $names = [
        'style1a','style1b','style1c','style2a','style2b','style2c',
        'style3a','style3b','style3c','style4a','style4b','style4c',
        'style5a','style5b','style5c','style6a','style6b','style6c'
    ];
    $theme = $defaults;
    foreach ($names as $i => $name) {
        $theme[$i] = tt_theme_hex($post[$name] ?? '', $defaults[$i]);
    }
    $theme[18] = tt_theme_hex($post['styletext'] ?? '', $defaults[18]);
    $size = (int)($post['stylesize'] ?? $defaults[19]);
    $theme[19] = (string)max(3, min(36, $size));
    return $theme;
}

function tt_theme_serialize(array $theme): string {
    return implode(';', tt_theme_parse(implode(';', $theme)));
}

function tt_theme_load(mysqli $con, int $userId): array {
    $stored = '';
    $stmt = $con->prepare('SELECT colors FROM accounts WHERE id = ? LIMIT 1');
    if (!$stmt) return tt_theme_defaults();
    $stmt->bind_param('i', $userId);
    if ($stmt->execute()) {
        $stmt->bind_result($stored);
        $stmt->fetch();
    }
    $stmt->close();
    return tt_theme_parse($stored);
}

function tt_theme_save(mysqli $con, int $userId, array $theme): bool {
    $stored = tt_theme_serialize($theme);
    $stmt = $con->prepare('UPDATE accounts SET colors = ? WHERE id = ?');
    if (!$stmt) return false;
    $stmt->bind_param('si', $stored, $userId);
    $ok = $stmt->execute();
    $stmt->close();
    return $ok;
}

function tt_theme_legacy_vars(array $theme): array {
    $theme = tt_theme_parse(implode(';', $theme));
    return [
        'colora'=>$theme[0], 'colorb'=>$theme[1], 'colorc'=>$theme[2],
        'colord'=>$theme[3], 'colore'=>$theme[4], 'colorf'=>$theme[5],
        'colora2'=>$theme[6], 'colorb2'=>$theme[7], 'colorc2'=>$theme[8],
        'colord2'=>$theme[9], 'colore2'=>$theme[10], 'colorf2'=>$theme[11],
        'colora3'=>$theme[12], 'colorb3'=>$theme[13], 'colorc3'=>$theme[14],
        'colord3'=>$theme[15], 'colore3'=>$theme[16], 'colorf3'=>$theme[17],
        'colort'=>$theme[18], 'fontSize'=>$theme[19],
    ];
}
