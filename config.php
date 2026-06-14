<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'queue_and_appointment_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create database connection
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die("Database connection failed. Please check your configuration.");
}
?>