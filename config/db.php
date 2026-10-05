<?php
// SANIX TOOL - Database Connection

require_once __DIR__ . '/app.php';

// Configurable Database Settings (Supports Environment Variables or Production Overrides)
if (!defined('DB_HOST')) define('DB_HOST', getenv('DB_HOST') ?: '127.0.0.1');
if (!defined('DB_USER')) define('DB_USER', getenv('DB_USER') ?: 'root');
if (!defined('DB_PASS')) define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
if (!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'sanix_tool');
if (!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT') ?: 3306);

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): ?PDO {
        if (self::$instance === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // Log error internally for security without exposing raw DB passwords
                error_log("Database Connection Error: " . $e->getMessage());
                return null;
            }
        }
        return self::$instance;
    }
}

// Global convenience function
function getDB(): ?PDO {
    return Database::getConnection();
}
