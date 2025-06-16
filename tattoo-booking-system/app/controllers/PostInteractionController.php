<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Post;
use App\Models\User;

class PostInteractionController extends Controller
{
    private $post;
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->post = new Post();
        $this->user = new User();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Only check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Please login to interact with posts']);
            exit;
        }
    }

    public function getComments($id)
    {
        try {
            $comments = $this->post->getComments($id);

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'success',
                'data' => $comments ?: []
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to load comments'
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
                $userData = $this->user->find($_SESSION['user_id']);

                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'comment' => [
                        'text' => $comment,
                        'user_name' => $userData['name'],
                        'avatar' => $userData['avatar'] ?? '/assets/images/default-avatar.png',
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                ]);
                exit;
            }
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
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
                'likesCount' => $likesCount['count']
            ]);
        } catch (\Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
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
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }
}
