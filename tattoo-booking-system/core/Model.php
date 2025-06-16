<?php

namespace Core;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        try {
            $this->db = Database::getInstance()->getConnection();
        } catch (\Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }
}
