<x-filament::page>
    <div class="space-y-6">
        <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ __('Welcome to the Admin Dashboard') }}
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('Manage your application from here.') }}
            </p>
        </div>

        <!-- Add your dashboard widgets here -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Example Widget -->
            <div class="p-6 bg-white rounded-lg shadow dark:bg-gray-800">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('Quick Stats') }}
                </h3>
                <div class="mt-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Total Users') }}
                        </span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{-- {{ \App\Models\User::count() }} --}} 0
                        </span>
                    </div>
                    <!-- Add more stats as needed -->
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
