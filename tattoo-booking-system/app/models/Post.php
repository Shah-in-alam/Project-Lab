<?php

namespace App\Models;

use Core\Database;

class Post
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPostsByArtistId($artistId)
    {
        // First get the artist_profile id
        $stmt = $this->db->prepare("
            SELECT id FROM artist_profiles WHERE user_id = ?
        ");
        $stmt->execute([$artistId]);
        $artistProfile = $stmt->fetch();

        if (!$artistProfile) {
            return [];
        }

        $stmt = $this->db->prepare("
            SELECT p.*, 
                   (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as likes_count,
                   (SELECT COUNT(*) FROM post_comments WHERE post_id = p.id) as comments_count
            FROM posts p
            WHERE p.artist_id = ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$artistId]);
        return $stmt->fetchAll();
    }

    public function create($data)
    {
        // First get the artist_profile id
        $stmt = $this->db->prepare("
            SELECT id FROM artist_profiles WHERE user_id = ?
        ");
        $stmt->execute([$data['artist_id']]);
        $artistProfile = $stmt->fetch();

        if (!$artistProfile) {
            throw new \Exception('Artist profile not found');
        }

        $stmt = $this->db->prepare("
            INSERT INTO posts (artist_id, image_url, caption)
            VALUES (:artist_id, :image_url, :caption)
        ");

        return $stmt->execute([
            'artist_id' => $data['artist_id'],
            'image_url' => $data['image_url'],
            'caption' => $data['caption']
        ]);
    }

    public function getComments($postId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                pc.id,
                pc.comment,
                pc.commented_at as created_at,
                pc.user_id,
                u.name as user_name,
                COALESCE(u.avatar, '/assets/images/default-avatar.png') as avatar
            FROM post_comments pc
            JOIN users u ON pc.user_id = u.id
            WHERE pc.post_id = ?
            ORDER BY pc.commented_at DESC
        ");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addComment($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO post_comments (post_id, user_id, comment)
            VALUES (:post_id, :user_id, :comment)
        ");

        return $stmt->execute([
            'post_id' => $data['post_id'],
            'user_id' => $data['user_id'],
            'comment' => $data['comment']
        ]);
    }

    public function toggleLike($postId, $userId)
    {
        // Check if like exists
        $stmt = $this->db->prepare("
            SELECT id FROM post_likes 
            WHERE post_id = ? AND user_id = ?
        ");
        $stmt->execute([$postId, $userId]);
        $like = $stmt->fetch();

        if ($like) {
            // Unlike
            $stmt = $this->db->prepare("
                DELETE FROM post_likes 
                WHERE post_id = ? AND user_id = ?
            ");
            $stmt->execute([$postId, $userId]);
            return ['action' => 'unliked'];
        } else {
            // Like
            $stmt = $this->db->prepare("
                INSERT INTO post_likes (post_id, user_id)
                VALUES (?, ?)
            ");
            $stmt->execute([$postId, $userId]);
            return ['action' => 'liked'];
        }
    }

    public function getLikeStatus($postId, $userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as liked
            FROM post_likes
            WHERE post_id = ? AND user_id = ?
        ");
        $stmt->execute([$postId, $userId]);
        $result = $stmt->fetch();
        return ['isLiked' => (bool)$result['liked']];
    }

    public function getLikesCount($postId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM post_likes
            WHERE post_id = ?
        ");
        $stmt->execute([$postId]);
        $result = $stmt->fetch();
        return ['count' => (int)$result['count']];
    }

    public function getByArtistProfileId($artistProfileId)
    {
        $sql = "
            SELECT p.*, 
                   COUNT(DISTINCT l.id) as likes_count,
                   COUNT(DISTINCT c.id) as comments_count
            FROM posts p
            LEFT JOIN likes l ON p.id = l.post_id
            LEFT JOIN comments c ON p.id = c.post_id
            JOIN artist_profiles ap ON p.artist_id = ap.user_id
            WHERE ap.id = ?
            GROUP BY p.id
            ORDER BY p.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$artistProfileId]);
        return $stmt->fetchAll();
    }

    protected $table = 'posts';

    public function getByUserId($userId)
    {
        $sql = "
            SELECT 
                p.*,
                COUNT(DISTINCT pl.id) as likes_count,
                COUNT(DISTINCT pc.id) as comments_count
            FROM posts p
            LEFT JOIN post_likes pl ON p.id = pl.post_id
            LEFT JOIN post_comments pc ON p.id = pc.post_id
            WHERE p.artist_id = ?
            GROUP BY p.id, p.artist_id, p.image_url, p.caption, p.created_at
            ORDER BY p.created_at DESC
        ";

        try {
            error_log("Fetching posts for artist_id (user_id): " . $userId);

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            error_log("Found " . count($results) . " posts");
            error_log("Posts data: " . print_r($results, true));

            return $results;
        } catch (\PDOException $e) {
            error_log("Database error in getByUserId: " . $e->getMessage());
            return [];
        }
    }
}
