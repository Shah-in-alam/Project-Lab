<?php include_once __DIR__ . '/../partials/header.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-pink-50 via-purple-50 to-indigo-50">
    <?php include_once __DIR__ . '/../partials/navbar.php'; ?>

    <main class="container mx-auto px-4 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent mb-4">
                Turn Your Art Into A Career
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Join our exclusive community of professional tattoo artists and start building your client base today
            </p>
        </div>

        <!-- Benefits Grid -->
        <div class="grid md:grid-cols-3 gap-8 mb-16">
            <!-- Benefit 1 -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-xl hover:transform hover:scale-105 transition-all">
                <div class="bg-pink-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Secure Payments</h3>
                <p class="text-gray-600">Get paid securely through our platform with multiple payment options</p>
            </div>

            <!-- Benefit 2 -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-xl hover:transform hover:scale-105 transition-all">
                <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Booking System</h3>
                <p class="text-gray-600">Manage your appointments and client schedule effortlessly</p>
            </div>

            <!-- Benefit 3 -->
            <div class="bg-white/80 backdrop-blur-sm rounded-xl p-6 shadow-xl hover:transform hover:scale-105 transition-all">
                <div class="bg-indigo-100 w-12 h-12 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-2">Analytics & Growth</h3>
                <p class="text-gray-600">Track your performance and grow your client base</p>
            </div>
        </div>

        <!-- Subscription Plans -->
        <div class="max-w-5xl mx-auto" x-data="{ showPaymentModal: false }">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-8 text-center">
                    <h2 class="text-3xl font-bold mb-4">Professional Artist Package</h2>
                    <div class="text-5xl font-bold text-purple-600 mb-4">
                        $29<span class="text-lg text-gray-500">/month</span>
                    </div>
                    <p class="text-gray-600 mb-8">Everything you need to start your professional tattoo artist journey</p>

                    <!-- Features List -->
                    <div class="grid md:grid-cols-2 gap-6 mb-8 text-left max-w-2xl mx-auto">
                        <div>
                            <h3 class="font-semibold mb-4 text-gray-800">Platform Features</h3>
                            <ul class="space-y-3">
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Professional Artist Profile
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Booking Management System
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Portfolio Showcase
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-4 text-gray-800">Business Tools</h3>
                            <ul class="space-y-3">
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Analytics Dashboard
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Payment Processing
                                </li>
                                <li class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                                    </svg>
                                    Client Management
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Single CTA Button -->
                    <div class="max-w-md mx-auto">
                        <button
                            @click="showPaymentModal = true"
                            type="button"
                            class="w-full py-4 px-8 rounded-full bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-white font-bold text-lg hover:shadow-lg transform hover:scale-105 transition-all">
                            Become an Artist Now
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <template x-teleport="body">
                <!-- Modal Backdrop -->
                <div
                    x-show="showPaymentModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50"
                    @click="showPaymentModal = false">
                </div>
            </template>

            <!-- Modal Content -->
            <template x-teleport="body">
                <div
                    x-show="showPaymentModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    class="fixed inset-0 flex items-center justify-center z-50"
                    @click.away="showPaymentModal = false">

                    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all"
                        @click.stop>
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-800">Complete Your Purchase</h3>
                            <p class="text-gray-600 mt-2">Professional Artist Package - $29/month</p>
                        </div>

                        <form action="/become-artist/process" method="POST" class="space-y-6">
                            <!-- Card Information -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                    <input type="text"
                                        placeholder="4242 4242 4242 4242"
                                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                        required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                        <input type="text"
                                            placeholder="MM/YY"
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                                        <input type="text"
                                            placeholder="123"
                                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms -->
                            <div class="flex items-start">
                                <input type="checkbox"
                                    class="mt-1 h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                    required>
                                <label class="ml-2 text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-purple-600 hover:text-purple-700">Terms of Service</a> and
                                    <a href="#" class="text-purple-600 hover:text-purple-700">Privacy Policy</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full py-3 px-4 rounded-lg bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-white font-semibold hover:shadow-lg transform hover:scale-105 transition-all">
                                Pay $29 and Become an Artist
                            </button>
                        </form>

                        <!-- Close Button -->
                        <button @click="showPaymentModal = false"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php'; ?>
</div>

<!-- Add this script to your header or at the end of the body -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('paymentModal', () => ({
            showPaymentModal: false,
            init() {
                window.addEventListener('open-payment-modal', () => {
                    this.showPaymentModal = true;
                });
            }
        }));
    });
</script>