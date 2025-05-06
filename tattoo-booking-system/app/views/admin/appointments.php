<?php
// appointments.php

include_once '../partials/header.php';

// Fetch appointments from the database (this is just a placeholder)
$appointments = []; // This should be replaced with actual data fetching logic

?>

<div class="container">
    <h1>Manage Appointments</h1>
    <a href="create.php" class="btn btn-primary">Create New Appointment</a>
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?php echo $appointment['id']; ?></td>
                    <td><?php echo $appointment['user']; ?></td>
                    <td><?php echo $appointment['date']; ?></td>
                    <td><?php echo $appointment['time']; ?></td>
                    <td><?php echo $appointment['status']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $appointment['id']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete.php?id=<?php echo $appointment['id']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include_once '../partials/footer.php'; ?>