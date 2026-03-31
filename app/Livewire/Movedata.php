<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class Movedata extends Component
{
    public $id_karyawan, $data_karyawan, $data_user;
    public function search()
    {
        // $apiUrl =  "https://payroll.yifang.co.id/api/getkaryawan/" . $this->id_karyawan;
        $apiUrlKaryawan =  "https://salary.yifang.co.id/api/getkaryawan/" . $this->id_karyawan;
        $apiUrlUser =  "https://salary.yifang.co.id/api/getuser/" . $this->id_karyawan;

        $this->data_karyawan = getDataApi($apiUrlKaryawan);
        $this->data_user = getDataApi($apiUrlUser);
        // dd($data);

        if (isset($this->data_karyawan['status']) && $this->data_karyawan['status'] === 'error') {
            // Display the error message
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data karyawan tidak ditemukan',
                position: 'center'
            );
            $this->data_karyawan = '';
            return;
        }
        if (isset($this->data_user['status']) && $this->data_user['status'] === 'error') {
            // Display the error message
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data user tidak ditemukan',
                position: 'center'
            );
            $this->data_user = '';
            return;
        }
    }
    public function deleteDataUserApi($apiUrl)
    {
        try {
            // Make the GET request
            $response = Http::delete($apiUrl);

            // Check if the request was successful
            if ($response->successful()) {
                // Get the response data
                return [
                    'status' => 'success',
                    'message' => 'Data karyawan berhasil di delte',
                    'error' => $response->body()
                ];
            } else {
                // Handle request errors
                return [
                    'status' => 'error',
                    'message' => 'Data karyawan GAGAL di delete',
                    'error' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return [
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ];
        }
    }
    public function deleteDataKaryawanApi($apiUrl)
    {
        try {
            // Make the GET request
            $response = Http::delete($apiUrl);

            // Check if the request was successful
            if ($response->successful()) {
                // Get the response data
                return [
                    'status' => 'success',
                    'message' => 'Data karyawan berhasil di delte',
                    'error' => $response->body()
                ];
            } else {
                // Handle request errors
                return [
                    'status' => 'error',
                    'message' => 'Data karyawan GAGAL di delete',
                    'error' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            // Handle exceptions
            return [
                'status' => 'error',
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ];
        }
    }
    public function create_data()
    {
        Karyawan::create([

            "id_karyawan" => $this->data_karyawan['id_karyawan'],
            "nama" => $this->data_karyawan['nama'],
            "email" => $this->data_karyawan['email'],
            "hp" => $this->data_karyawan['hp'],
            "telepon" => $this->data_karyawan['telepon'],
            "tempat_lahir" => $this->data_karyawan['tempat_lahir'],
            "tanggal_lahir" => $this->data_karyawan['tanggal_lahir'],
            "gender" => $this->data_karyawan['gender'],
            "status_pernikahan" => $this->data_karyawan['status_pernikahan'],
            "golongan_darah" => $this->data_karyawan['golongan_darah'],
            "agama" => $this->data_karyawan['agama'],
            "jenis_identitas" => $this->data_karyawan['jenis_identitas'],
            "no_identitas" => $this->data_karyawan['no_identitas'],
            "alamat_identitas" => $this->data_karyawan['alamat_identitas'],
            "alamat_tinggal" => $this->data_karyawan['alamat_tinggal'],
            "status_karyawan" => $this->data_karyawan['status_karyawan'],
            "tanggal_bergabung" => $this->data_karyawan['tanggal_bergabung'],
            "company_id" => $this->data_karyawan['company_id'],
            "placement_id" => $this->data_karyawan['placement_id'],
            "department_id" => $this->data_karyawan['department_id'],
            "jabatan_id" => $this->data_karyawan['jabatan_id'],
            "level_jabatan" => $this->data_karyawan['level_jabatan'],
            "nama_bank" => $this->data_karyawan['nama_bank'],
            "nomor_rekening" => $this->data_karyawan['nomor_rekening'],
            "metode_penggajian" => $this->data_karyawan['metode_penggajian'],
            "gaji_pokok" => $this->data_karyawan['gaji_pokok'],
            "gaji_overtime" => $this->data_karyawan['gaji_overtime'],
            "bonus" => $this->data_karyawan['bonus'],
            "tunjangan_jabatan" => $this->data_karyawan['tunjangan_jabatan'],
            "tunjangan_bahasa" => $this->data_karyawan['tunjangan_bahasa'],
            "tunjangan_skill" => $this->data_karyawan['tunjangan_skill'],
            "tunjangan_lembur_sabtu" => $this->data_karyawan['tunjangan_lembur_sabtu'],
            "tunjangan_lama_kerja" => $this->data_karyawan['tunjangan_lama_kerja'],
            "iuran_air" => $this->data_karyawan['iuran_air'],
            "iuran_locker" => $this->data_karyawan['iuran_locker'],
            "gaji_bpjs" => $this->data_karyawan['gaji_bpjs'],
            "potongan_JHT" => $this->data_karyawan['potongan_JHT'],
            "potongan_JP" => $this->data_karyawan['potongan_JP'],
            "potongan_JKK" => $this->data_karyawan['potongan_JKK'],
            "potongan_JKM" => $this->data_karyawan['potongan_JKM'],
            "potongan_kesehatan" => $this->data_karyawan['potongan_kesehatan'],
            "no_npwp" => $this->data_karyawan['no_npwp'],
            "ptkp" => $this->data_karyawan['ptkp'],
            "denda" => $this->data_karyawan['denda'],
            "gaji_shift_malam_satpam" => $this->data_karyawan['gaji_shift_malam_satpam'],
            "etnis" => $this->data_karyawan['etnis'],
            "tanggal_resigned" => $this->data_karyawan['tanggal_resigned'],
            "tanggal_blacklist" => $this->data_karyawan['tanggal_blacklist'],
            "kontak_darurat" => $this->data_karyawan['kontak_darurat'],
            "hp1" => $this->data_karyawan['hp1'],
            "hp2" => $this->data_karyawan['hp2'],
            "tanggungan" => $this->data_karyawan['tanggungan'],
            "id_file_karyawan" => $this->data_karyawan['id_file_karyawan'],
            "created_at" => $this->data_karyawan['created_at'],
            "updated_at" => $this->data_karyawan['updated_at']
        ]);

        User::create([
            "name" => $this->data_user['name'],
            "email" => $this->data_user['email'],
            "username" => $this->data_user['username'],
            "email_verified_at" => $this->data_user['email_verified_at'],
            "password" => Hash::make(generatePassword($this->data_karyawan['tanggal_lahir'])),
            "role" => $this->data_user['role'],
            "language" => $this->data_user['language'],
            "device" => $this->data_user['device'],
            "created_at" => $this->data_karyawan['created_at'],
            "updated_at" => $this->data_karyawan['updated_at'],
        ]);
        $user = User::where('username', $this->data_user['username'])->first();
        $user->created_at = $this->data_karyawan['created_at'];
        $user->updated_at = $this->data_karyawan['updated_at'];
        $user->save();
    }
    public function move()
    {
        // dd($this->data_user['password']);
        $this->create_data();
        // dd($this->data_karyawan['nama']);
        $apiUrlDeleteKaryawan =  "https://salary.yifang.co.id/api/delete_karyawan_yf_aja/" . $this->id_karyawan;
        $apiUrlDeleteUser =  "https://salary.yifang.co.id/api/delete_user_yf_aja/" . $this->id_karyawan;
        $data_delete_karyawan = $this->deleteDataKaryawanApi($apiUrlDeleteKaryawan);
        $data_delete_user = $this->deleteDataUserApi($apiUrlDeleteUser);

        if (isset($data_delete_karyawan['status']) && $data_delete_karyawan['status'] === 'error') {
            // Display the error message
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data tidak ditemukan',
                position: 'center'
            );
            return;
        } else if (isset($data_delete_user['status']) && $data_delete_user['status'] === 'error') {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data tidak ditemukan',
                position: 'center'
            );
            return;
        }


        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data telah dipindahkan',
            position: 'center'
        );
    }
    public function render()
    {
        return view('livewire.movedata', [
            'data_karyawan' => $this->data_karyawan,
            'data_user' => $this->data_user
        ]);
    }
}
