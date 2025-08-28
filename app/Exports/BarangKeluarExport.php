<?php

namespace App\Exports;

use App\Models\BarangKeluar;
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

class BarangKeluarExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = BarangKeluar::with(['details.produk']);
        
        // Filter berdasarkan tanggal
        if ($this->request->filled('start_date')) {
            $query->whereDate('tanggal_keluar', '>=', $this->request->start_date);
        }
        
        if ($this->request->filled('end_date')) {
            $query->whereDate('tanggal_keluar', '<=', $this->request->end_date);
        }
        
        return $query->orderBy('tanggal_keluar', 'asc');
    }

    public function headings(): array
    {
        return [
            'No Transaksi',
            'Tanggal',
            'Customer',
            'Jumlah Item',
            'Total Nilai (Rp)',
            'Tanggal Input',
        ];
    }

    public function map($barangKeluar): array
    {
        return [
            $barangKeluar->nomor_transaksi,
            Carbon::parse($barangKeluar->tanggal_keluar)->format('d/m/Y'),
            $barangKeluar->customer,
            $barangKeluar->details->count(),
            $barangKeluar->details->sum('subtotal'),
            Carbon::parse($barangKeluar->created_at)->format('d/m/Y H:i:s'),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
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
        return 'Laporan Barang Keluar';
    }
}
