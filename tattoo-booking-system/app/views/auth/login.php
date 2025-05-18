<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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

    <?php include_once __DIR__ . '/../partials/navbar.php'; ?>

    <div class="flex-1 flex justify-center items-center p-8">
        <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl p-10 w-full max-w-md flex flex-col">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                <p class="text-gray-600">Sign in to your account</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-500 text-white p-4 rounded-lg mb-6 text-center animate-shake">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <form action="/login" method="POST">
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required 
                           class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                           placeholder="Enter your email">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors"
                               placeholder="Enter your password">
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
                </div>

                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-3 rounded-lg font-semibold hover:bg-blue-600 transform hover:-translate-y-0.5 transition-all duration-200 shadow-lg hover:shadow-xl">
                    Sign In
                </button>
            </form>

            <div class="my-8 flex items-center">
                <div class="flex-grow border-t border-gray-300"></div>
                <span class="mx-4 text-gray-400 font-semibold">or</span>
                <div class="flex-grow border-t border-gray-300"></div>
            </div>

            <div class="flex flex-col gap-3 mb-6">
                <a href="/auth/google" class="flex items-center justify-center gap-3 w-full py-2 rounded-lg bg-white border border-gray-300 shadow hover:bg-gray-50 transition">
                    <svg class="h-6 w-6" viewBox="0 0 48 48"><g><path fill="#4285F4" d="M24 9.5c3.54 0 6.71 1.22 9.2 3.23l6.9-6.9C36.64 2.7 30.7 0 24 0 14.82 0 6.73 5.4 2.69 13.32l8.06 6.26C12.7 13.09 17.9 9.5 24 9.5z"/><path fill="#34A853" d="M46.1 24.5c0-1.64-.15-3.22-.43-4.74H24v9.04h12.4c-.54 2.9-2.18 5.36-4.64 7.04l7.2 5.6C43.27 37.09 46.1 31.34 46.1 24.5z"/><path fill="#FBBC05" d="M10.75 28.08A14.5 14.5 0 019.5 24c0-1.41.23-2.77.63-4.08l-8.06-6.26A23.93 23.93 0 000 24c0 3.77.9 7.34 2.5 10.44l8.25-6.36z"/><path fill="#EA4335" d="M24 48c6.48 0 11.92-2.15 15.89-5.85l-7.2-5.6c-2.01 1.36-4.58 2.17-8.69 2.17-6.1 0-11.3-3.59-13.25-8.66l-8.25 6.36C6.73 42.6 14.82 48 24 48z"/></g></svg>
                    <span class="font-semibold text-gray-700">Continue with Google</span>
                </a>
                <a href="/auth/facebook" class="flex items-center justify-center gap-3 w-full py-2 rounded-lg bg-blue-600 text-white shadow hover:bg-blue-700 transition">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.595 0 0 .592 0 1.326v21.348C0 23.408.595 24 1.325 24h11.495v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.797.143v3.24l-1.918.001c-1.504 0-1.797.715-1.797 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116C23.406 24 24 23.408 24 22.674V1.326C24 .592 23.406 0 22.675 0"/></svg>
                    <span class="font-semibold">Continue with Facebook</span>
                </a>
                <a href="/auth/github" class="flex items-center justify-center gap-3 w-full py-2 rounded-lg bg-gray-900 text-white shadow hover:bg-gray-800 transition">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.084-.729.084-.729 1.205.084 1.84 1.236 1.84 1.236 1.07 1.834 2.809 1.304 3.495.997.108-.775.418-1.305.762-1.605-2.665-.305-5.466-1.334-5.466-5.93 0-1.31.469-2.381 1.236-3.221-.124-.303-.535-1.523.117-3.176 0 0 1.008-.322 3.301 1.23a11.52 11.52 0 013.003-.404c1.018.005 2.045.138 3.003.404 2.291-1.553 3.297-1.23 3.297-1.23.653 1.653.242 2.873.119 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.803 5.624-5.475 5.921.43.372.823 1.102.823 2.222v3.293c0 .322.218.694.825.576C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                    <span class="font-semibold">Continue with GitHub</span>
                </a>
            </div>

            <div class="mt-8 text-center text-gray-600">
                <p>Don't have an account? 
                    <a href="/register" class="text-blue-500 font-semibold hover:text-blue-600 hover:underline transition-colors">
                        Create one here
                    </a>
                </p>
            </div>
        </div>
    </div>

    <div class="mt-auto">
        <?php require_once __DIR__ . '/../partials/footer.php'; ?>
    </div>

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
    </script>
</body>
</html>
