<?php
// dashboard.php

include_once '../partials/header.php';
?>

<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome to the admin dashboard. Here you can manage appointments and users.</p>
    
    <h2>Appointments</h2>
    <a href="appointments.php">View Appointments</a>
    
    <h2>Users</h2>
    <a href="users.php">Manage Users</a>
</div>

<?php
include_once '../partials/footer.php';
?>