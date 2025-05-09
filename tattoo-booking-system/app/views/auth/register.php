<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-gradient {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
    </style>
</head>

<body class="min-h-screen animate-gradient flex flex-col">
    <div class="absolute top-4 left-4">
        <a href="/" class="block">
            <img src="/assets/images/tatulogo.jpg" alt="Tattoo Booking Logo" class="h-16 w-auto rounded-lg shadow-lg">
        </a>
    </div>
    <div class="flex-1 flex justify-center items-center p-8">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-10 w-full max-w-xl">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Create Account</h2>
                <p class="text-gray-600">Join our tattoo community</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6 text-center animate-shake">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <form action="/register" method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-gray-700 font-medium mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" required 
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                               placeholder="Enter first name">
                    </div>
                    <div>
                        <label for="last_name" class="block text-gray-700 font-medium mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required 
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                               placeholder="Enter last name">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           onblur="checkEmailAvailability(this.value)"
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                           placeholder="Enter your email">
                    <div id="email-status" class="mt-1 text-sm"></div>
                </div>

                <div>
                    <label for="location" class="block text-gray-700 font-medium mb-1">Location</label>
                    <input type="text" id="location" name="location" required 
                           class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                           placeholder="City, Country">
                </div>

                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required 
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                               placeholder="Create a password">
                        <button type="button" 
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                            <!-- Eye Icon -->
                            <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Off Icon (hidden by default) -->
                            <svg id="eyeOffIcon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
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
                </div>

                <div>
                    <label for="confirm_password" class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" required 
                               class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                               placeholder="Confirm your password">
                        <button type="button" 
                                onclick="toggleConfirmPassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800 transition-colors">
                            <!-- Eye Icon -->
                            <svg id="confirmEyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Off Icon (hidden by default) -->
                            <svg id="confirmEyeOffIcon" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-1 hidden" id="password-match-status">
                        <span class="text-sm text-red-500">Passwords do not match</span>
                    </div>
                </div>

                <div class="flex items-start mt-4">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required
                               class="w-4 h-4 border border-gray-300 rounded focus:ring-2 focus:ring-blue-200"
                               onchange="checkAllRequirements()">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-gray-700">
                            I agree to the 
                            <a href="/terms" class="text-blue-500 hover:text-blue-600 hover:underline">Terms of Service</a>
                            and
                            <a href="/privacy" class="text-blue-500 hover:text-blue-600 hover:underline">Privacy Policy</a>
                        </label>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center text-gray-600">
                <p>Already have an account? 
                    <a href="/login" class="text-blue-500 font-semibold hover:text-blue-600 hover:underline transition-colors">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../partials/footer.php'; ?>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        function toggleConfirmPassword() {
            const passwordInput = document.getElementById('confirm_password');
            const eyeIcon = document.getElementById('confirmEyeIcon');
            const eyeOffIcon = document.getElementById('confirmEyeOffIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

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
                    } else {
                        statusDiv.innerHTML = '<span class="text-green-600">Email is available</span>';
                        checkAllRequirements();
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

        document.getElementById('confirm_password').addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('confirm_password').value;
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

            checkAllRequirements();
        }

        function checkAllRequirements() {
            const submitButton = document.querySelector('button[type="submit"]');
            const emailStatus = document.getElementById('email-status');
            const passwordRequirements = document.querySelectorAll('#password-requirements span');
            const passwordMatch = document.getElementById('password-match-status');
            const location = document.getElementById('location');
            const terms = document.getElementById('terms');
            
            const allRequirementsMet = 
                emailStatus.textContent.includes('available') &&
                [...passwordRequirements].every(span => span.classList.contains('text-green-500')) &&
                passwordMatch.textContent.includes('match') &&
                location.value.trim() !== '' &&
                terms.checked;

            submitButton.disabled = !allRequirementsMet;
        }

        // Add location field validation
        document.getElementById('location').addEventListener('input', checkAllRequirements);

        // Add terms checkbox validation
        document.getElementById('terms').addEventListener('change', checkAllRequirements);
    </script>
</body>

</html>