<?php

namespace App\Exports;

use App\Models\BarangMasuk;
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

class BarangMasukExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = BarangMasuk::with(['details.produk']);
        
        // Filter berdasarkan tanggal
        if ($this->request->filled('start_date')) {
            $query->whereDate('tanggal_masuk', '>=', $this->request->start_date);
        }
        
        if ($this->request->filled('end_date')) {
            $query->whereDate('tanggal_masuk', '<=', $this->request->end_date);
        }
        
        return $query->orderBy('tanggal_masuk', 'asc');
    }

    public function headings(): array
    {
        return [
            'No Transaksi',
            'Tanggal',
            'Supplier',
            'Jumlah Item',
            'Total Nilai (Rp)',
            'Tanggal Input',
        ];
    }

    public function map($barangMasuk): array
    {
        return [
            $barangMasuk->nomor_transaksi,
            Carbon::parse($barangMasuk->tanggal_masuk)->format('d/m/Y'),
            $barangMasuk->supplier,
            $barangMasuk->details->count(),
            $barangMasuk->details->sum('subtotal'),
            Carbon::parse($barangMasuk->created_at)->format('d/m/Y H:i:s'),
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
        return 'Laporan Barang Masuk';
    }
}
