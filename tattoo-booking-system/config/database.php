<?php
// Database configuration settings
// define('DB_HOST', 'localhost');
// define('DB_NAME', 'projectlab');
// define('DB_USER', 'root');
// define('DB_PASS', '');

$config = [
    'host' => 'localhost',
    'database' => 'projectlab',
    'username' => 'root',
    'password' => ''
];

try {
    // Create a new PDO instance
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']}",
        $config['username'],
        $config['password']
    );
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Handle connection error
    echo "Connection failed: " . $e->getMessage();
}

return $config;
