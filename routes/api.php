<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\LarkController;
use App\Http\Controllers\UserSyncController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LaporanApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/karyawan', [ApiController::class, 'index']);
Route::get('getkaryawan/{id}', [ApiController::class, 'getDataKaryawan']);
Route::get('getuser/{id}', [ApiController::class, 'getDataUser']);
Route::get('store/{id}', [ApiController::class, 'store']);
Route::delete('delete_karyawan_yf_aja/{id}', [ApiController::class, 'delete_data_karyawan_yf_aja']);
Route::delete('delete_user_yf_aja/{id}', [ApiController::class, 'delete_data_user_yf_aja']);
// Route::post('store', [ApiController::class, 'store']);

Route::get('getKaryawanById/{id}', [LarkController::class, 'getKaryawanById']);

Route::get('os-placement/{month}/{year}/{placement_id}', [LaporanApiController::class, 'osPlacement']);
Route::get('os-placement-name/{month}/{year}/{placement_name}', [LaporanApiController::class, 'osPlacementName']);

Route::get('/users/export', [UserSyncController::class, 'export']);
Route::get('/attendance/{user_id}/{month}/{year}', [AttendanceController::class, 'index']);
Route::get('/latest-month-year/{user_id}', [AttendanceController::class, 'getLatestMonthYearByUser']);
