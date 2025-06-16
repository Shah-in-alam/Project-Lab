<nav class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 shadow-xl dark:bg-gray-900" x-data="{ mobileMenu: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <!-- Logo Section -->
            <div class="flex-shrink-0 flex items-center <?php echo isset($customNavbarClass) ? $customNavbarClass : ''; ?>">
                <a href="/" class="flex items-center group">
                    <img class="h-12 w-auto rounded-lg shadow-lg border-2 border-white group-hover:scale-110 transition-transform duration-300" src="/assets/images/tatulogo.jpg" alt="Tatu Logo">
                    <span class="ml-2 text-2xl font-extrabold text-white tracking-wide drop-shadow-lg animate-pulse">Tatu</span>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-lg mx-4 mt-2 hidden md:block">
                <form action="/search" method="GET" class="relative">
                    <input type="text"
                        name="q"
                        placeholder="Search artists, styles, or designs..."
                        class="w-full px-5 py-2.5 rounded-full border-none shadow focus:outline-none focus:ring-2 focus:ring-white bg-white/80 text-gray-800 placeholder-gray-400 transition">
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-pink-500 hover:text-indigo-500 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex md:items-center md:ml-6 space-x-2">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="/" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Home</a>
                    <a href="/login" class="px-5 py-2 rounded-full text-base font-semibold text-white bg-gradient-to-r from-purple-400 via-pink-400 to-yellow-400 shadow hover:scale-105 transition">Login</a>
                    <a href="/register" class="px-5 py-2 rounded-full text-base font-semibold text-white bg-gradient-to-r from-yellow-400 via-pink-400 to-purple-500 shadow hover:scale-105 transition">Sign Up</a>
                <?php else: ?>
                    <?php if ($_SESSION['user_role'] === 2): ?>
                        <a href="/feed" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Feed</a>
                        <a href="/artists" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Find Artists</a>
                        <a href="/chat" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Chat</a>
                        <a href="/my-tattoos" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">My Tattoos</a>
                        <a href="/become-artist" class="px-4 py-2 rounded-full text-base font-semibold text-yellow-300 hover:bg-white/20 transition">Become an Artist</a>
                    <?php elseif ($_SESSION['user_role'] === 1): ?>
                        <a href="/admin/dashboard" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Dashboard</a>
                        <a href="/admin/artists" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Manage Artists</a>
                        <a href="/admin/users" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Manage Users</a>
                    <?php elseif ($_SESSION['user_role'] === 3): ?>
                        <a href="/artist/appointments" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Appointments</a>
                        <a href="/artist/tattoos" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">Tattoo Management</a>
                        <a href="/artist/services" class="px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">My Services</a>
                    <?php endif; ?>
                    <!-- User Account Dropdown -->
                    <div x-data="{ open: false }" class="ml-3 relative">
                        <button @click="open = !open" class="flex items-center space-x-2 px-4 py-2 rounded-full text-base font-semibold text-white hover:bg-white/20 transition">
                            <img class="h-8 w-8 rounded-full border-2 border-white shadow" src="<?php echo $userAvatar ?? '/assets/images/default-avatar.png'; ?>" alt="User avatar">
                            <span>My Account</span>
                            <svg class="ml-1 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-48 rounded-xl shadow-2xl bg-white dark:bg-gray-800 ring-2 ring-pink-200">
                            <div class="py-1">
                                <?php if ($_SESSION['user_role'] === 3): ?>
                                    <a href="/artist/profile" class="block px-4 py-2 text-base text-gray-700 hover:bg-pink-50 dark:text-gray-200 dark:hover:bg-gray-700 rounded">My Profile</a>
                                <?php endif; ?>
                                <a href="/settings" class="block px-4 py-2 text-base text-gray-700 hover:bg-pink-50 dark:text-gray-200 dark:hover:bg-gray-700 rounded">Settings</a>
                                <form action="/logout" method="POST">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-base text-gray-700 hover:bg-pink-50 dark:text-gray-200 dark:hover:bg-gray-700 rounded">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center md:hidden">
                <button @click="mobileMenu = !mobileMenu" type="button" class="inline-flex items-center justify-center p-2 rounded-full text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-7 w-7" x-show="!mobileMenu" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-7 w-7" x-show="mobileMenu" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenu" class="md:hidden bg-gradient-to-br from-pink-100 via-purple-100 to-indigo-100 shadow-xl rounded-b-2xl animate-fade-in-up">
        <div class="px-4 py-3">
            <form action="/search" method="GET" class="relative">
                <input type="text"
                    name="q"
                    placeholder="Search artists, styles, or designs..."
                    class="w-full px-4 py-2 rounded-full border-none shadow focus:outline-none focus:ring-2 focus:ring-pink-300 bg-white/90 text-gray-800 placeholder-gray-400 transition">
                <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-pink-500 hover:text-indigo-500 transition">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>
        <div class="pt-2 pb-3 space-y-1">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="/" class="block px-4 py-2 rounded-full text-base font-semibold text-pink-700 hover:bg-pink-100 transition">Home</a>
                <a href="/login" class="block px-4 py-2 rounded-full text-base font-semibold text-purple-700 hover:bg-purple-100 transition">Login</a>
                <a href="/register" class="block px-4 py-2 rounded-full text-base font-semibold text-white bg-gradient-to-r from-yellow-400 via-pink-400 to-purple-500 shadow hover:scale-105 transition">Sign Up</a>
            <?php else: ?>
                <?php if ($_SESSION['user_role'] === 2): ?>
                    <a href="/feed" class="block px-4 py-2 rounded-full text-base font-semibold text-pink-700 hover:bg-pink-100 transition">Feed</a>
                    <a href="/artists" class="block px-4 py-2 rounded-full text-base font-semibold text-purple-700 hover:bg-purple-100 transition">Find Artists</a>
                    <a href="/chat" class="block px-4 py-2 rounded-full text-base font-semibold text-indigo-700 hover:bg-indigo-100 transition">Chat</a>
                    <a href="/my-tattoos" class="block px-4 py-2 rounded-full text-base font-semibold text-pink-700 hover:bg-pink-100 transition">My Tattoos</a>
                    <a href="/become-artist" class="block px-4 py-2 rounded-full text-base font-semibold text-yellow-500 hover:bg-yellow-100 transition">Become an Artist</a>
                <?php elseif ($_SESSION['user_role'] === 1): ?>
                    <a href="/admin/dashboard" class="block px-4 py-2 rounded-full text-base font-semibold text-pink-700 hover:bg-pink-100 transition">Dashboard</a>
                    <a href="/admin/artists" class="block px-4 py-2 rounded-full text-base font-semibold text-purple-700 hover:bg-purple-100 transition">Manage Artists</a>
                    <a href="/admin/users" class="block px-4 py-2 rounded-full text-base font-semibold text-indigo-700 hover:bg-indigo-100 transition">Manage Users</a>
                <?php elseif ($_SESSION['user_role'] === 3): ?>
                    <a href="/artist/dashboard" class="block px-4 py-2 rounded-full text-base font-semibold text-pink-700 hover:bg-pink-100 transition">Dashboard</a>
                    <a href="/artist/appointments" class="block px-4 py-2 rounded-full text-base font-semibold text-purple-700 hover:bg-purple-100 transition">Appointments</a>
                    <a href="/artist/tattoos" class="block px-4 py-2 rounded-full text-base font-semibold text-indigo-700 hover:bg-indigo-100 transition">Tattoo Management</a>
                    <a href="/artist/services" class="block px-4 py-2 rounded-full text-base font-semibold text-yellow-500 hover:bg-yellow-100 transition">My Services</a>
                <?php endif; ?>
                <a href="/settings" class="block px-4 py-2 rounded-full text-base font-semibold text-gray-700 hover:bg-gray-100 transition">Settings</a>
                <form action="/logout" method="POST">
                    <button type="submit" class="block w-full text-left px-4 py-2 rounded-full text-base font-semibold text-gray-700 hover:bg-gray-100 transition">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>