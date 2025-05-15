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

        $sql = "INSERT INTO users (email, password_hash, name, location, role_id, confirmation_token) 
                VALUES (:email, :password_hash, :name, :location, 2, :token)"; // 2 = default user role

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $result = $stmt->execute([
                'email' => $data['email'],
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
                'name' => $data['name'],
                'location' => $data['location'],
                'token' => $token
            ]);

            if ($result) {
                return [
                    'id' => $this->db->getConnection()->lastInsertId(),
                    'token' => $token
                ];
            }
            return false;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function findByEmail($email)
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE u.email = :email";

        try {
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function verifyEmail($token)
    {
        try {
            // First, get the user data
            $sql = "SELECT id, name, role_id FROM users WHERE confirmation_token = :token";
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute(['token' => $token]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($user) {
                // Update the user's verification status
                $updateSql = "UPDATE users 
                             SET email_confirmed = TRUE, 
                                 confirmation_token = NULL 
                             WHERE confirmation_token = :token";

                $updateStmt = $this->db->getConnection()->prepare($updateSql);
                $updateStmt->execute(['token' => $token]);

                return $user;
            }

            return false;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
