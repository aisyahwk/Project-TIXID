<?php

namespace App\Exports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScheduleExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $rowNumber = 0;
    public function collection()
    {
        return Schedule::all();
    }

    public function headings(): array
    {
        return ['No', 'Bioskop', 'film', 'Jam', 'Harga'];
    }

    public function map($schedule): array
    {
        return [
            ++$this->rowNumber,
            $schedule->cinema->name,
            $schedule->movie->title,
            $schedule->hours,
            $schedule->price = 'Rp' . number_format($schedule->price, 0, ',', '.')
        ];
    }
}
