<div class="p-4 bg-gray-50 rounded-lg">
    <div class="space-y-2">
        <h4 class="font-medium">Preview Pilihan Dropdown</h4>
        
        <div class="border rounded-md p-3 bg-white">
            <label class="block text-sm font-medium text-gray-700 mb-1">Contoh Dropdown</label>
            <select class="w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <option value="">Pilih salah satu...</option>
                @if($get('payload'))
                    @php
                        $options = json_decode($get('payload'), true);
                        $options = is_array($options) ? $options : [];
                    @endphp
                    @foreach($options as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                @endif
            </select>
            
            @if($get('payload') && !json_validate($get('payload')))
                <p class="mt-1 text-sm text-red-600">Format JSON tidak valid</p>
            @endif
        </div>
        
        <p class="text-xs text-gray-500 mt-2">
            Pastikan untuk menggunakan format JSON yang valid. Contoh:
            <code class="block bg-gray-100 p-1 rounded mt-1">{"red":"Merah","blue":"Biru","green":"Hijau"}</code>
        </p>
    </div>
</div>
