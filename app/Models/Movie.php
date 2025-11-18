<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'genre', 'duration', 'description', 'age_rating', 'director', 'poster', 'activated'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
