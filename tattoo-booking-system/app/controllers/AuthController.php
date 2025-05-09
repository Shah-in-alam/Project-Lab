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
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            return $this->redirect('/login');
        }

        if ($this->user->verifyEmail($token)) {
            session_start();
            $_SESSION['success'] = 'Email verified successfully! You can now log in.';
        } else {
            $_SESSION['error'] = 'Invalid or expired verification link.';
        }

        return $this->redirect('/login');
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
}
