<?php

namespace App\Models;

use Core\Model;

class Appointment extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'artist_id',
        'user_id',
        'service_id',
        'start_time',
        'end_time',
        'status',
        'notes'
    ];

    private $id;
    private $userId;
    private $date;
    private $time;
    private $status;

    public function __construct()
    {
        parent::__construct();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function save()
    {
        // Code to save the appointment to the database
    }

    public static function findAll()
    {
        // Code to retrieve all appointments from the database
    }

    public static function findById($id)
    {
        // Code to retrieve a specific appointment by ID from the database
    }

    public function getByArtistAndDate($artistId, $date)
    {
        $stmt = $this->db->prepare("
            SELECT bookings.* 
            FROM bookings 
            JOIN artist_profiles ON bookings.artist_id = artist_profiles.id
            WHERE artist_profiles.user_id = ? 
            AND DATE(bookings.start_time) = ?
            ORDER BY bookings.start_time ASC
        ");

        $stmt->execute([$artistId, $date]);
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE bookings 
            SET status = ?, 
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        return $stmt->execute([$status, $id]);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   u.name as client_name,
                   u.email as client_email,
                   s.name as service_name,
                   s.duration as service_duration
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN services s ON b.service_id = s.id
            WHERE b.id = ?
        ");

        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getAvailableTimeSlots($artistId, $date)
    {
        $stmt = $this->db->prepare("
            SELECT start_time, end_time, id
            FROM bookings 
            WHERE artist_id = ? 
            AND DATE(start_time) = ?
            ORDER BY start_time ASC
        ");

        $stmt->execute([$artistId, $date]);
        return $stmt->fetchAll();
    }
}
