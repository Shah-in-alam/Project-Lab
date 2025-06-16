<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Services\EmailService;

class AuthController extends Controller
{
    private $user;
    private $emailService;

    public function __construct()
    {
        $this->user = new User();
        $this->emailService = new EmailService();
    }

    public function login()
    {
        return $this->view('auth/login');
    }

    public function register()
    {
        return $this->view('auth/register');
    }

    public function store()
    {
        // Start session at the beginning
        session_start();

        // Log the POST data
        error_log('Registration attempt with data: ' . print_r($_POST, true));

        // Validate input
        $errors = $this->validateRegistration($_POST);

        if (!empty($errors)) {
            error_log('Validation errors: ' . print_r($errors, true));
            return $this->view('auth/register', ['errors' => $errors]);
        }

        // Check if email already exists
        if ($this->user->findByEmail($_POST['email'])) {
            error_log('Email already exists: ' . $_POST['email']);
            $_SESSION['error'] = 'This email is already registered. Please use a different email or login.';
            return $this->redirect('/');
        }

        // Create user
        try {
            error_log('Attempting to create user...');
            $result = $this->user->create([
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'name' => $_POST['name'],
                'location' => $_POST['location']
            ]);

            error_log('User creation result: ' . print_r($result, true));

            if ($result) {
                // Send confirmation email
                try {
                    $this->emailService->sendConfirmationEmail(
                        $_POST['email'],
                        $_POST['name'],
                        $result['token']
                    );
                    error_log('Confirmation email sent successfully');
                } catch (\Exception $e) {
                    error_log('Failed to send confirmation email: ' . $e->getMessage());
                }

                $_SESSION['success'] = 'Registration successful! Please check your email to confirm your account.';
                error_log('Registration successful, redirecting to login');
                return $this->redirect('/login');
            } else {
                error_log('User creation failed - no result returned');
                $_SESSION['error'] = 'Registration failed. Please try again.';
                return $this->redirect('/register');
            }
        } catch (\PDOException $e) {
            error_log('Database error during registration: ' . $e->getMessage());
            if ($e->getCode() == 23000) {
                $_SESSION['error'] = 'This email is already registered. Please use a different email or login.';
                return $this->redirect('/');
            }

            $_SESSION['error'] = 'Registration failed. Please try again.';
            return $this->redirect('/register');
        } catch (\Exception $e) {
            error_log('General error during registration: ' . $e->getMessage());
            $_SESSION['error'] = 'Registration failed. Please try again.';
            return $this->redirect('/register');
        }
    }

    public function logout()
    {
        session_start();
        session_destroy();
        return $this->redirect('/');
    }

    public function verifyEmail()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $_SESSION['error'] = 'Invalid verification token';
            return $this->redirect('/login');
        }

        try {
            // Step 1: Get user data by token before verification
            $user = $this->user->findByToken($token);

            if (!$user) {
                $_SESSION['error'] = 'Invalid or expired verification link.';
                return $this->redirect('/login');
            }

            // Step 2: Update verification status
            $updated = $this->user->updateVerificationStatus($user['id']);

            if (!$updated) {
                $_SESSION['error'] = 'Failed to verify email. Please try again.';
                return $this->redirect('/login');
            }

            // Step 3: Get fresh user data for authentication
            $verifiedUser = $this->user->findById($user['id']);

            if (!$verifiedUser) {
                $_SESSION['error'] = 'Error retrieving user data.';
                return $this->redirect('/login');
            }

            // Step 4: Set up authentication session
            $_SESSION['user_id'] = $verifiedUser['id'];
            $_SESSION['user_name'] = $verifiedUser['name'];
            $_SESSION['user_role'] = $verifiedUser['role_id'];
            $_SESSION['user_location'] = $verifiedUser['location'];
            $_SESSION['is_verified'] = true;
            $_SESSION['success'] = "Email verified successfully! Welcome {$verifiedUser['name']}!";

            error_log("User verified and authenticated successfully: {$verifiedUser['id']} - {$verifiedUser['email']}");

            // Step 5: Redirect based on role
            switch ($verifiedUser['role_id']) {
                case 1: // Admin
                    return $this->redirect('/admin/dashboard');
                case 2: // Regular user
                    return $this->redirect('/');
                case 3: // Artist
                    return $this->redirect('/artist/dashboard');
                default:
                    return $this->redirect('/');
            }
        } catch (\Exception $e) {
            error_log("Error during email verification: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred during verification. Please try again.';
            return $this->redirect('/login');
        }
    }

    public function checkEmail()
    {
        $email = $_GET['email'] ?? '';

        if (empty($email)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Email is required']);
            return;
        }

        $exists = $this->user->findByEmail($email);

        header('Content-Type: application/json');
        echo json_encode(['exists' => (bool)$exists]);
    }

    private function validateRegistration($data)
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Name is required';
        }

        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }

        if (empty($data['password']) || strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if (empty($data['location'])) {
            $errors['location'] = 'Location is required';
        }

        if (empty($data['terms'])) {
            $errors['terms'] = 'You must accept the terms of service';
        }

        return $errors;
    }

    public function authenticate()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // Validate required fields
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['error'] = 'Email and password are required';
                return $this->redirect('/login');
            }

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            // Find user by email
            $user = $this->user->findByEmail($email);

            if (!$user) {
                error_log("Login failed: User not found - {$email}");
                $_SESSION['error'] = 'Invalid email or password';
                return $this->redirect('/login');
            }

            // Verify password
            if (!password_verify($password, $user['password_hash'])) {
                error_log("Login failed: Invalid password for user - {$email}");
                $_SESSION['error'] = 'Invalid email or password';
                return $this->redirect('/login');
            }

            // Check email confirmation
            if (!$user['email_confirmed']) {
                $_SESSION['error'] = 'Please verify your email before logging in';
                return $this->redirect('/login');
            }

            // Set session data
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role_id'];
            $_SESSION['user_location'] = $user['location'];
            $_SESSION['is_verified'] = $user['is_verified'];
            $_SESSION['success'] = "Welcome back, {$user['name']}!";

            error_log("User logged in successfully: {$user['id']} - {$user['email']}");

            // Redirect based on role
            switch ($user['role_id']) {
                case 1: // Admin
                    return $this->redirect('/admin/dashboard');
                case 2: // Regular user
                    return $this->redirect('/');
                case 3: // Artist
                    return $this->redirect('/artist/dashboard');
                default:
                    return $this->redirect('/');
            }
        } catch (\PDOException $e) {
            error_log("Database error during login: " . $e->getMessage());
            $_SESSION['error'] = 'An error occurred during login. Please try again.';
            return $this->redirect('/login');
        } catch (\Exception $e) {
            error_log("Unexpected error during login: " . $e->getMessage());
            $_SESSION['error'] = 'An unexpected error occurred. Please try again.';
            return $this->redirect('/login');
        }
    }
}
