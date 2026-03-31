<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\Jabatan;
use App\Models\Jobgrade;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Placement;
use Google\Service\Batch\Job;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\Rule;

class Karyawanwr extends Component
{
    public $id;
    public $id_karyawan, $nama, $email, $hp, $telepon, $tempat_lahir, $tanggal_lahir, $gender, $status_pernikahan, $golongan_darah, $agama, $etnis;
    public $jenis_identitas, $no_identitas, $alamat_identitas, $alamat_tinggal;
    public $status_karyawan, $tanggal_bergabung, $company_id, $placement_id, $department_id, $jabatan_id, $level_jabatan, $nama_bank, $nomor_rekening;

    public $metode_penggajian, $gaji_pokok, $gaji_overtime, $gaji_shift_malam_satpam;
    public $bonus, $tunjangan_jabatan, $tunjangan_bahasa;
    public $tunjangan_skill, $tunjangan_lembur_sabtu, $tunjangan_lama_kerja;
    public $iuran_air, $denda, $iuran_locker, $potongan_JHT, $gaji_bpjs, $potongan_JP, $potongan_JKK, $potongan_JKM, $potongan_kesehatan;
    public $no_npwp, $ptkp;
    public $kontak_darurat, $kontak_darurat2, $hp1, $hp2, $hubungan1, $hubungan2;
    public $tanggungan;

    public $id_karyawan_ini, $update, $status_off;
    public $is_update;
    public $pilih_jabatan;
    public $pilih_company;
    public $pilih_department;
    public $pilih_placement;
    public $jobgrades;

    public function mount()
    {
        $this->jobgrades = Jobgrade::orderBy('grade', 'asc')->get();
        $this->pilih_jabatan = Jabatan::orderBy('nama_jabatan', 'asc')->get();
        $this->pilih_company = Company::orderBy('company_name', 'asc')->get();
        $this->pilih_department = Department::orderBy('nama_department', 'asc')->get();
        $this->pilih_placement = Placement::orderBy('placement_name', 'asc')->get();

        $this->is_update = false;
        $this->update = false;
        // $this->id_karyawan = getNextIdKaryawan();
        $this->id_karyawan = '000000';
        $this->status_karyawan = 'PKWT';
        $this->tanggungan = 0;
        $this->tanggal_bergabung =  date('d M Y', strtotime(now()->toDateString()));
        $this->id_karyawan_ini = '';
        $this->status_off = true;
    }

    protected $rules = [

        'id_karyawan' => 'nullable',
        'nama' => 'required',
        'email' => 'email|nullable',
        'tanggal_lahir' => 'date|before:today|required',
        // PRIBADI
        'hp' => 'nullable',
        'telepon' => 'nullable',
        'tempat_lahir' => 'required',
        'gender' => 'required',
        'status_pernikahan' => 'nullable',
        'golongan_darah' => 'nullable',
        'agama' => 'nullable',
        'etnis' => 'required',
        'kontak_darurat' => 'nullable',
        'hp1' => 'nullable',
        'hp2' => 'nullable',
        'kontak_darurat2' => 'nullable',
        'hubungan1' => 'nullable',
        'hubungan2' => 'nullable',


        // IDENTITAS
        'jenis_identitas' => 'required',
        'no_identitas' => 'required',
        'alamat_identitas' => 'required',
        'alamat_tinggal' => 'required',
        // KEPEGAWAIAN
        'status_karyawan' => 'required',
        'tanggal_bergabung' => 'date|required|after:yesterday',
        'company_id' => 'required',
        'placement_id' => 'required',
        'department_id' => 'required',
        'jabatan_id' => 'required',
        'level_jabatan' => 'nullable',
        'nama_bank' => 'nullable',
        'nomor_rekening' => 'nullable',
        // PAYROLL
        'metode_penggajian' => 'required',
        'gaji_pokok' => 'numeric|required',
        'gaji_overtime' => 'numeric|required',
        'gaji_shift_malam_satpam' => 'numeric',
        'bonus' => 'numeric|nullable',
        'tunjangan_jabatan' => 'numeric|nullable',
        'tunjangan_bahasa' => 'numeric|nullable',
        'tunjangan_skill' => 'numeric|nullable',
        'tunjangan_lembur_sabtu' => 'numeric|nullable',
        'tunjangan_lama_kerja' => 'numeric|nullable',
        'iuran_air' => 'numeric|required',
        'denda' => 'numeric|nullable',
        'iuran_locker' => 'numeric|nullable',
        'gaji_bpjs' => 'nullable',
        'potongan_JHT' => 'nullable',
        'potongan_JP' => 'nullable',
        'potongan_JKK' => 'nullable',
        'potongan_JKM' => 'nullable',
        'potongan_kesehatan' => 'nullable',
        'tanggungan' => 'nullable',
        'no_npwp' => 'nullable',
        'ptkp' => 'nullable',

    ];



    public function save()
    {
        $this->gaji_pokok = convert_numeric($this->gaji_pokok);
        $this->gaji_overtime = convert_numeric($this->gaji_overtime);
        $this->gaji_shift_malam_satpam = convert_numeric($this->gaji_shift_malam_satpam);
        $this->bonus = convert_numeric($this->bonus);
        $this->tunjangan_jabatan = convert_numeric($this->tunjangan_jabatan);
        $this->tunjangan_bahasa = convert_numeric($this->tunjangan_bahasa);
        $this->tunjangan_skill = convert_numeric($this->tunjangan_skill);
        $this->tunjangan_lembur_sabtu = convert_numeric($this->tunjangan_lembur_sabtu);
        $this->tunjangan_lama_kerja = convert_numeric($this->tunjangan_lama_kerja);
        $this->iuran_air = convert_numeric($this->iuran_air);
        $this->iuran_locker = convert_numeric($this->iuran_locker);
        $this->gaji_bpjs = convert_numeric($this->gaji_bpjs);
        $this->denda = convert_numeric($this->denda);
        $this->validate();
        $this->tanggal_lahir = date('Y-m-d', strtotime($this->tanggal_lahir));
        $this->tanggal_bergabung = date('Y-m-d', strtotime($this->tanggal_bergabung));

        $ada = Karyawan::where('id_karyawan', $this->id_karyawan_ini)->first();

        if ($ada) {
            $this->id = $ada->id;
            $this->update();
        } else {
            $this->id = '';
            $this->id_karyawan = getNextIdKaryawan();
            $this->id_karyawan_ini = $this->id_karyawan;
            $data = new Karyawan();
            // Data Pribadi
            $data->id_karyawan = $this->id_karyawan;
            $data->nama = titleCase($this->nama);
            $data->email = trim($this->email, ' ');
            $data->hp = $this->hp;
            $data->telepon = $this->telepon;
            $data->tempat_lahir = titleCase($this->tempat_lahir);
            $data->tanggal_lahir = $this->tanggal_lahir;
            $data->gender = $this->gender;
            $data->status_pernikahan = $this->status_pernikahan;
            $data->golongan_darah = $this->golongan_darah;
            $data->agama = $this->agama;
            $data->etnis = $this->etnis;
            $data->kontak_darurat = $this->kontak_darurat;
            $data->kontak_darurat2 = $this->kontak_darurat2;
            $data->hp1 = $this->hp1;
            $data->hp2 = $this->hp2;
            $data->hubungan1 = $this->hubungan1;
            $data->hubungan2 = $this->hubungan2;
            $data->denda = $this->denda;

            // Identitas
            $data->jenis_identitas = $this->jenis_identitas;
            $data->no_identitas = $this->no_identitas;
            $data->alamat_identitas = titleCase($this->alamat_identitas);
            $data->alamat_tinggal = titleCase($this->alamat_tinggal);
            // Data Kepegawaian
            $data->status_karyawan = $this->status_karyawan;
            $data->tanggal_bergabung = $this->tanggal_bergabung;
            $data->company_id = $this->company_id;
            $data->placement_id = $this->placement_id;
            $data->department_id = $this->department_id;
            $data->jabatan_id = $this->jabatan_id;
            $data->level_jabatan = $this->level_jabatan;
            $data->nama_bank = $this->nama_bank;
            $data->nomor_rekening = $this->nomor_rekening;

            // Payroll
            $data->gaji_pokok = $this->gaji_pokok;
            $data->gaji_overtime = $this->gaji_overtime;
            $data->gaji_shift_malam_satpam = $this->gaji_shift_malam_satpam;
            $data->metode_penggajian = $this->metode_penggajian;
            $data->bonus = $this->bonus;
            $data->tunjangan_jabatan = $this->tunjangan_jabatan;
            $data->tunjangan_bahasa = $this->tunjangan_bahasa;
            $data->tunjangan_skill = $this->tunjangan_skill;
            $data->tunjangan_lembur_sabtu = $this->tunjangan_lembur_sabtu;
            $data->tunjangan_lama_kerja = $this->tunjangan_lama_kerja;
            $data->iuran_air = $this->iuran_air;
            $data->iuran_locker = $this->iuran_locker;
            $data->gaji_bpjs = $this->gaji_bpjs;
            $data->potongan_JHT = $this->potongan_JHT;
            $data->potongan_JP = $this->potongan_JP;
            $data->potongan_JKK = $this->potongan_JKK;
            $data->potongan_JKM = $this->potongan_JKM;
            $data->potongan_kesehatan = $this->potongan_kesehatan;
            $data->tanggungan = $this->tanggungan;
            $data->no_npwp = $this->no_npwp;
            $data->ptkp = $this->ptkp;



            try {
                $data->save();
                // create user
                User::create([
                    'name' => titleCase($this->nama),
                    'email' => trim($this->email, ' '),
                    'username' => $this->id_karyawan,
                    'role' => 1,
                    // 'email_verified_at' => now(),

                    'password' => Hash::make(generatePassword($this->tanggal_lahir)),
                    // 'remember_token' => Str::random(10),
                ]);
                $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
                $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
                // $this->dispatch('success', message: 'Data Karyawan Sudah di Save');
                $this->dispatch(
                    'message',
                    type: 'success',
                    title: 'Data Karyawan Sudah di Save',
                    position: 'center'
                );
            } catch (\Exception $e) {
                $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
                $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
                // $this->dispatch('error', message: $e->getMessage());
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: $e->getMessage(),
                );
                return $e->getMessage();
            }

            // $this->reset();
        }
        // get_data_karyawan();
    }

    public function update()
    {
        $this->gaji_pokok = convert_numeric($this->gaji_pokok);
        $this->gaji_overtime = convert_numeric($this->gaji_overtime);
        $this->gaji_shift_malam_satpam = convert_numeric($this->gaji_shift_malam_satpam);
        $this->bonus = convert_numeric($this->bonus);
        $this->tunjangan_jabatan = convert_numeric($this->tunjangan_jabatan);
        $this->tunjangan_bahasa = convert_numeric($this->tunjangan_bahasa);
        $this->tunjangan_skill = convert_numeric($this->tunjangan_skill);
        $this->tunjangan_lembur_sabtu = convert_numeric($this->tunjangan_lembur_sabtu);
        $this->tunjangan_lama_kerja = convert_numeric($this->tunjangan_lama_kerja);
        $this->iuran_air = convert_numeric($this->iuran_air);
        $this->iuran_locker = convert_numeric($this->iuran_locker);
        $this->gaji_bpjs = convert_numeric($this->gaji_bpjs);
        $this->denda = convert_numeric($this->denda);
        $this->validate();

        try {
            $data = Karyawan::find($this->id);

            $data->id_karyawan = $this->id_karyawan;
            $data->nama = titleCase($this->nama);
            $data->email = trim($this->email, ' ');
            $data->hp = $this->hp;
            $data->telepon = $this->telepon;
            $data->tempat_lahir = titleCase($this->tempat_lahir);
            $data->tanggal_lahir = $this->tanggal_lahir;
            $data->gender = $this->gender;
            $data->status_pernikahan = $this->status_pernikahan;
            $data->golongan_darah = $this->golongan_darah;
            $data->agama = $this->agama;
            $data->etnis = $this->etnis;
            $data->kontak_darurat = $this->kontak_darurat;
            $data->kontak_darurat2 = $this->kontak_darurat2;
            $data->hp1 = $this->hp1;
            $data->hp2 = $this->hp2;
            $data->hubungan1 = $this->hubungan1;
            $data->hubungan2 = $this->hubungan2;
            $data->denda = $this->denda;



            // Identitas
            $data->jenis_identitas = $this->jenis_identitas;
            $data->no_identitas = $this->no_identitas;
            $data->alamat_identitas = titleCase($this->alamat_identitas);
            $data->alamat_tinggal = titleCase($this->alamat_tinggal);
            // Data Kepegawaian
            $data->status_karyawan = $this->status_karyawan;
            $data->tanggal_bergabung = $this->tanggal_bergabung;
            $data->company_id = $this->company_id;
            $data->placement_id = $this->placement_id;
            $data->department_id = $this->department_id;
            $data->jabatan_id = $this->jabatan_id;
            $data->level_jabatan = $this->level_jabatan;
            $data->nama_bank = $this->nama_bank;
            $data->nomor_rekening = $this->nomor_rekening;

            // Payroll
            $data->gaji_pokok = $this->gaji_pokok;
            $data->gaji_overtime = $this->gaji_overtime;
            $data->gaji_shift_malam_satpam = $this->gaji_shift_malam_satpam;
            $data->metode_penggajian = $this->metode_penggajian;
            $data->bonus = $this->bonus;
            $data->tunjangan_jabatan = $this->tunjangan_jabatan;
            $data->tunjangan_bahasa = $this->tunjangan_bahasa;
            $data->tunjangan_skill = $this->tunjangan_skill;
            $data->tunjangan_lembur_sabtu = $this->tunjangan_lembur_sabtu;
            $data->tunjangan_lama_kerja = $this->tunjangan_lama_kerja;
            $data->iuran_air = $this->iuran_air;
            $data->iuran_locker = $this->iuran_locker;
            $data->gaji_bpjs = $this->gaji_bpjs;
            $data->potongan_JHT = $this->potongan_JHT;
            $data->potongan_JP = $this->potongan_JP;
            $data->potongan_JKK = $this->potongan_JKK;
            $data->potongan_JKM = $this->potongan_JKM;
            $data->potongan_kesehatan = $this->potongan_kesehatan;
            $data->tanggungan = $this->tanggungan;
            $data->no_npwp = $this->no_npwp;
            $data->ptkp = $this->ptkp;


            $data->save();
            $user = User::where('username', $this->id_karyawan)->first();
            $data_user = User::find($user->id);
            $data_user->name = titleCase($this->nama);
            $data_user->email = trim($this->email, ' ');
            $data_user->password  = Hash::make(generatePassword($this->tanggal_lahir));
            $data_user->save();


            $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
            $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
            // $this->dispatch('success', message: 'Data Karyawan Sudah di Update');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Karyawan Sudah di Save',
            );
        } catch (\Exception $e) {
            $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
            $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
            // $this->dispatch('error', message: $e->getMessage());
            $this->dispatch(
                'message',
                type: 'error',
                title: $e->getMessage(),
            );
            return $e->getMessage();
        }
    }

    public function clear()
    {
        $this->reset();
        // $this->id_karyawan = getNextIdKaryawan();
    }

    public function exit()
    {
        $this->reset();
        return redirect()->to('/karyawanindex');
    }
    // public function updated() {

    //     $this->gaji_pokok = convert_numeric($this->gaji_pokok);
    //     $this->gaji_overtime = convert_numeric($this->gaji_overtime);
    //         $this->bonus = convert_numeric($this->bonus);
    //         $this->tunjangan_jabatan = convert_numeric($this->tunjangan_jabatan);
    //         $this->tunjangan_bahasa = convert_numeric($this->tunjangan_bahasa);
    //         $this->tunjangan_skill = convert_numeric($this->tunjangan_skill);
    //         $this->tunjangan_lembur_sabtu = convert_numeric($this->tunjangan_lembur_sabtu);
    //         $this->tunjangan_lama_kerja = convert_numeric($this->tunjangan_lama_kerja);
    //         $this->iuran_air = convert_numeric($this->iuran_air);
    //         $this->iuran_locker = convert_numeric($this->iuran_locker);
    //         $this->gaji_bpjs = convert_numeric($this->gaji_bpjs);
    //         $this->denda = convert_numeric($this->denda);

    // }

    public function render()
    {
        return view('livewire.karyawanwr')->layout('layouts.appeloe');
    }
}
