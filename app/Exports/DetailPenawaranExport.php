<?php

namespace App\Exports;

use App\Models\DetailPenawaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DetailPenawaranExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $penawaranId;

    public function __construct($penawaranId)
    {
        $this->penawaranId = $penawaranId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DetailPenawaran::with(['produk'])
            ->where('penawaran_id', $this->penawaranId)
            ->get();
    }

    /**
     * @var DetailPenawaran $detail
     */
    public function map($detail): array
    {
        return [
            $detail->produk->kode_produk,
            $detail->produk->nama_produk,
            $detail->produk->kategori->nama_kategori,
            $detail->jumlah,
            number_format($detail->harga, 2),
            number_format($detail->subtotal, 2),
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama Produk',
            'Kategori',
            'Jumlah',
            'Harga',
            'Subtotal',
        ];
    }
}
