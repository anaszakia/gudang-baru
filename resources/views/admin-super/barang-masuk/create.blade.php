@extends('layouts.app')
@section('title', 'Tambah Transaksi Barang Masuk')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Transaksi Barang Masuk</h1>
                <p class="text-sm text-gray-500 mt-1">Input informasi awal transaksi barang masuk</p>
            </div>
            <a href="{{ route('admin-super.barang-masuk.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg transition-colors font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin-super.barang-masuk.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nomor Transaksi (Readonly) --}}
                <div>
                    <label for="nomor_transaksi" class="block text-sm font-medium text-gray-700 mb-1">Nomor Transaksi</label>
                    <input type="text" id="nomor_transaksi" value="{{ $nomorTransaksi }}" class="bg-gray-100 text-gray-500 w-full px-4 py-2.5 rounded-lg border border-gray-300" readonly>
                    <p class="text-xs text-gray-500 mt-1">Nomor transaksi dibuat otomatis oleh sistem</p>
                </div>

                {{-- Tanggal Masuk --}}
                <div>
                    <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk</label>
                    <input type="date" id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('tanggal_masuk')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Supplier --}}
                <div class="md:col-span-2">
                    <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                    <input type="text" id="supplier" name="supplier" value="{{ old('supplier') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Masukkan nama supplier" required>
                    @error('supplier')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-gray-200 pt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg transition-colors font-medium flex items-center gap-2">
                    Lanjutkan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
