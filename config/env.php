<?php
/**
 * Environment detection for StageTunisia.
 * Supports local (XAMPP) and production (AlwaysData / InfinityFree / 000webhost).
 *
 * LOCAL  : http://localhost/dv/         → BASE_PATH = /dv/
 * PROD   : https://username.alwaysdata.net/ → BASE_PATH = /
 */

// Auto-detect environment
define('IS_LOCAL', strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false);

if (IS_LOCAL) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $scriptDir = trim($scriptDir, '/');
    if ($scriptDir === '') {
        $basePath = '/';
    } else {
        $parts = explode('/', $scriptDir);
        $basePath = '/' . $parts[0] . '/';
    }
    define('BASE_PATH', $basePath);

    define('DB_HOST', 'localhost');
    define('DB_NAME', 'stage_portal');
    define('DB_USER', 'root');
    define('DB_PASS', '');
} else {
    define('BASE_PATH', '/');
    // ==== CHANGE THESE for your production host ====
    define('DB_HOST', 'mysql-username.alwaysdata.net');
    define('DB_NAME', 'stage_portal');
    define('DB_USER', 'username');
    define('DB_PASS', 'your_db_password');
    // ================================================
}
