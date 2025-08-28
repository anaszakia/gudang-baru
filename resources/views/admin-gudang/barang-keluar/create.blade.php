@extends('layouts.app')
@section('title', 'Tambah Transaksi Barang Keluar')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Transaksi Barang Keluar</h1>
                <p class="text-sm text-gray-500 mt-1">Input informasi awal transaksi barang keluar</p>
            </div>
            <a href="{{ route('admin-gudang.barang-keluar.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg transition-colors font-medium flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin-gudang.barang-keluar.store') }}" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kode Barang Keluar (Readonly) --}}
                <div>
                    <label for="kode_barang_keluar" class="block text-sm font-medium text-gray-700 mb-1">Kode Barang Keluar</label>
                    <input type="text" id="kode_barang_keluar" value="{{ $kodeBarangKeluar }}" class="bg-gray-100 text-gray-500 w-full px-4 py-2.5 rounded-lg border border-gray-300" readonly>
                    <p class="text-xs text-gray-500 mt-1">Kode barang keluar dibuat otomatis oleh sistem</p>
                </div>

                {{-- Tanggal Keluar --}}
                <div>
                    <label for="tanggal_keluar" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Keluar</label>
                    <input type="date" id="tanggal_keluar" name="tanggal_keluar" value="{{ old('tanggal_keluar', date('Y-m-d')) }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    @error('tanggal_keluar')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Penerima --}}
                <div class="md:col-span-1">
                    <label for="penerima" class="block text-sm font-medium text-gray-700 mb-1">Penerima</label>
                    <input type="text" id="penerima" name="penerima" value="{{ old('penerima') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Masukkan nama penerima" required>
                    @error('penerima')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Payment --}}
                <div class="md:col-span-1">
                    <label for="payment" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                    <select id="payment" name="payment" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="cash" {{ old('payment') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ old('payment') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="piutang" {{ old('payment') == 'piutang' ? 'selected' : '' }}>Piutang</option>
                    </select>
                    @error('payment')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat Penerima --}}
                <div class="md:col-span-2">
                    <label for="alamat_penerima" class="block text-sm font-medium text-gray-700 mb-1">Alamat Penerima</label>
                    <textarea id="alamat_penerima" name="alamat_penerima" rows="2" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Masukkan alamat penerima">{{ old('alamat_penerima') }}</textarea>
                    @error('alamat_penerima')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Telepon Penerima --}}
                <div class="md:col-span-1">
                    <label for="telepon_penerima" class="block text-sm font-medium text-gray-700 mb-1">Telepon Penerima</label>
                    <input type="text" id="telepon_penerima" name="telepon_penerima" value="{{ old('telepon_penerima') }}" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Masukkan nomor telepon">
                    @error('telepon_penerima')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan --}}
                <div class="md:col-span-1">
                    <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea id="catatan" name="catatan" rows="2" 
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Masukkan catatan transaksi (opsional)">{{ old('catatan') }}</textarea>
                    @error('catatan')
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
