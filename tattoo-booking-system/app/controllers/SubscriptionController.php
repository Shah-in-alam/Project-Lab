<?php

namespace App\Controllers;

use Core\Controller;
use Core\Database;
use App\Models\User;
use App\Models\Membership;

class SubscriptionController extends Controller
{
    private $user;
    private $membership;
    protected $db; // Changed from private to protected to match parent class

    public function __construct()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->user = new User();
        $this->membership = new Membership();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to become an artist';
            return $this->redirect('/login');
        }

        if ($_SESSION['user_role'] !== 2) {
            $_SESSION['error'] = 'You already have an artist account';
            return $this->redirect('/');
        }

        return $this->view('subscription/index');
    }

    public function process()
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                throw new \Exception('Please login to continue');
            }

            // Start transaction
            $this->db->beginTransaction();

            // Create membership record
            $membershipData = [
                'user_id' => $_SESSION['user_id'],
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+1 month'))
            ];

            $membership_id = $this->membership->create($membershipData);

            // Update user role to artist (3)
            $this->user->updateRole($_SESSION['user_id'], 3);
            $_SESSION['user_role'] = 3;

            // Record payment
            $payment_id = $this->recordPayment($_SESSION['user_id'], 29.00, 'artist_subscription');

            // Create artist profile
            $artist_profile_id = $this->createArtistProfile($_SESSION['user_id']);

            // Commit transaction
            $this->db->commit();

            $_SESSION['success'] = 'Welcome to the artist community! Your account has been upgraded.';
            return $this->redirect('/'); // Changed from /artist/dashboard to /
        } catch (\Exception $e) {
            // Rollback transaction on error
            if ($this->db && $this->db->inTransaction()) {
                $this->db->rollBack();
            }

            error_log("Subscription error: " . $e->getMessage());
            $_SESSION['error'] = 'Subscription failed. Please try again.';
            return $this->redirect('/become-artist');
        }
    }

    private function recordPayment($user_id, $amount, $payment_type)
    {
        $sql = "INSERT INTO payments (
            user_id, 
            amount, 
            payment_type, 
            status,
            created_at
        ) VALUES (
            :user_id, 
            :amount, 
            :payment_type, 
            'completed',
            CURRENT_TIMESTAMP
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_id' => $user_id,
            'amount' => $amount,
            'payment_type' => $payment_type
        ]);

        return $this->db->lastInsertId();
    }

    private function createArtistProfile($user_id)
    {
        $sql = "INSERT INTO artist_profiles (
            user_id,
            created_at
        ) VALUES (
            :user_id,
            CURRENT_TIMESTAMP
        )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);

        return $this->db->lastInsertId();
    }

    public function showUpgradePage()
    {
        error_log('Debug: Entering showUpgradePage');
        error_log('Debug: Session data: ' . print_r($_SESSION, true));
        error_log('Debug: User Role: ' . ($_SESSION['user_role'] ?? 'not set'));
        error_log('Debug: User Role Type: ' . gettype($_SESSION['user_role'] ?? null));

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            error_log('Debug: No user_id in session');
            $_SESSION['error'] = 'Please login to become an artist';
            return $this->redirect('/login');
        }

        // Ensure role comparison is done with strings since session data shows it's stored as string
        if ($_SESSION['user_role'] != "2") {  // Using loose comparison
            error_log('Debug: Invalid role - current role: ' . $_SESSION['user_role']);
            $_SESSION['error'] = 'You already have an artist account';
            return $this->redirect('/');
        }

        error_log('Debug: All checks passed, showing upgrade page');
        return $this->view('subscription/upgrade');
    }
}
