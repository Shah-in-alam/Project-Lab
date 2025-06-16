<div id="createPostModal" class="fixed inset-0 z-50 hidden backdrop-blur-sm">
    <!-- Modal Backdrop -->
    <div class="fixed inset-0 bg-black/30"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white/95 rounded-2xl shadow-2xl max-w-lg w-full mx-auto backdrop-blur-md">
            <form action="/artist/posts" method="POST" enctype="multipart/form-data">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Create New Post</h3>
                        <button type="button" class="modal-close text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Upload Area -->
                    <div class="mb-6">
                        <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-500 transition cursor-pointer">
                            <input type="file" id="fileInput" name="image" class="hidden" accept="image/*">
                            <div id="preview" class="hidden">
                                <img id="previewImage" class="mx-auto max-h-48 rounded-lg">
                            </div>
                            <div id="placeholder">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="mt-4 text-sm text-gray-600">Drag and drop your photos here, or click to select</p>
                            </div>
                        </div>
                    </div>

                    <!-- Caption -->
                    <div class="mb-6">
                        <label for="caption" class="block text-sm font-medium text-gray-700 mb-2">Caption</label>
                        <textarea
                            id="caption"
                            name="caption"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                            placeholder="Write a caption..."></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        id="submitButton"
                        class="w-full px-6 py-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition shadow-lg disabled:opacity-50"
                        disabled>
                        Share Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .modal-fade-in {
        animation: modalFadeIn 0.2s ease-out;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
            backdrop-filter: blur(0px);
        }

        to {
            opacity: 1;
            transform: scale(1);
            backdrop-filter: blur(8px);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('createPostModal');
        const closeBtn = modal.querySelector('.modal-close');
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const preview = document.getElementById('preview');
        const previewImage = document.getElementById('previewImage');
        const placeholder = document.getElementById('placeholder');
        const submitButton = document.getElementById('submitButton');

        // Open modal
        window.openCreatePostModal = function() {
            modal.classList.remove('hidden');
            modal.querySelector('.bg-white').classList.add('modal-fade-in');
        };

        // Close modal
        function closeModal() {
            modal.classList.add('hidden');
        }

        // Close modal events
        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });

        // File handling
        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-purple-500');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-purple-500');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-purple-500');
            handleFiles(e.dataTransfer.files);
        });

        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(files) {
            const file = files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                    submitButton.disabled = false;
                };
                reader.readAsDataURL(file);
            }
        }
    });
</script>