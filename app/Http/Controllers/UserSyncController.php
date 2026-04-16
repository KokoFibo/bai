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
            'companies.company_name',
            'karyawans.outsource'
        )
            ->join('karyawans', 'karyawans.id_karyawan', '=', 'users.username')
            ->join('companies', 'companies.id', '=', 'karyawans.company_id')
            ->whereNotIn('karyawans.status_karyawan', ['Resigned', 'Blacklist'])
            ->get()
            ->map(function ($user) {
                $user->db_code = 'bai';
                return $user;
            });
        $users->makeVisible(['password']);
        return response()->json($users);
    }
}
