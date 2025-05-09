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

    public function showLogin()
    {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function showRegister()
    {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function login()
    {
        // Login logic will be implemented here
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // TODO: Implement actual login logic
        if ($email && $password) {
            // Redirect to home page after successful login
            header('Location: /');
            exit;
        }

        // If login fails, show error
        $error = "Invalid email or password";
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        // Registration logic will be implemented here
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // TODO: Implement actual registration logic
        if ($name && $email && $password) {
            // Redirect to login page after successful registration
            header('Location: /login');
            exit;
        }

        // If registration fails, show error
        $error = "Registration failed. Please try again.";
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout()
    {
        // TODO: Implement logout logic
        session_destroy();
        header('Location: /login');
        exit;
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
