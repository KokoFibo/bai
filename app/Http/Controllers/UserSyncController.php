<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserSyncController extends Controller
{
    public function export()
    {
        $users = User::select(
            'users.name',
            'users.email',
            'users.password',
            'users.role',
            'users.language',
            'karyawans.id_karyawan as id_karyawan',
            'companies.company_name'
        )
            ->join('karyawans', 'karyawans.id_karyawan', '=', 'users.username')
            ->join('companies', 'companies.id', '=', 'karyawans.company_id')
            ->get()
            ->map(function ($user) {
                $user->db_code = 'sti';
                return $user;
            });
        $users->makeVisible(['password']);
        return response()->json($users);
    }
}
