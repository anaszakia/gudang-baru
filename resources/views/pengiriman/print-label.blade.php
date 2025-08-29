<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Pengiriman - {{ $pengiriman->barangKeluar->kode_barang_keluar }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .label-container {
            width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 2px solid #000;
        }
        .label-header {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .label-title {
            font-size: 14px;
            margin: 5px 0;
        }
        .barcode {
            text-align: center;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .section-content {
            font-size: 14px;
            line-height: 1.4;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 15px 0;
        }
        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 8px 15px;
            background-color: #4472C4;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .label-container {
                width: 100%;
                border: none;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button">Cetak</button>
    
    <div class="label-container">
        <div class="label-header">
            <p class="company-name">GUDANG BARU</p>
            <p class="label-title">LABEL PENGIRIMAN</p>
        </div>
        
        <div class="barcode">
            {{ $pengiriman->barangKeluar->kode_barang_keluar }}
        </div>
        
        <div class="section">
            <div class="section-title">PENGIRIM:</div>
            <div class="section-content">
                GUDANG BARU<br>
                Jl. Industri No. 123, Kawasan Industri<br>
                Telp: (021) 123-4567
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="section">
            <div class="section-title">PENERIMA:</div>
            <div class="section-content">
                <strong>{{ $pengiriman->barangKeluar->penerima }}</strong><br>
                {{ $pengiriman->barangKeluar->alamat_penerima }}<br>
                Telp: {{ $pengiriman->barangKeluar->telepon_penerima }}
            </div>
        </div>
        
        <div class="divider"></div>
        
        <div class="section">
            <div class="section-title">DETAIL PENGIRIMAN:</div>
            <div class="section-content">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 40%;">Tanggal:</td>
                        <td>{{ $pengiriman->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td>No. Referensi:</td>
                        <td>{{ $pengiriman->barangKeluar->kode_barang_keluar }}</td>
                    </tr>
                    <tr>
                        <td>Metode Pengiriman:</td>
                        <td>{{ $pengiriman->metode_pengiriman == 'ambil_sendiri' ? 'Diambil Sendiri' : 'Diantar Driver' }}</td>
                    </tr>
                    @if($pengiriman->driver)
                    <tr>
                        <td>Driver:</td>
                        <td>{{ $pengiriman->driver->name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Status:</td>
                        <td>
                            @if($pengiriman->status_pengiriman == 'belum_dikirim')
                                Belum Dikirim
                            @elseif($pengiriman->status_pengiriman == 'dalam_perjalanan')
                                Dalam Perjalanan
                            @elseif($pengiriman->status_pengiriman == 'istirahat')
                                Istirahat
                            @elseif($pengiriman->status_pengiriman == 'selesai')
                                Selesai
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="footer">
            Label ini adalah bukti sah pengiriman barang dari GUDANG BARU
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            // Uncomment line below to automatically open print dialog
            window.print();
        }
    </script>
</body>
</html>
