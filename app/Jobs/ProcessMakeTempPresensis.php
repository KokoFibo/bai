<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessMakeTempPresensis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tgl;

    /**
     * Create a new job instance.
     */
    public function __construct($tgl)
    {
        $this->tgl   = $tgl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::select('CALL spImportPresensi(' . date('Ymd', strtotime($this->tgl)) . ')');
    }
}
