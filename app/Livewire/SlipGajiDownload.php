<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Payroll;
use App\Exports\SlipGajiExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Illuminate\Support\Facades\File;

class SlipGajiDownload extends Component
{

    public $bulan;

    public $tahun;

    public function mount()
    {
        $this->bulan = now()->month;
        $this->tahun = now()->year;
    }

    public function render()
    {
        return view('livewire.slip-gaji-download');
    }

    protected function rules()
    {
        return [

            'bulan' => 'required|numeric|min:1|max:12',

            'tahun' => 'required|numeric'

        ];
    }

    public function download()
    {
        $this->validate();

        $tempFolder = storage_path('app/temp-slip');

        // Membuat folder jika belum ada
        if (!File::exists($tempFolder)) {
            File::makeDirectory($tempFolder, 0755, true);
        }

        // Menghapus seluruh file lama
        File::cleanDirectory($tempFolder);

        $jumlahExport = 0;

        Payroll::query()

            ->leftJoin(
                'companies',
                'companies.id',
                '=',
                'payrolls.company_id'
            )

            ->leftJoin(
                'placements',
                'placements.id',
                '=',
                'payrolls.placement_id'
            )

            ->leftJoin(
                'departments',
                'departments.id',
                '=',
                'payrolls.department_id'
            )

            ->leftJoin(
                'jabatans',
                'jabatans.id',
                '=',
                'payrolls.jabatan_id'
            )
            ->leftJoin(
                'karyawans',
                'karyawans.id_karyawan',
                '=',
                'payrolls.id_karyawan'
            )

            ->select(
                'payrolls.id',
                'payrolls.*',
                'companies.company_name',
                'placements.placement_name',
                'departments.nama_department',
                'jabatans.nama_jabatan',
                'karyawans.potongan_JHT as potongan_JHT',
                'karyawans.potongan_JP as potongan_JP',
                'karyawans.potongan_JKK as potongan_JKK',
                'karyawans.potongan_JKM as potongan_JKM',
                'karyawans.potongan_kesehatan as potongan_kesehatan'
            )

            ->whereMonth('payrolls.date', $this->bulan)

            ->whereYear('payrolls.date', $this->tahun)

            ->whereIn(
                'payrolls.status_karyawan',
                [
                    'PKWT',
                    'PKWTT',
                    'Resigned'
                ]
            )

            // ->orderBy('payrolls.id')

            ->chunkById(
                100,
                function ($payrolls) use (&$jumlahExport) {

                    foreach ($payrolls as $payroll) {

                        try {

                            $namaFile =
                                str()->slug($payroll->nama)
                                . '_'
                                . $payroll->id_karyawan
                                . '_'
                                . Carbon::parse($payroll->date)->format('Ym')
                                . '_SlipGaji.xlsx';

                            Excel::store(
                                new SlipGajiExport($payroll),
                                'temp-slip/' . $namaFile,
                                'local'
                            );

                            $jumlahExport++;
                        } catch (\Throwable $e) {
                            dd($e->getMessage());
                            // logger()->error(
                            //     'Slip Gaji Gagal',
                            //     [
                            //         'id' => $payroll->id,
                            //         'error' => $e->getMessage(),
                            //     ]
                            // );

                            logger()->error(
                                'Slip Gaji Gagal : '
                                    . $payroll->id_karyawan
                                    . ' - '
                                    . $payroll->nama
                                    . ' => '
                                    . $e->getMessage()
                            );
                        } finally {

                            unset($payroll);
                        }
                    }
                },
                'payrolls.id',
                'id'
            );

        if ($jumlahExport == 0) {

            session()->flash(
                'error',
                'Tidak ada slip gaji yang berhasil dibuat.'
            );

            return;
        }

        // Bagian berikutnya:
        // - Membuat ZIP
        $zipName = sprintf(
            'Slip_Gaji_%04d_%02d.zip',
            $this->tahun,
            $this->bulan
        );

        $zipPath = storage_path('app/' . $zipName);
        if (File::exists($zipPath)) {
            File::delete($zipPath);
        }
        $zip = new ZipArchive();

        if (
            $zip->open(
                $zipPath,
                ZipArchive::CREATE | ZipArchive::OVERWRITE
            ) !== true
        ) {

            session()->flash(
                'error',
                'Gagal membuat file ZIP.'
            );

            return;
        }
        $files = File::files($tempFolder);
        foreach ($files as $file) {

            $zip->addFile(

                $file->getRealPath(),

                $file->getFilename()

            );
        }
        $zip->close();
        File::cleanDirectory($tempFolder);
        // - Download ZIP
        return response()->download(
            $zipPath
        )->deleteFileAfterSend(true);
    }
}
