<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Yfrekappresensi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class AttendanceController extends Controller
{
    /**
     * Get attendance data for specific user and month
     */

    public function getLatestMonthYearByUser($user_id)
    {
        try {
            $latestRecord = Yfrekappresensi::where('user_id', $user_id)
                ->latest('date')
                ->first();

            if ($latestRecord) {
                $date = Carbon::parse($latestRecord->date);
                $result = [
                    'month' => $date->month,
                    'year' => $date->year,
                    'month_year' => $date->format('F Y'),

                ];
            } else {
                $result = [
                    'month' => Carbon::now()->month,
                    'year' => Carbon::now()->year,
                    'month_year' => Carbon::now()->format('F Y'),

                ];
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving latest month year for user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index($user_id, $month, $year)
    {
        // Ambil data attendance dan available months secara parallel
        $attendanceQuery = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'DESC');

        $availableMonthsQuery = Yfrekappresensi::where('user_id', $user_id)
            ->select('date');

        // Execute queries
        $attendanceData = $attendanceQuery->get();
        $availableMonthsData = $availableMonthsQuery->get();

        // Process available months
        $availableMonths = $availableMonthsData
            ->map(function ($item) {
                $date = strtotime($item->date);
                return [
                    'month_year' => date('F Y', $date),
                    'month' => (int) date('n', $date),
                    'year' => (int) date('Y', $date)
                ];
            })
            ->unique('month_year')
            ->sortByDesc(function ($item) {
                return $item['year'] * 100 + $item['month'];
            })
            ->values()
            ->toArray();

        // Jika tidak ada data attendance
        if ($attendanceData->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'available_months' => $availableMonths,
                'summary' => $this->getEmptySummary(),
                'current_month_year' => [
                    'month' => (int)$month,
                    'year' => (int)$year,
                    'month_year' => date('F Y', strtotime("$year-$month-01"))
                ],
                'message' => 'No attendance data found for this user and month'
            ]);
        }

        // Transform data
        $transformedData = $this->transformAttendanceData($attendanceData);

        // Hitung summary
        $summary = $this->calculateSummary($attendanceData);

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'available_months' => $availableMonths,
            'summary' => $summary,
            'current_month_year' => [
                'month' => (int)$month,
                'year' => (int)$year,
                'month_year' => date('F Y', strtotime("$year-$month-01"))
            ],
            'message' => 'Attendance data retrieved successfully'
        ]);
    }

    // Helper methods
    private function getEmptySummary()
    {
        return [
            'total_jam_kerja' => 0,
            'total_hari_kerja' => 0,
            'total_jam_lembur' => 0,
            'total_jam_kerja_libur' => 0,
            'total_hari_kerja_libur' => 0,
            'total_jam_lembur_libur' => 0,
            'total_shift_malam' => 0,
            'total_hari_kerja_keseluruhan' => 0,
        ];
    }

    private function transformAttendanceData($attendanceData)
    {
        return $attendanceData->map(function ($item) {
            return [
                'date' => $item->date,
                'first_in' => $item->first_in,
                'first_out' => $item->first_out,
                'second_in' => $item->second_in,
                'second_out' => $item->second_out,
                'overtime_in' => $item->overtime_in,
                'overtime_out' => $item->overtime_out,
                'total_jam_kerja' => (float)$item->total_jam_kerja,
                'total_hari_kerja' => (float)$item->total_hari_kerja,
                'total_jam_lembur' => (float)$item->total_jam_lembur,
                'total_jam_kerja_libur' => (float)$item->total_jam_kerja_libur,
                'total_hari_kerja_libur' => (float)$item->total_hari_kerja_libur,
                'total_jam_lembur_libur' => (float)$item->total_jam_lembur_libur,
                'late' => $item->late,
                'no_scan' => $item->no_scan,
                'shift' => $item->shift,
                'shift_malam' => $item->shift_malam,
            ];
        });
    }

    private function calculateSummary($attendanceData)
    {
        $totalHariKerja = $attendanceData->sum('total_hari_kerja');
        $totalHariKerjaLibur = $attendanceData->sum('total_hari_kerja_libur');

        return [
            'total_jam_kerja' => $attendanceData->sum('total_jam_kerja'),
            'total_hari_kerja' => $totalHariKerja,
            'total_jam_lembur' => $attendanceData->sum('total_jam_lembur'),
            'total_jam_kerja_libur' => $attendanceData->sum('total_jam_kerja_libur'),
            'total_hari_kerja_libur' => $totalHariKerjaLibur,
            'total_jam_lembur_libur' => $attendanceData->sum('total_jam_lembur_libur'),
            'total_shift_malam' => $attendanceData->sum('shift_malam'),
            'total_hari_kerja_keseluruhan' => $totalHariKerja + $totalHariKerjaLibur,
        ];
    }

    public function index2($user_id, $month, $year)
    {
        // Ambil data attendance berdasarkan user_id, month, year
        $attendanceData = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'DESC')
            ->get();

        // Ambil semua bulan-tahun yang tersedia untuk user ini
        $availableMonths = Yfrekappresensi::where('user_id', $user_id)
            ->select('date')
            ->get()
            ->map(function ($item) {
                $date = strtotime($item->date);
                return [
                    'month_year' => date('F Y', $date),
                    'month' => (int) date('n', $date),
                    'year' => (int) date('Y', $date)
                ];
            })
            ->unique('month_year')
            ->sortBy(function ($item) {
                return $item['year'] * 100 + $item['month'];
            })
            ->values()
            ->toArray();

        // Jika tidak ada data attendance
        if ($attendanceData->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'available_months' => $availableMonths,
                'summary' => [
                    'total_jam_kerja' => 0,
                    'total_hari_kerja' => 0,
                    'total_jam_lembur' => 0,
                    'total_jam_kerja_libur' => 0,
                    'total_hari_kerja_libur' => 0,
                    'total_jam_lembur_libur' => 0,
                    'total_shift_malam' => 0,
                    'total_hari_kerja_keseluruhan' => 0,
                ],
                'message' => 'No attendance data found for this user and month'
            ]);
        }

        // Transform data sesuai field tabel
        $transformedData = $attendanceData->map(function ($item) {
            return [
                'date' => $item->date,
                'first_in' => $item->first_in,
                'first_out' => $item->first_out,
                'second_in' => $item->second_in,
                'second_out' => $item->second_out,
                'overtime_in' => $item->overtime_in,
                'overtime_out' => $item->overtime_out,
                'total_jam_kerja' => (float)$item->total_jam_kerja,
                'total_hari_kerja' => (float)$item->total_hari_kerja,
                'total_jam_lembur' => (float)$item->total_jam_lembur,
                'total_jam_kerja_libur' => (float)$item->total_jam_kerja_libur,
                'total_hari_kerja_libur' => (float)$item->total_hari_kerja_libur,
                'total_jam_lembur_libur' => (float)$item->total_jam_lembur_libur,
                'late' => $item->late,
                'no_scan' => $item->no_scan,
                'shift' => $item->shift,
                'shift_malam' => $item->shift_malam,
            ];
        });

        // Hitung summary
        $totalHariKerja = $attendanceData->sum('total_hari_kerja');
        $totalHariKerjaLibur = $attendanceData->sum('total_hari_kerja_libur');

        $summary = [
            'total_jam_kerja' => $attendanceData->sum('total_jam_kerja'),
            'total_hari_kerja' => $totalHariKerja,
            'total_jam_lembur' => $attendanceData->sum('total_jam_lembur'),
            'total_jam_kerja_libur' => $attendanceData->sum('total_jam_kerja_libur'),
            'total_hari_kerja_libur' => $totalHariKerjaLibur,
            'total_jam_lembur_libur' => $attendanceData->sum('total_jam_lembur_libur'),
            'total_shift_malam' => $attendanceData->sum('shift_malam'),
            'total_hari_kerja_keseluruhan' => $totalHariKerja + $totalHariKerjaLibur,
        ];

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'available_months' => $availableMonths,
            'summary' => $summary,
            'current_month_year' => [
                'month' => (int)$month,
                'year' => (int)$year,
                'month_year' => date('F Y', strtotime("$year-$month-01"))
            ],
            'message' => 'Attendance data retrieved successfully'
        ]);
    }
    public function index1($user_id, $month, $year)
    {
        // Ambil data attendance
        $attendanceData = Yfrekappresensi::where('user_id', $user_id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'DESC')
            ->get();

        // Jika tidak ada data
        if ($attendanceData->isEmpty()) {
            return response()->json([
                'success' => false,
                'data' => [],
                'summary' => [
                    'total_jam_kerja' => 0,
                    'total_hari_kerja' => 0,
                    'total_jam_lembur' => 0,
                    'total_jam_kerja_libur' => 0,
                    'total_hari_kerja_libur' => 0,
                    'total_shift_malam' => 0,
                    'total_hari_kerja_keseluruhan' => 0, // total keseluruhan
                ],
                'message' => 'No attendance data found for this user and month'
            ]);
        }

        // Transform data sesuai field tabel
        $transformedData = $attendanceData->map(function ($item) {
            return [
                'date' => $item->date,
                'first_in' => $item->first_in,
                'first_out' => $item->first_out,
                'second_in' => $item->second_in,
                'second_out' => $item->second_out,
                'overtime_in' => $item->overtime_in,
                'overtime_out' => $item->overtime_out,
                'total_jam_kerja' => (float)$item->total_jam_kerja,
                'total_hari_kerja' => (float)$item->total_hari_kerja,
                'total_jam_lembur' => (float)$item->total_jam_lembur,
                'total_jam_kerja_libur' => (float)$item->total_jam_kerja_libur,
                'total_hari_kerja_libur' => (float)$item->total_hari_kerja_libur,
                'total_jam_lembur_libur' => (float)$item->total_jam_lembur_libur,
                'late' => $item->late,
                'no_scan' => $item->no_scan,
                'shift' => $item->shift,
                'shift_malam' => $item->shift_malam,
            ];
        });

        // Hitung summary
        $totalHariKerja = $attendanceData->sum('total_hari_kerja');
        $totalHariKerjaLibur = $attendanceData->sum('total_hari_kerja_libur');

        $summary = [
            'total_jam_kerja' => $attendanceData->sum('total_jam_kerja'),
            'total_hari_kerja' => $totalHariKerja,
            'total_jam_lembur' => $attendanceData->sum('total_jam_lembur'),
            'total_jam_kerja_libur' => $attendanceData->sum('total_jam_kerja_libur'),
            'total_hari_kerja_libur' => $totalHariKerjaLibur,
            'total_jam_lembur_libur' => $attendanceData->sum('total_jam_lembur_libur'),
            'total_shift_malam' => $attendanceData->sum('shift_malam'),
            'total_hari_kerja_keseluruhan' => $totalHariKerja + $totalHariKerjaLibur, // total keseluruhan
        ];

        return response()->json([
            'success' => true,
            'data' => $transformedData,
            'summary' => $summary,
            'message' => 'Attendance data retrieved successfully'
        ]);
    }
}
