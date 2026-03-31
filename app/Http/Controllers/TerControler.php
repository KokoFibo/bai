<?php

namespace App\Http\Controllers;

use App\Models\Ter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rules\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class TerControler extends Controller
{
    public function index()
    {
        return view('ter');
    }

    public function upload(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => ['required', 'file', File::types(['xlsx'])->max(1024)],
        ]);

        Ter::query()->truncate();
        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file);

        $importedData = $spreadsheet->getActiveSheet();
        $row_limit = $importedData->getHighestDataRow();
        for ($i = 2; $i <= $row_limit; $i++) {

            if ($importedData->getCell('A' . $i)->getValue() != '') {
                $ter = $importedData->getCell('A' . $i)->getValue();
                $from = $importedData->getCell('B' . $i)->getValue();
                $to = $importedData->getCell('D' . $i)->getValue();
                $rate = $importedData->getCell('E' . $i)->getValue();
            }

            Ter::create([
                'ter' => $ter,
                'from' => $from,
                'to' => $to,
                'rate' => $rate,
            ]);
        }
        return Redirect::to('ter');
    }
}
