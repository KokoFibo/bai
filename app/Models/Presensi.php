<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'department',
        'date',
        'time',
        'location',
        'day_number'
    ];

    public function totalPresensi()
    {
        return $this->belongsTo(TotalPresensi::class, 'user_id', 'user_id')
            ->where('date', $this->date);
    }
}
