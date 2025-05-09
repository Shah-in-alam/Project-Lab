<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tattoo Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out;
        }
        .animated-bg {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        .main-content {
            min-height: calc(100vh - 64px - 400px); /* viewport height - navbar height - footer height */
        }
        /* Custom navbar styles for register page */
        .navbar-logo {
            margin-left: -1rem !important;
            padding-left: 0 !important;
        }
        .navbar-logo img {
            height: 2.5rem !important;
            width: auto !important;
        }
        .navbar-logo span {
            font-size: 1.25rem !important;
            font-weight: 700 !important;
        }
        @media (max-width: 640px) {
            .navbar-logo {
                margin-left: 0 !important;
            }
        }
    </style>
</head>

<body class="animated-bg flex flex-col min-h-screen">
    <?php 
    // Add custom class to navbar logo
    $customNavbarClass = 'navbar-logo';
    include_once '../app/views/partials/navbar.php'; 
    ?>

    <div class="main-content flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 animate-fade-in">
        <div class="max-w-md w-full space-y-8 bg-white/90 backdrop-blur-sm rounded-2xl p-8 shadow-2xl">
            <!-- Header -->
            <div class="text-center animate-slide-up" style="animation-delay: 0.1s">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Create your account
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Or
                    <a href="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
                        sign in to your existing account
                    </a>
                </p>
            </div>

            <!-- Registration Form -->
            <form class="mt-8 space-y-6" action="/register" method="POST">
                <div class="rounded-md shadow-sm -space-y-px animate-slide-up" style="animation-delay: 0.2s">
                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input id="name" name="name" type="text" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition duration-150 ease-in-out"
                            placeholder="John Doe">
                        <?php if (isset($errors['name'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['name']; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            onblur="checkEmailAvailability(this.value)"
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition duration-150 ease-in-out"
                            placeholder="you@example.com">
                        <div id="email-status" class="mt-1 text-sm"></div>
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['email']; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input id="location" name="location" type="text" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition duration-150 ease-in-out"
                            placeholder="City, Country">
                        <?php if (isset($errors['location'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['location']; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition duration-150 ease-in-out">
                        <div id="password-requirements" class="mt-2 hidden space-y-1 text-sm">
                            <div class="flex items-center">
                                <span id="length-check" class="text-red-500">✗ At least 8 characters</span>
                            </div>
                            <div class="flex items-center">
                                <span id="uppercase-check" class="text-red-500">✗ Contains uppercase letter</span>
                            </div>
                            <div class="flex items-center">
                                <span id="lowercase-check" class="text-red-500">✗ Contains lowercase letter</span>
                            </div>
                            <div class="flex items-center">
                                <span id="number-check" class="text-red-500">✗ Contains number</span>
                            </div>
                            <div class="flex items-center">
                                <span id="special-check" class="text-red-500">✗ Contains special character</span>
                            </div>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['password']; ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm transition duration-150 ease-in-out">
                        <div id="password-match-status" class="mt-1 hidden">
                            <span class="text-sm text-red-500">Passwords do not match</span>
                        </div>
                        <?php if (isset($errors['password_confirmation'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['password_confirmation']; ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-center animate-slide-up" style="animation-delay: 0.3s">
                    <input id="terms" name="terms" type="checkbox" required
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition duration-150 ease-in-out">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the
                        <a href="/terms" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Terms of Service
                        </a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="animate-slide-up" style="animation-delay: 0.4s">
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out transform hover:-translate-y-1">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once '../app/views/partials/footer.php'; ?>

    <script>
        // Email availability check
        function checkEmailAvailability(email) {
            if (!email) return;

            const statusDiv = document.getElementById('email-status');
            const submitButton = document.querySelector('button[type="submit"]');

            statusDiv.innerHTML = '<span class="text-gray-500">Checking availability...</span>';

            fetch('/check-email?email=' + encodeURIComponent(email))
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        statusDiv.innerHTML = '<span class="text-red-600">This email is already registered</span>';
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        statusDiv.innerHTML = '<span class="text-green-600">Email is available</span>';
                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    statusDiv.innerHTML = '<span class="text-red-600">Error checking email availability</span>';
                });
        }

        // Password validation
        document.getElementById('password').addEventListener('focus', function() {
            document.getElementById('password-requirements').classList.remove('hidden');
        });

        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            const submitButton = document.querySelector('button[type="submit"]');

            // Password requirements
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*]/.test(password)
            };

            // Update requirement status
            Object.keys(requirements).forEach(requirement => {
                const element = document.getElementById(`${requirement}-check`);
                if (requirements[requirement]) {
                    element.classList.remove('text-red-500');
                    element.classList.add('text-green-500');
                    element.textContent = element.textContent.replace('✗', '✓');
                } else {
                    element.classList.remove('text-green-500');
                    element.classList.add('text-red-500');
                    element.textContent = element.textContent.replace('✓', '✗');
                }
            });

            // Check password match
            const confirmPassword = document.getElementById('password_confirmation').value;
            const matchStatus = document.getElementById('password-match-status');
            if (confirmPassword) {
                if (password === confirmPassword) {
                    matchStatus.classList.add('hidden');
                } else {
                    matchStatus.classList.remove('hidden');
                }
            }
        });

        // Password confirmation check
        document.getElementById('password_confirmation').addEventListener('input', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = e.target.value;
            const matchStatus = document.getElementById('password-match-status');

            if (password === confirmPassword) {
                matchStatus.classList.add('hidden');
            } else {
                matchStatus.classList.remove('hidden');
            }
        });
    </script>
</body>

</html>