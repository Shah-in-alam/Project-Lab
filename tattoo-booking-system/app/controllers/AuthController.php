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
        // Validate input
        $errors = $this->validateRegistration($_POST);

        if (!empty($errors)) {
            return $this->view('auth/register', ['errors' => $errors]);
        }

        // Create user
        try {
            $result = $this->user->create([
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'name' => $_POST['name'],
                'location' => $_POST['location']
            ]);

            if ($result) {
                // Send confirmation email
                $this->emailService->sendConfirmationEmail(
                    $_POST['email'],
                    $_POST['name'],
                    $result['token']
                );

                // Start session and redirect
                session_start();
                $_SESSION['success'] = 'Registration successful! Please check your email to confirm your account.';
                return $this->redirect('/login');
            }
        } catch (\Exception $e) {
            return $this->view('auth/register', [
                'errors' => ['general' => 'Registration failed. Please try again.']
            ]);
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
