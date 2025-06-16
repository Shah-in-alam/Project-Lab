<?php include_once __DIR__ . '/../partials/header.php'; ?>

<div class="flex flex-col min-h-screen">
    <?php include_once __DIR__ . '/../partials/navbar.php'; ?>

    <div class="flex-grow bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Search Filters -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form action="/artists" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Name Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Artist Name</label>
                            <input type="text"
                                name="name"
                                value="<?php echo htmlspecialchars($filters['name']); ?>"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <!-- Style Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Style</label>
                            <input type="text"
                                name="style"
                                value="<?php echo htmlspecialchars($filters['style']); ?>"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <!-- Location Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                            <input type="text"
                                name="location"
                                value="<?php echo htmlspecialchars($filters['location']); ?>"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>

                        <!-- Day Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Working Day</label>
                            <select name="day" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Any Day</option>
                                <option value="1" <?php echo $filters['day'] == '1' ? 'selected' : ''; ?>>Monday</option>
                                <option value="2" <?php echo $filters['day'] == '2' ? 'selected' : ''; ?>>Tuesday</option>
                                <option value="3" <?php echo $filters['day'] == '3' ? 'selected' : ''; ?>>Wednesday</option>
                                <option value="4" <?php echo $filters['day'] == '4' ? 'selected' : ''; ?>>Thursday</option>
                                <option value="5" <?php echo $filters['day'] == '5' ? 'selected' : ''; ?>>Friday</option>
                            </select>
                        </div>

                        <!-- Time Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Time</label>
                            <input type="time"
                                name="time"
                                value="<?php echo htmlspecialchars($filters['time']); ?>"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Search Artists
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($artists as $artist): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <img src="<?php echo htmlspecialchars($artist['avatar'] ?? '/assets/images/default-avatar.png'); ?>"
                                    alt="<?php echo htmlspecialchars($artist['artist_name']); ?>"
                                    class="w-16 h-16 rounded-full object-cover">
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <?php echo htmlspecialchars($artist['artist_name']); ?>
                                    </h3>
                                    <?php if (!empty($artist['location'])): ?>
                                        <p class="text-sm text-gray-500">
                                            📍 <?php echo htmlspecialchars($artist['location']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if (!empty($artist['style'])): ?>
                                <p class="text-gray-600 mb-4">
                                    🎨 <?php echo htmlspecialchars($artist['style']); ?>
                                </p>
                            <?php endif; ?>

                            <div class="flex justify-end">
                                <a href="/artists/profile/<?php echo htmlspecialchars($artist['id']); ?>"
                                    class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($artists)): ?>
                <div class="text-center py-12">
                    <p class="text-gray-500 text-lg">No artists found matching your criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../partials/footer.php'; ?>