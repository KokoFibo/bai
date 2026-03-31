<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class Uploadkaryawanwr extends Component
{
    use WithFileUploads;
    public $file;

    #[Rule('file|mimes:xlsx|max:102400')]
    public function import () {
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);
        $importedData = $spreadsheet->getActiveSheet();
        $row_limit    = $importedData->getHighestDataRow();
        for($i=2; $i<$row_limit; $i++){
            if ($importedData->getCell('A' . $i)->getValue() != "") {

                $id_karyawan = $importedData->getCell('A' . $i)->getValue();
                $nama = $importedData->getCell('B' . $i)->getValue();
                $departemen = $importedData->getCell('C' . $i)->getValue();
                $jabatan = $importedData->getCell('D' . $i)->getValue();
                $gender = $importedData->getCell('E' . $i)->getValue();
                $tanggal_bergabung = $importedData->getCell('F' . $i)->getValue();

                $no_identitas = $importedData->getCell('G' . $i)->getValue();
                $tempat_lahir = $importedData->getCell('H' . $i)->getValue();
                $tanggal_lahir = $importedData->getCell('I' . $i)->getValue();
                $email = $importedData->getCell('J' . $i)->getValue();
                $hp = $importedData->getCell('K' . $i)->getValue();
                // $no rek = $importedData->getCell('L' . $i)->getValue();
                $alamat_identitas = $importedData->getCell('M' . $i)->getValue();


                Karyawan::create([
                    'id_karyawan' => $id_karyawan,
                    'nama' => Str::of($nama)->title(),
                    'departemen' => $departemen,
                    'jabatan' => $jabatan,
                    'gender' => $gender,
                    // 'tanggal_bergabung' => $tanggal_bergabung,
                    'tanggal_bergabung' => '2000-01-01',
                    'no_identitas' => $no_identitas,
                    'tempat_lahir' => $tempat_lahir,
                    // 'tanggal_lahir' => $tanggal_lahir,
                    'tanggal_lahir' => '2000-01-01',
                    'email' => $email,
                    'hp' => $hp,
                    'alamat_identitas' => $alamat_identitas,

                ]);



            }

        }
        return back()->with('message','File Excel Sudah Berhasil di tambahkan');
    }
    public function render()
    {
        return view('livewire.uploadkaryawanwr');
    }
}
