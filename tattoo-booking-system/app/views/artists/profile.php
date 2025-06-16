<?php include_once __DIR__ . '/../partials/header.php'; ?>

<div class="flex flex-col min-h-screen">
    <?php include_once __DIR__ . '/../partials/navbar.php'; ?>

    <div class="flex-grow bg-gray-50">
        <!-- Profile Header -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
                <!-- Profile Image -->
                <div class="relative group">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-full overflow-hidden ring-4 ring-white shadow-xl">
                        <img
                            src="<?php echo $artist['avatar'] ?? '/assets/images/default-avatar.png'; ?>"
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
                        <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($artist['artist_name']); ?></h1>
                        <a href="/book/<?php echo $artist['id']; ?>"
                            class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition inline-block">
                            Book Appointment
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="flex justify-center md:justify-start space-x-8 mb-4">
                        <div class="text-center">
                            <span class="font-semibold text-gray-900"><?php echo $postsCount ?? 0; ?></span>
                            <span class="text-gray-600 block">posts</span>
                        </div>
                        <div class="text-center">
                            <span class="font-semibold text-gray-900"><?php echo $clientsCount ?? 0; ?></span>
                            <span class="text-gray-600 block">clients</span>
                        </div>
                        <div class="text-center">
                            <span class="font-semibold text-gray-900"><?php echo number_format($rating ?? 0, 1); ?></span>
                            <span class="text-gray-600 block">rating</span>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div class="max-w-lg space-y-3">
                        <div class="grid grid-cols-1 gap-3 text-sm">
                            <!-- Tattoo Style -->
                            <div class="flex items-center space-x-3 p-2 bg-white rounded-lg shadow-sm">
                                <span class="text-lg">🎨</span>
                                <div class="flex-1">
                                    <h3 class="text-gray-500 text-xs uppercase">Styles</h3>
                                    <p class="text-gray-900">
                                        <?php echo !empty($artist['style']) ? htmlspecialchars($artist['style']) : 'No styles specified'; ?>
                                    </p>
                                </div>
                            </div>

                            <!-- Artist Bio -->
                            <?php if (!empty($artist['bio'])): ?>
                                <div class="flex items-center space-x-3 p-2 bg-white rounded-lg shadow-sm">
                                    <span class="text-lg">✍️</span>
                                    <div class="flex-1">
                                        <h3 class="text-gray-500 text-xs uppercase">About</h3>
                                        <p class="text-gray-900"><?php echo nl2br(htmlspecialchars($artist['bio'])); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Section -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Posts</h2>
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
                                        <?php echo $post['likes_count'] ?? 0; ?>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                        </svg>
                                        <?php echo $post['comments_count'] ?? 0; ?>
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
                            <p class="text-gray-500">This artist hasn't shared any work yet</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../partials/post-detail-modal.php'; ?>
<?php include_once __DIR__ . '/../partials/working-hours-modal.php'; ?>

<script>
    // Working Hours Functions
    function showWorkingHours() {
        const workingHours = <?php echo json_encode($workingHours); ?>;
        const content = document.getElementById('workingHoursContent');
        content.innerHTML = formatWorkingHours(workingHours);
        document.getElementById('workingHoursModal').classList.remove('hidden');
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
            if (!schedule || !schedule.is_working) return '';

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

    // Post Detail Functions
    async function openPostDetail(postId, imageUrl) {
        const modal = document.getElementById('postDetailModal');
        const modalImage = document.getElementById('modalImage');
        const artistAvatar = document.getElementById('artistAvatar');
        const artistName = document.getElementById('artistName');

        modalImage.src = imageUrl;
        artistAvatar.src = '<?php echo $artist['avatar'] ?? '/assets/images/default-avatar.png'; ?>';
        artistName.textContent = '<?php echo htmlspecialchars($artist['artist_name']); ?>';

        document.getElementById('postId').value = postId;
        modal.classList.remove('hidden');

        // Load comments and likes
        await Promise.all([
            loadComments(postId),
            fetchLikeStatus(postId)
        ]);
    }

    async function loadComments(postId) {
        try {
            const response = await fetch(`/posts/${postId}/comments`);
            const data = await response.json();

            if (data.status === 'success') {
                const container = document.getElementById('commentsContainer');
                container.innerHTML = data.data.map(comment => `
                    <div class="p-4 border-b border-gray-200">
                        <div class="flex items-center space-x-2">
                            <img src="${comment.avatar || '/assets/images/default-avatar.png'}" 
                                 alt="${comment.user_name}" 
                                 class="w-8 h-8 rounded-full">
                            <div>
                                <span class="font-semibold">${comment.user_name}</span>
                                <span class="text-gray-500 text-sm ml-2">${formatDate(comment.created_at)}</span>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-700">${comment.comment}</p>
                    </div>
                `).join('') || '<p class="text-center py-4 text-gray-500">No comments yet</p>';
            }
        } catch (error) {
            console.error('Error loading comments:', error);
        }
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Comment form submission
        const commentForm = document.getElementById('commentForm');
        commentForm?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const postId = document.getElementById('postId').value;
            const comment = this.querySelector('input[name="comment"]').value;

            try {
                const response = await fetch(`/artist/posts/${postId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        comment
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        this.querySelector('input[name="comment"]').value = '';
                        await loadComments(postId);
                    }
                }
            } catch (error) {
                console.error('Error posting comment:', error);
            }
        });

        // Like button functionality
        const likeButton = document.getElementById('likeButton');
        likeButton?.addEventListener('click', async function() {
            const postId = document.getElementById('postId').value;
            try {
                const response = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        await fetchLikeStatus(postId);
                    }
                }
            } catch (error) {
                console.error('Error toggling like:', error);
            }
        });
    });

    async function fetchLikeStatus(postId) {
        try {
            const response = await fetch(`/posts/${postId}/like-status`);
            const data = await response.json();

            if (data.status === 'success') {
                const {
                    liked,
                    totalLikes
                } = data.data;
                const likeButton = document.getElementById('likeButton');
                const likeCount = document.getElementById('likeCount');

                // Update like button appearance
                likeButton.classList.toggle('liked', liked);
                likeButton.innerHTML = liked ? '❤️ Liked' : '🤍 Like';

                // Update like count
                likeCount.textContent = totalLikes > 0 ? totalLikes : '';
            }
        } catch (error) {
            console.error('Error fetching like status:', error);
        }
    }
</script>

<?php include_once __DIR__ . '/../partials/footer.php'; ?>