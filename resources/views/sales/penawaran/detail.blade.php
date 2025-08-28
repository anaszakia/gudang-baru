@extends('layouts.app')

@section('title', 'Detail Penawaran')

@section('content')
<div x-data="penawaranData()" class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Penawaran</h1>
                <p class="text-gray-600 mt-1">Kelola detail penawaran dan produk</p>
                
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
                                <span class="text-sm text-gray-500">Detail Penawaran</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="text-right">
                    <p class="text-sm text-gray-500">Kode Penawaran</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $penawaran->kode_penawaran }}</p>
                </div>
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- INFORMASI PENAWARAN --}}
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Informasi Penawaran
                </h2>
            </div>
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                        <dd class="text-sm text-gray-900">{{ date('d M Y', strtotime($penawaran->tanggal_penawaran)) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="text-sm">
                            @if($penawaran->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Persetujuan</span>
                            @elseif($penawaran->status == 'approved')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disetujui</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ditolak</span>
                            @endif
                        </dd>
                    </div>
                    @if($penawaran->status != 'pending')
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Diproses Oleh</dt>
                        <dd class="text-sm text-gray-900">{{ $penawaran->approver->name ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">Diproses Pada</dt>
                        <dd class="text-sm text-gray-900">{{ $penawaran->approved_at ? date('d M Y H:i', strtotime($penawaran->approved_at)) : '-' }}</dd>
                    </div>
                    @endif
                </dl>

                <div class="mt-6 pt-6 border-t">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pelanggan</h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Nama</dt>
                            <dd class="text-sm text-gray-900">{{ $penawaran->nama_pelanggan }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Telepon</dt>
                            <dd class="text-sm text-gray-900">{{ $penawaran->telepon_pelanggan }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="text-sm text-gray-900">{{ $penawaran->email_pelanggan ?? '-' }}</dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Alamat</dt>
                            <dd class="text-sm text-gray-900">{{ $penawaran->alamat_pelanggan }}</dd>
                        </div>
                        @if($penawaran->catatan)
                        <div class="flex flex-col">
                            <dt class="text-sm font-medium text-gray-500 mb-1">Catatan</dt>
                            <dd class="text-sm text-gray-900">{{ $penawaran->catatan }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        {{-- TAMBAH PRODUK --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah Produk</h2>
            <form method="POST" action="{{ route('sales.penawaran.store-detail', $penawaran) }}" x-ref="addProductForm" class="space-y-4">
                @csrf
                
                {{-- Metode Input --}}
                <div class="border-b border-gray-200 pb-3 mb-1">
                    <div class="flex items-center justify-start space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="input_method" value="dropdown" class="form-radio h-4 w-4 text-blue-600" 
                                   x-model="inputMode" value="dropdown" checked>
                            <span class="ml-2 text-sm text-gray-700">Pilih dari List</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="input_method" value="manual" class="form-radio h-4 w-4 text-blue-600" 
                                   x-model="inputMode" value="manual">
                            <span class="ml-2 text-sm text-gray-700">Input Kode</span>
                        </label>
                    </div>
                </div>
                
                {{-- Produk (Dropdown) --}}
                <div x-show="inputMode === 'dropdown'" x-transition>
                    <div>
                        <label for="produk_id" class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                        <select id="produk_id" name="produk_id" 
                                @change="selectProductFromDropdown($event)"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}" 
                                        data-kode="{{ $produk->kode }}"
                                        data-nama="{{ $produk->nama }}"
                                        data-harga="{{ $produk->harga }}">
                                    {{ $produk->kode }} - {{ $produk->nama }} (Rp. {{ number_format($produk->harga, 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('produk_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Produk (Input Kode) --}}
                <div x-show="inputMode === 'manual'" x-transition>
                    <div>
                        <label for="kode_produk" class="block text-sm font-medium text-gray-700 mb-1">Kode Produk</label>
                        <div class="relative">
                            <input type="text" x-model="productCode" 
                                   @input.debounce.300ms="searchProductByCode()"
                                   @blur="validateProductCode()"
                                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Masukkan kode produk">
                            <div x-show="isSearching" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <svg class="animate-spin h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                        </div>
                        <input type="hidden" name="produk_id" x-bind:value="selectedProduct ? selectedProduct.id : ''">
                        
                        <div x-show="selectedProduct && inputMode === 'manual'" x-transition class="mt-2 p-2 bg-green-50 rounded border border-green-200">
                            <p class="text-sm text-green-800">
                                <span class="font-medium" x-text="selectedProduct ? selectedProduct.nama : ''"></span> - 
                                <span x-text="selectedProduct ? formatCurrency(selectedProduct.price) : ''"></span>
                            </p>
                        </div>
                        
                        <div x-show="codeError" x-transition class="mt-1 text-xs text-red-500" x-text="codeError"></div>
                    </div>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" x-model.number="quantity" @input="calculateSubtotal()"
                           name="jumlah" value="{{ old('jumlah', 1) }}" 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Masukkan jumlah" min="1" required>
                    @error('jumlah')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview Subtotal --}}
                <div x-show="selectedProduct && quantity > 0" x-transition class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-blue-700">Subtotal:</span>
                        <span class="font-medium text-blue-800" x-text="formatCurrency(subtotal)"></span>
                    </div>
                </div>
                
                {{-- Hidden field for harga --}}
                <input type="hidden" name="harga" x-bind:value="price">

                <div class="pt-2">
                    <button type="button" @click="submitForm()" 
                            :disabled="isSubmitting || !selectedProduct"
                            :class="{ 'opacity-50 cursor-not-allowed': isSubmitting || !selectedProduct }"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                        <span x-show="!isSubmitting">Tambah Produk</span>
                        <span x-show="isSubmitting">Menambahkan...</span>
                    </button>
                </div>
            </form>
            
            @if($penawaran->status != 'pending')
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">Status Penawaran</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Penawaran sudah diproses, tidak dapat menambah produk baru.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- DETAIL PRODUK --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Detail Produk
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        @if($penawaran->status == 'pending')
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($details as $index => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-blue-600">{{ $detail->produk->kode }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $detail->produk->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $detail->produk->kategori->nama_kategori }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        @if($penawaran->status == 'pending')
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('sales.penawaran.destroy-detail', $detail->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.closest('form'), '{{ $detail->produk->nama }}')" 
                                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors" 
                                        title="Hapus Produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $penawaran->status == 'pending' ? '8' : '7' }}" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a1 1 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-1-1M4 5l1-1"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500 mb-2">Belum ada produk</p>
                            <p class="text-gray-400">Tambahkan produk untuk penawaran ini</p>
                        </td>
                    </tr>
                    @endforelse
                    
                    @if(!$details->isEmpty())
                    <tr class="bg-gray-50 font-semibold">
                        <td colspan="{{ $penawaran->status == 'pending' ? '6' : '6' }}" class="px-6 py-4 text-right text-gray-900">Total:</td>
                        <td class="px-6 py-4 text-right text-lg text-gray-900">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</td>
                        @if($penawaran->status == 'pending')
                        <td></td>
                        @endif
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Action Buttons --}}
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex flex-col sm:flex-row justify-between gap-3">
                <a href="{{ route('sales.penawaran.index') }}" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                
                <div class="flex gap-3">
                    @if($penawaran->status == 'pending' && !$details->isEmpty())
                    <form action="{{ route('sales.penawaran.submit', $penawaran) }}" method="POST" class="inline">
                        @csrf
                        <button type="button" onclick="confirmSubmit(this.closest('form'))" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 16 9-2zm0 0v-8"/>
                            </svg>
                            Ajukan Penawaran
                        </button>
                    </form>
                    @endif

                    @if($penawaran->status == 'approved')
                    <a href="{{ route('sales.penawaran.export-pdf', $penawaran) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export PDF
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Alpine.js Component --}}
<script>
function penawaranData() {
    return {
        isSubmitting: false,
        selectedProduct: null,
        quantity: 1,
        price: 0,
        subtotal: 0,
        inputMode: 'dropdown',
        productCode: '',
        isSearching: false,
        codeError: '',
        searchTimeout: null,
        
        // Product data from server
        products: {!! json_encode($produks->map(function($produk) {
            return [
                'id' => $produk->id,
                'kode' => $produk->kode,
                'nama' => $produk->nama,
                'harga' => $produk->harga
            ];
        })) !!},
        
        selectProductFromDropdown(event) {
            const option = event.target.selectedOptions[0];
            if (option.value) {
                this.selectedProduct = {
                    id: option.value,
                    kode: option.dataset.kode,
                    nama: option.dataset.nama,
                    price: parseFloat(option.dataset.harga || 0)
                };
                this.price = this.selectedProduct.price;
                this.calculateSubtotal();
                this.productCode = this.selectedProduct.kode;
                this.codeError = '';
            } else {
                this.resetSelection();
            }
        },
        
        searchProductByCode() {
            // Clear previous timeout
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }
            
            // Reset states
            this.codeError = '';
            this.selectedProduct = null;
            this.price = 0;
            this.subtotal = 0;
            
            if (!this.productCode.trim()) {
                return;
            }
            
            this.isSearching = true;
            
            // Debounce search
            this.searchTimeout = setTimeout(() => {
                this.performSearch();
            }, 300);
        },
        
        performSearch() {
            const code = this.productCode.trim().toLowerCase();
            const product = this.products.find(p => p.kode.toLowerCase() === code);
            
            this.isSearching = false;
            
            if (product) {
                this.selectedProduct = {
                    id: product.id,
                    kode: product.kode,
                    nama: product.nama,
                    price: parseFloat(product.harga || 0)
                };
                this.price = this.selectedProduct.price;
                this.calculateSubtotal();
                this.codeError = '';
            } else if (this.productCode.trim()) {
                this.codeError = 'Produk dengan kode tersebut tidak ditemukan';
            }
        },
        
        validateProductCode() {
            if (this.inputMode === 'manual' && this.productCode.trim() && !this.selectedProduct) {
                this.codeError = 'Produk dengan kode tersebut tidak ditemukan';
            }
        },
        
        resetSelection() {
            this.selectedProduct = null;
            this.price = 0;
            this.subtotal = 0;
            this.productCode = '';
            this.codeError = '';
        },
        
        calculateSubtotal() {
            this.subtotal = this.quantity * this.price;
        },
        
        formatCurrency(amount) {
            return 'Rp ' + this.formatNumber(amount);
        },
        
        formatNumber(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        },
        
        submitForm() {
            if (!this.selectedProduct) {
                alert('Silakan pilih produk terlebih dahulu');
                return;
            }
            
            this.isSubmitting = true;
            this.$refs.addProductForm.submit();
        }
    }
}

// Utility Functions
function confirmDelete(form, productName) {
    if (confirm(`Apakah Anda yakin ingin menghapus produk "${productName}" dari penawaran ini?`)) {
        form.submit();
    }
}

function confirmSubmit(form) {
    if (confirm('Apakah Anda yakin ingin mengajukan penawaran ini? Setelah diajukan, Anda tidak dapat mengedit lagi.')) {
        form.submit();
    }
}
</script>
@endsection