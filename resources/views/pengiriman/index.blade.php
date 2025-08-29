@extends('layouts.app')

@section('title', 'Data Pengiriman')

@section('content')
<div x-data="{ 
    exportModal: false,
    detailModal: null,
    openModal(type, id = null) {
        this[type + 'Modal'] = id !== undefined ? id : true;
        document.body.style.overflow = 'hidden';
    },
    closeModal(type) {
        this[type + 'Modal'] = null;
        document.body.style.overflow = '';
    }
}" class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Pengiriman</h1>
                <p class="text-gray-600 mt-1">Kelola data pengiriman barang keluar</p>
                
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
                                <span class="text-sm text-gray-500">Data Pengiriman</span>
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

                {{-- Refresh Button --}}
                <button onclick="window.location.reload()" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="bg-white rounded-lg shadow-sm border p-4">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                <select class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" onchange="filterByStatus(this.value)">
                    <option value="">Semua Status</option>
                    <option value="belum_dikirim">Belum Dikirim</option>
                    <option value="dalam_perjalanan">Dalam Perjalanan</option>
                    <option value="istirahat">Istirahat</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter Metode</label>
                <select class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" onchange="filterByMethod(this.value)">
                    <option value="">Semua Metode</option>
                    <option value="ambil_sendiri">Diambil Sendiri</option>
                    <option value="diantar_driver">Diantar Driver</option>
                </select>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="pengiriman-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penerima</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode Pengiriman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengirimanList as $key => $pengiriman)
                    <tr class="hover:bg-gray-50" data-status="{{ $pengiriman->status_pengiriman }}" data-method="{{ $pengiriman->metode_pengiriman }}">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $pengirimanList->firstItem() + $key }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $pengiriman->barangKeluar->kode_barang_keluar }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $pengiriman->barangKeluar->penerima }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($pengiriman->metode_pengiriman == 'ambil_sendiri')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Diambil Sendiri</span>
                            @else
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2.5 py-0.5 rounded">Diantar Driver</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($pengiriman->driver)
                                {{ $pengiriman->driver->name }}
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @if($pengiriman->status_pengiriman == 'belum_dikirim')
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Belum Dikirim</span>
                            @elseif($pengiriman->status_pengiriman == 'dalam_perjalanan')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Dalam Perjalanan</span>
                            @elseif($pengiriman->status_pengiriman == 'istirahat')
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Istirahat</span>
                            @elseif($pengiriman->status_pengiriman == 'selesai')
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Selesai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                {{-- View Detail Button --}}
                                <a href="{{ route(Auth::user()->role . '.pengiriman.show', $pengiriman->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                {{-- Track Button --}}
                                @if(in_array($pengiriman->status_pengiriman, ['dalam_perjalanan', 'istirahat']))
                                <button onclick="trackPengiriman({{ $pengiriman->id }})" 
                                       class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors" 
                                       title="Lacak Pengiriman">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                                @endif

                                {{-- Print Label Button --}}
                                <button onclick="printLabel({{ $pengiriman->id }})" 
                                       class="bg-purple-500 hover:bg-purple-600 text-white p-2 rounded-lg transition-colors" 
                                       title="Cetak Label">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500 mb-2">Belum ada data pengiriman</p>
                            <p class="text-gray-400">Data pengiriman akan muncul di sini setelah ada barang keluar</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- EXPORT MODAL --}}
    <div x-show="exportModal" x-cloak 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div @click.away="closeModal('export')" 
             class="bg-white rounded-lg shadow-xl w-full max-w-md">
            
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Export Data Pengiriman</h3>
                <button @click="closeModal('export')" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <form action="{{ route(Auth::user()->role . '.pengiriman.export-excel') }}" method="POST" class="p-6">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                        <select name="status_filter" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="belum_dikirim">Belum Dikirim</option>
                            <option value="dalam_perjalanan">Dalam Perjalanan</option>
                            <option value="istirahat">Istirahat</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>

                    <div class="text-sm text-gray-500">
                        Data yang akan diexport adalah pengiriman pada rentang tanggal yang dipilih.
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
    @if(isset($pengirimanList) && method_exists($pengirimanList, 'links'))
        <div class="bg-white rounded-lg shadow-sm border p-4">
            {{ $pengirimanList->links() }}
        </div>
    @endif
</div>

{{-- Scripts --}}
<script>
    // Filter functions
    function filterByStatus(status) {
        const table = document.getElementById('pengiriman-table');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            if (row.classList.contains('empty-row')) continue;
            
            const rowStatus = row.getAttribute('data-status');
            if (status === '' || rowStatus === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    function filterByMethod(method) {
        const table = document.getElementById('pengiriman-table');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            if (row.classList.contains('empty-row')) continue;
            
            const rowMethod = row.getAttribute('data-method');
            if (method === '' || rowMethod === method) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }

    // Track pengiriman
    function trackPengiriman(id) {
        // Implement tracking functionality with correct parameter passing
        window.open(`{{ url('/') }}/{{ Auth::user()->role }}/pengiriman/${id}/track`, '_blank');
    }

    // Print label
    function printLabel(id) {
        // Implement print label functionality with correct parameter passing
        window.open(`{{ url('/') }}/{{ Auth::user()->role }}/pengiriman/${id}/print-label`, '_blank');
    }

    // Close modal when pressing Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            window.dispatchEvent(new CustomEvent('keydown-escape'));
        }
    });

    // Initialize DataTable when document is ready
    $(document).ready(function() {
        $('#pengiriman-table').DataTable({
            paging: false,
            ordering: true,
            info: false,
            responsive: true,
            language: {
                search: "Cari:",
                searchPlaceholder: "Cari pengiriman...",
                zeroRecords: "Tidak ada data yang ditemukan",
                emptyTable: "Belum ada data pengiriman"
            }
        });
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
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        margin-left: 0.5rem;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Animation for hover effects */
    .transition-colors {
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }
</style>

@endsection