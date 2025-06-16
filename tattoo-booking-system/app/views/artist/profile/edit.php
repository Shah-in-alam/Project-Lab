<?php include_once __DIR__ . '/../../partials/header.php'; ?>

<div class="min-h-screen bg-gray-50 flex flex-col">
    <?php include_once __DIR__ . '/../../partials/navbar.php'; ?>

    <div class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Artist Profile</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="/artist/profile/update" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Tattoo Style -->
                <div>
                    <label for="style" class="block text-sm font-medium text-gray-700">Tattoo Style</label>
                    <textarea
                        id="style"
                        name="style"
                        rows="2"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                        placeholder="e.g., Traditional, Neo-traditional, Realistic..."><?php echo htmlspecialchars($profile['style'] ?? ''); ?></textarea>
                </div>

                <!-- Bio -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                    <textarea
                        id="bio"
                        name="bio"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                        placeholder="Tell us about yourself and your work..."><?php echo htmlspecialchars($profile['bio'] ?? ''); ?></textarea>
                </div>

                <!-- Working Hours -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">Working Hours</h3>

                    <?php
                    $days = [
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday'
                    ];
                    ?>

                    <?php foreach ($days as $dayNum => $dayName): ?>
                        <div class="flex items-center gap-4">
                            <div class="w-32">
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                        name="working_days[<?php echo $dayNum; ?>]"
                                        value="1"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                                        <?php echo isset($workingHours[$dayNum]) && $workingHours[$dayNum]['is_working'] ? 'checked' : ''; ?>>
                                    <span class="ml-2"><?php echo $dayName; ?></span>
                                </label>
                            </div>
                            <div class="flex gap-2 items-center flex-1">
                                <input type="time"
                                    name="start_time[<?php echo $dayNum; ?>]"
                                    value="<?php echo $workingHours[$dayNum]['start_time'] ?? '09:00'; ?>"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <span class="text-gray-500">to</span>
                                <input type="time"
                                    name="end_time[<?php echo $dayNum; ?>]"
                                    value="<?php echo $workingHours[$dayNum]['end_time'] ?? '17:00'; ?>"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="/artist/profile" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-500">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once __DIR__ . '/../../partials/footer.php'; ?>
</div>