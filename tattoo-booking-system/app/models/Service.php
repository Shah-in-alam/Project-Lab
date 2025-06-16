<?php

namespace App\Models;

use Core\Model;

class Service extends Model
{
    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO services (artist_id, name, description, price, duration)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $data['artist_id'],
            $data['name'],
            $data['description'],
            $data['price'],
            $data['duration']
        ]);
    }

    public function getByArtistId($artistId)
    {
        $stmt = $this->db->prepare("
            SELECT services.* 
            FROM services 
            JOIN artist_profiles ON services.artist_id = artist_profiles.id 
            WHERE artist_profiles.user_id = ?
            ORDER BY services.name ASC
        ");
        $stmt->execute([$artistId]);
        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE services 
            SET name = ?, description = ?, price = ?, duration = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price'],
            $data['duration'],
            $id
        ]);
    }
}
