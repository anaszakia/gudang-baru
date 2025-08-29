@extends('layouts.app')

@section('title', 'Pengiriman Saya')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengiriman Saya</h1>
                <p class="text-gray-600 mt-1">Kelola data pengiriman yang ditugaskan kepada Anda</p>
                
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
                                <span class="text-sm text-gray-500">Pengiriman Saya</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
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
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200" id="deliveries-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang Keluar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penerima</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alamat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pengirimanList as $key => $pengiriman)
                    <tr class="hover:bg-gray-50" data-status="{{ $pengiriman->status_pengiriman }}">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $pengirimanList->firstItem() + $key }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $pengiriman->barangKeluar->kode_barang_keluar }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $pengiriman->barangKeluar->penerima }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $pengiriman->barangKeluar->alamat_penerima }}</td>
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
                                {{-- Terima Pengiriman --}}
                                @if($pengiriman->status_pengiriman == 'belum_dikirim')
                                <a href="{{ route('driver.deliveries.accept', $pengiriman->id) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors" 
                                   title="Terima Pengiriman">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </a>
                                @endif
                                
                                {{-- Detail Button --}}
                                <a href="{{ route('driver.deliveries.detail', $pengiriman->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                {{-- Update Status Button --}}
                                @if(in_array($pengiriman->status_pengiriman, ['dalam_perjalanan', 'istirahat']))
                                <a href="{{ route('driver.deliveries.update-form', $pengiriman->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-lg transition-colors" 
                                   title="Update Status">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @endif
                                
                                {{-- Print Surat Jalan (hanya muncul jika sudah diterima/dalam proses) --}}
                                @if(in_array($pengiriman->status_pengiriman, ['dalam_perjalanan', 'istirahat', 'selesai']))
                                <a href="{{ route('driver.deliveries.print-note', $pengiriman->id) }}" target="_blank"
                                   class="bg-purple-500 hover:bg-purple-600 text-white p-2 rounded-lg transition-colors" 
                                   title="Cetak Surat Jalan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500 mb-2">Belum ada pengiriman yang ditugaskan</p>
                            <p class="text-gray-400">Data pengiriman akan muncul saat Anda mendapat tugas pengiriman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- PAGINATION --}}
    @if(isset($pengirimanList) && method_exists($pengirimanList, 'links'))
        <div class="bg-white rounded-lg shadow-sm border p-4">
            {{ $pengirimanList->links() }}
        </div>
    @endif
</div>

<script>
    // Filter functions
    function filterByStatus(status) {
        const table = document.getElementById('deliveries-table');
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
</script>
@endsection
