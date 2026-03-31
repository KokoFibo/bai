<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalPresensi extends Model
{
    use HasFactory;
    protected $table = 'vtotalcheck';

    public function presensiRecords()
    {
        return $this->hasMany(Presensi::class, 'user_id', 'user_id')
            ->where('date', $this->date);
    }
}
