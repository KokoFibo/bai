<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LarkController extends Controller
{
    public function getKaryawanById($id): JsonResponse
    {
        $data = Karyawan::where('id_karyawan', $id)->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Karyawan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'id_karyawan' => $data->id_karyawan,
            'nama' => $data->nama,
            'status_karyawan' => $data->status_karyawan,
        ]);
    }
}
