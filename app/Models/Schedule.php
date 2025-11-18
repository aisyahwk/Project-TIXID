<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['cinema_id', 'movie_id', 'hours', 'price'];

    protected function casts(): array
    {
        return [
            // agar format data yang disimpan array bukan json
            'hours' => 'array'
        ];
    }

    public function cinema()
    {
        //karena schedule ada FK cinema_id didefinisikan dengan : belongsTo
        //karena schedule ada di posisi kedua dan schedule menyimpan data FK, dia hanya menyambungkan jadi menggunakan belongsTo
        return $this->belongsTo(Cinema::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
