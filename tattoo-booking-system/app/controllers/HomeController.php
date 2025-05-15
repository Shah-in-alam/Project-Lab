<?php

namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Get session data based on DB structure and roles table
        $userData = [
            'title' => 'Welcome to Tattoo World',
            'userRole' => $this->getRoleName($_SESSION['user_role'] ?? null),
            'userName' => $_SESSION['user_name'] ?? null,
            'userId' => $_SESSION['user_id'] ?? null,
            'isVerified' => $_SESSION['is_verified'] ?? false
        ];

        return $this->view('home', $userData);
    }

    private function getRoleName($roleId)
    {
        if (!$roleId) return null;

        $roles = [
            1 => 'admin',
            2 => 'user',
            3 => 'artist'
        ];

        return $roles[$roleId] ?? 'user';
    }
}
