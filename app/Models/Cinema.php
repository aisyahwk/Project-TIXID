<?php

namespace App\Models; //alamat ketika class mau dipake

use Illuminate\Database\Eloquent\Model; //use = mengimport
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //mengaktifkan softdeletes : menghapus tanpa benar benar hilang di database
    use SoftDeletes;

    //mendaftarkan column-column selain yang bawaannya, selain id dan timestampts softdeletes. agar dapat diisi datanya ke column tersebut
    protected $fillable = ['name', 'location'];

    //mendefinisikan relasi, one to many (cinema ke schedule)
    //many di schedule, jadi nama fungsi jamak (s)
    public function schedules()
    {
        //panggil jenis relasi
        return $this->hasMany(Schedule::class);
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
}
