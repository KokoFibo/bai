<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Karyawan extends Model
{
    use HasFactory, HasUuids, LogsActivity;
    protected $guarded = [];
    protected static $recordEvents = ['updated', 'deleted'];

    public function timeoff()
    {
        return $this->hasMany(Timeoff::class);
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

    public function getActivitylogOptions(): LogOptions
    {


        return LogOptions::defaults()
            ->logOnly(['gaji_pokok', 'gaji_overtime', 'bonus'])
            // ->setDescriptionForEvent(fn (string $eventName) => "This model has been {$eventName}")
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
        // Chain fluent methods for configuration options
    }





    public function yfrekappresensi()
    {
        return $this->hasMany(Yfrekappresensi::class);
    }
}
