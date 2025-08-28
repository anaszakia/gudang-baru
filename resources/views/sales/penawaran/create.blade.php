@extends('layouts.app')

@section('title', 'Buat Penawaran Baru')

@section('content')
<div x-data="{ 
    isSubmitting: false,
    submitForm() {
        this.isSubmitting = true;
        this.$refs.form.submit();
    }
}" class="space-y-6">

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
                <h1 class="text-2xl font-bold text-gray-900">Buat Penawaran Baru</h1>
                <p class="text-gray-600 mt-1">Lengkapi informasi untuk membuat penawaran baru</p>
                
                {{-- Breadcrumb --}}
                <nav class="flex mt-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('sales.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600">Dashboard</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <a href="{{ route('sales.penawaran.index') }}" class="text-sm text-gray-500 hover:text-blue-600">Daftar Penawaran</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-500">Buat Penawaran Baru</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- FORM CARD --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Informasi Penawaran
            </h2>
            <p class="text-sm text-gray-600 mt-1">Masukkan detail penawaran dan informasi pelanggan</p>
        </div>

        <div class="p-6">
            <form x-ref="form" action="{{ route('sales.penawaran.store') }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Customer Information Section --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Informasi Pelanggan</h3>
                        
                        {{-- Nama Pelanggan --}}
                        <div>
                            <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Pelanggan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_pelanggan" 
                                   name="nama_pelanggan" 
                                   value="{{ old('nama_pelanggan') }}"
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_pelanggan') border-red-300 ring-red-500 @enderror" 
                                   placeholder="Masukkan nama lengkap pelanggan"
                                   required>
                            @error('nama_pelanggan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Telepon Pelanggan --}}
                        <div>
                            <label for="telepon_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                                Telepon Pelanggan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="telepon_pelanggan" 
                                   name="telepon_pelanggan" 
                                   value="{{ old('telepon_pelanggan') }}"
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('telepon_pelanggan') border-red-300 ring-red-500 @enderror" 
                                   placeholder="Contoh: 0812-3456-7890"
                                   required>
                            @error('telepon_pelanggan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email Pelanggan --}}
                        <div>
                            <label for="email_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Pelanggan
                            </label>
                            <input type="email" 
                                   id="email_pelanggan" 
                                   name="email_pelanggan" 
                                   value="{{ old('email_pelanggan') }}"
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('email_pelanggan') border-red-300 ring-red-500 @enderror" 
                                   placeholder="contoh@email.com">
                            @error('email_pelanggan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Detail Penawaran</h3>
                        
                        {{-- Tanggal Penawaran --}}
                        <div>
                            <label for="tanggal_penawaran" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Penawaran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="tanggal_penawaran" 
                                   name="tanggal_penawaran" 
                                   value="{{ old('tanggal_penawaran', date('Y-m-d')) }}"
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_penawaran') border-red-300 ring-red-500 @enderror" 
                                   required>
                            @error('tanggal_penawaran')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Info --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">Status Penawaran</h4>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Penawaran baru akan dibuat dengan status "Menunggu Persetujuan"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Full Width Fields --}}
                <div class="space-y-6 pt-6 border-t">
                    {{-- Alamat Pelanggan --}}
                    <div>
                        <label for="alamat_pelanggan" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Pelanggan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alamat_pelanggan" 
                                  name="alamat_pelanggan" 
                                  rows="3"
                                  class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat_pelanggan') border-red-300 ring-red-500 @enderror" 
                                  placeholder="Masukkan alamat lengkap pelanggan"
                                  required>{{ old('alamat_pelanggan') }}</textarea>
                        @error('alamat_pelanggan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan
                        </label>
                        <textarea id="catatan" 
                                  name="catatan" 
                                  rows="3"
                                  class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('catatan') border-red-300 ring-red-500 @enderror" 
                                  placeholder="Tambahkan catatan atau keterangan khusus (opsional)">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('sales.penawaran.index') }}" 
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
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan & Lanjutkan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Next Steps Info --}}
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-medium text-blue-900">Langkah Selanjutnya</h3>
                <p class="text-blue-800 mt-1">
                    Setelah menyimpan penawaran, Anda dapat menambahkan produk dan mengelola detail penawaran. 
                    Penawaran akan tersimpan dengan status "Menunggu Persetujuan" dan dapat diedit hingga mendapat persetujuan.
                </p>
                <ul class="text-sm text-blue-700 mt-3 space-y-1">
                    <li>• Tambahkan produk ke dalam penawaran</li>
                    <li>• Atur harga dan kuantitas</li>
                    <li>• Kirim ke pelanggan untuk persetujuan</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-format phone number
    document.getElementById('telepon_pelanggan').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.startsWith('0')) {
            if (value.length <= 4) {
                e.target.value = value;
            } else if (value.length <= 8) {
                e.target.value = value.slice(0, 4) + '-' + value.slice(4);
            } else {
                e.target.value = value.slice(0, 4) + '-' + value.slice(4, 8) + '-' + value.slice(8, 12);
            }
        } else {
            e.target.value = value;
        }
    });

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const requiredFields = form.querySelectorAll('[required]');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-300', 'ring-red-500');
                } else {
                    field.classList.remove('border-red-300', 'ring-red-500');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Mohon lengkapi semua field yang wajib diisi.');
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
</style>

@endsection