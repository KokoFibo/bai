<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KviewController extends Controller
{
    public function index()
    {
        $data = Karyawan::where('company_id', 1)->get();
        $header_text = 'test header';
        return view('karyawan_excel_view', [
            'data' => $data,
            'header_text' => $header_text
        ]);
    }
}
