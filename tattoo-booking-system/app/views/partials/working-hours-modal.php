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