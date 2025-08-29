@extends('layouts.app')

@section('title', 'Lacak Pengiriman')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lacak Pengiriman</h1>
                <p class="text-gray-600 mt-1">Detail pengiriman dan status terkini</p>
                
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
                                <a href="{{ route(Auth::user()->role . '.pengiriman.index') }}" class="text-sm text-gray-500 hover:text-blue-600">Pengiriman</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-500">Lacak</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex items-center space-x-2">
                <button onclick="window.history.back()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </button>
            </div>
        </div>
    </div>

    {{-- TRACKING INFO CARD --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Informasi Pengiriman
            </h2>
            <p class="text-sm text-gray-600 mt-1">Detail pengiriman #{{ $pengiriman->id }}</p>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Kode Barang</span>
                        <span class="text-sm text-blue-600 font-mono font-medium">: {{ $pengiriman->barangKeluar->kode_barang_keluar }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Tanggal</span>
                        <span class="text-sm text-gray-900">: {{ $pengiriman->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Status</span>
                        <span class="text-sm">: 
                            @if($pengiriman->status_pengiriman == 'belum_dikirim')
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Belum Dikirim</span>
                            @elseif($pengiriman->status_pengiriman == 'dalam_perjalanan')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Dalam Perjalanan</span>
                            @elseif($pengiriman->status_pengiriman == 'istirahat')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Istirahat</span>
                            @elseif($pengiriman->status_pengiriman == 'selesai')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Selesai</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Metode</span>
                        <span class="text-sm">: 
                            @if($pengiriman->metode_pengiriman == 'ambil_sendiri')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Diambil Sendiri</span>
                            @else
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Diantar Driver</span>
                            @endif
                        </span>
                    </div>
                    @if($pengiriman->driver)
                    <div class="flex">
                        <span class="w-32 text-sm font-medium text-gray-500">Driver</span>
                        <span class="text-sm text-gray-900">: {{ $pengiriman->driver->name }}</span>
                    </div>
                    @endif
                    <div class="flex items-start">
                        <span class="w-32 text-sm font-medium text-gray-500 mt-0.5">Catatan</span>
                        <span class="text-sm text-gray-900 flex-1">: {{ $pengiriman->catatan ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TRACKING TIMELINE --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Timeline Status
            </h2>
            <p class="text-sm text-gray-600 mt-1">Riwayat status pengiriman</p>
        </div>

        <div class="p-6">
            <ol class="relative border-l border-gray-200">
                <!-- Status "Dibuat" selalu ada -->
                <li class="mb-10 ml-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                        <svg class="w-3 h-3 text-blue-800" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 12a1 1 0 112 0 1 1 0 01-2 0zm1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" />
                        </svg>
                    </span>
                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Pengiriman Dibuat</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-500">{{ $pengiriman->created_at->format('d M Y, H:i') }}</time>
                    <p class="mb-4 text-base font-normal text-gray-600">
                        Pengiriman dengan kode {{ $pengiriman->barangKeluar->kode_barang_keluar }} telah dibuat.
                    </p>
                </li>
                
                <!-- Status "Dalam Perjalanan" jika ada -->
                @if($pengiriman->waktu_mulai)
                <li class="mb-10 ml-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 bg-yellow-100 rounded-full -left-3 ring-8 ring-white">
                        <svg class="w-3 h-3 text-yellow-800" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm7 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0h4.1a2.5 2.5 0 014.9 0H15a1 1 0 001-1V5a1 1 0 00-1-1H3zm0 2.5A1.5 1.5 0 013 4h11a1.5 1.5 0 011.5 1.5v8A1.5 1.5 0 0114 15h-.05a2.5 2.5 0 00-4.9 0h-4.1a2.5 2.5 0 00-4.9 0H3a1.5 1.5 0 01-1.5-1.5v-7A1.5 1.5 0 013 4.5z" />
                        </svg>
                    </span>
                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Dalam Perjalanan</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-500">{{ $pengiriman->waktu_mulai->format('d M Y, H:i') }}</time>
                    <p class="mb-4 text-base font-normal text-gray-600">
                        @if($pengiriman->metode_pengiriman == 'diantar_driver')
                            Driver memulai pengiriman barang ke alamat tujuan.
                        @else
                            Barang siap untuk diambil oleh pelanggan.
                        @endif
                    </p>
                </li>
                @endif
                
                <!-- Status "Selesai" jika ada -->
                @if($pengiriman->waktu_selesai)
                <li class="ml-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3 ring-8 ring-white">
                        <svg class="w-3 h-3 text-green-800" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900">Pengiriman Selesai</h3>
                    <time class="block mb-2 text-sm font-normal leading-none text-gray-500">{{ $pengiriman->waktu_selesai->format('d M Y, H:i') }}</time>
                    <p class="text-base font-normal text-gray-600">
                        @if($pengiriman->metode_pengiriman == 'diantar_driver')
                            Barang telah berhasil dikirim ke alamat tujuan.
                        @else
                            Barang telah diambil oleh pelanggan.
                        @endif
                    </p>
                </li>
                @endif
            </ol>
        </div>
    </div>

    {{-- RECIPIENT INFO --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Informasi Penerima
            </h2>
            <p class="text-sm text-gray-600 mt-1">Detail alamat pengiriman</p>
        </div>

        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-800">Informasi Alamat Pengiriman</h4>
                        <p class="text-sm text-blue-700 mt-1">
                            Berikut adalah informasi detail penerima untuk pengiriman ini.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-500">Nama Penerima</span>
                    <span class="text-sm text-gray-900">: {{ $pengiriman->barangKeluar->penerima }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-500">No. Telepon</span>
                    <span class="text-sm text-gray-900">: {{ $pengiriman->barangKeluar->telepon_penerima }}</span>
                </div>
                <div class="flex items-start">
                    <span class="w-32 text-sm font-medium text-gray-500 mt-0.5">Alamat</span>
                    <span class="text-sm text-gray-900 flex-1">: {{ $pengiriman->barangKeluar->alamat_penerima }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTIONS BUTTONS --}}
    <div class="flex flex-col sm:flex-row gap-3 justify-between">
        <a href="{{ route(Auth::user()->role . '.pengiriman.show', $pengiriman->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            Detail Lengkap
        </a>
        <a href="{{ route(Auth::user()->role . '.pengiriman.print-label', $pengiriman->id) }}" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Label
        </a>
    </div>
</div>

<style>
    /* Custom timeline styling */
    .relative.border-l::before {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 1px;
        background-color: #e5e7eb;
    }
</style>

@endsection
