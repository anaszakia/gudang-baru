<?php

namespace App\Exports;

use App\Models\Pengiriman;
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

class PengirimanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle, WithColumnFormatting
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Pengiriman::with(['barangKeluar', 'driver']);
        
        // Filter berdasarkan tanggal
        if ($this->request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $this->request->start_date);
        }
        
        if ($this->request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $this->request->end_date);
        }

        // Filter berdasarkan status
        if ($this->request->filled('status_filter')) {
            $query->where('status_pengiriman', $this->request->status_filter);
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID Pengiriman',
            'Kode Barang Keluar',
            'Tanggal Dibuat',
            'Penerima',
            'Alamat Penerima',
            'Telepon Penerima',
            'Metode Pengiriman',
            'Driver',
            'Status Pengiriman',
            'Waktu Mulai',
            'Waktu Selesai',
            'Catatan'
        ];
    }
    
    public function map($pengiriman): array
    {
        // Format metode pengiriman
        $metode = $pengiriman->metode_pengiriman == 'ambil_sendiri' ? 'Diambil Sendiri' : 'Diantar Driver';
        
        // Format status
        $status = '';
        switch ($pengiriman->status_pengiriman) {
            case 'belum_dikirim':
                $status = 'Belum Dikirim';
                break;
            case 'dalam_perjalanan':
                $status = 'Dalam Perjalanan';
                break;
            case 'istirahat':
                $status = 'Istirahat';
                break;
            case 'selesai':
                $status = 'Selesai';
                break;
            default:
                $status = $pengiriman->status_pengiriman;
        }
        
        return [
            $pengiriman->id,
            $pengiriman->barangKeluar->kode_barang_keluar,
            $pengiriman->created_at->format('d/m/Y H:i'),
            $pengiriman->barangKeluar->penerima,
            $pengiriman->barangKeluar->alamat_penerima,
            $pengiriman->barangKeluar->telepon_penerima,
            $metode,
            $pengiriman->driver ? $pengiriman->driver->name : 'Tidak Ada Driver',
            $status,
            $pengiriman->waktu_mulai ? $pengiriman->waktu_mulai->format('d/m/Y H:i') : '-',
            $pengiriman->waktu_selesai ? $pengiriman->waktu_selesai->format('d/m/Y H:i') : '-',
            $pengiriman->catatan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (heading row)
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ],
        ];
    }

    public function title(): string
    {
        return 'Data Pengiriman';
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' ' . NumberFormat::FORMAT_DATE_TIME4,
            'J' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' ' . NumberFormat::FORMAT_DATE_TIME4,
            'K' => NumberFormat::FORMAT_DATE_DDMMYYYY . ' ' . NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}
