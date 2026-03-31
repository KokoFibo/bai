<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jamkerjaid extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function karyawan () {
        return $this->belongsTo(Karyawan::class);
    }
    public function payroll () {
        return $this->hasMany(Payroll::class, 'jamkerjaid_id');
    }

    public function yfrekappresensi () {
        return $this->belongsTo(Yfrekappresensi::class);
    }



}
