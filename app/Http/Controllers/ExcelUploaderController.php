<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class ExcelUploaderController extends Controller
{
    public function index () {
        return view('excel-uploader');
    }

    public function store (  Request $request ) {

        $request->validate( [
            'file' => 'required|mimes:xlsx|max:2048',
        ] );
        $arrId = [];
        $arrId_ada = [];
        $cx=0;

        $file = $request->file( 'file' );
        $spreadsheet = IOFactory::load( $file );

        $importedData = $spreadsheet->getActiveSheet();
        $row_limit = $importedData->getHighestDataRow();



        for ( $i = 2; $i <= $row_limit; $i++ ) {
            if ( $importedData->getCell( 'B' . $i )->getValue() != '' ) {
                $user_id = $importedData->getCell( 'B' . $i )->getValue();
                $data = Karyawan::where('id_karyawan', $user_id )->first();
                if($data == null){
                    $arrId[] = $user_id;

                } else {
                    $data_karyawan = Karyawan::find($data->id);
                    $data_karyawan->iuran_locker = 10000;
                    $data_karyawan->save();
                    // $arrId_ada[] = $user_id;
                    $cx++;
                }
            }
        }
        dd('data added : ', $cx, 'Data Tidak ditemukan: ',$arrId);
        // dd('data added : ', $cx, 'Data  ditemukan: ',$arrId_ada, 'Data  ditemukan: ', $arrId );
    }
}
