<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function yfrekappresensi()
    {
        return $this->belongsTo(Yfrekappresensi::class);
    }
    public function jamkerjaid()
    {
        return $this->belongsTo(Jamkerjaid::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function placement()
    {
        return $this->belongsTo(Placement::class);
    }
}
