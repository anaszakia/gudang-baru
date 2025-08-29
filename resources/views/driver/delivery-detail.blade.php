@extends('layouts.app')

@section('title', 'Detail Pengiriman')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengiriman</h1>
                <p class="text-gray-600 mt-1">Detail dan pengelolaan status pengiriman</p>
                
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
                                <span class="text-sm text-gray-500">Detail Pengiriman</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                @if(in_array($pengiriman->status_pengiriman, ['dalam_perjalanan', 'istirahat', 'selesai']))
                <a href="{{ route('driver.deliveries.print-note', $pengiriman->id) }}" target="_blank"
                   class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Surat Jalan
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- STATUS --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Status Pengiriman
            </h2>
            <p class="text-sm text-gray-600 mt-1">Update status pengiriman saat ini</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <div class="bg-gray-50 border rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700">Status Saat Ini:</h4>
                        <div class="mt-3">
                            @if($pengiriman->status_pengiriman == 'belum_dikirim')
                                <span class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded-lg">Belum Dikirim</span>
                            @elseif($pengiriman->status_pengiriman == 'dalam_perjalanan')
                                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-lg">Dalam Perjalanan</span>
                            @elseif($pengiriman->status_pengiriman == 'istirahat')
                                <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-lg">Istirahat</span>
                            @elseif($pengiriman->status_pengiriman == 'selesai')
                                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-lg">Selesai</span>
                            @endif
                        </div>
                        
                        <div class="mt-4 space-y-2">
                            @if($pengiriman->waktu_mulai && is_object($pengiriman->waktu_mulai))
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-gray-700">Waktu Mulai: <span class="font-medium">{{ $pengiriman->waktu_mulai->format('d/m/Y H:i') }}</span></span>
                            </div>
                            @elseif($pengiriman->waktu_mulai)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm text-gray-700">Waktu Mulai: <span class="font-medium">{{ $pengiriman->waktu_mulai }}</span></span>
                            </div>
                            @endif
                            
                            @if($pengiriman->waktu_selesai && is_object($pengiriman->waktu_selesai))
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-700">Waktu Selesai: <span class="font-medium">{{ $pengiriman->waktu_selesai->format('d/m/Y H:i') }}</span></span>
                            </div>
                            @elseif($pengiriman->waktu_selesai)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-700">Waktu Selesai: <span class="font-medium">{{ $pengiriman->waktu_selesai }}</span></span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($pengiriman->status_pengiriman != 'selesai')
                <div>
                    <div class="bg-white border border-yellow-200 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-800 flex items-center mb-3">
                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Update Status Pengiriman
                        </h4>
                        
                        <form action="{{ route('driver.deliveries.update-status', $pengiriman->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="status_pengiriman" class="block text-sm font-medium text-gray-700 mb-1">
                                    Status
                                </label>
                                <select id="status_pengiriman" 
                                        name="status_pengiriman" 
                                        class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="dalam_perjalanan" {{ $pengiriman->status_pengiriman == 'dalam_perjalanan' ? 'selected' : '' }}>
                                        Dalam Perjalanan
                                    </option>
                                    <option value="istirahat" {{ $pengiriman->status_pengiriman == 'istirahat' ? 'selected' : '' }}>
                                        Istirahat
                                    </option>
                                    <option value="selesai">
                                        Selesai
                                    </option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Catatan (opsional)
                                </label>
                                <textarea id="catatan" 
                                          name="catatan" 
                                          rows="3"
                                          class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Tambahkan catatan jika ada">{{ $pengiriman->catatan }}</textarea>
                            </div>
                            
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                Update Status
                            </button>
                        </form>
                    </div>
                </div>
                @endif
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
            <p class="text-sm text-gray-600 mt-1">Informasi detail pengiriman</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Kode</span>
                        <span class="text-sm font-mono text-blue-600 font-medium">: {{ $pengiriman->barangKeluar->kode_barang_keluar }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Tanggal</span>
                        <span class="text-sm text-gray-900">: {{ $pengiriman->barangKeluar->tanggal_keluar->format('d/m/Y') }}</span>
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

    {{-- DETAIL BARANG --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Detail Barang
            </h2>
            <p class="text-sm text-gray-600 mt-1">Daftar barang yang dikirim</p>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pengiriman->barangKeluar->detailBarangKeluars as $key => $detail)
                        <tr>
                            <td class="px-4 py-3">{{ $key + 1 }}</td>
                            <td class="px-4 py-3 font-mono text-blue-600">{{ $detail->produk->kode }}</td>
                            <td class="px-4 py-3">{{ $detail->produk->nama }}</td>
                            <td class="px-4 py-3">{{ $detail->jumlah }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
