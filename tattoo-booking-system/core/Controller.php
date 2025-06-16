<?php

namespace Core;

use PDO;

class Controller
{
    protected $db;

    public function __construct()
    {
        try {
            // Get database configuration
            $config = require dirname(__DIR__) . '/config/database.php';

            if (!is_array($config)) {
                throw new \Exception('Invalid database configuration');
            }

            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                $config['host'],
                $config['database']
            );

            $this->db = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (\Exception $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }

    protected function view($view, $data = [])
    {
        // Extract data to make it available in view
        extract($data);

        // Include the view file
        require_once "../app/views/{$view}.php";
    }

    protected function redirect($path)
    {
        header("Location: {$path}");
        exit();
    }
}
