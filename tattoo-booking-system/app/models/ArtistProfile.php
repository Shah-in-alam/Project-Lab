<?php

namespace App\Models;

use Core\Model;

class ArtistProfile extends Model
{
    protected $table = 'artist_profiles';

    public function update($id, $data)
    {
        $sql = "UPDATE artist_profiles SET style = ?, bio = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['style'],
            $data['bio'],
            $id
        ]);
    }

    public function getByUserId($userId)
    {
        $sql = "SELECT * FROM artist_profiles WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function searchArtists($filters)
    {
        $sql = "
            SELECT DISTINCT 
                ap.*,
                u.name as artist_name,
                u.email,
                u.location,
                u.avatar
            FROM artist_profiles ap
            JOIN users u ON ap.user_id = u.id
            LEFT JOIN artist_working_hours wh ON ap.id = wh.artist_id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['name'])) {
            $sql .= " AND u.name LIKE ?";
            $params[] = '%' . $filters['name'] . '%';
        }

        if (!empty($filters['style'])) {
            $sql .= " AND ap.style LIKE ?";
            $params[] = '%' . $filters['style'] . '%';
        }

        if (!empty($filters['location'])) {
            $sql .= " AND u.location LIKE ?";  // Changed from ap.location to u.location
            $params[] = '%' . $filters['location'] . '%';
        }

        if (!empty($filters['day'])) {
            $sql .= " AND wh.day_of_week = ? AND wh.is_working = 1";
            $params[] = $filters['day'];

            if (!empty($filters['time'])) {
                $sql .= " AND ? BETWEEN wh.start_time AND wh.end_time";
                $params[] = $filters['time'];
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getFullProfileById($id)
    {
        $sql = "
            SELECT 
                ap.*,
                u.id as user_id,
                u.name as artist_name,
                u.email,
                u.location,
                u.avatar,
                (SELECT COUNT(*) FROM posts p WHERE p.artist_id = ap.id) as posts_count
            FROM artist_profiles ap
            JOIN users u ON ap.user_id = u.id
            WHERE ap.id = ?
            LIMIT 1
        ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            $result = $stmt->fetch();

            if (!$result) {
                error_log("Artist profile not found for ID: " . $id);
                return null;
            }

            // Debug log
            error_log("Artist profile found: " . print_r($result, true));

            return $result;
        } catch (\PDOException $e) {
            error_log("Database error in getFullProfileById: " . $e->getMessage());
            throw new \Exception("Failed to fetch artist profile: " . $e->getMessage());
        }
    }
}
