@extends('layouts.print')

@section('title', 'Surat Jalan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="text-center">
            <h1 class="text-xl font-bold uppercase mb-1">SURAT JALAN</h1>
            <p class="text-sm">No. {{ $pengiriman->barangKeluar->kode_barang_keluar }}</p>
        </div>
    </div>

    <div class="flex justify-between mb-6">
        <div>
            <h2 class="font-bold text-sm mb-2 uppercase">Pengirim:</h2>
            <p class="text-sm">Gudang XYZ</p>
            <p class="text-sm">Jalan Raya No. 123</p>
            <p class="text-sm">Telp. (021) 1234567</p>
        </div>
        <div>
            <h2 class="font-bold text-sm mb-2 uppercase">Penerima:</h2>
            <p class="text-sm">{{ $pengiriman->barangKeluar->penerima }}</p>
            <p class="text-sm">{{ $pengiriman->barangKeluar->alamat_penerima }}</p>
            <p class="text-sm">Telp. {{ $pengiriman->barangKeluar->telepon_penerima }}</p>
        </div>
        <div>
            <h2 class="font-bold text-sm mb-2 uppercase">Tanggal:</h2>
            <p class="text-sm">{{ $pengiriman->created_at->format('d/m/Y') }}</p>
            <h2 class="font-bold text-sm mb-2 mt-4 uppercase">Driver:</h2>
            <p class="text-sm">{{ $pengiriman->driver->name }}</p>
        </div>
    </div>

    <table class="w-full border-collapse border border-gray-300 text-sm mb-8">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 py-2 px-3 text-left">No.</th>
                <th class="border border-gray-300 py-2 px-3 text-left">Kode Produk</th>
                <th class="border border-gray-300 py-2 px-3 text-left">Nama Produk</th>
                <th class="border border-gray-300 py-2 px-3 text-left">Qty</th>
                <th class="border border-gray-300 py-2 px-3 text-left">Satuan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengiriman->barangKeluar->detailBarangKeluars as $key => $detail)
            <tr>
                <td class="border border-gray-300 py-2 px-3">{{ $key + 1 }}</td>
                <td class="border border-gray-300 py-2 px-3">{{ $detail->produk->kode }}</td>
                <td class="border border-gray-300 py-2 px-3">{{ $detail->produk->nama }}</td>
                <td class="border border-gray-300 py-2 px-3">{{ $detail->jumlah }}</td>
                <td class="border border-gray-300 py-2 px-3">{{ $detail->produk->satuan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex justify-between mb-10">
        <div class="w-1/3 text-center">
            <p class="font-bold text-sm">Pengirim</p>
            <div class="h-20"></div>
            <p class="text-sm">(_________________)</p>
            <p class="text-sm">Gudang XYZ</p>
        </div>
        <div class="w-1/3 text-center">
            <p class="font-bold text-sm">Driver</p>
            <div class="h-20"></div>
            <p class="text-sm">(_________________)</p>
            <p class="text-sm">{{ $pengiriman->driver->name }}</p>
        </div>
        <div class="w-1/3 text-center">
            <p class="font-bold text-sm">Penerima</p>
            <div class="h-20"></div>
            <p class="text-sm">(_________________)</p>
            <p class="text-sm">{{ $pengiriman->barangKeluar->penerima }}</p>
        </div>
    </div>

    <div class="text-xs">
        <p class="mb-1"><strong>Catatan:</strong></p>
        <p class="mb-1">1. Surat jalan ini merupakan bukti serah terima barang yang sah.</p>
        <p class="mb-1">2. Pastikan jumlah dan kondisi barang sesuai sebelum menandatangani.</p>
        <p>3. Surat jalan dibuat rangkap 3: putih untuk pengirim, merah untuk driver, kuning untuk penerima.</p>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>
@endsection
