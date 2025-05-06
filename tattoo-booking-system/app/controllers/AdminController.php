<?php
class AdminController {
    public function dashboard() {
        // Logic to display the admin dashboard
        include '../views/admin/dashboard.php';
    }

    public function manageAppointments() {
        // Logic to manage appointments
        include '../views/admin/appointments.php';
    }
}
?>