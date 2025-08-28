@extends('layouts.app')
@section('title', 'Manajemen Barang Masuk')

@section('content')
<div x-data="{ 
    editModal: null,
    detailModal: null,
    filterModal: null,
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
                <h1 class="text-2xl font-bold text-gray-900">Barang Masuk</h1>
                <p class="text-gray-600 mt-1">Kelola transaksi barang masuk</p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                {{-- Search Form --}}
                <form method="GET" action="{{ route('admin-super.barang-masuk.index') }}" class="flex gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Cari transaksi atau supplier..."
                               class="border-gray-300 rounded-lg px-4 py-2 text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
                        <svg class="absolute right-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Cari
                    </button>
                </form>

                {{-- Export Button --}}
                <button onclick="toggleFilterModal()" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Excel
                </button>

                {{-- Add Button --}}
                <a href="{{ route('admin-super.barang-masuk.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Barang Masuk
                </a>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($barangMasuks as $index => $bm)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $barangMasuks->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm font-mono text-blue-600 font-medium">{{ $bm->nomor_transaksi }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $bm->supplier }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($bm->tanggal_masuk)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">Rp {{ number_format($bm->details->sum('subtotal'), 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                {{-- Detail Button --}}
                                <button @click="openModal('detail', {{ $bm->id }})" 
                                        class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-lg transition-colors" 
                                        title="Lihat Detail Produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                {{-- Print Invoice Button --}}
                                <a href="{{ route('admin-super.barang-masuk.invoice', $bm->id) }}" target="_blank"
                                   class="bg-green-500 hover:bg-green-600 text-white p-2 rounded-lg transition-colors" 
                                   title="Cetak Invoice">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>

                                {{-- Edit Button --}}
                                <button @click="openModal('edit', {{ $bm->id }})" 
                                        class="bg-amber-500 hover:bg-amber-600 text-white p-2 rounded-lg transition-colors" 
                                        title="Edit Transaksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                {{-- Delete Button --}}
                                <form action="{{ route('admin-super.barang-masuk.destroy', $bm) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this.closest('form'), '{{ $bm->nomor_transaksi }}')" 
                                            class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-colors" 
                                            title="Hapus Transaksi">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-1-1M4 5l1-1"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500 mb-2">Belum ada transaksi</p>
                            <p class="text-gray-400">Mulai dengan menambahkan barang masuk pertama Anda</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- DETAIL MODAL untuk setiap barang masuk --}}
    @foreach($barangMasuks as $bm)
        <div x-show="detailModal === {{ $bm->id }}" x-cloak 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div @click.away="closeModal('detail')" 
                 class="bg-white rounded-lg shadow-xl w-full max-w-5xl max-h-[90vh] overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-6 border-b">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Detail Produk - {{ $bm->nomor_transaksi }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $bm->supplier }} • {{ \Carbon\Carbon::parse($bm->tanggal_masuk)->format('d M Y') }}</p>
                    </div>
                    <button @click="closeModal('detail')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-160px)]">
                    @if($bm->details->count() > 0)
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bm->details as $index => $detail)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 font-mono">{{ $detail->produk->kode ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $detail->produk->nama ?? '-' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 text-right font-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Total --}}
                        <div class="mt-6 pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-500">
                                    Total {{ $bm->details->count() }} produk
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-500">Total Nilai Transaksi</div>
                                    <div class="text-2xl font-bold text-gray-900">Rp {{ number_format($bm->details->sum('subtotal'), 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-1-1M4 5l1-1"/>
                            </svg>
                            <p class="text-lg font-medium text-gray-500 mb-2">Belum ada produk</p>
                            <p class="text-gray-400">Tambahkan produk untuk transaksi ini</p>
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
                    <a href="{{ route('admin-super.barang-masuk.detail', $bm->id) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Tambah Produk
                    </a>
                    <button @click="closeModal('detail')" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endforeach

    {{-- EDIT MODAL untuk setiap barang masuk --}}
    @foreach($barangMasuks as $bm)
        <div x-show="editModal === {{ $bm->id }}" x-cloak 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div @click.away="closeModal('edit')" 
                 class="bg-white rounded-lg shadow-xl w-full max-w-md">
                
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Transaksi</h3>
                    <button @click="closeModal('edit')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <form method="POST" action="{{ route('admin-super.barang-masuk.update', $bm) }}" class="p-6">
                    @csrf @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Transaksi</label>
                            <input type="text" value="{{ $bm->nomor_transaksi }}" 
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 bg-gray-50" 
                                   readonly>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                            <input type="text" name="supplier" value="{{ $bm->supplier }}" 
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" value="{{ $bm->tanggal_masuk }}" 
                                   class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t">
                        <button type="button" @click="closeModal('edit')" 
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                            Batal
                        </button>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach



    {{-- FILTER MODAL (Hidden Form) --}}
    <div id="filterModal" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 p-4 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Filter Laporan Barang Masuk</h3>
                <button onclick="toggleFilterModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Content --}}
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Awal</label>
                        <input type="date" id="start_date" value="{{ date('Y-m-d', strtotime('-30 days')) }}"
                               class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                        <input type="date" id="end_date" value="{{ date('Y-m-d') }}"
                               class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500" 
                               required>
                    </div>

                    <div class="text-sm text-gray-500">
                        Data yang akan diexport adalah transaksi pada rentang tanggal yang dipilih.
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end gap-3 pt-6 mt-6 border-t">
                    <button type="button" onclick="toggleFilterModal()" 
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="button" onclick="exportData()" 
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Download Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if(isset($barangMasuks) && method_exists($barangMasuks, 'links'))
        <div class="bg-white rounded-lg shadow-sm border p-4">
            {{ $barangMasuks->links() }}
        </div>
    @endif
</div>

{{-- Scripts --}}
<script>
    function confirmDelete(form, item) {
        if (confirm(`Apakah Anda yakin ingin menghapus transaksi ${item}?\n\nTindakan ini tidak dapat dibatalkan.`)) {
            form.submit();
        }
    }

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

    function toggleFilterModal() {
        const filterModal = document.getElementById('filterModal');
        if (filterModal.classList.contains('hidden')) {
            filterModal.classList.remove('hidden');
            filterModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        } else {
            filterModal.classList.add('hidden');
            filterModal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }
    
    function exportData() {
        // Close modal
        toggleFilterModal();
        
        // Get dates from modal
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        // Validate dates
        if (!startDate || !endDate) {
            alert('Silakan pilih rentang tanggal terlebih dahulu.');
            return;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
            return;
        }
        
        // Show loading state
        const exportBtn = event.target;
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Exporting...';
        exportBtn.disabled = true;
        
        // Buat form untuk POST request
        const exportForm = document.createElement('form');
        exportForm.method = 'POST';
        exportForm.action = '{{ route('admin-super.barang-masuk.export-excel') }}';
        exportForm.style.display = 'none';
        
        // Tambahkan CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        exportForm.appendChild(csrfInput);
        
        // Tambahkan input tanggal dari modal
        const startDateInput = document.createElement('input');
        startDateInput.type = 'hidden';
        startDateInput.name = 'start_date';
        startDateInput.value = startDate;
        exportForm.appendChild(startDateInput);
        
        const endDateInput = document.createElement('input');
        endDateInput.type = 'hidden';
        endDateInput.name = 'end_date';
        endDateInput.value = endDate;
        exportForm.appendChild(endDateInput);
        
        // Submit form
        document.body.appendChild(exportForm);
        exportForm.submit();
        document.body.removeChild(exportForm);
        
        // Reset button state after a short delay
        setTimeout(() => {
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        }, 3000);
    }
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
</style>

@endsection