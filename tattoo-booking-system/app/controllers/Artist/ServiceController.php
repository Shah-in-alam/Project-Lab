<?php

namespace App\Controllers\Artist;

use Core\Controller;
use App\Models\Service;
use App\Models\ArtistProfile;

class ServiceController extends Controller
{
    private $service;
    private $artistProfile;

    public function __construct()
    {
        parent::__construct();
        $this->service = new Service();
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

    public function index()
    {
        $services = $this->service->getByArtistId($_SESSION['user_id']);
        return $this->view('artist/services/index', [
            'services' => $services
        ]);
    }

    public function store()
    {
        try {
            // Validate inputs
            if (
                empty($_POST['name']) || empty($_POST['description']) ||
                !isset($_POST['price']) || !isset($_POST['duration'])
            ) {
                throw new \Exception('Please fill in all fields');
            }

            // Validate duration
            $duration = (int)$_POST['duration'];
            if ($duration < 15) {
                throw new \Exception('Duration must be at least 15 minutes');
            }

            // Get artist profile
            $artistProfile = $this->artistProfile->getByUserId($_SESSION['user_id']);
            if (!$artistProfile) {
                throw new \Exception('Artist profile not found');
            }

            // Create service using the Service model
            $this->service->create([
                'artist_id' => $artistProfile['id'],
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'duration' => $duration
            ]);

            $_SESSION['success'] = 'Service added successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        return $this->redirect('/artist/services');
    }

    public function getService($id)
    {
        try {
            $service = $this->service->getById($id);

            // Check if service belongs to the current artist
            $artistProfile = $this->artistProfile->getByUserId($_SESSION['user_id']);
            if (!$service || $service['artist_id'] !== $artistProfile['id']) {
                throw new \Exception('Service not found');
            }

            header('Content-Type: application/json');
            echo json_encode($service);
            exit;
        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }

    public function update()
    {
        try {
            // Validate inputs
            if (
                empty($_POST['id']) || empty($_POST['name']) ||
                empty($_POST['description']) || !isset($_POST['price']) ||
                !isset($_POST['duration'])
            ) {
                throw new \Exception('Please fill in all fields');
            }

            // Validate duration
            $duration = (int)$_POST['duration'];
            if ($duration < 15) {
                throw new \Exception('Duration must be at least 15 minutes');
            }

            // Get artist profile
            $artistProfile = $this->artistProfile->getByUserId($_SESSION['user_id']);
            if (!$artistProfile) {
                throw new \Exception('Artist profile not found');
            }

            // Verify service belongs to artist
            $service = $this->service->getById($_POST['id']);
            if (!$service || $service['artist_id'] !== $artistProfile['id']) {
                throw new \Exception('Service not found');
            }

            // Update service
            $this->service->update($_POST['id'], [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'duration' => $duration
            ]);

            $_SESSION['success'] = 'Service updated successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        return $this->redirect('/artist/services');
    }
}
