<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use App\Mail\SurveyEmail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class KirimSurveyKaryawan extends Command
{
    protected $signature = 'survey:kirim';
    protected $description = 'Kirim email survey ke karyawan yang sudah bekerja selama 3 bulan dengan status PKWT/PKWTT';

    public function handle()
    {
        $targetDate = Carbon::now()->subMonths(3)->format('Y-m-d');

        $karyawans = Karyawan::whereDate('tanggal_bergabung', $targetDate)
            ->whereIn('status_karyawan', ['PKWT', 'PKWTT'])
            ->get();

        foreach ($karyawans as $karyawan) {
            Mail::to($karyawan->email)->send(new SurveyEmail($karyawan));
            $this->info("Email survei dikirim ke: {$karyawan->email}");
        }

        return 0;
    }
}
