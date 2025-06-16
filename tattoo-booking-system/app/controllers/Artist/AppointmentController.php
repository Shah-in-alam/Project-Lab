<?php

namespace App\Controllers\Artist;

use Core\Controller;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use App\Models\ArtistProfile;

class AppointmentController extends Controller
{
    private $appointment;
    private $service;
    private $user;
    private $artistProfile;

    public function __construct()
    {
        parent::__construct();
        $this->appointment = new Appointment();  // Now no arguments needed
        $this->service = new Service();
        $this->user = new User();
        $this->artistProfile = new ArtistProfile();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check authentication
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 3) {
            $_SESSION['error'] = 'Access denied';
            $this->redirect('/');
            exit();
        }
    }

    // Main dashboard view
    public function dashboard()
    {
        return $this->view('artist/appointments/dashboard');
    }

    public function getAppointments($date)
    {
        try {
            $appointments = $this->appointment->getByArtistAndDate($_SESSION['user_id'], $date);

            // Format appointments for frontend
            $formattedAppointments = array_map(function ($appointment) {
                $client = $this->user->findById($appointment['user_id']);
                $service = $this->service->getById($appointment['service_id']);

                return [
                    'id' => $appointment['id'],
                    'time' => date('H:i', strtotime($appointment['start_time'])),
                    'client_name' => $client['name'] ?? 'Unknown',
                    'service_name' => $service['name'] ?? 'Unknown',
                    'duration' => $service['duration'] ?? 0,
                    'status' => $appointment['status'],
                    'notes' => $appointment['notes']
                ];
            }, $appointments);

            header('Content-Type: application/json');
            echo json_encode($formattedAppointments);
            exit;
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    public function updateStatus($id)
    {
        try {
            $status = $_POST['status'];
            $validStatuses = ['approved', 'denied', 'cancelled', 'completed', 'in_progress'];

            if (!in_array($status, $validStatuses)) {
                throw new \Exception('Invalid status');
            }

            $this->appointment->updateStatus($id, $status);
            return $this->json(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(500);
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    public function getDetails($id)
    {
        try {
            $appointment = $this->appointment->getById($id);

            if (!$appointment) {
                throw new \Exception('Appointment not found');
            }

            // Verify the appointment belongs to this artist
            $artistProfile = $this->artistProfile->getByUserId($_SESSION['user_id']);
            if (!$artistProfile || $appointment['artist_id'] !== $artistProfile['id']) {
                throw new \Exception('Unauthorized');
            }

            return $this->json($appointment);
        } catch (\Exception $e) {
            http_response_code(404);
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    public function getTimeSlots($date)
    {
        try {
            $artistProfile = $this->artistProfile->getByUserId($_SESSION['user_id']);
            if (!$artistProfile) {
                throw new \Exception('Artist profile not found');
            }

            // Check if it's a weekend
            $dayOfWeek = date('N', strtotime($date));
            if ($dayOfWeek > 5) {
                return $this->json(['error' => 'We are closed on weekends']);
            }

            // Check if it's a holiday
            $holiday = $this->db->prepare("SELECT * FROM holidays WHERE date = ?");
            $holiday->execute([$date]);
            if ($holiday->fetch()) {
                return $this->json(['error' => 'We are closed on public holidays']);
            }

            // Get working hours for this day
            $workingHours = $this->db->prepare("
                SELECT start_time, end_time, is_working 
                FROM artist_working_hours 
                WHERE artist_id = ? AND day_of_week = ?
            ");
            $workingHours->execute([$artistProfile['id'], $dayOfWeek]);
            $hours = $workingHours->fetch();

            if (!$hours || !$hours['is_working']) {
                return $this->json(['error' => 'No working hours set for this day']);
            }

            // Get existing appointments
            $appointments = $this->appointment->getByArtistAndDate($artistProfile['id'], $date);

            // Generate time slots
            $startTime = strtotime($date . ' ' . $hours['start_time']);
            $endTime = strtotime($date . ' ' . $hours['end_time']);
            $interval = 10 * 60; // 10 minutes

            $timeSlots = [];
            for ($time = $startTime; $time < $endTime; $time += $interval) {
                $slot = [
                    'time' => date('H:i', $time),
                    'available' => true,
                    'appointment_id' => null
                ];

                // Check conflicts with existing appointments
                foreach ($appointments as $appointment) {
                    $appointmentStart = strtotime($appointment['start_time']);
                    $appointmentEnd = strtotime($appointment['end_time']);

                    if ($time >= $appointmentStart && $time < $appointmentEnd) {
                        $slot['available'] = false;
                        $slot['appointment_id'] = $appointment['id'];
                        break;
                    }
                }

                $timeSlots[] = $slot;
            }

            return $this->json($timeSlots);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
