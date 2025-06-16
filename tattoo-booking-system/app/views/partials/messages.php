<?php if (isset($_SESSION['success'])): ?>
    <div class="notification-message fixed top-4 right-4 z-50 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-md opacity-100 transition-opacity duration-500" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span><?php echo $_SESSION['success']; ?></span>
        </div>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="notification-message fixed top-4 right-4 z-50 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-md opacity-100 transition-opacity duration-500" role="alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span><?php echo $_SESSION['error']; ?></span>
        </div>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifications = document.querySelectorAll('.notification-message');

        notifications.forEach(notification => {
            // Wait 5 seconds before starting fade out
            setTimeout(() => {
                // Add opacity-0 class to fade out
                notification.classList.add('opacity-0');

                // Remove the element after fade animation completes
                setTimeout(() => {
                    notification.remove();
                }, 500); // 500ms matches the duration-500 class
            }, 5000);
        });
    });
</script>