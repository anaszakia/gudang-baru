<?php

namespace App\Exports;

use App\Models\Penawaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PenawaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Penawaran::with(['user', 'approver']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_penawaran', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    /**
     * @var Penawaran $penawaran
     */
    public function map($penawaran): array
    {
        return [
            $penawaran->kode_penawaran,
            $penawaran->tanggal_penawaran,
            $penawaran->nama_pelanggan,
            $penawaran->alamat_pelanggan,
            $penawaran->telepon_pelanggan,
            $penawaran->email_pelanggan ?? '-',
            $penawaran->user->name,
            number_format($penawaran->total_harga, 2),
            $penawaran->status,
            $penawaran->approver ? $penawaran->approver->name : '-',
            $penawaran->approved_at ? date('Y-m-d H:i:s', strtotime($penawaran->approved_at)) : '-',
            $penawaran->catatan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Penawaran',
            'Tanggal',
            'Nama Pelanggan',
            'Alamat Pelanggan',
            'Telepon Pelanggan',
            'Email Pelanggan',
            'Sales',
            'Total Harga',
            'Status',
            'Disetujui Oleh',
            'Tanggal Persetujuan',
            'Catatan',
        ];
    }
}
