<?php

namespace App\Models;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = \Core\Database::getInstance();
    }

    public function create($data)
    {
        $token = bin2hex(random_bytes(50));

        $sql = "INSERT INTO users (name, email, password_hash, location, confirmation_token) 
                VALUES (:name, :email, :password_hash, :location, :token)";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':location' => $data['location'] ?? null,
                ':token' => $token
            ]);

            return ['id' => $this->db->getConnection()->lastInsertId(), 'token' => $token];
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        return $this->db->query($sql, ['email' => $email])->fetch();
    }

    public function verifyEmail($token)
    {
        $sql = "UPDATE users SET email_confirmed = TRUE, confirmation_token = NULL 
                WHERE confirmation_token = :token";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            return $stmt->execute([':token' => $token]);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
