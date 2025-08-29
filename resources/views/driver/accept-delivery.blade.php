@extends('layouts.app')

@section('title', 'Terima Pengiriman')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Terima Pengiriman</h1>
                <p class="text-gray-600 mt-1">Konfirmasi untuk memulai tugas pengiriman</p>
                
                {{-- Breadcrumb --}}
                <nav class="flex mt-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('driver.dashboard') }}" class="text-sm text-gray-500 hover:text-blue-600">Dashboard</a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <a href="{{ route('driver.deliveries') }}" class="text-sm text-gray-500 hover:text-blue-600">Pengiriman Saya</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-500">Terima Pengiriman</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- DELIVERY INFO --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Detail Pengiriman
            </h2>
            <p class="text-sm text-gray-600 mt-1">Informasi pengiriman yang akan diterima</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Kode Pengiriman</span>
                        <span class="text-sm font-mono text-blue-600 font-medium">: {{ $pengiriman->barangKeluar->kode_barang_keluar }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Tanggal</span>
                        <span class="text-sm text-gray-900">: {{ $pengiriman->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Status</span>
                        <span class="text-sm">: 
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Belum Dikirim</span>
                        </span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Penerima</span>
                        <span class="text-sm text-gray-900">: {{ $pengiriman->barangKeluar->penerima }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Telepon</span>
                        <span class="text-sm text-gray-900">: {{ $pengiriman->barangKeluar->telepon_penerima }}</span>
                    </div>
                    <div class="flex items-start">
                        <span class="w-32 text-sm font-medium text-gray-500 mt-0.5">Alamat</span>
                        <span class="text-sm text-gray-900 flex-1">: {{ $pengiriman->barangKeluar->alamat_penerima }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CONFIRMATION FORM --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Konfirmasi Pengiriman
            </h2>
            <p class="text-sm text-gray-600 mt-1">Konfirmasi untuk memulai tugas pengiriman ini</p>
        </div>

        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">Informasi Penting</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Dengan menerima tugas pengiriman ini, Anda menyatakan siap untuk mengantarkan barang ke alamat tujuan.
                            Status pengiriman akan berubah menjadi "Dalam Perjalanan" dan waktu pengiriman akan mulai dihitung.
                        </p>
                    </div>
                </div>
            </div>

            <form action="{{ route('driver.deliveries.accept-confirm', $pengiriman->id) }}" method="POST" class="space-y-6">
                @csrf
                
                {{-- Catatan --}}
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea id="catatan" 
                              name="catatan" 
                              rows="4"
                              class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                              placeholder="Tambahkan catatan jika ada (opsional)"></textarea>
                </div>

                {{-- Form Actions --}}
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('driver.deliveries') }}" 
                       class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Terima Pengiriman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
