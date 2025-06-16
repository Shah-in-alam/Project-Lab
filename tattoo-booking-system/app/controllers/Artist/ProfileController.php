<?php

namespace App\Controllers\Artist;

use Core\Controller;
use App\Models\Post;
use App\Models\ArtistProfile;
use App\Models\WorkingHours;

class ProfileController extends Controller
{
    private $post;
    private $artistProfile;
    private $workingHours;

    public function __construct()
    {
        // First call parent constructor
        parent::__construct();

        // Initialize Post model
        $this->post = new Post();
        $this->artistProfile = new ArtistProfile();
        $this->workingHours = new WorkingHours();

        // Start session if not started
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

    public function show()
    {
        // Get posts for the artist
        $posts = $this->post->getPostsByArtistId($_SESSION['user_id']);

        // Get artist profile
        $profile = $this->artistProfile->getByUserId($_SESSION['user_id']);

        // Pass data to view
        return $this->view('artist/profile', [
            'posts' => $posts,
            'profile' => $profile
        ]);
    }

    public function edit()
    {
        $profile = $this->artistProfile->getByUserId($_SESSION['user_id']);
        $workingHours = [];

        if ($profile) {
            $workingHours = $this->workingHours->getWorkingHours($profile['id']);
        }

        return $this->view('artist/profile/edit', [
            'profile' => $profile,
            'workingHours' => $workingHours
        ]);
    }

    public function update()
    {
        // Handle profile update logic
        try {
            $this->db->beginTransaction();

            // Get artist profile
            $profile = $this->artistProfile->getByUserId($_SESSION['user_id']);
            if (!$profile) {
                throw new \Exception('Artist profile not found');
            }

            // Update profile data
            $this->artistProfile->update($profile['id'], [
                'style' => $_POST['style'] ?? '',
                'bio' => $_POST['bio'] ?? ''
            ]);

            // Process working hours
            $workingHours = [];
            for ($day = 1; $day <= 5; $day++) {
                $workingHours[$day] = [
                    'start_time' => $_POST['start_time'][$day] ?? '09:00',
                    'end_time' => $_POST['end_time'][$day] ?? '17:00',
                    'is_working' => isset($_POST['working_days'][$day])
                ];
            }

            // Save working hours using WorkingHours model
            $this->workingHours->saveWorkingHours($profile['id'], $workingHours);

            $this->db->commit();
            $_SESSION['success'] = 'Profile updated successfully';
        } catch (\Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = 'Failed to update profile: ' . $e->getMessage();
        }

        return $this->redirect('/artist/profile/edit');
    }

    public function getWorkingHours()
    {
        try {
            $profile = $this->artistProfile->getByUserId($_SESSION['user_id']);
            if (!$profile) {
                throw new \Exception('Artist profile not found');
            }

            $workingHours = $this->workingHours->getWorkingHours($profile['id']);

            header('Content-Type: application/json');
            echo json_encode($workingHours);
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
