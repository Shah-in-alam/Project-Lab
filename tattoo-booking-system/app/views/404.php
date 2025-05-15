<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php include_once '../app/views/partials/header.php'; ?>
    <?php include_once '../app/views/partials/navbar.php'; ?>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-gray-900">404</h1>
            <p class="mt-2 text-2xl text-gray-600">Page not found</p>
            <div class="mt-6">
                <a href="/" class="text-accent hover:text-accent-dark">Return to home page</a>
            </div>
        </div>
    </div>

    <?php include_once '../app/views/partials/footer.php'; ?>
</body>

</html>