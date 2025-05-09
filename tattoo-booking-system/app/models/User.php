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
        $token = bin2hex(random_bytes(32)); // Generate confirmation token
        
        $sql = "INSERT INTO users (name, email, password_hash, location, is_verified, confirmation_token) 
                VALUES (:name, :email, :password, :location, :is_verified, :token)";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->execute([
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ':location' => $data['location'] ?? null,
                ':is_verified' => false,
                ':token' => $token
            ]);

            return [
                'id' => $this->db->getConnection()->lastInsertId(),
                'token' => $token
            ];
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
        $sql = "UPDATE users SET is_verified = TRUE, email_confirmed = TRUE WHERE confirmation_token = :token";
        return $this->db->query($sql, ['token' => $token])->rowCount() > 0;
    }
}
