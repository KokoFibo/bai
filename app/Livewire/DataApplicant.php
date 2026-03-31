<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Applicantdata;
use App\Models\Applicantfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Google\Service\YouTube\ThirdPartyLinkStatus;


class DataApplicant extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $show_data, $show_table, $personal_data, $personal_files;
    public  $status, $editId = null;
    public $delete_id, $terima_id;
    public $search;
    // public $amazonUrl;




    public function terimaConfirmation($id)
    {
        $this->terima_id = $id;
        $data = Applicantdata::find($id);

        $this->dispatch('show-terima-confirmation', text: $data->nama);
    }
    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $data = Applicantdata::find($id);

        $this->dispatch('show-delete-confirmation', text: $data->nama);
    }


    public function cancel()
    {
        $this->editId = null;
    }


    public function save()
    {
        $data = Applicantdata::find($this->editId);
        $data->status = $this->status;
        // $data->save();
        if ($this->status == 8) {
            // $this->diterima();
            // dispatch terima confirmation
            $this->terimaConfirmation($this->editId);
        } else {
            // $this->dispatch('success', message: 'Status sudah berhasil di rubah');
            $data->save();
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Status sudah berhasil di rubah',
            );
        }
        $this->editId = null;
    }

    public function edit($id)
    {
        $this->editId = $id;
        $data = Applicantdata::find($this->editId);
        $this->status = $data->status;
    }



    #[On('terima-confirmed')]
    public function diterima()
    {
        // dd($this->terima_id);
        $id = $this->terima_id;
        $array_hasil_check = check_resigned_blacklist($id);
        $hasil_check = $array_hasil_check[0];
        // dd($array_hasil_check[0]);
        // dd($hasil_check);
        if ($hasil_check == 1) {
            $this->dispatch(
                'message',
                type: 'error',
                // title: 'Karyawan ini sudah pernah RESIGNED, ID : ' . getId($id),
                title: 'Karyawan ini sudah pernah RESIGNED, ID : ' . $array_hasil_check[1],
            );
            return;
        } else if ($hasil_check == 2) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Karyawan ini sudah pernah Blacklist, ID : ' . $array_hasil_check[1],
            );
            return;
        } else if ($hasil_check == 3) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Karyawan ini sudah pernah terdaftar dalam database, ID : ' . $array_hasil_check[1],
            );
            return;
        }
        $dataApplicant = Applicantdata::find($id);
        $dataKaryawan = Karyawan::where('id_file_karyawan', $dataApplicant->applicant_id)->first();
        if ($dataKaryawan != null) {
            // $this->dispatch('error', message: 'Data karyawan ini sudah di berada dalam database karyawan');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Data karyawan ini sudah di berada dalam database karyawan',
            );
            return;
        }

        // chdeck email double di user
        $email_user = User::where('email', $dataApplicant->email)->first();
        // dd($email_user, $dataApplicant->email);
        if ($email_user != null) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Email ini sudah terdaftar',
            );
            return;
        }
        if ($dataKaryawan == null) {

            $email_user = User::where('email', $dataApplicant->email)->first();
            // dd($email_user);
            if ($email_user != null) {
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: 'Email ini sudah terdaftar',
                );
                return;
            }
            // ambil ID karyawan
            $id_karyawan_terbaru = getNextIdKaryawan();

            // data yg mau di entry ke karyawan


            Karyawan::create([
                'id_karyawan' => $id_karyawan_terbaru,
                'nama' => $dataApplicant->nama,
                'email' => trim($dataApplicant->email, ' '),
                'hp' => $dataApplicant->hp,
                'telepon' => $dataApplicant->telp,
                'tempat_lahir' => $dataApplicant->tempat_lahir,
                'tanggal_lahir' => $dataApplicant->tgl_lahir,
                'gender' => $dataApplicant->gender,
                'status_pernikahan' => $dataApplicant->status_pernikahan,
                'golongan_darah' => $dataApplicant->golongan_darah,
                'agama' => $dataApplicant->agama,
                'etnis' => $dataApplicant->etnis,
                'ptkp' => $dataApplicant->ptkp,
                'kontak_darurat' => $dataApplicant->nama_contact_darurat,
                'kontak_darurat2' => $dataApplicant->nama_contact_darurat_2,
                'hp1' => $dataApplicant->contact_darurat_1,
                'hp2' => $dataApplicant->contact_darurat_2,
                'hubungan1' => $dataApplicant->hubungan_1,
                'hubungan2' => $dataApplicant->hubungan_2,
                'jenis_identitas' => $dataApplicant->jenis_identitas,
                'no_identitas' => $dataApplicant->no_identitas,
                'alamat_identitas' => $dataApplicant->alamat_identitas,
                'alamat_tinggal' => $dataApplicant->alamat_tinggal_sekarang,
                'id_file_karyawan' => $dataApplicant->applicant_id,
                'status_karyawan' => 'PKWT',
                'jabatan_id' => 100,
                'company_id' => 100,
                'department_id' => 100,
                'placement_id' => 100,
                'tanggal_bergabung' => Carbon::now()->toDateString()
            ]);

            User::create([
                'name' => titleCase($dataApplicant->nama),
                'email' => trim($dataApplicant->email, ' '),
                'username' => $id_karyawan_terbaru,
                'role' => 1,
                'password' => Hash::make($dataApplicant->password),
            ]);


            // hapus data applicant
            $dataApplicant->delete();
            // $this->dispatch('success', message: 'Data Aplicant sudah berhasil di pindahkan kedalam database karyawan');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Aplicant sudah berhasil di pindahkan kedalam database karyawan',
            );
            return;
        }
    }

    public function mount()
    {
        $this->show_table = true;
        $this->show_data = false;
        // $this->amazonUrl = "https://yifang-payroll.s3.ap-southeast-1.amazonaws.com/";
    }




    #[On('delete-confirmed')]
    public function delete()
    {
        $id = $this->delete_id;
        $applicant_data = Applicantdata::find($id);
        $applicant_id = $applicant_data->applicant_id;
        $applicant_files = Applicantfile::where('id_karyawan', $applicant_id)->get();
        $applicant_data->delete();
        foreach ($applicant_files as $d) {
            $d->delete();
            Storage::disk('s3')->delete($d->filename);
        }
        // $this->dispatch('success', message: 'Data telah di hapus');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data telah di delete',
        );
    }
    public function show($id)
    {
        $this->personal_data = Applicantdata::find($id);
        $this->show_data = true;
        $this->show_table = false;
        // $this->personal_files = Applicantfile::where('id_karyawan', $this->personal_data->applicant_id)->get();
        $order = ['ktp', 'kk', 'ijazah', 'nilai', 'cv', 'pasfoto', 'npwp', 'paklaring', 'bpjs', 'skck', 'sertifikat', 'bri'];

        $this->personal_files = Applicantfile::where('id_karyawan', $this->personal_data->applicant_id)
            ->get()
            ->sortBy(function ($file) use ($order) {
                return array_search($file->file_category, $order);
            });
    }




    public function kembali()
    {
        $this->show_data = false;
        $this->show_table = true;
    }

    public function render()
    {
        $data = Applicantdata::orderBy('created_at', 'asc')
            ->where(function ($query) {
                $query->where('nama', 'LIKE', '%' . trim($this->search) . '%')
                    ->orWhere('email', 'LIKE', '%' . trim($this->search) . '%');
            })
            ->paginate(10);

        return view('livewire.data-applicant', [
            'data' => $data,
        ]);
    }
}
