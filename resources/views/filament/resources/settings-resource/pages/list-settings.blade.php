<x-filament-panels::page>
    <div class="flex items-center justify-center py-12">
        <div class="text-center
            <div class="flex justify-center mb-4">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
            </div>
            <p class="text-gray-600 dark:text-gray-400">Mengarahkan ke halaman pengaturan...</p>
        </div>
    </div>

    @push('scripts')
        <script>
            // Redirect after a short delay
            setTimeout(function() {
                window.location.href = '{{ EditSettings::getUrl() }}';
            }, 500);
        </script>
    @endpush
</x-filament-panels::page>
