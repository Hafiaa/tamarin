<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}

        <div class="flex justify-end mt-6">
            <button 
                type="submit" 
                class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Simpan Pengaturan
            </button>
        </div>
    </form>
</x-filament::page>
