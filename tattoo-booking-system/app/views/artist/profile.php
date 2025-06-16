<?php include_once __DIR__ . '/../partials/header.php'; ?>

<!-- Change the outer div structure -->
<div class="flex flex-col min-h-screen">
    <?php include_once __DIR__ . '/../partials/navbar.php'; ?>

    <!-- Add a flex-grow div to push footer down -->
    <div class="flex-grow bg-gray-50">
        <!-- Profile Header -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Profile Image -->
                <div class="relative group">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden ring-4 ring-white shadow-xl">
                        <img
                            src="<?php echo $userAvatar ?? '/assets/images/default-avatar.png'; ?>"
                            alt="Artist Profile"
                            class="w-full h-full object-cover">
                    </div>
                    <div class="flex gap-2 mt-6">
                        <button
                            class="w-full px-3 py-1.5 bg-white text-gray-700 rounded-md border border-gray-200 hover:bg-gray-50 shadow-sm text-sm font-medium"
                            onclick="showWorkingHours()">
                            ⏰ Hours
                        </button>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center md:text-left">
                    <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo $_SESSION['user_name']; ?></h1>
                        <a href="/artist/profile/edit" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition inline-block">
                            Edit Profile
                        </a>
                        <!-- <button
                            onclick="showWorkingHours()"
                            class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition inline-block">
                            Check Working Hours
                        </button> -->
                    </div>

                    <!-- Stats -->
                    <div class="flex justify-center md:justify-start space-x-8 mb-4">
                        <div class="text-center">
                            <span class="font-semibold text-gray-900">245</span>
                            <span class="text-gray-600 block">posts</span>
                        </div>
                        <div class="text-center">
                            <span class="font-semibold text-gray-900">1.2k</span>
                            <span class="text-gray-600 block">clients</span>
                        </div>
                        <div class="text-center">
                            <span class="font-semibold text-gray-900">4.9</span>
                            <span class="text-gray-600 block">rating</span>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="max-w-lg space-y-3">
                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 gap-3 text-sm">
                            <!-- Tattoo Style -->
                            <div class="flex items-center space-x-3 p-2 bg-white rounded-lg shadow-sm">
                                <span class="text-lg">🎨</span>
                                <div class="flex-1">
                                    <h3 class="text-gray-500 text-xs uppercase">Styles</h3>
                                    <p class="text-gray-900">
                                        <?php echo !empty($profile['style']) ? htmlspecialchars($profile['style']) : 'No styles added yet'; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Artist Bio -->
                            <div class="flex items-center space-x-3 p-2 bg-white rounded-lg shadow-sm">
                                <span class="text-lg">✍️</span>
                                <div class="flex-1">
                                    <h3 class="text-gray-500 text-xs uppercase">About Me</h3>
                                    <p class="text-gray-900">
                                        <?php echo !empty($profile['bio']) ? htmlspecialchars($profile['bio']) : 'Bio not added yet'; ?>
                                    </p>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Post Button -->
        <div class="max-w-4xl mx-auto px-4 py-4 border-t border-gray-200">
            <button
                onclick="openCreatePostModal()"
                type="button"
                class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-xl hover:from-purple-600 hover:to-pink-600 transition shadow-lg flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Post
            </button>
        </div>

        <!-- Gallery Grid -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="relative group aspect-square bg-gray-100 rounded-lg overflow-hidden hover:opacity-90 transition cursor-pointer"
                            onclick="openPostDetail('<?php echo $post['id']; ?>', '<?php echo htmlspecialchars($post['image_url']); ?>')">
                            <img
                                src="<?php echo htmlspecialchars($post['image_url']); ?>"
                                alt="Tattoo artwork"
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <div class="flex items-center space-x-4 text-white">
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                        </svg>
                                        <?php echo $post['likes_count']; ?>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                        </svg>
                                        <?php echo $post['comments_count']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-3 py-20 text-center">
                        <div class="max-w-sm mx-auto">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No posts yet</h3>
                            <p class="text-gray-500">Start sharing your work by creating your first post</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- Footer will now stick to bottom -->
    <footer>
        <?php include_once __DIR__ . '/../partials/footer.php'; ?>
    </footer>
</div>

<?php include_once __DIR__ . '/../partials/create-post-modal.php'; ?>
<?php include_once __DIR__ . '/../partials/post-detail-modal.php'; ?>

<!-- Add this before the closing body tag -->
<div id="workingHoursModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 backdrop-blur-sm bg-black/20"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Working Hours</h3>
                <button onclick="closeWorkingHoursModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4">
                <div id="workingHoursContent" class="divide-y divide-gray-200">
                    <!-- Working hours will be populated here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add this before the closing body tag, after the modal markup -->
<script>
    async function showWorkingHours() {
        try {
            const response = await fetch(`/artist/working-hours`);
            const workingHours = await response.json();

            if (response.ok) {
                const content = document.getElementById('workingHoursContent');
                content.innerHTML = formatWorkingHours(workingHours);
                document.getElementById('workingHoursModal').classList.remove('hidden');
            } else {
                throw new Error(workingHours.error || 'Failed to load working hours');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Failed to load working hours');
        }
    }

    function formatWorkingHours(hours) {
        const days = {
            1: 'Monday',
            2: 'Tuesday',
            3: 'Wednesday',
            4: 'Thursday',
            5: 'Friday'
        };

        return Object.entries(hours).map(([dayNum, schedule]) => {
            if (!schedule.is_working) return '';

            const startTime = schedule.start_time.substring(0, 5);
            const endTime = schedule.end_time.substring(0, 5);

            return `
                <div class="py-3 flex items-center justify-between">
                    <span class="font-medium text-gray-900">${days[dayNum]}</span>
                    <span class="text-gray-600">${startTime} - ${endTime}</span>
                </div>
            `;
        }).join('');
    }

    function closeWorkingHoursModal() {
        document.getElementById('workingHoursModal').classList.add('hidden');
    }
</script>