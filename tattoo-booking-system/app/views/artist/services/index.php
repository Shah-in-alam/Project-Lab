<?php include_once __DIR__ . '/../../partials/header.php'; ?>

<div class="flex flex-col min-h-screen">
    <?php include_once __DIR__ . '/../../partials/navbar.php'; ?>

    <div class="flex-grow bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">My Services</h1>
                <a href="/artist/services/create" class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Service
                </a>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <?php if (!empty($services)): ?>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($services as $service): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($service['name']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?php echo htmlspecialchars($service['description']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo $service['duration']; ?> minutes
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        $<?php echo number_format($service['price'], 2); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="/artist/services/edit/<?php echo $service['id']; ?>" class="text-purple-600 hover:text-purple-900">Edit</a>
                                        <button onclick="deleteService(<?php echo $service['id']; ?>)" class="ml-3 text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No services</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new service.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../partials/footer.php'; ?>
</div>

<div id="addServiceModal" class="fixed inset-0 z-50 hidden">
    <!-- Modal Backdrop with blur -->
    <div class="fixed inset-0 backdrop-blur-sm bg-black/30"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Add New Service</h3>
            </div>

            <form id="addServiceForm" action="/artist/services/store" method="POST" class="p-6">
                <div class="space-y-4">
                    <!-- Service Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" name="duration" id="duration" min="15" step="15" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                            placeholder="e.g., 60">
                        <p class="mt-1 text-xs text-gray-500">Minimum duration: 15 minutes</p>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                        <input type="number" name="price" id="price" step="0.01" min="0" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeAddServiceModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-purple-500 text-white text-sm font-medium rounded-md hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Add Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editServiceModal" class="fixed inset-0 z-50 hidden">
    <!-- Modal Backdrop with blur -->
    <div class="fixed inset-0 backdrop-blur-sm bg-black/30"></div>

    <!-- Modal Content -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Edit Service</h3>
            </div>

            <form id="editServiceForm" action="/artist/services/update" method="POST" class="p-6">
                <input type="hidden" name="id" id="edit_id">
                <div class="space-y-4">
                    <!-- Service Name -->
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Service Name</label>
                        <input type="text" name="name" id="edit_name" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="edit_description" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="edit_duration" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                        <input type="number" name="duration" id="edit_duration" min="15" step="15" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        <p class="mt-1 text-xs text-gray-500">Minimum duration: 15 minutes</p>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="edit_price" class="block text-sm font-medium text-gray-700">Price ($)</label>
                        <input type="number" name="price" id="edit_price" step="0.01" min="0" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeEditServiceModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-purple-500 text-white text-sm font-medium rounded-md hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openAddServiceModal() {
        document.getElementById('addServiceModal').classList.remove('hidden');
    }

    function closeAddServiceModal() {
        document.getElementById('addServiceModal').classList.add('hidden');
    }

    function openEditServiceModal(service) {
        document.getElementById('edit_id').value = service.id;
        document.getElementById('edit_name').value = service.name;
        document.getElementById('edit_description').value = service.description;
        document.getElementById('edit_duration').value = service.duration;
        document.getElementById('edit_price').value = service.price;
        document.getElementById('editServiceModal').classList.remove('hidden');
    }

    function closeEditServiceModal() {
        document.getElementById('editServiceModal').classList.add('hidden');
    }

    // Update the "Add New Service" button to open modal
    document.querySelector('a[href="/artist/services/create"]').addEventListener('click', function(e) {
        e.preventDefault();
        openAddServiceModal();
    });

    // Update the edit button click handlers
    document.querySelectorAll('a[href^="/artist/services/edit/"]').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            const serviceId = this.href.split('/').pop();
            try {
                const response = await fetch(`/artist/services/${serviceId}`);
                const service = await response.json();
                openEditServiceModal(service);
            } catch (error) {
                console.error('Error fetching service:', error);
            }
        });
    });

    // Close modal when clicking outside
    document.getElementById('addServiceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddServiceModal();
        }
    });

    document.getElementById('editServiceModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditServiceModal();
        }
    });
</script>