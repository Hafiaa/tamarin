@extends('filament.pages.page')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-6">Pengaturan Situs</h2>
        
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Informasi Dasar -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold border-b pb-2">Informasi Dasar</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Situs</label>
                        <input type="text" name="site_name" value="{{ setting('site_name') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Situs</label>
                        <textarea name="site_description" rows="3" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ setting('site_description') }}</textarea>
                    </div>
                </div>
                
                <!-- Informasi Kontak -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold border-b pb-2">Kontak</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ setting('email') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telepon</label>
                        <input type="text" name="phone" value="{{ setting('phone') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="address" rows="3" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ setting('address') }}</textarea>
                    </div>
                </div>
                
                <!-- Media Sosial -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold border-b pb-2">Media Sosial</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Facebook</label>
                        <input type="url" name="facebook_url" value="{{ setting('facebook_url') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Instagram</label>
                        <input type="url" name="instagram_url" value="{{ setting('instagram_url') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">YouTube</label>
                        <input type="url" name="youtube_url" value="{{ setting('youtube_url') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                
                <!-- Lainnya -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold border-b pb-2">Lainnya</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jam Operasional</label>
                        <input type="text" name="working_hours" value="{{ setting('working_hours') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Google Maps Embed</label>
                        <textarea name="google_maps_embed" rows="3" 
                                 class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ setting('google_maps_embed') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Masukkan kode embed dari Google Maps</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection
