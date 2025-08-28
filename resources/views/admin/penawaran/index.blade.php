@extends('layouts.app')

@section('title', 'Daftar Penawaran')

@section('content')
<div x-data="{ 
    exportModal: false,
    activeTab: 'all',
    openModal(type) {
        this[type + 'Modal'] = true;
        document.body.style.overflow = 'hidden';
    },
    closeModal(type) {
        this[type + 'Modal'] = false;
        document.body.style.overflow = '';
    },
    setActiveTab(tab) {
        this.activeTab = tab;
    }
}" class="space-y-6">


    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Daftar Penawaran</h1>
                <p class="text-gray-600 mt-1">Kelola penawaran kepada pelanggan</p>
                
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
                                <span class="text-sm text-gray-500">Daftar Penawaran</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                {{-- Export Button --}}
                <button @click="openModal('export')" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </button>
            </div>
        </div>
    </div>

    {{-- TABS AND TABLE --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        {{-- Tab Navigation --}}
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button @click="setActiveTab('all')" 
                        :class="activeTab === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Semua
                </button>
                <button @click="setActiveTab('pending')" 
                        :class="activeTab === 'pending' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Menunggu Persetujuan
                </button>
                <button @click="setActiveTab('approved')" 
                        :class="activeTab === 'approved' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Disetujui
                </button>
                <button @click="setActiveTab('rejected')" 
                        :class="activeTab === 'rejected' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Ditolak
                </button>
            </nav>
        </div>

        {{-- Tab Content --}}
        <div class="overflow-x-auto">
            {{-- All Tab --}}
            <div x-show="activeTab === 'all'">
                <table class="min-w-full divide-y divide-gray-200" id="all-penawaran-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Penawaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penawarans as $penawaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $penawaran->kode_penawaran }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ date('d M Y', strtotime($penawaran->tanggal_penawaran)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $penawaran->nama_pelanggan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->user->name }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($penawaran->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Menunggu Persetujuan</span>
                                @elseif($penawaran->status == 'approved')
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Disetujui</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route(Auth::user()->role . '.penawaran.show', $penawaran) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                       title="Lihat Penawaran">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    @if($penawaran->status == 'pending')
                                        <a href="{{ route(Auth::user()->role . '.penawaran.approve', $penawaran) }}" 
                                           class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors" 
                                           title="Proses Penawaran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-500 mb-2">Belum ada penawaran</p>
                                <p class="text-gray-400">Belum ada data penawaran yang tersedia</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pending Tab --}}
            <div x-show="activeTab === 'pending'">
                <table class="min-w-full divide-y divide-gray-200" id="pending-penawaran-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Penawaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penawarans->where('status', 'pending') as $penawaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $penawaran->kode_penawaran }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ date('d M Y', strtotime($penawaran->tanggal_penawaran)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $penawaran->nama_pelanggan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->user->name }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route(Auth::user()->role . '.penawaran.show', $penawaran) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                       title="Lihat Penawaran">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route(Auth::user()->role . '.penawaran.approve', $penawaran) }}" 
                                       class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors" 
                                       title="Proses Penawaran">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-500 mb-2">Tidak ada penawaran menunggu</p>
                                <p class="text-gray-400">Semua penawaran sudah diproses</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Approved Tab --}}
            <div x-show="activeTab === 'approved'">
                <table class="min-w-full divide-y divide-gray-200" id="approved-penawaran-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Penawaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disetujui Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Persetujuan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penawarans->where('status', 'approved') as $penawaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $penawaran->kode_penawaran }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ date('d M Y', strtotime($penawaran->tanggal_penawaran)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $penawaran->nama_pelanggan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->user->name }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->approver ? $penawaran->approver->name : '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->approved_at ? date('d M Y H:i', strtotime($penawaran->approved_at)) : '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route(Auth::user()->role . '.penawaran.show', $penawaran) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                       title="Lihat Penawaran">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-500 mb-2">Belum ada penawaran disetujui</p>
                                <p class="text-gray-400">Penawaran yang disetujui akan muncul di sini</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Rejected Tab --}}
            <div x-show="activeTab === 'rejected'">
                <table class="min-w-full divide-y divide-gray-200" id="rejected-penawaran-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Penawaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ditolak Oleh</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Penolakan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($penawarans->where('status', 'rejected') as $penawaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $penawaran->kode_penawaran }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ date('d M Y', strtotime($penawaran->tanggal_penawaran)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $penawaran->nama_pelanggan }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->user->name }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->approver ? $penawaran->approver->name : '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $penawaran->approved_at ? date('d M Y H:i', strtotime($penawaran->approved_at)) : '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route(Auth::user()->role . '.penawaran.show', $penawaran) }}" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                       title="Lihat Penawaran">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-500 mb-2">Belum ada penawaran ditolak</p>
                                <p class="text-gray-400">Penawaran yang ditolak akan muncul di sini</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- EXPORT MODAL --}}
    <div x-show="exportModal" x-cloak 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div @click.away="closeModal('export')" 
             class="bg-white rounded-lg shadow-xl w-full max-w-md">
            
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Export Data Penawaran</h3>
                <button @click="closeModal('export')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <form action="{{ route(Auth::user()->role . '.penawaran.export-excel') }}" method="POST" class="p-6">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" 
                               class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="end_date" 
                               class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="text-sm text-gray-500">
                        Data yang akan diexport adalah penawaran pada rentang tanggal yang dipilih.
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end gap-3 pt-6 mt-6 border-t">
                    <button type="button" @click="closeModal('export')" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Export
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if(isset($penawarans) && method_exists($penawarans, 'links'))
        <div class="bg-white rounded-lg shadow-sm border p-4">
            {{ $penawarans->links() }}
        </div>
    @endif
</div>

{{-- Scripts --}}
<script>
    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            // Find active modal and close it
            const activeElement = document.activeElement;
            if (activeElement && activeElement.closest('[x-show]')) {
                // Trigger escape event for Alpine.js
                window.dispatchEvent(new CustomEvent('keydown-escape'));
            }
        }
    });

    // Initialize DataTable when document is ready
    $(document).ready(function() {
        // Common DataTable configuration
        const dataTableConfig = {
            paging: false,
            ordering: true,
            info: false,
            responsive: true,
            language: {
                search: "Cari:",
                searchPlaceholder: "Cari penawaran...",
                zeroRecords: "Tidak ada data yang ditemukan",
                emptyTable: "Belum ada data penawaran"
            }
        };

        // Initialize all tables
        $('#all-penawaran-table').DataTable(dataTableConfig);
        $('#pending-penawaran-table').DataTable(dataTableConfig);
        $('#approved-penawaran-table').DataTable(dataTableConfig);
        $('#rejected-penawaran-table').DataTable(dataTableConfig);
    });
</script>

<style>
    [x-cloak] { display: none !important; }
    
    /* Custom scrollbar for modal content */
    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* DataTable custom styling */
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 1rem;
        text-align: right;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        margin-left: 0.5rem;
        width: 200px;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .dataTables_wrapper .dataTables_filter label {
        font-weight: 500;
        color: #374151;
    }

    /* Tab transition */
    [x-show] {
        transition: opacity 0.2s ease-in-out;
    }

    /* Hover effects for table rows */
    .hover\\:bg-gray-50:hover {
        background-color: #f9fafb;
    }

    /* Badge hover effects */
    .bg-yellow-100:hover {
        background-color: #fef3c7;
    }

    .bg-green-100:hover {
        background-color: #dcfce7;
    }

    .bg-red-100:hover {
        background-color: #fee2e2;
    }

    /* Button hover animations */
    .transition-colors {
        transition-property: color, background-color, border-color;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    /* Modal animation */
    [x-show="exportModal"] {
        animation: fadeIn 0.2s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive table improvements */
    @media (max-width: 768px) {
        .overflow-x-auto {
            scrollbar-width: thin;
        }
        
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }
    }

    /* Tab active state improvements */
    .border-b-2.border-blue-500 {
        border-bottom-width: 2px;
        border-bottom-color: #3b82f6;
    }

    /* Focus styles for accessibility */
    button:focus,
    input:focus,
    a:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }

    /* Loading states */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Print styles */
    @media print {
        .no-print {
            display: none !important;
        }
        
        .bg-white {
            background-color: white !important;
        }
        
        .shadow-sm,
        .shadow-xl {
            box-shadow: none !important;
        }
    }
</style>

@endsection

@push('scripts')
<script>
    // Additional scripts for enhanced functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-close alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert-dismissible, [role="alert"]');
        alerts.forEach(alert => {
            if (alert.querySelector('button')) {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            }
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Close modal on Escape
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('[x-show]');
                modals.forEach(modal => {
                    if (modal.style.display !== 'none') {
                        // Trigger Alpine.js close
                        modal.dispatchEvent(new CustomEvent('click-away'));
                    }
                });
            }
        });

        // Table row click handler for better UX
        document.querySelectorAll('tbody tr').forEach(row => {
            row.addEventListener('click', function(e) {
                if (e.target.tagName === 'TD' && !e.target.querySelector('button, a')) {
                    const viewLink = this.querySelector('a[title="Lihat Penawaran"]');
                    if (viewLink) {
                        viewLink.click();
                    }
                }
            });
        });

        // Enhanced search functionality
        const searchInputs = document.querySelectorAll('.dataTables_filter input');
        searchInputs.forEach(input => {
            input.setAttribute('placeholder', 'Cari penawaran...');
            
            // Add search icon
            const wrapper = input.parentElement;
            wrapper.style.position = 'relative';
            
            const icon = document.createElement('div');
            icon.innerHTML = `
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            `;
            icon.style.position = 'absolute';
            icon.style.right = '10px';
            icon.style.top = '50%';
            icon.style.transform = 'translateY(-50%)';
            icon.style.pointerEvents = 'none';
            
            wrapper.appendChild(icon);
            input.style.paddingRight = '40px';
        });

        // Form validation for export modal
        const exportForm = document.querySelector('form[action*="export-excel"]');
        if (exportForm) {
            exportForm.addEventListener('submit', function(e) {
                const startDate = this.querySelector('input[name="start_date"]').value;
                const endDate = this.querySelector('input[name="end_date"]').value;
                
                if (new Date(startDate) > new Date(endDate)) {
                    e.preventDefault();
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                    return false;
                }
                
                // Show loading state
                const submitButton = this.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = `
                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Mengexport...
                `;
                submitButton.disabled = true;
                
                // Reset after 5 seconds (in case of error)
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }, 5000);
            });
        }

        // Tab switching with keyboard navigation
        const tabs = document.querySelectorAll('[role="tab"], nav button');
        tabs.forEach((tab, index) => {
            tab.addEventListener('keydown', function(e) {
                let nextTab;
                
                if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    nextTab = index > 0 ? tabs[index - 1] : tabs[tabs.length - 1];
                } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    nextTab = index < tabs.length - 1 ? tabs[index + 1] : tabs[0];
                } else if (e.key === 'Home') {
                    e.preventDefault();
                    nextTab = tabs[0];
                } else if (e.key === 'End') {
                    e.preventDefault();
                    nextTab = tabs[tabs.length - 1];
                }
                
                if (nextTab) {
                    nextTab.focus();
                    nextTab.click();
                }
            });
        });

        // Smooth scrolling for table navigation
        const tables = document.querySelectorAll('table');
        tables.forEach(table => {
            table.addEventListener('wheel', function(e) {
                if (Math.abs(e.deltaX) > Math.abs(e.deltaY)) {
                    e.preventDefault();
                    this.scrollLeft += e.deltaX;
                }
            });
        });

        // Auto-refresh data every 30 seconds for pending tab
        setInterval(function() {
            const activeTab = document.querySelector('[x-data]').__x.$data.activeTab;
            if (activeTab === 'pending') {
                // Only refresh if on pending tab and page is visible
                if (!document.hidden) {
                    // You could implement AJAX refresh here
                    console.log('Auto-refresh pending data');
                }
            }
        }, 30000);

        // Notification for new pending items (if implementing real-time updates)
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animate in
            notification.style.transform = 'translateX(100%)';
            notification.style.transition = 'transform 0.3s ease-out';
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 5000);
        }

        // Export success handling
        window.addEventListener('export-complete', function(e) {
            showNotification('Export berhasil! File sedang diunduh...', 'success');
        });
    });
</script>
@endpush