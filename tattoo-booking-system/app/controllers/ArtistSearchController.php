<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\ArtistProfile;
use App\Models\WorkingHours;
use App\Models\Post;
use App\Models\Appointment;
use App\Models\Review;

class ArtistSearchController extends Controller
{
    private $artistProfile;
    private $workingHours;
    private $post;
    private $appointment;
    private $review;

    public function __construct()
    {
        parent::__construct();
        $this->artistProfile = new ArtistProfile();
        $this->workingHours = new WorkingHours();
        $this->post = new Post();
        $this->appointment = new Appointment();
        // $this->review = new Review();
    }

    public function index()
    {
        $filters = [
            'name' => $_GET['name'] ?? '',
            'style' => $_GET['style'] ?? '',
            'location' => $_GET['location'] ?? '',
            'day' => $_GET['day'] ?? '',
            'time' => $_GET['time'] ?? ''
        ];

        $artists = $this->artistProfile->searchArtists($filters);

        return $this->view('artists/search', [
            'artists' => $artists,
            'filters' => $filters
        ]);
    }
    public function profile($id)
    {
        try {
            // Get artist profile with user data
            $artist = $this->artistProfile->getFullProfileById($id);
            error_log("Step 1 - Artist Profile Data:");
            error_log(print_r($artist, true));

            if (!$artist) {
                throw new \Exception('Artist not found');
            }

            // Debug: Show the user_id we're using to fetch posts
            error_log("Step 2 - Fetching posts for user_id: " . $artist['user_id']);

            // Get posts using user_id from the artist profile
            $posts = $this->post->getByUserId($artist['user_id']);

            // Debug: Output the raw posts data
            error_log("Step 3 - Posts Query Result:");
            error_log("Number of posts found: " . count($posts));
            error_log("Raw posts data:");
            error_log(print_r($posts, true));

            // Get working hours
            $workingHours = $this->workingHours->getWorkingHours($id);

            // Get posts count
            $postsCount = count($posts);
            error_log("Step 4 - Final counts:");
            error_log("Posts count: " . $postsCount);

            // Debug: Show what's being sent to the view
            error_log("Step 5 - Data being sent to view:");
            error_log(print_r([
                'artist' => $artist,
                'posts' => $posts,
                'postsCount' => $postsCount,
                'workingHours' => $workingHours
            ], true));

            // Debug: Check the view file path
            $viewPath = __DIR__ . '/../views/artists/profile.php';
            error_log("Step 6 - View file path: " . $viewPath);
            error_log("View file exists: " . (file_exists($viewPath) ? 'Yes' : 'No'));

            // Render view with all data
            return $this->view('artists/profile', [
                'artist' => $artist,
                'posts' => $posts,
                'postsCount' => $postsCount,
                'workingHours' => $workingHours
            ]);
        } catch (\Exception $e) {
            error_log("Error in profile method:");
            error_log("Message: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            $_SESSION['error'] = "Could not load artist profile";
            return $this->redirect('/artists');
        }
    }
}
