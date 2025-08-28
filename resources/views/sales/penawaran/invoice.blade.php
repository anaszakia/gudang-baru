<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $penawaran->kode_penawaran }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: A4;
            margin: 0.5in;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; padding: 0; }
            .print-container { box-shadow: none; margin: 0; }
        }
        
        body { 
            font-family: Arial, sans-serif; 
        }
    </style>
</head>
<body class="bg-white">
    <!-- Print Buttons -->
    <div class="no-print text-center py-4 bg-gray-50">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded mr-3">
            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Cetak Invoice
        </button>
        <a href="{{ route('sales.penawaran.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">
            <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="print-container bg-white mx-auto max-w-4xl p-6">
        <!-- Header -->
        <div class="border-b-2 border-gray-800 pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">PT. GUDANG BARANG</h1>
                    <div class="text-sm text-gray-600 mt-2">
                        <p>Jl. Gudang Barang No. 123</p>
                        <p>Jakarta, Indonesia 12345</p>
                        <p>Telp: (021) 123-4567 | Email: info@gudangbarang.com</p>
                        <p>NPWP: 01.234.567.8-901.000</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-bold text-gray-800">INVOICE</h2>
                    <p class="text-lg text-gray-600">PENAWARAN</p>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <h3 class="font-bold text-gray-800 mb-3 border-b border-gray-300 pb-1">INFORMASI PENAWARAN</h3>
                <table class="text-sm">
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">Nomor Penawaran</td>
                        <td class="text-gray-800">: {{ $penawaran->kode_penawaran }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">Tanggal Penawaran</td>
                        <td class="text-gray-800">: {{ \Carbon\Carbon::parse($penawaran->tanggal_penawaran)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">Status</td>
                        <td class="text-gray-800">: 
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded
                                @if($penawaran->status == 'disetujui') bg-green-100 text-green-800
                                @elseif($penawaran->status == 'menunggu') bg-yellow-100 text-yellow-800
                                @elseif($penawaran->status == 'ditolak') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($penawaran->status ?? 'Draft') }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">Waktu Cetak</td>
                        <td class="text-gray-800">: {{ now()->format('d F Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>
            
            <div>
                <h3 class="font-bold text-gray-800 mb-3 border-b border-gray-300 pb-1">PELANGGAN</h3>
                <div class="text-sm">
                    <p class="font-bold text-gray-800 text-lg">{{ $penawaran->nama_pelanggan }}</p>
                    <p class="text-gray-700 mt-2">{{ $penawaran->alamat_pelanggan }}</p>
                    <p class="text-gray-600 mt-1">Telp: {{ $penawaran->telepon_pelanggan }}</p>
                    @if($penawaran->email_pelanggan)
                    <p class="text-gray-600">Email: {{ $penawaran->email_pelanggan }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sales Info -->
        <div class="mb-6 p-3 bg-blue-50 border border-blue-200 rounded">
            <div class="flex justify-between items-center text-sm">
                <div>
                    <span class="font-medium text-blue-800">Petugas Sales:</span>
                    <span class="text-blue-700 ml-2">{{ $penawaran->user->name ?? 'N/A' }}</span>
                </div>
                @if($penawaran->approved_at && $penawaran->approver)
                <div>
                    <span class="font-medium text-blue-800">Disetujui Oleh:</span>
                    <span class="text-blue-700 ml-2">{{ $penawaran->approver->name }}</span>
                    <span class="text-blue-600 ml-2">({{ \Carbon\Carbon::parse($penawaran->approved_at)->format('d/m/Y') }})</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Products Table -->
        <div class="border border-gray-300 mb-6">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="text-left py-3 px-4 font-semibold text-gray-800 text-sm w-12">NO</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800 text-sm">KODE PRODUK</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800 text-sm">NAMA PRODUK</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-800 text-sm w-20">QTY</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-800 text-sm w-32">HARGA SATUAN</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-800 text-sm w-32">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $index => $detail)
                        <tr class="border-b border-gray-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="py-3 px-4 text-center text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-sm font-mono text-gray-800">{{ $detail->produk->kode_produk ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-gray-800">{{ $detail->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                            <td class="py-3 px-4 text-center text-sm text-gray-700">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-sm text-gray-700">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-sm font-semibold text-gray-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <p class="text-lg">Tidak ada produk dalam penawaran ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="flex justify-end mb-6">
            <div class="w-80">
                <table class="w-full border border-gray-300">
                    <tr class="border-b border-gray-200">
                        <td class="py-2 px-4 text-sm font-medium text-gray-700">Total Item:</td>
                        <td class="py-2 px-4 text-sm text-right text-gray-800">{{ $details->count() }} produk</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-2 px-4 text-sm font-medium text-gray-700">Total Kuantitas:</td>
                        <td class="py-2 px-4 text-sm text-right text-gray-800">{{ number_format($details->sum('jumlah'), 0, ',', '.') }} unit</td>
                    </tr>
                    <tr class="bg-blue-100 font-bold border-t-2 border-blue-300">
                        <td class="py-3 px-4 text-sm text-gray-800">TOTAL PENAWARAN:</td>
                        <td class="py-3 px-4 text-right text-lg text-gray-800">Rp {{ number_format($penawaran->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Notes -->
        <div class="border border-gray-300 p-4 mb-6 bg-gray-50">
            <h4 class="font-semibold text-gray-800 mb-2">Catatan & Syarat Ketentuan:</h4>
            <div class="text-sm text-gray-700">
                @if($penawaran->catatan)
                <div class="mb-3 p-2 bg-white border-l-4 border-blue-400">
                    <span class="font-medium">Catatan Khusus:</span> {{ $penawaran->catatan }}
                </div>
                @endif
                <p>• Penawaran ini berlaku selama 30 hari dari tanggal yang tertera</p>
                <p>• Harga sudah termasuk pajak dan dapat berubah sewaktu-waktu</p>
                <p>• Pembayaran dilakukan secara bertahap sesuai kesepakatan</p>
                <p>• Untuk konfirmasi pemesanan, hubungi petugas sales yang tertera</p>
                <p>• Barang yang sudah dipesan tidak dapat dibatalkan tanpa persetujuan</p>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-12 mt-8">
            <div class="text-center">
                <p class="font-medium text-gray-800 mb-16">Pelanggan</p>
                <div class="border-t border-gray-800 pt-2">
                    <p class="font-medium">{{ $penawaran->nama_pelanggan }}</p>
                    <p class="text-sm text-gray-600">Tanggal: ________________</p>
                </div>
            </div>
            
            <div class="text-center">
                <p class="font-medium text-gray-800 mb-16">Petugas Sales</p>
                <div class="border-t border-gray-800 pt-2">
                    <p class="font-medium">{{ $penawaran->user->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600">PT. Gudang Barang</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-gray-300 text-center">
            <p class="text-xs text-gray-500">
                Dokumen ini dibuat secara otomatis pada {{ now()->format('d F Y, H:i:s') }} WIB | 
                Terima kasih atas kepercayaan Anda menggunakan jasa kami
            </p>
        </div>
    </div>

    <script>
        // Auto print ketika halaman dimuat (opsional)
        // window.onload = function() { 
        //     window.print(); 
        // }
        
        // Tutup window setelah print atau cancel
        window.addEventListener('afterprint', function() {
            // window.close(); // Uncomment jika ingin auto close
        });
        
        // Tutup juga jika user menekan ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                // window.close(); // Uncomment jika ingin auto close
            }
        });

        // Format currency on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional JavaScript here if needed
        });
    </script>
</body>
</html>