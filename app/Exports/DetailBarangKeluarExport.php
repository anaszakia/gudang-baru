<?php

namespace App\Exports;

use App\Models\DetailBarangKeluar;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;

class DetailBarangKeluarExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = DetailBarangKeluar::with(['barangKeluar', 'produk']);
        
        // Filter berdasarkan tanggal
        if ($this->request->filled('start_date')) {
            $query->whereHas('barangKeluar', function($q) {
                $q->whereDate('tanggal_keluar', '>=', $this->request->start_date);
            });
        }
        
        if ($this->request->filled('end_date')) {
            $query->whereHas('barangKeluar', function($q) {
                $q->whereDate('tanggal_keluar', '<=', $this->request->end_date);
            });
        }
        
        return $query->orderBy('id', 'asc');
    }

    public function headings(): array
    {
        return [
            'No Transaksi',
            'Tanggal',
            'Customer',
            'Kode Produk',
            'Nama Produk',
            'Jumlah',
            'Harga Satuan (Rp)',
            'Subtotal (Rp)',
        ];
    }

    public function map($detail): array
    {
        return [
            $detail->barangKeluar->nomor_transaksi,
            Carbon::parse($detail->barangKeluar->tanggal_keluar)->format('d/m/Y'),
            $detail->barangKeluar->customer,
            $detail->produk->kode ?? '-',
            $detail->produk->nama ?? '-',
            $detail->jumlah,
            $detail->harga_satuan,
            $detail->subtotal,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Detail Barang Keluar';
    }
}
