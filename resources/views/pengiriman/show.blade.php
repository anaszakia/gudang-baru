@extends('layouts.app')

@section('title', 'Detail Pengiriman')

@section('content')
<div x-data="{ 
    deleteModal: false,
    openModal(type) {
        this[type + 'Modal'] = true;
        document.body.style.overflow = 'hidden';
    },
    closeModal(type) {
        this[type + 'Modal'] = false;
        document.body.style.overflow = '';
    }
}" class="space-y-6">
    
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengiriman</h1>
                <p class="text-gray-600 mt-1">{{ $pengiriman->barangKeluar->kode_barang_keluar }}</p>
                
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
                                <a href="{{ route(Auth::user()->role . '.pengiriman.index') }}" class="text-sm text-gray-500 hover:text-blue-600">Daftar Pengiriman</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-500">Detail Pengiriman</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <a href="{{ route(Auth::user()->role . '.pengiriman.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                
                <a href="{{ route(Auth::user()->role . '.barang-keluar.invoice', $pengiriman->barangKeluar->id) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Lihat Invoice
                </a>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- INFORMASI PENGIRIMAN --}}
        <div class="xl:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Pengiriman</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Kode Barang Keluar</span>
                            <span class="text-sm font-mono text-blue-600 font-medium">{{ $pengiriman->barangKeluar->kode_barang_keluar }}</span>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Tanggal</span>
                            <span class="text-sm text-gray-900">{{ date('d M Y', strtotime($pengiriman->barangKeluar->tanggal_keluar)) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Metode Pengiriman</span>
                            <div>
                                @if($pengiriman->metode_pengiriman == 'ambil_sendiri')
                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Diambil Sendiri</span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Diantar Driver</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Status</span>
                            <div>
                                @if($pengiriman->status_pengiriman == 'belum_dikirim')
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Belum Dikirim</span>
                                @elseif($pengiriman->status_pengiriman == 'dalam_perjalanan')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Dalam Perjalanan</span>
                                @elseif($pengiriman->status_pengiriman == 'istirahat')
                                    <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Istirahat</span>
                                @elseif($pengiriman->status_pengiriman == 'selesai')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Selesai</span>
                                @endif
                            </div>
                        </div>

                        @if($pengiriman->metode_pengiriman == 'diantar_driver')
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Driver</span>
                            <span class="text-sm text-gray-900">{{ $pengiriman->driver->name ?? '-' }}</span>
                        </div>
                        @endif
                        
                        @if($pengiriman->waktu_mulai)
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Waktu Mulai</span>
                            <span class="text-sm text-gray-900">{{ date('d M Y H:i', strtotime($pengiriman->waktu_mulai)) }}</span>
                        </div>
                        @endif
                        
                        @if($pengiriman->waktu_selesai)
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Waktu Selesai</span>
                            <span class="text-sm text-gray-900">{{ date('d M Y H:i', strtotime($pengiriman->waktu_selesai)) }}</span>
                        </div>
                        @endif
                        
                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm text-gray-600 font-medium">Total Harga</span>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-green-600">Rp {{ number_format($pengiriman->barangKeluar->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($pengiriman->catatan)
                            <div class="pt-4 border-t">
                                <span class="text-sm text-gray-600 font-medium block mb-2">Catatan</span>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $pengiriman->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($pengiriman->metode_pengiriman == 'diantar_driver' && in_array(Auth::user()->role, ['admin-super', 'admin-gudang']) && $pengiriman->status_pengiriman != 'selesai')
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden mt-6">
                <div class="bg-yellow-50 px-6 py-4 border-b">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Update Status Pengiriman</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route(Auth::user()->role . '.pengiriman.update-status', $pengiriman->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status_pengiriman" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="dalam_perjalanan" {{ $pengiriman->status_pengiriman == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                <option value="istirahat" {{ $pengiriman->status_pengiriman == 'istirahat' ? 'selected' : '' }}>Istirahat</option>
                                <option value="selesai" {{ $pengiriman->status_pengiriman == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>

        {{-- INFORMASI PENERIMA --}}
        <div class="xl:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Penerima</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Penerima</label>
                                <div class="text-lg font-semibold text-gray-900">{{ $pengiriman->barangKeluar->penerima }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Telepon</label>
                                <div class="text-sm text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    {{ $pengiriman->barangKeluar->telepon_penerima }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                                <div class="text-sm text-gray-900 flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="leading-relaxed">{{ $pengiriman->barangKeluar->alamat_penerima }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DETAIL PRODUK --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900">Detail Barang</h3>
                </div>
                <div class="text-sm text-gray-600">
                    Total: {{ count($pengiriman->barangKeluar->detailBarangKeluars) }} item{{ count($pengiriman->barangKeluar->detailBarangKeluars) > 1 ? 's' : '' }}
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengiriman->barangKeluar->detailBarangKeluars as $key => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $key + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">{{ $detail->produk->kode }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="font-medium">{{ $detail->produk->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 font-medium">{{ $detail->jumlah }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">Rp {{ number_format($detail->produk->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Total Harga:</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-green-600">Rp {{ number_format($pengiriman->barangKeluar->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    
    /* Custom scrollbar for tables */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }
</style>
@endsection
