<nav class="bg-white shadow-lg dark:bg-gray-800" x-data="{ mobileMenu: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo Section -->
            <div class="flex-shrink-0 flex items-center <?php echo isset($customNavbarClass) ? $customNavbarClass : ''; ?>">
                <a href="/" class="flex items-center">
                    <img class="h-12 w-auto rounded-lg" src="/assets/images/tatulogo.jpg" alt="Tatu Logo">
                    <span class="ml-2 text-xl font-bold text-gray-800 dark:text-white">Tatu</span>
                </a>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center sm:hidden">
                <button @click="mobileMenu = !mobileMenu" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-accent">
                    <span class="sr-only">Open main menu</span>
                    <svg class="h-6 w-6" x-show="!mobileMenu" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" x-show="mobileMenu" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Navigation based on authentication status -->
                <?php if (!isset($userRole)): ?>
                    <a href="/" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Home</a>
                    <div class="ml-6 flex items-center">
                        <a href="/register" class="ml-0 px-4 py-2 rounded-md text-sm font-medium text-white bg-accent hover:bg-accent-dark">Sign Up</a>
                    </div>
                <?php else: ?>
                    <?php if ($userRole === 'user'): ?>
                        <a href="/feed" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Feed</a>
                        <a href="/artists" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Find Artists</a>
                        <a href="/chat" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Chat</a>
                        <a href="/my-tattoos" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">My Tattoos</a>
                        <a href="/become-artist" class="px-3 py-2 rounded-md text-sm font-medium text-accent hover:text-accent-dark">Become an Artist</a>
                    <?php elseif ($userRole === 'admin'): ?>
                        <a href="/admin/artists" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Manage Artists</a>
                        <a href="/admin/users" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Manage Users</a>
                    <?php elseif ($userRole === 'artist'): ?>
                        <a href="/artist/profile" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Profile</a>
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">
                                Clients
                                <svg class="ml-1 -mr-0.5 h-4 w-4 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700">
                                <div class="py-1">
                                    <a href="/artist/appointments" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Appointments</a>
                                    <a href="/artist/tattoos" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Tattoo Management</a>
                                </div>
                            </div>
                        </div>
                        <a href="/artist/subscription" class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">My Subscription</a>
                    <?php endif; ?>

                    <!-- User Account Dropdown (Common for all roles) -->
                    <div x-data="{ open: false }" class="ml-3 relative">
                        <button @click="open = !open" class="flex items-center space-x-2 px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">
                            <img class="h-8 w-8 rounded-full" src="<?php echo $userAvatar ?? '/assets/images/default-avatar.png'; ?>" alt="User avatar">
                            <span>My Account</span>
                            <svg class="ml-1 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-700">
                            <div class="py-1">
                                <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Settings</a>
                                <form action="/logout" method="POST">
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="mobileMenu" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <?php if (!isset($userRole)): ?>
                <a href="/" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Home</a>
                <div class="border-t border-gray-200 dark:border-gray-600 my-2"></div>
                <a href="/login" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Login</a>
                <a href="/register" class="block px-3 py-2 text-base font-medium text-accent hover:text-accent-dark">Sign Up</a>
            <?php else: ?>
                <?php if ($userRole === 'user'): ?>
                    <a href="/feed" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Feed</a>
                    <a href="/artists" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Find Artists</a>
                    <a href="/chat" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Chat</a>
                    <a href="/my-tattoos" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">My Tattoos</a>
                    <a href="/become-artist" class="block px-3 py-2 text-base font-medium text-accent hover:text-accent-dark">Become an Artist</a>
                <?php elseif ($userRole === 'admin'): ?>
                    <a href="/admin/artists" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Manage Artists</a>
                    <a href="/admin/users" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Manage Users</a>
                <?php elseif ($userRole === 'artist'): ?>
                    <a href="/artist/profile" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Profile</a>
                    <a href="/artist/appointments" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Appointments</a>
                    <a href="/artist/tattoos" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Tattoo Management</a>
                    <a href="/artist/subscription" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">My Subscription</a>
                <?php endif; ?>
                <div class="border-t border-gray-200 dark:border-gray-600"></div>
                <a href="/settings" class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Settings</a>
                <form action="/logout" method="POST">
                    <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-accent dark:text-gray-300 dark:hover:text-white">Logout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>