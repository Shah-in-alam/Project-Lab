<div id="postDetailModal" class="fixed inset-0 z-50 hidden backdrop-blur-sm">
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black/30"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white/95 rounded-2xl shadow-2xl w-full max-w-6xl mx-auto overflow-hidden">
            <div class="flex h-[80vh]">
                <!-- Left side - Image -->
                <div class="w-2/3 bg-black flex items-center">
                    <img id="modalImage" src="" alt="Post detail" class="w-full h-full object-contain">
                </div>

                <!-- Right side - Comments -->
                <div class="w-1/3 flex flex-col bg-white">
                    <!-- Header with close button -->
                    <div class="p-4 border-b flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <img id="artistAvatar" src="/assets/images/default-avatar.png" alt="Artist" class="w-8 h-8 rounded-full">
                            <span id="artistName" class="font-semibold"></span>
                        </div>
                        <button class="modal-close text-gray-500 hover:text-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Comments Section -->
                    <div class="flex-1 overflow-y-auto" id="commentsContainer">
                        <!-- Comments will be loaded here dynamically -->
                        <div class="p-4 space-y-4">
                            <!-- Individual comments will be inserted here -->
                        </div>
                    </div>

                    <!-- Like and Comment Section -->
                    <div class="border-t p-4">
                        <!-- Like Button Area -->
                        <div class="flex items-center space-x-4 mb-4">
                            <button id="likeButton" class="flex items-center space-x-2 focus:outline-none group">
                                <svg class="w-6 h-6 text-gray-500 transition-colors duration-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                                </svg>
                                <span id="likeCount" class="font-medium">0</span>
                            </button>
                        </div>

                        <!-- Comment Form -->
                        <form id="commentForm" class="flex items-center space-x-2">
                            <input type="hidden" id="postId" name="post_id" value="">
                            <input
                                type="text"
                                name="comment"
                                class="flex-1 border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Add a comment...">
                            <button
                                type="submit"
                                class="px-4 py-2 bg-purple-500 text-white rounded-full hover:bg-purple-600 transition">
                                Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add these functions before the DOMContentLoaded event
    function updateLikeUI(count, isLiked) {
        const likeButton = document.getElementById('likeButton');
        const likeIcon = likeButton.querySelector('svg');
        const likeCount = document.getElementById('likeCount');

        // Update count
        likeCount.textContent = count;

        // Update like button appearance
        if (isLiked) {
            likeIcon.classList.add('text-pink-500');
            likeIcon.classList.remove('text-gray-500');
        } else {
            likeIcon.classList.remove('text-pink-500');
            likeIcon.classList.add('text-gray-500');
        }
    }

    async function fetchLikeStatus(postId) {
        try {
            const response = await fetch(`/artist/posts/${postId}/like-status`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const text = await response.text(); // First get the raw text
            let data;
            try {
                data = JSON.parse(text); // Try to parse it as JSON
            } catch (e) {
                console.error('Invalid JSON response:', text);
                throw new Error('Invalid JSON response from server');
            }

            if (!data || typeof data.likesCount === 'undefined') {
                throw new Error('Invalid response format');
            }

            updateLikeUI(data.likesCount, data.isLiked);
        } catch (error) {
            console.error('Error fetching like status:', error);
            // Set default values on error
            updateLikeUI(0, false);
        }
    }

    // Move this before the DOMContentLoaded event listener
    window.retryLoad = function(postId) {
        const imageUrl = document.getElementById('modalImage')?.src;
        if (postId && imageUrl) {
            window.openPostDetail(postId, imageUrl);
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('postDetailModal');
        const commentsContainer = document.getElementById('commentsContainer');
        const commentForm = document.getElementById('commentForm');
        const likeButton = document.getElementById('likeButton');
        const closeButton = modal.querySelector('.modal-close');
        let currentPostId = null;

        // Add close button handler
        closeButton.addEventListener('click', function() {
            modal.classList.add('hidden');
            currentPostId = null;
        });

        // Update backdrop click handler
        modal.addEventListener('click', function(e) {
            // Only close if clicking the actual backdrop
            if (e.target.classList.contains('bg-black/30')) {
                modal.classList.add('hidden');
                currentPostId = null;
            }
        });

        // Keep the existing openPostDetail function
        window.openPostDetail = async function(postId, imageUrl) {
            try {
                // Debug logging
                console.log('Opening post detail for:', postId);

                currentPostId = postId;
                modal.classList.remove('hidden');
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('postId').value = postId;

                // Clear existing content
                commentsContainer.innerHTML = '<div class="p-4">Loading comments...</div>';
                document.getElementById('likeCount').textContent = '...';

                // Load comments with debug
                console.log('Fetching comments from:', `/artist/posts/${postId}/comments`);
                const commentsResponse = await fetch(`/artist/posts/${postId}/comments`);

                if (!commentsResponse.ok) {
                    console.error('Comments response not OK:', commentsResponse.status);
                    throw new Error(`HTTP error! status: ${commentsResponse.status}`);
                }

                const response = await commentsResponse.json();
                console.log('Parsed response:', response);

                if (!response || !response.status) {
                    console.error('Invalid response format:', response);
                    throw new Error('Invalid response format');
                }

                // Update comments section
                if (response.status === 'success' && Array.isArray(response.data)) {
                    if (response.data.length > 0) {
                        const commentHTML = response.data.map(comment => `
                            <div class="p-4 border-b border-gray-200/50">
                                <div class="flex items-center space-x-2 mb-1">
                                    <img src="${comment.avatar || '/assets/images/default-avatar.png'}" 
                                         alt="${comment.user_name}" 
                                         class="w-8 h-8 rounded-full">
                                    <div>
                                        <span class="font-semibold">${comment.user_name}</span>
                                        <span class="text-gray-500 text-sm ml-2">${formatDate(comment.created_at)}</span>
                                    </div>
                                </div>
                                <p class="text-gray-700 mt-2">${comment.comment}</p>
                            </div>
                        `).join('');
                        commentsContainer.innerHTML = commentHTML;
                    }
                } else {
                    throw new Error('Invalid response format');
                }

                // Add this line to fetch like status
                await fetchLikeStatus(postId);
            } catch (error) {
                handleError(error, postId, imageUrl);
            }
        };

        // Keep your existing like button handler
        likeButton.addEventListener('click', async function() {
            if (!currentPostId) return;

            try {
                const response = await fetch(`/artist/posts/${currentPostId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response:', text);
                    throw new Error('Invalid JSON response from server');
                }

                if (data.success) {
                    updateLikeUI(data.likesCount, data.action === 'liked');
                } else {
                    throw new Error(data.message || 'Failed to toggle like');
                }
            } catch (error) {
                console.error('Error toggling like:', error);
                // Refresh like status on error
                await fetchLikeStatus(currentPostId);
            }
        });

        // Update the comment submission handler
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!currentPostId) return;

            const commentInput = this.querySelector('input[name="comment"]');
            const comment = commentInput.value.trim();
            if (!comment) return;

            try {
                const response = await fetch(`/artist/posts/${currentPostId}/comments`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        comment: comment
                    })
                });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Invalid JSON response:', text);
                    throw new Error('Invalid JSON response from server');
                }

                if (data.success) {
                    const commentElement = document.createElement('div');
                    commentElement.className = 'p-4 border-b border-gray-200/50 hover:bg-gray-50/50 transition duration-150';
                    commentElement.innerHTML = `
                        <div class="flex items-center space-x-2 mb-1">
                            <img src="${data.avatar || '/assets/images/default-avatar.png'}" 
                                 alt="${data.user_name}" 
                                 class="w-8 h-8 rounded-full">
                            <div>
                                <span class="font-semibold">${data.user_name}</span>
                                <span class="text-gray-500 text-sm ml-2">just now</span>
                            </div>
                        </div>
                        <p class="text-gray-700 mt-2 ">${comment}</p>
                    `;

                    // Add the new comment at the top
                    if (commentsContainer.firstChild) {
                        commentsContainer.insertBefore(commentElement, commentsContainer.firstChild);
                    } else {
                        commentsContainer.appendChild(commentElement);
                    }

                    // Clear input
                    commentInput.value = '';
                } else {
                    throw new Error(data.error || 'Failed to post comment');
                }
            } catch (error) {
                console.error('Error posting comment:', error);
            }
        });

        // Helper function to add a new comment to the UI
        function addNewComment(comment) {
            const commentElement = document.createElement('div');
            commentElement.className = 'mb-4';
            commentElement.innerHTML = `
                <div class="flex items-center space-x-2 mb-1">
                    <span class="font-semibold">${comment.user_name}</span>
                    <span class="text-gray-500 text-sm">${formatDate(comment.created_at)}</span>
                </div>
                <p class="text-gray-700">${comment.comment}</p>
            `;

            // Add the new comment at the top of the container
            commentsContainer.insertBefore(commentElement, commentsContainer.firstChild);
        }

        // Update loadComments function to maintain scroll position
        async function loadComments(postId) {
            try {
                const response = await fetch(`/artist/posts/${postId}/comments`);
                const comments = await response.json();

                const scrollPos = commentsContainer.scrollTop;

                commentsContainer.innerHTML = comments.map(comment => `
                    <div class="p-4 border-b border-gray-200/50">
                        <div class="flex items-center space-x-2 mb-1">
                            <img src="${comment.avatar || '/assets/images/default-avatar.png'}" 
                                 alt="${comment.user_name}" 
                                 class="w-8 h-8 rounded-full">
                            <div>
                                <span class="font-semibold">${comment.user_name}</span>
                                <span class="text-gray-500 text-sm ml-2">${formatDate(comment.created_at)}</span>
                            </div>
                        </div>
                        <p class="text-gray-700 mt-2">${comment.comment}</p>
                    </div>
                `).join('');

                commentsContainer.scrollTop = scrollPos;
            } catch (error) {
                console.error('Error loading comments:', error);
            }
        }

        async function loadLikeStatus(postId) {
            try {
                const response = await fetch(`/artist/posts/${postId}/like-status`);
                const data = await response.json();

                document.getElementById('likeCount').textContent = data.likesCount;
                likeButton.classList.toggle('text-pink-500', data.isLiked);
                likeButton.classList.toggle('text-gray-500', !data.isLiked);
            } catch (error) {
                console.error('Error loading like status:', error);
            }
        }

        // Add retry function
        function retryLoad(postId) {
            if (postId) {
                openPostDetail(postId, document.getElementById('modalImage').src);
            }
        }

        // Update the formatDate function to handle invalid dates
        function formatDate(dateString) {
            if (!dateString) return 'unknown date';

            try {
                const date = new Date(dateString);
                if (isNaN(date.getTime())) throw new Error('Invalid date');

                const now = new Date();
                const diff = Math.floor((now - date) / 1000);

                if (diff < 60) return 'just now';
                if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
                if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
                if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;

                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });
            } catch (e) {
                console.error('Date formatting error:', e);
                return 'unknown date';
            }
        }

        // Update the existing error handling function in post-detail-modal.php
        function handleError(error, postId, imageUrl) {
            console.group('Error Details');
            console.error('Error:', {
                message: error.message,
                name: error.name,
                stack: error.stack,
                postId: postId,
                imageUrl: imageUrl
            });
            console.groupEnd();

            commentsContainer.innerHTML = `
                <div class="p-4">
                    <div class="text-red-500 mb-2">
                        Error: ${error.message || 'Failed to load comments'}
                    </div>
                    <div class="text-gray-500 text-sm mb-4">
                        Please try again or contact support if the problem persists.
                        <br>
                        <code class="text-xs bg-gray-100 p-1 rounded">${error.name}: ${error.message}</code>
                    </div>
                    <button onclick="window.retryLoad('${postId}')" 
                            class="text-purple-500 hover:underline">
                        Retry Loading Comments
                    </button>
                </div>
            `;
        }

        // Add this debug function:
        function debugResponse(response) {
            console.group('Response Debug');
            console.log('Status:', response.status);
            console.log('Headers:', Object.fromEntries(response.headers.entries()));
            console.log('Body:', response);
            console.groupEnd();
        }
    });
</script>