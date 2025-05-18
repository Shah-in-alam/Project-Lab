<?php include_once 'partials/header.php'; ?>
<?php include_once 'partials/navbar.php'; ?>
<?php include_once 'partials/messages.php'; ?>

<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-fuchsia-700 via-indigo-900 to-black overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-5xl tracking-tight font-extrabold text-white sm:text-6xl md:text-7xl animate-bounce">
                        <span class="block drop-shadow-lg">Find Your Perfect</span>
                        <span class="block text-pink-400 drop-shadow-xl animate-pulse">Tattoo Artist</span>
                    </h1>
                    <p class="mt-4 text-lg text-indigo-100 sm:mt-6 sm:text-xl sm:max-w-xl sm:mx-auto md:mt-6 md:text-2xl lg:mx-0 animate-fade-in">
                        <span class="bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400 bg-clip-text text-transparent font-bold">
                            Connect, book, and showcase your ink in our vibrant community.
                        </span>
                    </p>
                    <div class="mt-8 sm:mt-10 sm:flex sm:justify-center lg:justify-start space-x-4">
                        <a href="/artists" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-lg font-semibold rounded-md text-white bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 hover:from-indigo-500 hover:to-pink-500 transition duration-300 shadow-xl animate-fade-in-up">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 17l4 4 4-4m-4-5v9"></path></svg>
                            Find Artists
                        </a>
                        <a href="/register" class="inline-flex items-center justify-center px-8 py-3 border border-pink-400 text-lg font-semibold rounded-md text-pink-600 bg-white hover:bg-pink-50 transition duration-300 shadow-xl animate-fade-in-up">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"></path></svg>
                            Join Now
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 animate-fade-in">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full rounded-bl-3xl shadow-2xl saturate-150 contrast-125" src="/assets/images/hero-banner.jpg" alt="Tattoo artist at work">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 pointer-events-none"></div>
    </div>
</div>

<!-- Featured Artists Section -->
<section class="py-16 bg-gradient-to-br from-white via-pink-50 to-purple-100 animate-fade-in-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center mb-10">
            <h2 class="text-4xl font-extrabold tracking-tight text-purple-900 sm:text-5xl animate-fade-in-down">
                <span class="bg-gradient-to-r from-pink-400 via-purple-400 to-indigo-400 bg-clip-text text-transparent">Featured Artists</span>
            </h2>
            <p class="mt-4 max-w-2xl text-xl text-purple-500 lg:mx-auto animate-fade-in">
                Meet our top-rated tattoo artists who are ready to bring your vision to life.
            </p>
        </div>
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <?php for ($i = 0; $i < 3; $i++): ?>
                <div class="bg-white overflow-hidden shadow-2xl rounded-2xl hover:scale-105 transform transition duration-300 border-4 border-pink-200 animate-fade-in-up">
                    <div class="relative pb-2/3">
                        <img class="absolute h-full w-full object-cover saturate-150" src="/assets/images/artist-<?php echo $i + 1; ?>.jpg" alt="Artist profile">
                        <span class="absolute top-2 right-2 bg-gradient-to-r from-pink-400 to-purple-400 text-white text-xs px-3 py-1 rounded-full shadow">Top Rated</span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-purple-900">Artist Name <?php echo $i + 1; ?></h3>
                        <p class="mt-2 text-sm text-gray-500">Specializing in <span class="text-pink-500 font-bold">Traditional</span>, <span class="text-purple-500 font-bold">Japanese</span></p>
                        <div class="mt-4">
                            <a href="/artists/profile" class="text-pink-500 hover:text-purple-600 font-medium transition">View Profile →</a>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- Latest Works Section -->
<section class="py-16 bg-gradient-to-br from-purple-100 via-pink-50 to-white animate-fade-in-up">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center mb-10">
            <h2 class="text-4xl font-extrabold tracking-tight text-pink-700 sm:text-5xl animate-fade-in-down">
                Latest Works
            </h2>
            <p class="mt-4 max-w-2xl text-xl text-purple-500 lg:mx-auto animate-fade-in">
                Check out the most recent masterpieces from our talented artists.
            </p>
        </div>
        <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
            <?php for ($i = 0; $i < 8; $i++): ?>
                <div class="relative pb-full rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 border-2 border-pink-200 animate-fade-in-up">
                    <img class="absolute h-full w-full object-cover saturate-150" src="/assets/images/tattoo-<?php echo $i + 1; ?>.jpg" alt="Tattoo work">
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-60 h-1/3"></div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 animate-fade-in-up">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8 lg:flex lg:items-center lg:justify-between">
        <h2 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl animate-bounce">
            <span class="block">Ready to get inked?</span>
            <span class="block text-yellow-200">Join our community today.</span>
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
            <div class="inline-flex rounded-md shadow-lg">
                <a href="/register" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-lg font-semibold rounded-md text-pink-600 bg-white hover:bg-yellow-50 transition duration-300 animate-pulse">
                    Get started
                </a>
            </div>
        </div>
    </div>
</section>

<?php include_once 'partials/footer.php'; ?>