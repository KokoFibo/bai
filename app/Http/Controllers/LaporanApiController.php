<?php

namespace App\Http\Controllers;

use App\Models\Placement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LaporanApiController extends Controller
{
    public function osPlacement($month, $year, $placement_id)
    {

        // Validasi input
        $validator = Validator::make(
            ['month' => $month, 'year' => $year, 'placement_id' => $placement_id],
            [
                'month' => 'required|integer|min:1|max:12',
                'year' => 'required|integer|min:2000',
                'placement_id' => 'required|integer|exists:payrolls,placement_id',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = DB::table('payrolls')
                // ->join('karyawans', 'payrolls.id_karyawan', '=', 'karyawans.id_karyawan')
                ->whereMonth('payrolls.date', $month)
                ->whereYear('payrolls.date', $year)
                ->where('payrolls.placement_id', $placement_id)
                ->sum('payrolls.total');

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function osPlacementName($month, $year, $placement_name)
    {

        // Validasi input
        $validator = Validator::make(
            ['month' => $month, 'year' => $year, 'placement_name' => $placement_name],
            [
                'month' => 'required|integer|min:1|max:12',
                'year' => 'required|integer|min:2000',
                'placement_name' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid parameters.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $placement_id = Placement::where('placement_name', $placement_name)->value('id');
        if (!$placement_id) {
            return response()->json([
                'status' => false,
                'message' => 'Nama Placement salah',
                'errors' => 'Nama Placement salah',
            ], 422);
        }

        try {

            $data = DB::table('payrolls')
                // ->join('karyawans', 'payrolls.id_karyawan', '=', 'karyawans.id_karyawan')
                ->whereMonth('payrolls.date', $month)
                ->whereYear('payrolls.date', $year)
                ->where('payrolls.placement_id', $placement_id)
                ->sum('payrolls.total');

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat mengambil data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
