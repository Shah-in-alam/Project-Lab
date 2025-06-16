<?php

namespace App\Models;

use Core\Database;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function verifyEmail($token)
    {
        $sql = "UPDATE users SET is_verified = TRUE, email_confirmed = TRUE WHERE confirmation_token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->rowCount() > 0;
    }

    public function create($data)
    {
        try {
            $token = bin2hex(random_bytes(50));

            $sql = "INSERT INTO users (
                email, 
                password_hash, 
                name, 
                location, 
                role_id, 
                confirmation_token, 
                created_at
            ) VALUES (
                :email, 
                :password_hash, 
                :name, 
                :location, 
                2, 
                :token, 
                CURRENT_TIMESTAMP
            )";

            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'email' => $data['email'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'name' => $data['name'],
                'location' => $data['location'],
                'token' => $token
            ]);

            if ($result) {
                return [
                    'id' => $this->db->lastInsertId(),
                    'token' => $token
                ];
            }

            return false;
        } catch (\PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateRole($user_id, $role_id)
    {
        $sql = "UPDATE users SET role_id = :role_id WHERE id = :user_id";

        try {
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'role_id' => $role_id,
                'user_id' => $user_id
            ]);
        } catch (\PDOException $e) {
            error_log("Role update error: " . $e->getMessage());
            throw $e;
        }
    }

    public function confirmEmail($userId)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET email_confirmed = 1 
            WHERE id = ?
        ");
        return $stmt->execute([$userId]);
    }

    public function findByToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT id, email, name, role_id, location, is_verified 
            FROM users 
            WHERE confirmation_token = ?
        ");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("
            SELECT id, email, name, role_id, location, is_verified, email_confirmed 
            FROM users 
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function updateVerificationStatus($userId)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET email_confirmed = true,
                is_verified = true,
                confirmation_token = NULL
            WHERE id = ?
        ");
        return $stmt->execute([$userId]);
    }
}
