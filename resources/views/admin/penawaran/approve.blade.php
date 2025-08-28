@extends('layouts.app')

@section('title', 'Proses Persetujuan Penawaran')

@section('content')
<div x-data="{ 
    approvalAction: '',
    showConfirm: false,
    confirmType: '',
    setAction(action) {
        this.approvalAction = action;
    },
    showInfo(action) {
        return this.approvalAction === action;
    },
    confirmAction(type) {
        this.confirmType = type;
        this.showConfirm = true;
        document.body.style.overflow = 'hidden';
    },
    closeConfirm() {
        this.showConfirm = false;
        document.body.style.overflow = '';
    }
}" class="space-y-6">
    
    {{-- HEADER --}}
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Proses Persetujuan Penawaran</h1>
                <p class="text-gray-600 mt-1">{{ $penawaran->kode_penawaran }}</p>
                
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
                                <a href="{{ route(Auth::user()->role . '.penawaran.index') }}" class="text-sm text-gray-500 hover:text-blue-600">Daftar Penawaran</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-gray-500">Proses Persetujuan</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            
            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <a href="{{ route(Auth::user()->role . '.penawaran.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2 justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        
        {{-- INFORMASI PENAWARAN --}}
        <div>
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Penawaran</h3>
                    </div>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Kode Penawaran</span>
                            <span class="text-sm font-mono text-blue-600 font-medium">{{ $penawaran->kode_penawaran }}</span>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Tanggal</span>
                            <span class="text-sm text-gray-900">{{ date('d M Y', strtotime($penawaran->tanggal_penawaran)) }}</span>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Nama Pelanggan</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $penawaran->nama_pelanggan }}</span>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Telepon</span>
                            <span class="text-sm text-gray-900">{{ $penawaran->telepon_pelanggan }}</span>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Email</span>
                            <span class="text-sm text-gray-900">{{ $penawaran->email_pelanggan ?? '-' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600 font-medium">Sales</span>
                            <span class="text-sm text-gray-900">{{ $penawaran->user->name }}</span>
                        </div>
                        
                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-start">
                                <span class="text-sm text-gray-600 font-medium">Alamat</span>
                            </div>
                            <p class="text-sm text-gray-900 mt-1">{{ $penawaran->alamat_pelanggan }}</p>
                        </div>
                        
                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm text-gray-600 font-medium">Total Harga</span>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl font-bold text-green-600">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($penawaran->catatan)
                            <div class="pt-4 border-t">
                                <span class="text-sm text-gray-600 font-medium block mb-2">Catatan</span>
                                <p class="text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $penawaran->catatan }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM PERSETUJUAN --}}
        <div>
            <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900">Form Persetujuan</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    {{-- STOCK ISSUES WARNING --}}
                    @if(!empty($stockIssues))
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.182 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <h4 class="font-medium text-amber-800">Peringatan Stok</h4>
                                <p class="text-sm text-amber-700 mt-1 mb-3">Beberapa produk memiliki stok yang tidak mencukupi:</p>
                                <ul class="space-y-1">
                                    @foreach($stockIssues as $issue)
                                    <li class="text-sm text-amber-700">• {{ $issue['produk'] }} - Diminta: {{ $issue['requested'] }}, Tersedia: {{ $issue['available'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <form action="{{ route(Auth::user()->role . '.penawaran.process-approval', $penawaran) }}" method="POST" id="approval-form">
                        @csrf
                        <div class="space-y-6">
                            {{-- Tindakan --}}
                            <div>
                                <label for="approval_action" class="block text-sm font-medium text-gray-700 mb-2">Tindakan</label>
                                <select x-model="approvalAction" @change="setAction($event.target.value)" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('approval_action') border-red-300 @enderror" 
                                        id="approval_action" name="approval_action" required>
                                    <option value="">-- Pilih Tindakan --</option>
                                    <option value="approve" {{ old('approval_action') == 'approve' ? 'selected' : '' }}>Setujui</option>
                                    <option value="reject" {{ old('approval_action') == 'reject' ? 'selected' : '' }}>Tolak</option>
                                </select>
                                @error('approval_action')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="approval_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('approval_notes') border-red-300 @enderror" 
                                          id="approval_notes" name="approval_notes" rows="4">{{ old('approval_notes') }}</textarea>
                                @error('approval_notes')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- APPROVE INFO --}}
                            <div x-show="showInfo('approve')" x-cloak class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-800">Dengan menyetujui penawaran ini, sistem akan:</p>
                                        <ul class="text-sm text-blue-700 mt-2 space-y-1">
                                            <li>• Membuat data Barang Keluar baru</li>
                                            <li>• Mengurangi stok produk</li>
                                            <li>• Mengubah status penawaran menjadi "Disetujui"</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- REJECT INFO --}}
                            <div x-show="showInfo('reject')" x-cloak class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.864-.833-2.634 0L4.182 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-red-800">Dengan menolak penawaran ini, sistem akan:</p>
                                        <ul class="text-sm text-red-700 mt-2 space-y-1">
                                            <li>• Mengubah status penawaran menjadi "Ditolak"</li>
                                            <li>• Tidak ada perubahan stok yang akan terjadi</li>
                                            <li>• Sales akan menerima notifikasi penolakan</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="flex justify-between pt-4">
                                <a href="{{ route(Auth::user()->role . '.penawaran.index') }}" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                                    Batal
                                </a>
                                <button type="submit" 
                                        :class="{
                                            'bg-green-600 hover:bg-green-700': approvalAction === 'approve',
                                            'bg-red-600 hover:bg-red-700': approvalAction === 'reject',
                                            'bg-blue-600 hover:bg-blue-700': !approvalAction
                                        }"
                                        class="text-white px-6 py-2 rounded-lg transition-colors"
                                        x-text="approvalAction === 'approve' ? 'Setujui Penawaran' : (approvalAction === 'reject' ? 'Tolak Penawaran' : 'Proses')">
                                    Proses
                                </button>
                            </div>
                        </div>
                    </form>
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
                    <h3 class="text-lg font-semibold text-gray-900">Detail Produk</h3>
                </div>
                <div class="text-sm text-gray-600">
                    Total: {{ count($details) }} item{{ count($details) > 1 ? 's' : '' }}
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Tersedia</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($details as $index => $detail)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-blue-600">{{ $detail->produk->kode }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="font-medium">{{ $detail->produk->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded">
                                {{ $detail->produk->kategori->nama }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 font-medium">{{ $detail->jumlah }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-gray-900 font-medium">{{ $detail->produk->stokTersedia() }}</span>
                                @if($detail->produk->stokTersedia() < $detail->jumlah)
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded">Stok Tidak Cukup</span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded">Stok Mencukupi</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Total Harga:</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-green-600">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</td>
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
        background: #a8a8a8;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle form submission with confirmation
        document.getElementById('approval-form').addEventListener('submit', function(e) {
            const action = document.getElementById('approval_action').value;
            let confirmMessage = '';
            
            if (action === 'approve') {
                @if(!empty($stockIssues))
                confirmMessage = 'PERINGATAN: Ada produk dengan stok tidak mencukupi. Apakah Anda yakin ingin melanjutkan persetujuan?';
                @else
                confirmMessage = 'Anda yakin ingin menyetujui penawaran ini?';
                @endif
            } else if (action === 'reject') {
                confirmMessage = 'Anda yakin ingin menolak penawaran ini?';
            }
            
            if (confirmMessage && !confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush
@endsection