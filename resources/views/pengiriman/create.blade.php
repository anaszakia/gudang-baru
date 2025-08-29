@extends('layouts.app')

@section('title', 'Buat Pengiriman Baru')

@section('content')
<div x-data="{ 
    isSubmitting: false,
    metode_pengiriman: '{{ old('metode_pengiriman') ?: '' }}',
    submitForm() {
        this.isSubmitting = true;
        this.$refs.form.submit();
    }
}" class="space-y-6" x-cloak="false">

    {{-- ERROR ALERTS --}}
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 relative">
        <div class="flex">
            <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-red-800">Error!</h3>
                <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
            </div>
        </div>
        <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-red-400 hover:text-red-600">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
    @endif

    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Pengiriman Baru</h1>
                <p class="text-gray-600 mt-1">Atur pengiriman untuk barang keluar yang telah diproses</p>
                
                {{-- Breadcrumb --}}
                <nav class="flex mt-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route(Auth::user()->role . '.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600">Dashboard</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <a href="{{ route(Auth::user()->role . '.barang-keluar.index') }}" class="text-sm text-gray-500 hover:text-blue-600">Barang Keluar</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-500">Buat Pengiriman</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- BARANG KELUAR INFO CARD --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Detail Barang Keluar
            </h2>
            <p class="text-sm text-gray-600 mt-1">Informasi barang yang akan dikirim</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex">
                        <span class="w-24 text-sm font-medium text-gray-500">Kode</span>
                        <span class="text-sm text-gray-900">: {{ $barangKeluar->kode_barang_keluar }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 text-sm font-medium text-gray-500">Tanggal</span>
                        <span class="text-sm text-gray-900">: {{ $barangKeluar->tanggal_keluar->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-24 text-sm font-medium text-gray-500">Penerima</span>
                        <span class="text-sm text-gray-900">: {{ $barangKeluar->penerima }}</span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex">
                        <span class="w-24 text-sm font-medium text-gray-500">Telepon</span>
                        <span class="text-sm text-gray-900">: {{ $barangKeluar->telepon_penerima }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="w-24 text-sm font-medium text-gray-500 mt-0.5">Alamat</span>
                        <span class="text-sm text-gray-900 flex-1">: {{ $barangKeluar->alamat_penerima }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM PENGIRIMAN CARD --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 8h6m-6 4h6m-8-9h12a2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V10a2 2 0 012-2z"/>
                </svg>
                Pengaturan Pengiriman
            </h2>
            <p class="text-sm text-gray-600 mt-1">Tentukan metode dan detail pengiriman barang</p>
        </div>

        <div class="p-6">
            <form x-ref="form" action="{{ route(Auth::user()->role . '.pengiriman.store', $barangKeluar->id) }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Metode Pengiriman --}}
                <div>
                    <label for="metode_pengiriman" class="block text-sm font-medium text-gray-700 mb-2">
                        Metode Pengiriman <span class="text-red-500">*</span>
                    </label>
                    <select id="metode_pengiriman" 
                            name="metode_pengiriman" 
                            x-model="metode_pengiriman"
                            @change="console.log('Metode changed:', metode_pengiriman)"
                            class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('metode_pengiriman') border-red-300 ring-red-500 @enderror" 
                            required>
                        <option value="">-- Pilih Metode Pengiriman --</option>
                        <option value="ambil_sendiri">
                            🏪 Diambil Sendiri oleh Pelanggan
                        </option>
                        <option value="diantar_driver">
                            🚛 Diantar oleh Driver
                        </option>
                    </select>
                    @error('metode_pengiriman')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Driver Selection (conditionally shown) --}}
                <div x-show="metode_pengiriman === 'diantar_driver'" 
                     style="display: none;"
                     class="space-y-4">
                    
                    <div>
                        <label for="driver_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Pilih Driver <span class="text-red-500">*</span>
                        </label>
                        <select id="driver_id" 
                                name="driver_id" 
                                :required="metode_pengiriman === 'diantar_driver'"
                                class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('driver_id') border-red-300 ring-red-500 @enderror">
                            <option value="">-- Pilih Driver yang Tersedia --</option>
                            @if(isset($availableDrivers) && $availableDrivers->count() > 0)
                                @foreach($availableDrivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        👤 {{ $driver->name }} @if(isset($driver->no_hp))- {{ $driver->no_hp }}@endif
                                    </option>
                                @endforeach
                            @else
                                <option disabled>Tidak ada driver tersedia</option>
                            @endif
                        </select>
                        @error('driver_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Driver Info --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-blue-800">Informasi Pengiriman dengan Driver</h4>
                                <p class="text-sm text-blue-700 mt-1">
                                    Driver akan mengantar barang langsung ke alamat penerima. Pastikan driver memiliki kontak penerima.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Self Pickup Info --}}
                <div x-show="metode_pengiriman === 'ambil_sendiri'" 
                     style="display: none;">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <h4 class="text-sm font-medium text-green-800">Pengambilan Mandiri</h4>
                                <p class="text-sm text-green-700 mt-1">
                                    Barang akan dipersiapkan untuk diambil sendiri oleh pelanggan. Pastikan untuk menghubungi pelanggan mengenai jadwal pengambilan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan --}}
                <div class="pt-6 border-t">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan Pengiriman
                    </label>
                    <textarea id="catatan" 
                              name="catatan" 
                              rows="4"
                              class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('catatan') border-red-300 ring-red-500 @enderror" 
                              placeholder="Tambahkan catatan khusus untuk pengiriman (opsional)">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                    <a href="{{ route(Auth::user()->role . '.barang-keluar.show', $barangKeluar->id) }}" 
                       class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="button"
                            @click="submitForm()" 
                            :disabled="isSubmitting"
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <svg x-show="isSubmitting" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Buat Pengiriman'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Status Info --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-medium text-blue-900">Status Pengiriman</h3>
                <p class="text-blue-800 mt-1">
                    Setelah pengiriman dibuat, status akan berubah menjadi "Dalam Proses". 
                    Anda dapat memantau dan mengupdate status pengiriman sesuai dengan progress aktual.
                </p>
                <ul class="text-sm text-blue-700 mt-3 space-y-1">
                    <li>• <strong>Dalam Proses:</strong> Pengiriman sedang dipersiapkan</li>
                    <li>• <strong>Dalam Perjalanan:</strong> Barang sedang dalam perjalanan (untuk pengiriman dengan driver)</li>
                    <li>• <strong>Siap Diambil:</strong> Barang siap untuk diambil (untuk pengambilan mandiri)</li>
                    <li>• <strong>Selesai:</strong> Barang telah sampai di tujuan atau diambil pelanggan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const metodeSelect = document.getElementById('metode_pengiriman');
        const driverSection = document.querySelector('[x-show="metode_pengiriman === \'diantar_driver\'"]');
        const selfPickupSection = document.querySelector('[x-show="metode_pengiriman === \'ambil_sendiri\'"]');
        
        // Manual toggle for dropdown visibility since Alpine might not be working
        metodeSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            
            // Hide all sections first
            driverSection.style.display = 'none';
            selfPickupSection.style.display = 'none';
            
            // Show relevant section based on selection
            if (selectedValue === 'diantar_driver') {
                driverSection.style.display = 'block';
            } else if (selectedValue === 'ambil_sendiri') {
                selfPickupSection.style.display = 'block';
            }
        });
        
        // Trigger change event if there's a pre-selected value
        if (metodeSelect.value) {
            const event = new Event('change');
            metodeSelect.dispatchEvent(event);
        }
        
        form.addEventListener('submit', function(e) {
            const metode = document.getElementById('metode_pengiriman').value;
            const driverId = document.getElementById('driver_id').value;
            
            if (!metode) {
                e.preventDefault();
                alert('Mohon pilih metode pengiriman.');
                return;
            }
            
            if (metode === 'diantar_driver' && !driverId) {
                e.preventDefault();
                alert('Mohon pilih driver untuk pengiriman.');
                return;
            }
        });
    });
</script>

<style>
    /* Custom focus styles */
    input:focus, textarea:focus, select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    /* Custom scrollbar */
    textarea::-webkit-scrollbar {
        width: 8px;
    }
    
    textarea::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    textarea::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    
    textarea::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Animation for loading state */
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Smooth transitions */
    /* Hapus aturan yang menyembunyikan elemen */
/* [x-cloak] { display: none !important; } */
</style>

@endsection