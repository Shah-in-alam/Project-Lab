<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Tattoo World - Book your next tattoo appointment with our trusted artists">
    <meta name="keywords" content="tattoo, booking, appointments, artists, tattoo studio">

    <title><?php echo isset($title) ? $title . ' - Tattoo World' : 'Tattoo World'; ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    </script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/styles.css">

    <!-- Alpine.js for interactions -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<?php
// Check if the user is logged in and get their role
session_start();
$userRole = $_SESSION['user_role'] ?? null;
$userAvatar = $_SESSION['user_avatar'] ?? null;
?>
<script>
    // Initialize Alpine.js
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbar', () => ({
            mobileMenu: false,
            open: false,
        }));
    });
</script>