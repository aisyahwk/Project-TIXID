<?php

namespace App\Exports;

use App\Models\Promo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PromoExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $rowNumber = 0;
    public function collection()
    {
        return Promo::all();
    }

    public function headings(): array
    {
        return ['No', 'Kode Promo', 'Total Potongan'];
    }

    public function map($promo): array
    {
        if ($promo->type == 'percent') {
            $promo->discount = $promo->discount . '%';
        } else {
            $promo->discount = 'Rp' . number_format($promo->discount, 0, ',', '.');
        }
        return [
            ++$this->rowNumber,
            $promo->promo_code,
            $promo->discount,
        ];
    }
}
