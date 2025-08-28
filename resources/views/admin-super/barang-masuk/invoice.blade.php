<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $barangMasuk->nomor_transaksi }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page {
            size: landscape;
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
            Cetak Invoice
        </button>
        <a href="{{ route('admin-super.barang-masuk.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded">
            Kembali
        </a>
    </div>

    <div class="print-container bg-white mx-auto max-w-6xl p-6">
        <!-- Header -->
        <div class="border-b-2 border-gray-800 pb-4 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">PT. NAMA PERUSAHAAN</h1>
                    <div class="text-sm text-gray-600 mt-2">
                        <p>Jl. Alamat Perusahaan No. 123</p>
                        <p>Kota, Kode Pos 12345</p>
                        <p>Telp: (021) 12345678 | Email: info@perusahaan.com</p>
                        <p>NPWP: 01.234.567.8-901.000</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-bold text-gray-800">INVOICE</h2>
                    <p class="text-lg text-gray-600">BARANG MASUK</p>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="grid grid-cols-2 gap-8 mb-6">
            <div>
                <h3 class="font-bold text-gray-800 mb-3 border-b border-gray-300 pb-1">INFORMASI TRANSAKSI</h3>
                <table class="text-sm">
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">No. Transaksi</td>
                        <td class="text-gray-800">: {{ $barangMasuk->nomor_transaksi }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">Payment</td>
                        <td class="text-gray-800">: {{ $barangMasuk->payment }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">Tanggal</td>
                        <td class="text-gray-800">: {{ \Carbon\Carbon::parse($barangMasuk->tanggal_masuk)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium text-gray-700 pr-4 py-1">Waktu Cetak</td>
                        <td class="text-gray-800">: {{ now()->format('d F Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>
            
            <div>
                <h3 class="font-bold text-gray-800 mb-3 border-b border-gray-300 pb-1">SUPPLIER</h3>
                <div class="text-sm">
                    <p class="font-bold text-gray-800 text-lg">{{ $barangMasuk->supplier }}</p>
                    <p class="text-gray-600 mt-1">Supplier Barang Masuk</p>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="border border-gray-300 mb-6">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300">
                        <th class="text-left py-3 px-4 font-semibold text-gray-800 text-sm w-12">NO</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800 text-sm">KODE</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-800 text-sm">NAMA PRODUK</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-800 text-sm w-20">QTY</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-800 text-sm w-32">HARGA SATUAN</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-800 text-sm w-32">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangMasuk->details as $index => $detail)
                        <tr class="border-b border-gray-200 {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                            <td class="py-3 px-4 text-center text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-sm font-mono text-gray-800">{{ $detail->produk->kode ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-gray-800">{{ $detail->produk->nama ?? 'Produk Tidak Ditemukan' }}</td>
                            <td class="py-3 px-4 text-center text-sm text-gray-700">{{ number_format($detail->jumlah, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-sm text-gray-700">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="py-3 px-4 text-right text-sm font-semibold text-gray-800">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <p class="text-lg">Tidak ada produk dalam transaksi ini</p>
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
                        <td class="py-2 px-4 text-sm text-right text-gray-800">{{ $barangMasuk->details->count() }} produk</td>
                    </tr>
                    <tr class="border-b border-gray-200">
                        <td class="py-2 px-4 text-sm font-medium text-gray-700">Total Kuantitas:</td>
                        <td class="py-2 px-4 text-sm text-right text-gray-800">{{ number_format($barangMasuk->details->sum('jumlah'), 0, ',', '.') }} unit</td>
                    </tr>
                    <tr class="bg-gray-100 font-bold">
                        <td class="py-3 px-4 text-sm text-gray-800">TOTAL NILAI:</td>
                        <td class="py-3 px-4 text-right text-lg text-gray-800">Rp {{ number_format($barangMasuk->details->sum('subtotal'), 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Notes -->
        <div class="border border-gray-300 p-4 mb-6 bg-gray-50">
            <h4 class="font-semibold text-gray-800 mb-2">Catatan:</h4>
            <div class="text-sm text-gray-700">
                <p>• Invoice ini merupakan bukti penerimaan barang masuk dari supplier</p>
                <p>• Harap menyimpan invoice ini sebagai arsip perusahaan</p>
                <p>• Untuk pertanyaan lebih lanjut, hubungi bagian gudang atau admin</p>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="grid grid-cols-2 gap-12 mt-8">
            <div class="text-center">
                <p class="font-medium text-gray-800 mb-16">Diterima Oleh:</p>
                <div class="border-t border-gray-800 pt-2">
                    <p class="font-medium">Admin Gudang</p>
                    <p class="text-sm text-gray-600">Tanggal: ________________</p>
                </div>
            </div>
            
            <div class="text-center">
                <p class="font-medium text-gray-800 mb-16">Diserahkan Oleh:</p>
                <div class="border-t border-gray-800 pt-2">
                    <p class="font-medium">{{ $barangMasuk->supplier }}</p>
                    <p class="text-sm text-gray-600">Tanggal: ________________</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-4 border-t border-gray-300 text-center">
            <p class="text-xs text-gray-500">
                Dokumen ini dibuat secara otomatis pada {{ now()->format('d F Y, H:i:s') }} WIB
            </p>
        </div>
    </div>

    <script>
        // Auto print ketika halaman dimuat
        window.onload = function() { 
            window.print(); 
        }
        
        // Tutup window setelah print atau cancel
        window.addEventListener('afterprint', function() {
            window.close();
        });
        
        // Tutup juga jika user menekan ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                window.close();
            }
        });
    </script>
</body>
</html> 