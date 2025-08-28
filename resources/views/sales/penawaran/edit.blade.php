@extends('layouts.app')

@section('title', 'Edit Penawaran')

@section('content')
<div x-data="{ 
    isSubmitting: false,
    submitForm() {
        this.isSubmitting = true;
        this.$refs.form.submit();
    }
}" class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Penawaran</h1>
                <p class="text-gray-600 mt-1">Ubah informasi penawaran #{{ $penawaran->kode_penawaran }}</p>
                
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
                                <span class="text-sm text-gray-500">Edit Penawaran</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                    {{ $penawaran->kode_penawaran }}
                </div>
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
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
                Edit Informasi Penawaran
            </h2>
            <p class="text-sm text-gray-600 mt-1">Perbarui detail penawaran dan informasi pelanggan</p>
        </div>

        <div class="p-6">
            <form x-ref="form" action="{{ route('sales.penawaran.update', $penawaran) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
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
                                   value="{{ old('nama_pelanggan', $penawaran->nama_pelanggan) }}"
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
                                   value="{{ old('telepon_pelanggan', $penawaran->telepon_pelanggan) }}"
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
                                   value="{{ old('email_pelanggan', $penawaran->email_pelanggan) }}"
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
                                   value="{{ old('tanggal_penawaran', date('Y-m-d', strtotime($penawaran->tanggal_penawaran))) }}"
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_penawaran') border-red-300 ring-red-500 @enderror" 
                                   required>
                            @error('tanggal_penawaran')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status Info --}}
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-amber-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-amber-800">Status Saat Ini</h4>
                                    <p class="text-sm text-amber-700 mt-1">
                                        Penawaran ini sedang dalam tahap: 
                                        <span class="font-medium">{{ ucfirst($penawaran->status ?? 'Draft') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Kode Penawaran Display --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kode Penawaran
                            </label>
                            <div class="w-full border-gray-300 rounded-lg px-3 py-2 bg-gray-50 text-gray-600">
                                {{ $penawaran->kode_penawaran }}
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Kode penawaran tidak dapat diubah</p>
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
                                  required>{{ old('alamat_pelanggan', $penawaran->alamat_pelanggan) }}</textarea>
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
                                  placeholder="Tambahkan catatan atau keterangan khusus (opsional)">{{ old('catatan', $penawaran->catatan) }}</textarea>
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
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Action Info --}}
    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-medium text-green-900">Perubahan Data</h3>
                <p class="text-green-800 mt-1">
                    Anda sedang mengedit penawaran yang sudah ada. Setelah menyimpan perubahan, data akan segera diperbarui.
                </p>
                <ul class="text-sm text-green-700 mt-3 space-y-1">
                    <li>• Perubahan akan mempengaruhi seluruh data penawaran</li>
                    <li>• Status penawaran akan tetap sama setelah diubah</li>
                    <li>• Produk yang sudah ditambahkan tidak akan terpengaruh</li>
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

        // Remove validation styling when user starts typing
        requiredFields.forEach(field => {
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('border-red-300', 'ring-red-500');
                }
            });
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

    /* Disabled field styling */
    input:disabled, textarea:disabled {
        background-color: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }
</style>

@endsection