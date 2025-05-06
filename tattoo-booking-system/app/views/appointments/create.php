<?php
// Create Appointment Form

include_once '../partials/header.php';
?>

<div class="container">
    <h2>Create Appointment</h2>
    <form action="/appointments/store" method="POST">
        <div class="form-group">
            <label for="user_id">User ID:</label>
            <input type="text" class="form-control" id="user_id" name="user_id" required>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="time">Time:</label>
            <input type="time" class="form-control" id="time" name="time" required>
        </div>
        <div class="form-group">
            <label for="service">Service:</label>
            <select class="form-control" id="service" name="service" required>
                <option value="tattoo">Tattoo</option>
                <option value="piercing">Piercing</option>
                <option value="consultation">Consultation</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Appointment</button>
    </form>
</div>

<?php
include_once '../partials/footer.php';
?>