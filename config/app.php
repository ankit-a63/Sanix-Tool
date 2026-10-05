<?php
// SANIX TOOL - Application Configuration

define('APP_NAME', 'SANIX TOOL');
define('APP_TAGLINE', 'One Platform. Every Tool.');
define('APP_VERSION', '1.0.0');

// Base URL configuration (Supports Environment Variable Override, Subfolder Deployments, and Auto-Detection)
$envAppUrl = getenv('APP_URL') ?: (defined('APP_URL') ? APP_URL : null);

if ($envAppUrl) {
    define('BASE_URL', rtrim($envAppUrl, '/') . '/');
} else {
    $protocol = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] == 1)) ||
                (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    
    // Automatically detect subdirectory deployment if uploaded under public_html/subfolder
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $basePath = rtrim($scriptDir, '/');
    
    if ($basePath === '' || $basePath === '.' || $basePath === '/tools' || $basePath === '/api' || $basePath === '/admin' || strpos($basePath, '/tools/') === 0) {
        // Strip nested subfolder path to resolve root BASE_URL cleanly
        $rootPath = preg_replace('#/(tools|api|admin)(/.*)?$#i', '', $basePath);
        define('BASE_URL', $protocol . $host . (empty($rootPath) ? '/' : $rootPath . '/'));
    } else {
        define('BASE_URL', $protocol . $host . $basePath . '/');
    }
}

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('TEMP_DIR', __DIR__ . '/../temp/');
define('MAX_UPLOAD_SIZE', 25 * 1024 * 1024); // 25MB limit

// Error handling settings (Hide raw PHP warnings from users in production)
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', '0');
