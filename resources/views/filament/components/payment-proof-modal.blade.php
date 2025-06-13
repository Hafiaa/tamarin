<div class="space-y-4">
    @if($isImage)
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <img src="{{ $url }}" class="max-w-full max-h-[70vh] mx-auto rounded shadow" alt="Bukti Pembayaran">
        </div>
    @elseif($isPdf)
        <div class="bg-gray-100 p-4 rounded-lg mb-4 h-[70vh]">
            <iframe src="{{ $url }}" class="w-full h-full border-0 rounded" frameborder="0"></iframe>
        </div>
    @else
        <div class="p-4 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="mt-2 text-sm font-medium">Format file tidak didukung: {{ $mimeType }}</p>
        </div>
    @endif
    
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <p class="font-medium text-gray-500">Nama File</p>
            <p class="mt-1">{{ $filename }}</p>
        </div>
        <div>
            <p class="font-medium text-gray-500">Ukuran</p>
            <p class="mt-1">{{ $fileSize }}</p>
        </div>
        <div>
            <p class="font-medium text-gray-500">Tipe File</p>
            <p class="mt-1">{{ $mimeType }}</p>
        </div>
        <div>
            <p class="font-medium text-gray-500">Diunggah Pada</p>
            <p class="mt-1">{{ $uploadedAt }}</p>
        </div>
    </div>
    
    <div class="pt-4 border-t border-gray-200">
        <a href="{{ $url }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Unduh File
        </a>
    </div>
</div>
