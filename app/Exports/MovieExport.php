<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MovieExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $rowNumber = 0;
    public function collection()
    {
        return Movie::all();
    }

    public function headings(): array
    {
        return ['No', 'Judul', 'Durasi', 'Genre', 'Sutradara', 'Usia Minimal', 'Poster', 'Sinopsis'];
    }

    public function map($movie): array
    {
        return [
            //increment rowNumber yang sebelumnya 0, tiap mapping (looping) data
            ++$this->rowNumber,
            $movie->title,
            //carbon : manipulasi datetime laravel .h (jam 1(menit))
            Carbon::parse($movie->duration)->format('h') . "Jam" . Carbon::parse($movie->duration)
                ->format('i') . "Menit",
            $movie->genre,
            $movie->director,
            //konkret string + :contoh 10 +
            $movie->age_rating,
            //link publik gambar
            asset('storage/' . $movie->poster),
            $movie->description
        ];
    }
}
