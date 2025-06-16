<?php

namespace App\Models;

use Core\Model;
use Core\Database;

class Membership extends Model
{
    public function create($data)
    {
        $sql = "INSERT INTO memberships (
            user_id,
            start_date,
            end_date,
            is_active,
            created_at
        ) VALUES (
            :user_id,
            :start_date,
            :end_date,
            TRUE,
            CURRENT_TIMESTAMP
        )";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'user_id' => $data['user_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date']
            ]);

            return $this->db->lastInsertId();
        } catch (\PDOException $e) {
            error_log("Membership creation error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getActiveMembership($user_id)
    {
        $sql = "SELECT * FROM memberships 
                WHERE user_id = :user_id 
                AND is_active = TRUE 
                AND end_date >= CURRENT_DATE";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['user_id' => $user_id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            error_log("Error fetching membership: " . $e->getMessage());
            throw $e;
        }
    }
}
