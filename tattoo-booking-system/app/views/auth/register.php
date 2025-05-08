<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php include_once '../app/views/partials/header.php'; ?>
    <?php include_once '../app/views/partials/navbar.php'; ?>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="/login" class="font-medium text-accent hover:text-accent-dark">
                    sign in to your existing account
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="/register" method="POST">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm"
                                placeholder="John Doe">
                        </div>
                        <?php if (isset($errors['name'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['name']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                onblur="checkEmailAvailability(this.value)"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm"
                                placeholder="you@example.com">
                        </div>
                        <div id="email-status" class="mt-1 text-sm"></div>
                        <?php if (isset($errors['email'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['email']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700">
                            Location
                        </label>
                        <div class="mt-1">
                            <input id="location" name="location" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm"
                                placeholder="City, Country">
                        </div>
                        <?php if (isset($errors['location'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['location']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm">
                        </div>
                        <div class="mt-2 hidden" id="password-requirements">
                            <div class="text-sm space-y-1">
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
                                    <span id="special-check" class="text-red-500">✗ Contains special character (!@#$%^&*)</span>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['password']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirm Password
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-accent focus:border-accent sm:text-sm">
                        </div>
                        <div class="mt-1 hidden" id="password-match-status">
                            <span class="text-sm text-red-500">Passwords do not match</span>
                        </div>
                        <?php if (isset($errors['password_confirmation'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo $errors['password_confirmation']; ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="terms" name="terms" type="checkbox" required
                                class="h-4 w-4 text-accent focus:ring-accent border-gray-300 rounded">
                            <label for="terms" class="ml-2 block text-sm text-gray-900">
                                I agree to the
                                <a href="/terms" class="font-medium text-accent hover:text-accent-dark">
                                    Terms of Service
                                </a>
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include_once '../app/views/partials/footer.php'; ?>

    <script>
        function checkEmailAvailability(email) {
            if (!email) return;

            const statusDiv = document.getElementById('email-status');
            const submitButton = document.querySelector('button[type="submit"]');

            // Show loading state
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
                    element.innerHTML = '✓ ' + element.textContent.replace('✗ ', '').replace('✓ ', '');
                } else {
                    element.classList.remove('text-green-500');
                    element.classList.add('text-red-500');
                    element.innerHTML = '✗ ' + element.textContent.replace('✗ ', '').replace('✓ ', '');
                }
            });

            checkPasswordMatch();
        });

        document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const submitButton = document.querySelector('button[type="submit"]');
            const matchStatus = document.getElementById('password-match-status');

            if (confirmation.length > 0) {
                matchStatus.classList.remove('hidden');

                if (password === confirmation) {
                    matchStatus.children[0].classList.remove('text-red-500');
                    matchStatus.children[0].classList.add('text-green-500');
                    matchStatus.children[0].innerHTML = '✓ Passwords match';
                } else {
                    matchStatus.children[0].classList.remove('text-green-500');
                    matchStatus.children[0].classList.add('text-red-500');
                    matchStatus.children[0].innerHTML = '✗ Passwords do not match';
                }
            } else {
                matchStatus.classList.add('hidden');
            }

            // Check all requirements
            const allRequirementsMet = [
                    ...document.querySelectorAll('#password-requirements span')
                ].every(span => span.classList.contains('text-green-500')) &&
                password === confirmation && confirmation.length > 0;

            submitButton.disabled = !allRequirementsMet;
            if (!allRequirementsMet) {
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }
    </script>
</body>

</html>