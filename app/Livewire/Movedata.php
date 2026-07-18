<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;


class Movedata extends Component
{
    public $id_karyawan, $data_karyawan, $data_user, $database;
    public function search()
    {
        // $apiUrl =  "https://payroll.yifang.co.id/api/getkaryawan/" . $this->id_karyawan;
        // $apiUrlKaryawan =  "https://salary.yifang.co.id/api/getkaryawan/" . $this->id_karyawan;
        // $apiUrlUser =  "https://salary.yifang.co.id/api/getuser/" . $this->id_karyawan;
        switch ($this->database) {
            case 'nonos':
                $apiUrlKaryawan =  "https://salary.yifang.co.id/api/getkaryawan/" . $this->id_karyawan;
                break;
            case 'sti':
                $apiUrlKaryawan =  "https://sti.yifang.co.id/api/getkaryawan/" . $this->id_karyawan;
                break;
            case 'os':
                $apiUrlKaryawan =  "https://payroll.yifang.co.id/api/getkaryawan/" . $this->id_karyawan;
                break;
        }

        // $apiUrlKaryawan =  "http://127.0.0.1:8080/api/getkaryawan/" . $this->id_karyawan;

        // $apiUrlUser =  "http://127.0.0.1:8080/api/getuser/" . $this->id_karyawan;

        $this->data_karyawan = getDataApi($apiUrlKaryawan);
        // $this->data_user = getDataApi($apiUrlUser);

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
        // if (isset($this->data_user['status']) && $this->data_user['status'] === 'error') {
        //     // Display the error message
        //     $this->dispatch(
        //         'message',
        //         type: 'error',
        //         title: 'Data user tidak ditemukan',
        //         position: 'center'
        //     );
        //     $this->data_user = '';
        //     return;
        // }
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
        DB::transaction(function () {

            // ID karyawan baru
            $this->data_karyawan['id_karyawan'] =
                (Karyawan::max('id_karyawan') ?? 0) + 1;

            // Ambil daftar kolom tabel (hanya sekali)
            static $columns = null;

            if ($columns === null) {
                $columns = Schema::getColumnListing((new Karyawan)->getTable());
            }

            // Sisakan hanya field yang memang ada pada tabel
            $data = array_intersect_key(
                $this->data_karyawan,
                array_flip($columns)
            );

            Karyawan::create($data);
        });
    }

    public function move()
    {
        DB::beginTransaction();

        try {

            // 1️⃣ COPY DATA KE DB BARU
            $this->create_data();

            // 2️⃣ DELETE DATA DI API LAMA
            // $apiDeleteKaryawan = "https://salary.yifang.co.id/api/delete_karyawan_yf_aja/{$this->id_karyawan}";
            // $apiDeleteUser     = "https://salary.yifang.co.id/api/delete_user_yf_aja/{$this->id_karyawan}";

            // $deleteKaryawan = $this->deleteDataKaryawanApi($apiDeleteKaryawan);
            // $deleteUser     = $this->deleteDataUserApi($apiDeleteUser);

            // if (
            //     (isset($deleteKaryawan['status']) && $deleteKaryawan['status'] === 'error') ||
            //     (isset($deleteUser['status']) && $deleteUser['status'] === 'error')
            // ) {
            //     throw new \Exception('Gagal menghapus data di sistem lama');
            // }

            // 3️⃣ SEMUA SUKSES → COMMIT
            DB::commit();

            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data berhasil dipindahkan',
                position: 'center'
            );
        } catch (\Throwable $e) {

            // ❌ ADA YANG GAGAL → ROLLBACK
            DB::rollBack();

            $this->dispatch(
                'message',
                type: 'error',
                title: $e->getMessage(),
                position: 'center'
            );
        }
    }

    public function render()
    {
        return view('livewire.movedata', [
            'data_karyawan' => $this->data_karyawan,
            'data_user' => $this->data_user
        ]);
    }
}
