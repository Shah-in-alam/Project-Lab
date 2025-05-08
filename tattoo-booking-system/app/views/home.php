<?php include_once 'partials/header.php'; ?>
<?php include_once 'partials/navbar.php'; ?>
<?php include_once 'partials/messages.php'; ?>

<!-- Hero Section -->
<div class="relative bg-gray-900 overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block">Find Your Perfect</span>
                        <span class="block text-accent">Tattoo Artist</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Connect with talented tattoo artists, book appointments, and showcase your ink in our growing community.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="/artists" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-accent hover:bg-accent-dark md:py-4 md:text-lg md:px-10">
                                Find Artists
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="/register" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-accent bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                Join Now
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="/assets/images/hero-banner.jpg" alt="Tattoo artist at work">
    </div>
</div>

<!-- Featured Artists Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Featured Artists
            </h2>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                Meet our top-rated tattoo artists who are ready to bring your vision to life.
            </p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                <!-- Artist Cards - Replace with dynamic data -->
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                        <div class="relative pb-2/3">
                            <img class="absolute h-full w-full object-cover" src="/assets/images/artist-<?php echo $i + 1; ?>.jpg" alt="Artist profile">
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900">Artist Name</h3>
                            <p class="mt-2 text-sm text-gray-500">Specializing in Traditional, Japanese</p>
                            <div class="mt-4">
                                <a href="/artists/profile" class="text-accent hover:text-accent-dark">View Profile →</a>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<!-- Latest Works Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Latest Works
            </h2>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                Check out the most recent masterpieces from our talented artists.
            </p>
        </div>

        <div class="mt-10">
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                <?php for ($i = 0; $i < 8; $i++): ?>
                    <div class="relative pb-full rounded-lg overflow-hidden">
                        <img class="absolute h-full w-full object-cover" src="/assets/images/tattoo-<?php echo $i + 1; ?>.jpg" alt="Tattoo work">
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-accent">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
            <span class="block">Ready to get inked?</span>
            <span class="block text-gray-900">Join our community today.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow">
                <a href="/register" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-accent bg-white hover:bg-gray-50">
                    Get started
                </a>
            </div>
        </div>
    </div>
</section>

<?php include_once 'partials/footer.php'; ?>