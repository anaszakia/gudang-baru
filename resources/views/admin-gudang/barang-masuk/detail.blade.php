@extends('layouts.app')
@section('title', 'Detail Barang Masuk')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi Barang Masuk</h1>
                <p class="text-sm text-gray-500 mt-1">Tambahkan produk ke transaksi {{ $barangMasuk->nomor_transaksi }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin-gudang.barang-masuk.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg transition-colors font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Batal
                </a>
                <a href="{{ route('admin-gudang.barang-masuk.finalize', $barangMasuk->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Selesai
                </a>
            </div>
        </div>
    </div>

    {{-- INFO TRANSAKSI --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Transaksi</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-sm text-gray-500">Nomor Transaksi</div>
                <div class="font-medium text-gray-900">{{ $barangMasuk->nomor_transaksi }}</div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-sm text-gray-500">Tanggal Masuk</div>
                <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d M Y') }}</div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="text-sm text-gray-500">Supplier</div>
                <div class="font-medium text-gray-900">{{ $barangMasuk->supplier }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- FORM TAMBAH PRODUK --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Tambah Produk</h2>
            <form method="POST" action="{{ route('admin-gudang.barang-masuk.store-detail', $barangMasuk->id) }}" class="space-y-4" id="addProductForm">
                @csrf
                
                {{-- Metode Input --}}
                <div class="border-b border-gray-200 pb-3 mb-1">
                    <div class="flex items-center justify-start space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="input_method" value="dropdown" class="form-radio h-4 w-4 text-blue-600" checked onclick="toggleInputMethod('dropdown')">
                            <span class="ml-2 text-sm text-gray-700">Pilih dari List</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="input_method" value="code" class="form-radio h-4 w-4 text-blue-600" onclick="toggleInputMethod('code')">
                            <span class="ml-2 text-sm text-gray-700">Input Kode</span>
                        </label>
                    </div>
                </div>
                
                {{-- Produk (Dropdown) --}}
                <div id="dropdown-method">
                    <div>
                        <label for="produk_id" class="block text-sm font-medium text-gray-700 mb-1">Produk</label>
                        <select id="produk_id" name="produk_id" 
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($produks as $produk)
                                <option value="{{ $produk->id }}">{{ $produk->nama }} - {{ $produk->kode }}</option>
                            @endforeach
                        </select>
                        @error('produk_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Produk (Input Kode) --}}
                <div id="code-method" class="hidden">
                    <div>
                        <label for="kode_produk" class="block text-sm font-medium text-gray-700 mb-1">Kode Produk</label>
                        <input type="text" id="kode_produk" name="kode_produk" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Masukkan kode produk">
                        <input type="hidden" id="produk_id_by_code" name="produk_id_by_code">
                        <p id="produk_info" class="mt-1 text-sm text-gray-600 hidden"></p>
                        <p id="produk_error" class="mt-1 text-xs text-red-500 hidden">Kode produk tidak ditemukan</p>
                    </div>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah') }}" 
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Masukkan jumlah" min="1" required>
                    @error('jumlah')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition-colors font-medium">
                        Tambah Produk
                    </button>
                </div>
            </form>
        </div>

        {{-- TABEL PRODUK YANG SUDAH DITAMBAHKAN --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Produk</h2>
            
            @if($barangMasuk->details->isEmpty())
                <div class="py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v10l-8 4m-8-4V7m16 10l-8-4m-8 4l8-4m8 4v-10"/>
                        </svg>
                        <p class="text-lg font-medium">Belum ada produk ditambahkan</p>
                        <p class="text-sm text-gray-400 mt-1">Silahkan tambahkan produk di form sebelah</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($barangMasuk->details as $index => $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->produk->kode }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->produk->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->jumlah }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <form action="{{ route('admin-gudang.barang-masuk.destroy-detail', $detail->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 border-t border-gray-200 pt-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Total Jenis Produk:</span>
                            <span class="ml-2 text-lg font-semibold text-gray-900">{{ $barangMasuk->details->count() }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Total Nilai:</span>
                            <span class="ml-2 text-lg font-semibold text-gray-900">Rp {{ number_format($barangMasuk->details->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
{{-- JavaScript untuk handling input kode produk --}}
<script>
    // Toggle antara metode input dropdown dan kode
    function toggleInputMethod(method) {
        if (method === 'dropdown') {
            document.getElementById('dropdown-method').classList.remove('hidden');
            document.getElementById('code-method').classList.add('hidden');
            document.getElementById('produk_id').setAttribute('required', 'required');
            document.getElementById('kode_produk').removeAttribute('required');
        } else {
            document.getElementById('dropdown-method').classList.add('hidden');
            document.getElementById('code-method').classList.remove('hidden');
            document.getElementById('produk_id').removeAttribute('required');
            document.getElementById('kode_produk').setAttribute('required', 'required');
        }
    }

    // Inisialisasi event listener saat dokumen siap
    document.addEventListener('DOMContentLoaded', function() {
        // Pencarian kode produk dengan debounce
        let typingTimer;
        const doneTypingInterval = 500; // milliseconds
        const kodeInput = document.getElementById('kode_produk');
        
        kodeInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            if (kodeInput.value) {
                typingTimer = setTimeout(searchProductByCode, doneTypingInterval);
            } else {
                document.getElementById('produk_info').classList.add('hidden');
                document.getElementById('produk_error').classList.add('hidden');
                document.getElementById('produk_id_by_code').value = '';
            }
        });

        // Setup validasi form submit
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            const inputMethod = document.querySelector('input[name="input_method"]:checked').value;
            
            if (inputMethod === 'code') {
                if (!document.getElementById('produk_id_by_code').value) {
                    e.preventDefault();
                    document.getElementById('produk_error').textContent = 'Silahkan pilih produk yang valid';
                    document.getElementById('produk_error').classList.remove('hidden');
                }
            }
        });
    });

    // Fungsi untuk mencari produk berdasarkan kode
    function searchProductByCode() {
        const kode = document.getElementById('kode_produk').value;
        const infoElement = document.getElementById('produk_info');
        const errorElement = document.getElementById('produk_error');
        const hiddenInput = document.getElementById('produk_id_by_code');
        
        // Reset tampilan
        infoElement.classList.add('hidden');
        errorElement.classList.add('hidden');
        
        // Cari produk dari daftar yang tersedia
        let found = false;
        @foreach($produks as $produk)
        if ('{{ $produk->kode }}'.toLowerCase() === kode.toLowerCase()) {
            infoElement.textContent = 'Produk: {{ $produk->nama }} - Rp. {{ number_format($produk->harga, 0, ',', '.') }}';
            infoElement.classList.remove('hidden');
            hiddenInput.value = '{{ $produk->id }}';
            found = true;
        }
        @endforeach
        
        if (!found) {
            errorElement.textContent = 'Kode produk tidak ditemukan';
            errorElement.classList.remove('hidden');
            hiddenInput.value = '';
        }
    }
</script>
@endsection