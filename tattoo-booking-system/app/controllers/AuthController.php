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

        // Check if email already exists
        if ($this->user->findByEmail($_POST['email'])) {
            session_start();
            $_SESSION['error'] = 'This email is already registered. Please use a different email or login.';
            return $this->redirect('/');
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
        } catch (\PDOException $e) {
            // Handle database errors, including duplicate entries
            if ($e->getCode() == 23000) { // MySQL duplicate entry error code
                session_start();
                $_SESSION['error'] = 'This email is already registered. Please use a different email or login.';
                return $this->redirect('/');
            }

            return $this->view('auth/register', [
                'errors' => ['general' => 'Registration failed. Please try again.']
            ]);
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

        $user = $this->user->verifyEmail($token);

        if ($user) {
            // Start session and set user data
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role_id'];
            $_SESSION['success'] = 'Email verified successfully! Welcome to your account.';

            // Redirect based on role
            if ($user['role_id'] === 1) { // Admin
                return $this->redirect('/admin/dashboard');
            } else if ($user['role_id'] === 3) { // Artist
                return $this->redirect('/artist/dashboard');
            } else { // Regular user
                return $this->redirect('/dashboard');
            }
        } else {
            $_SESSION['error'] = 'Invalid or expired verification link.';
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

    // Add this method to your AuthController class

    public function authenticate()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required';
            return $this->redirect('/login');
        }

        $user = $this->user->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['error'] = 'Invalid email or password';
            return $this->redirect('/login');
        }

        if (!$user['email_confirmed']) {
            $_SESSION['error'] = 'Please verify your email before logging in';
            return $this->redirect('/login');
        }

        // Start session and set user data
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role_id'];
        $_SESSION['is_verified'] = $user['is_verified'];
        $_SESSION['location'] = $user['location'];
        $_SESSION['success'] = 'Welcome back, ' . $user['name'] . '!';


        // Redirect based on role
        if ($user['role_id'] === 1) { // Admin
            return $this->redirect('/admin/dashboard');
        } else if ($user['role_id'] === 3) { // Artist
            return $this->redirect('/artist/dashboard');
        } else { // Regular user
            return $this->redirect('/');
        }
    }
}
