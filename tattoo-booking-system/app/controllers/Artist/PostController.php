<?php

namespace App\Controllers\Artist;

use Core\Controller;
use App\Models\Post;
use App\Models\User; // Add this line

class PostController extends Controller
{
    private $post;
    private $user; // Add this line

    public function __construct()
    {
        parent::__construct();
        $this->post = new Post();
        $this->user = new User(); // Add this line

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 3) {
            $_SESSION['error'] = 'Access denied';
            return $this->redirect('/');
        }
    }

    public function index()
    {
        // Get all posts for the artist
        return $this->view('artist/posts/index');
    }

    public function create()
    {
        return $this->view('artist/posts/create');
    }

    public function store()
    {
        try {
            if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                throw new \Exception('No image uploaded');
            }

            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($_FILES['image']['type'], $allowedTypes)) {
                throw new \Exception('Invalid file type');
            }

            // Generate unique filename
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $extension;

            // Define upload path
            $uploadDir = __DIR__ . '/../../../public/uploads/posts/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move uploaded file
            $uploadPath = $uploadDir . $filename;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                throw new \Exception('Failed to save image');
            }

            // Save to database
            $imageUrl = '/uploads/posts/' . $filename;
            $caption = $_POST['caption'] ?? '';

            $result = $this->post->create([
                'artist_id' => $_SESSION['user_id'],
                'image_url' => $imageUrl,
                'caption' => $caption
            ]);

            if (!$result) {
                throw new \Exception('Failed to create post');
            }

            $_SESSION['success'] = 'Post created successfully';
            return $this->redirect('/artist/profile');
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return $this->redirect('/artist/profile');
        }
    }

    public function delete($id)
    {
        try {
            // Delete post logic
            $_SESSION['success'] = 'Post deleted successfully';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Failed to delete post';
        }
        return $this->redirect('/artist/posts');
    }

    public function getPosts($artistId)
    {
        return $this->post->getPostsByArtistId($artistId);
    }

    public function getComments($id)
    {
        try {
            $comments = $this->post->getComments($id);

            // Debug logging
            error_log('Comments retrieved for post ' . $id . ': ' . json_encode($comments));

            // Ensure proper content type and encoding
            header('Content-Type: application/json; charset=utf-8');

            // Ensure we're returning a valid JSON array
            echo json_encode([
                'status' => 'success',
                'data' => $comments ?: [],
                'timestamp' => date('c')
            ]);
        } catch (\Exception $e) {
            error_log('Error getting comments: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());

            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to load comments',
                'debug' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function toggleLike($id)
    {
        try {
            $result = $this->post->toggleLike($id, $_SESSION['user_id']);
            $likesCount = $this->post->getLikesCount($id);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'action' => $result['action'],
                'likesCount' => $likesCount['count'] ?? 0
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Failed to toggle like',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function getLikeStatus($id)
    {
        try {
            $status = $this->post->getLikeStatus($id, $_SESSION['user_id']);
            $count = $this->post->getLikesCount($id);

            header('Content-Type: application/json');
            echo json_encode([
                'isLiked' => $status['isLiked'] ?? false,
                'likesCount' => $count['count'] ?? 0
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Failed to get like status',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function addComment($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $comment = $data['comment'] ?? '';

            if (empty($comment)) {
                throw new \Exception('Comment is required');
            }

            $result = $this->post->addComment([
                'post_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'comment' => $comment
            ]);

            if ($result) {
                // Get user info
                $userData = $this->user->find($_SESSION['user_id']);

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'comment' => $comment,
                    'user_name' => $userData['name'] ?? 'Anonymous',
                    'created_at' => date('Y-m-d H:i:s'),
                    'avatar' => $userData['avatar'] ?? '/assets/images/default-avatar.png'
                ]);
                exit;
            }

            throw new \Exception('Failed to add comment');
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
            exit;
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
