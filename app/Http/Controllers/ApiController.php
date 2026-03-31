<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    public function store($id)
    {
        $respKaryawan = Http::get('https://salary.yifang.co.id/api/getkaryawan/' . $id);
        $dataKaryawan = $respKaryawan->json();

        $respUser = Http::get('https://salary.yifang.co.id/api/getuser/' . $id);
        $dataUser = $respUser->json();

        if ($respKaryawan->successful() && $respUser->successful()) {

            // dd('berhasil');
            $karyawan = Karyawan::create($dataKaryawan);
            $user = User::create($dataUser);
            return response()->json(
                [
                    'message' => 'Karyawan created successfully!',
                    'karyawan' => $karyawan
                ],
                200
            );
        } else {
            return response()->json(['error' => 'Data karyawan ini tidak dalam database'], 500);
        }
    }

    public function index()
    {
        return Karyawan::where('id_karyawan', '2')->get();
    }

    // Dibawah ini hanya contoh saja
    // public function getDataUser($id)
    // {
    //     // Find the user by ID
    //     $user = User::where('username', $id)->first();

    //     // Check if the user exists
    //     if (!$user) {
    //         return response()->json([
    //             'message' => 'User not found'
    //         ], 404);
    //     }

    //     // Return user data
    //     return response()->json($user, 200);
    // }

    public function delete_data_user_yf_aja($id)
    {
        try {
            // Find the karyawan by id
            $user = User::where('username', $id)->first();

            // Check if the karyawan exists
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Karyawan not found',
                ], 404);
            }

            // Delete the karyawan record
            $user->delete();

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting User',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function delete_data_karyawan_yf_aja($id)
    {
        try {
            // Find the karyawan by id
            $karyawan = Karyawan::where('id_karyawan', $id)->first();

            // Check if the karyawan exists
            if (!$karyawan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Karyawan not found',
                ], 404);
            }

            // Delete the karyawan record
            $karyawan->delete();

            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Karyawan deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            // Handle any exceptions that occur
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting karyawan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getDataUser($id)
    {
        // Find the user by ID
        $user = User::where('username', $id)->first();

        // Check if the user exists
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Return user data
        return response()->json($user, 200);
    }

    public function getDataKaryawan($id)
    {
        // Find the user by ID
        $karyawan = Karyawan::where('id_karyawan', $id)->first();

        // Check if the user exists
        if (!$karyawan) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Return user data
        return response()->json($karyawan, 200);
    }

    public function move_data($id)
    {
        // Find the user by ID
        $user = User::where('username', $id)->first();
        $karyawan = Karyawan::where('id_karyawan', $id)->first();

        // Check if the user exists
        if (!$user || !$karyawan) {
            return response()->json([
                'message' => 'User or Karyawan not found'
            ], 404);
        }

        // Return user data
        return response()->json($user, 200);
    }
}
