<!-- filepath: /tattoo-booking-system/tattoo-booking-system/app/views/auth/login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tattoo Booking System</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <?php include '../partials/header.php'; ?>
    
    <div class="container">
        <h2>Login</h2>
        <form action="/auth/login" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="/auth/register">Register here</a>.</p>
    </div>

    <?php include '../partials/footer.php'; ?>
</body>
</html>