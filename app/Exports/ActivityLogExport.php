<?php

namespace App\Exports;

use Spatie\Activitylog\Models\Activity;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class ActivityLogExport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnWidths
{
    protected $admin, $month, $year;

    public function __construct($admin, $month, $year)
    {
        $this->admin = $admin;
        $this->month = $month;
        $this->year = $year;
    }


    public function columnWidths(): array
    {
        return [
            // kolom Tanggal
            'E' => 70,   // kolom Perubahan (biar cukup)
            'F' => 70,   // kolom Perubahan (biar cukup)
        ];
    }

    public function collection()
    {
        return Activity::whereIn('event', ['updated', 'deleted'])
            ->whereMonth('updated_at', $this->month)
            ->whereYear('updated_at', $this->year)
            ->where('causer_id', $this->admin)
            ->orderBy('updated_at', 'DESC')
            ->get()
            ->map(function ($item) {
                return [
                    'event' => $item->event,
                    'karyawan' => getSubjectName($item->subject_id),
                    'admin' => getCauserName($item->causer_id),
                    'tanggal_update' => $item->updated_at->format('Y-m-d H:i:s'),
                    'perubahan' => strip_tags(view('log_detail', ['a' => $item])->render()),
                    'menjadi' => strip_tags(view('log_detail2', ['a' => $item])->render()),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Event',
            'Nama Karyawan',
            'Nama Admin',
            'Tanggal Update',
            'Sebelum Perubahan',
            'Sesudah Perubahan',
        ];
    }
}
