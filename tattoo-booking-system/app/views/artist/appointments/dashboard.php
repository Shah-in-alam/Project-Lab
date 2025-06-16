<?php include_once __DIR__ . '/../../partials/header.php'; ?>

<div class="flex flex-col min-h-screen">
    <?php include_once __DIR__ . '/../../partials/navbar.php'; ?>

    <div class="flex-grow bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Appointment Management</h1>
                <div class="flex items-center gap-4">
                    <input type="date"
                        id="appointmentDate"
                        class="border rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                        value="<?php echo date('Y-m-d'); ?>"
                        min="<?php echo date('Y-m-d'); ?>"
                        onchange="loadAppointments(this.value)">
                </div>
            </div>

            <!-- Time Slots Grid -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-medium text-gray-900">Time Slots</h2>
                    <div class="flex items-center gap-2">
                        <span class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-sm text-gray-500">Available</span>
                        </span>
                        <span class="flex items-center gap-1">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-sm text-gray-500">Booked</span>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-6 gap-2" id="timeSlots">
                    <!-- Time slots will be populated by JavaScript -->
                </div>
            </div>

            <!-- Appointments List -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Appointments</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="appointmentsList">
                            <!-- Appointments will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../partials/footer.php'; ?>
</div>

<!-- Appointment Detail Modal -->
<div id="appointmentDetailModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 backdrop-blur-sm bg-black/30"></div>

    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Appointment Details</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6" id="appointmentDetails">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load initial appointments for today
        loadAppointments(document.getElementById('appointmentDate').value);
    });

    async function loadAppointments(date) {
        try {
            // Load both time slots and appointments
            const [slotsResponse, appointmentsResponse] = await Promise.all([
                fetch(`/artist/appointments/slots/${date}`),
                fetch(`/artist/appointments/list/${date}`)
            ]);

            if (!slotsResponse.ok || !appointmentsResponse.ok) {
                throw new Error('Failed to load data');
            }

            const slots = await slotsResponse.json();
            const appointments = await appointmentsResponse.json();

            renderTimeSlots(slots);
            renderAppointments(appointments);
        } catch (error) {
            console.error('Error:', error);
            showError('Failed to load appointments');
        }
    }

    async function loadTimeSlots(date) {
        try {
            const response = await fetch(`/artist/appointments/slots/${date}`);
            const slots = await response.json();

            if (response.ok) {
                renderTimeSlots(slots);
            } else {
                throw new Error(slots.error || 'Failed to load time slots');
            }
        } catch (error) {
            console.error('Error loading time slots:', error);
            showError('Failed to load time slots');
        }
    }

    function renderTimeSlots(slots) {
        const container = document.getElementById('timeSlots');
        container.innerHTML = '';

        slots.forEach(slot => {
            const slotElement = document.createElement('div');
            slotElement.className = `p-2 text-center rounded-lg ${
                slot.available 
                    ? 'bg-green-100 text-green-800 cursor-pointer hover:bg-green-200' 
                    : 'bg-red-100 text-red-800'
            }`;
            slotElement.textContent = slot.time;

            if (slot.appointment_id && !slot.available) {
                slotElement.setAttribute('data-appointment-id', slot.appointment_id);
                slotElement.addEventListener('click', () => viewDetails(slot.appointment_id));
            }

            container.appendChild(slotElement);
        });
    }

    function renderAppointments(appointments) {
        const tbody = document.getElementById('appointmentsList');
        tbody.innerHTML = '';

        appointments.forEach(appointment => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${appointment.time}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${appointment.client_name}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${appointment.service_name}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${appointment.duration} min
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(appointment.status)}">
                        ${appointment.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <button onclick="viewDetails(${appointment.id})" class="text-purple-600 hover:text-purple-900">
                        View Details
                    </button>
                    ${appointment.status === 'requested' ? `
                        <button onclick="updateStatus(${appointment.id}, 'approved')" class="ml-2 text-green-600 hover:text-green-900">
                            Approve
                        </button>
                        <button onclick="updateStatus(${appointment.id}, 'denied')" class="ml-2 text-red-600 hover:text-red-900">
                            Deny
                        </button>
                    ` : ''}
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function getStatusColor(status) {
        const colors = {
            requested: 'bg-yellow-100 text-yellow-800',
            approved: 'bg-green-100 text-green-800',
            denied: 'bg-red-100 text-red-800',
            cancelled: 'bg-gray-100 text-gray-800',
            completed: 'bg-blue-100 text-blue-800',
            in_progress: 'bg-purple-100 text-purple-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    async function updateStatus(id, status) {
        try {
            const response = await fetch(`/artist/appointments/${id}/status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    status
                })
            });

            if (response.ok) {
                // Reload appointments after status update
                loadAppointments(document.getElementById('appointmentDate').value);
            } else {
                throw new Error('Failed to update status');
            }
        } catch (error) {
            console.error('Error updating status:', error);
            showError('Failed to update appointment status');
        }
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded';
        errorDiv.innerHTML = `
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">${message}</span>
        `;
        document.body.appendChild(errorDiv);
        setTimeout(() => errorDiv.remove(), 5000);
    }

    async function viewDetails(id) {
        try {
            const response = await fetch(`/artist/appointments/${id}/details`);
            const appointment = await response.json();

            if (response.ok) {
                const detailsHtml = `
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Client Information</h4>
                            <p class="mt-1 text-sm text-gray-900">${appointment.client_name}</p>
                            <p class="mt-1 text-sm text-gray-500">${appointment.client_email}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Service</h4>
                            <p class="mt-1 text-sm text-gray-900">${appointment.service_name}</p>
                            <p class="mt-1 text-sm text-gray-500">${appointment.service_duration} minutes</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Appointment Time</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                ${new Date(appointment.start_time).toLocaleString()}
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Status</h4>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(appointment.status)}">
                                ${appointment.status}
                            </span>
                        </div>
                        
                        ${appointment.notes ? `
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Notes</h4>
                                <p class="mt-1 text-sm text-gray-900">${appointment.notes}</p>
                            </div>
                        ` : ''}
                        
                        <div class="mt-6 flex justify-end gap-3">
                            ${getActionButtons(appointment)}
                        </div>
                    </div>
                `;

                document.getElementById('appointmentDetails').innerHTML = detailsHtml;
                document.getElementById('appointmentDetailModal').classList.remove('hidden');
            } else {
                throw new Error(appointment.error || 'Failed to load appointment details');
            }
        } catch (error) {
            console.error('Error loading appointment details:', error);
            showError('Failed to load appointment details');
        }
    }

    function getActionButtons(appointment) {
        if (appointment.status === 'requested') {
            return `
                <button onclick="updateStatus(${appointment.id}, 'approved')" 
                    class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">
                    Approve
                </button>
                <button onclick="updateStatus(${appointment.id}, 'denied')" 
                    class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600">
                    Deny
                </button>
            `;
        }
        return '';
    }

    function closeDetailModal() {
        document.getElementById('appointmentDetailModal').classList.add('hidden');
    }
</script>

<?php include_once __DIR__ . '/../../partials/footer.php'; ?>