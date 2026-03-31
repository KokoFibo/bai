<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;

class DeveloperDashboard extends Component
{
    public function delete_karyawan_company()
    {
        $users_count = User::count();
        // dd($users_count);
        $datas_count = Karyawan::whereIn('company_id', [7, 5, 3, 6, 4])->count();
        $data = Karyawan::whereIn('company_id', [7, 5, 3, 6, 4])->get();
        // delete user
        $cx = 0;
        foreach ($data as $d) {
            $user = User::where('username', $d->id_karyawan)->first();
            if ($user) {
                if ($user->role == 1) {

                    $user->delete();
                    $cx++;
                }
            }
        }
        // dd($cx);
        // delete data
        $delete_data = Karyawan::whereIn('company_id', [7, 5, 3, 6, 4])->delete();

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Berhasil di delete : ' . $datas_count . ' data, ' . $cx . ' users',
        );
    }

    public function delete_diatas_4jt()
    {
        $users_count = User::count();
        // dd($users_count);
        $datas_count = Karyawan::where('gaji_pokok', '>=', 4000000)->count();
        $data = Karyawan::where('gaji_pokok', '>=', 4000000)->get();
        // delete user
        $cx = 0;
        foreach ($data as $d) {
            $user = User::where('username', $d->id_karyawan)->first();
            if ($user) {
                if ($user->role == 1) {

                    $user->delete();
                    $cx++;
                }
            }
        }
        // dd($cx);
        // delete data
        $delete_data = Karyawan::where('gaji_pokok', '>=', 4000000)->delete();

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Berhasil di delete : ' . $datas_count . ' data, ' . $cx . ' users',
        );
    }
    public function delete_dibawah_4jt_keep_company()
    {
        $users_count = User::count();
        // dd($users_count);
        $datas_count = Karyawan::where('gaji_pokok', '<', 4000000)
            // ['YAM', 'YIG', 'YCME', 'YSM']
            ->whereNotIn('company_id', [7, 5, 3, 6, 4])
            ->count();
        $data = Karyawan::where('gaji_pokok', '<', 4000000)->whereNotIn('company_id', [7, 5, 3, 6, 4])->get();
        // delete user
        $cx = 0;
        foreach ($data as $d) {
            $user = User::where('username', $d->id_karyawan)->first();
            if ($user) {
                if ($user->role == 1) {
                    $user->delete();
                    $cx++;
                }
            }
        }
        // dd($cx);

        // delete data
        // $delete_data = Karyawan::where('gaji_pokok', '<', 4000000)->delete();
        Karyawan::where('gaji_pokok', '<', 4000000)->whereNotIn('company_id', [7, 5, 3, 6, 4])->delete();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Berhasil di delete : ' . $datas_count . ' data, ' . $cx . ' users',
        );
    }
    public function delete_dibawah_4jt()
    {
        $users_count = User::count();
        // dd($users_count);
        $datas_count = Karyawan::where('gaji_pokok', '<', 4000000)

            ->count();
        $data = Karyawan::where('gaji_pokok', '<', 4000000)->get();
        // delete user
        $cx = 0;
        foreach ($data as $d) {
            $user = User::where('username', $d->id_karyawan)->first();
            if ($user) {
                if ($user->role == 1) {
                    $user->delete();
                    $cx++;
                }
            }
        }
        // dd($cx);

        // delete data
        // $delete_data = Karyawan::where('gaji_pokok', '<', 4000000)->delete();
        Karyawan::where('gaji_pokok', '<', 4000000)->delete();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Berhasil di delete : ' . $datas_count . ' data, ' . $cx . ' users',
        );
    }

    public function delete_failed_jobs()
    {
        delete_failed_jobs();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Failed Jobs Deleted',
            position: 'center'
        );
    }

    public function clear_payroll_rebuild()
    {
        clear_payroll_rebuild();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Payrol rebuild Clear',
            position: 'center'
        );
    }
    public function clear_build()
    {
        clear_build();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Build Clear',
            position: 'center'
        );
    }

    public function delete_all_presensi_kosong()
    {
        delete_all_presensi_kosong();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Absensi kosong telah berhasil di hapus',
            position: 'center'
        );
    }
    public function render()
    {
        return view('livewire.developer-dashboard');
    }
}
