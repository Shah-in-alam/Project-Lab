<?php

class Appointment {
    private $id;
    private $userId;
    private $date;
    private $time;
    private $status;

    public function __construct($userId, $date, $time, $status = 'pending') {
        $this->userId = $userId;
        $this->date = $date;
        $this->time = $time;
        $this->status = $status;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getDate() {
        return $this->date;
    }

    public function getTime() {
        return $this->time;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function save() {
        // Code to save the appointment to the database
    }

    public static function findAll() {
        // Code to retrieve all appointments from the database
    }

    public static function findById($id) {
        // Code to retrieve a specific appointment by ID from the database
    }
}