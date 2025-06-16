<?php

namespace App\Models;

use Core\Model;

class WorkingHours extends Model
{
    protected $table = 'artist_working_hours';

    public function __construct()
    {
        parent::__construct();
    }

    public function saveWorkingHours($artistId, $workingHours)
    {
        try {
            $this->db->beginTransaction();

            // Delete existing working hours
            $stmt = $this->db->prepare("DELETE FROM artist_working_hours WHERE artist_id = ?");
            $stmt->execute([$artistId]);

            // Insert new working hours
            $stmt = $this->db->prepare("
                INSERT INTO artist_working_hours 
                (artist_id, day_of_week, start_time, end_time, is_working)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($workingHours as $dayNum => $hours) {
                $stmt->execute([
                    $artistId,
                    $dayNum,
                    $hours['start_time'],
                    $hours['end_time'],
                    $hours['is_working'] ? 1 : 0
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getWorkingHours($artistId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM artist_working_hours 
            WHERE artist_id = ?
            ORDER BY day_of_week
        ");
        $stmt->execute([$artistId]);

        $hours = [];
        while ($row = $stmt->fetch()) {
            $hours[$row['day_of_week']] = $row;
        }
        return $hours;
    }
}
