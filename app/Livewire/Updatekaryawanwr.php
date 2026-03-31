<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Company;
use App\Models\Jabatan;
use Livewire\Component;
use App\Models\Jobgrade;
use App\Models\Karyawan;
use App\Models\Placement;
use App\Models\Department;
use Livewire\Attributes\On;
use App\Rules\FileSizeLimit;
use Livewire\Attributes\Url;
use App\Models\Applicantfile;
use Livewire\WithFileUploads;
use App\Livewire\Karyawanindexwr;
use App\Rules\AllowedFileExtension;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\RequiredIf;
use Google\Service\YouTube\ThirdPartyLinkStatus;
use Intervention\Image\ImageManagerStatic as Image;

class Updatekaryawanwr extends Component
{
    use WithFileUploads;

    public $id;
    public $id_karyawan, $nama, $email, $hp, $telepon, $tempat_lahir, $tanggal_lahir, $gender, $status_pernikahan, $golongan_darah, $agama, $etnis;
    public $jenis_identitas, $no_identitas, $alamat_identitas, $alamat_tinggal;
    public $status_karyawan, $tanggal_bergabung, $tanggal_resigned, $tanggal_blacklist,  $company_id, $placement_id,  $department_id, $jabatan_id, $level_jabatan, $nama_bank, $nomor_rekening;
    public $gaji_pokok, $gaji_overtime, $gaji_shift_malam_satpam, $metode_penggajian,  $bonus, $tunjangan_jabatan, $tunjangan_bahasa;
    public $tunjangan_skill, $tunjangan_lembur_sabtu, $tunjangan_lama_kerja,  $iuran_air, $iuran_locker, $denda, $gaji_bpjs, $potongan_JHT, $potongan_JP, $potongan_JKK, $potongan_JKM;
    public  $potongan_kesehatan, $update;
    public  $no_npwp, $ptkp, $status_off;
    public $kontak_darurat, $kontak_darurat2, $hp1, $hp2, $hubungan1, $hubungan2;

    public $tanggungan, $id_file_karyawan;
    public $show_arsip, $personal_files;
    // public $files = [];
    // public $filenames = [];
    public $is_update;
    public $pilih_jabatan;
    public $pilih_company;
    public $pilih_department;
    public $pilih_placement;
    public $delete_id;
    public $folder_name;
    public $is_folder_kosong = false;

    public $ktp = [], $kk = [], $ijazah = [], $nilai = [], $cv = [], $pasfoto = [];
    public $npwp = [], $paklaring = [], $bpjs = [], $skck = [], $sertifikat = [], $bri = [];
    public $applicant_id;
    public $jobgrades;




    public function deleteFile($id)
    {
        // $id = $this->delete_id;
        // $data = Applicantfile::where('filename', $filename)->first();
        $data = Applicantfile::find($id);
        if ($data != null) {
            try {
                // $result = Storage::disk('google')->delete($data->filename);
                $result = Storage::disk('s3')->delete($data->filename);
                if ($result) {
                    // File was deleted successfully
                    $data->delete();
                    // $this->dispatch('success', message: 'File telah di delete');
                    $this->dispatch(
                        'message',
                        type: 'success',
                        title: 'File telah di delete',
                        position: 'center'
                    );
                    $this->check_isi_dokumen();

                    return 'File deleted successfully.';
                } else {
                    // File could not be deleted
                    // return 'Failed to delete file.';


                    // $this->dispatch('error', message: 'File GAGAL di delete');
                    $this->check_isi_dokumen();

                    $this->dispatch(
                        'message',
                        type: 'error',
                        title: 'File GAGAL di delete',
                        position: 'center'
                    );
                }
            } catch (\Exception $e) {

                // An error occurred while deleting the file
                return 'An error occurred: ' . $e->getMessage();
            }
        } else {
            // $this->dispatch('error', message: 'File tidak ketemu');
            $this->check_isi_dokumen();

            $this->dispatch(
                'message',
                type: 'error',
                title: 'File tidak ketemu',
                position: 'center'
            );
        }
    }



    // public function updatedFiles()
    // {
    //     $this->validate(
    //         [
    //             // 'files.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
    //             'files.*' => ['nullable', new AllowedFileExtension],
    //         ],
    //         // [
    //         //     'files.*.mimes' => ['Hanya menerima file png dan jpg'],
    //         // ]
    //     );
    // }





    public function arsip()
    {
        $this->show_arsip = true;
    }

    public function tutup_arsip()
    {
        $this->show_arsip = false;
    }

    public function mount($id)
    {
        $this->jobgrades = Jobgrade::orderBy('grade', 'asc')->get();

        $this->pilih_jabatan = Jabatan::orderBy('nama_jabatan', 'asc')->get();
        $this->pilih_company = Company::orderBy('company_name', 'asc')->get();
        $this->pilih_department = Department::orderBy('nama_department', 'asc')->get();
        $this->pilih_placement = Placement::orderBy('placement_name', 'asc')->get();



        $this->is_update = true;
        $this->show_arsip = false;
        $this->status_off = false;
        $this->update = true;
        $this->id = $id;
        $data = Karyawan::find($id);
        $this->id_karyawan = $data->id_karyawan;
        $this->nama = $data->nama;
        $this->email = trim($data->email);
        $this->hp = $data->hp;
        $this->telepon = $data->telepon;
        $this->tempat_lahir = $data->tempat_lahir;
        //  $this->tanggal_lahir = $data->tanggal_lahir;
        $this->tanggal_lahir =  date('d M Y', strtotime($data->tanggal_lahir));

        $this->gender = $data->gender;
        $this->status_pernikahan = trim($data->status_pernikahan);
        $this->golongan_darah = trim($data->golongan_darah);
        $this->agama = trim($data->agama);
        $this->etnis = trim($data->etnis);
        $this->kontak_darurat = trim($data->kontak_darurat);
        $this->kontak_darurat2 = trim($data->kontak_darurat2);
        $this->hp1 = trim($data->hp1);
        $this->hp2 = trim($data->hp2);
        $this->hubungan1 = trim($data->hubungan1);
        $this->hubungan2 = trim($data->hubungan2);


        // Identitas
        $this->jenis_identitas = trim($data->jenis_identitas);
        $this->no_identitas = $data->no_identitas;
        $this->alamat_identitas = $data->alamat_identitas;
        $this->alamat_tinggal = $data->alamat_tinggal;

        //Data Kepegawaian
        $this->status_karyawan = trim($data->status_karyawan);
        $this->tanggal_bergabung =  date('d M Y', strtotime($data->tanggal_bergabung));
        $this->tanggal_resigned = $data->tanggal_resigned;
        $this->tanggal_blacklist = $data->tanggal_blacklist;

        $this->company_id = $data->company_id;
        $this->placement_id = $data->placement_id;
        $this->department_id = $data->department_id;
        $this->jabatan_id = $data->jabatan_id;

        if ($this->jabatan_id == 100) {
            $this->jabatan_id = '';
        }
        if ($this->company_id == 100) {
            $this->company_id = '';
        }
        if ($this->department_id == 100) {
            $this->department_id = '';
        }
        if ($this->placement_id == 100) {
            $this->placement_id = '';
        }
        $this->level_jabatan = trim($data->level_jabatan);
        $this->nama_bank = trim($data->nama_bank);
        $this->nomor_rekening = $data->nomor_rekening;

        //Payroll
        $this->metode_penggajian = trim($data->metode_penggajian);
        //  $this->gaji_pokok = $data->gaji_pokok;
        $this->gaji_pokok = $data->gaji_pokok;
        $this->gaji_overtime = $data->gaji_overtime;
        $this->gaji_shift_malam_satpam = $data->gaji_shift_malam_satpam;
        $this->bonus = $data->bonus;
        $this->tunjangan_jabatan = $data->tunjangan_jabatan;
        $this->tunjangan_bahasa = $data->tunjangan_bahasa;
        $this->tunjangan_skill = $data->tunjangan_skill;
        $this->tunjangan_lembur_sabtu = $data->tunjangan_lembur_sabtu;
        $this->tunjangan_lama_kerja = $data->tunjangan_lama_kerja;
        $this->iuran_air = $data->iuran_air;
        $this->denda = $data->denda;
        $this->iuran_locker = $data->iuran_locker;
        $this->gaji_bpjs = $data->gaji_bpjs;
        $this->potongan_JHT = $data->potongan_JHT;
        $this->potongan_JP = $data->potongan_JP;
        $this->potongan_JKK = $data->potongan_JKK;
        $this->potongan_JKM = $data->potongan_JKM;
        $this->potongan_kesehatan = $data->potongan_kesehatan;
        $this->tanggungan = $data->tanggungan;
        $this->no_npwp = $data->no_npwp;
        $this->ptkp = $data->ptkp;
        $this->id_file_karyawan = $data->id_file_karyawan;

        // data Applicant files
        // $this->personal_files = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->get();
        // Cek applicant id apakah sudah ada 
        // ggg
        if ($data->id_file_karyawan == '') {
            $this->applicant_id = makeApplicationId($data->nama, $data->tanggal_lahir);
            $data->id_file_karyawan = $this->applicant_id;
            $data->save();
            $this->is_folder_kosong = true;

            // DataApplicant::create([
            //     'applicant_id' => $this->applicant_id;
            // ]);
        } else {

            $this->applicant_id =  $data->id_file_karyawan;
            $isiFolder = Applicantfile::where('id_karyawan', $this->applicant_id)->count();

            if ($isiFolder < 1) $this->is_folder_kosong = true;
        }
    }

    public function check_isi_dokumen()
    {
        $isiFolder = Applicantfile::where('id_karyawan', $this->applicant_id)->count();
        if ($isiFolder < 1) {
            return $this->is_folder_kosong = true;
        }
        return $this->is_folder_kosong = false;
    }

    public function messages()
    {
        return [

            'ktp.*.image' => 'File KTP harus berupa gambar.',
            'ktp.*.mimes' => 'File KTP harus dalam format: jpeg, png, jpg.',
            'ktp.*.max' => 'Ukuran file KTP maksimal 2MB.',
            'kk.*.image' => 'File kartu keluarga harus berupa gambar.',
            'kk.*.mimes' => 'File kartu keluarga harus dalam format: jpeg, png, jpg.',
            'kk.*.max' => 'Ukuran file kartu keluarga maksimal 2MB.',
            'ijazah.*.image' => 'File IJAZAH harus berupa gambar.',
            'ijazah.*.mimes' => 'File IJAZAH harus dalam format: jpeg, png, jpg.',
            'ijazah.*.max' => 'Ukuran file IJAZAH maksimal 2MB.',
            'nilai.*.image' => 'File NILAI harus berupa gambar.',
            'nilai.*.mimes' => 'File NILAI harus dalam format: jpeg, png, jpg.',
            'nilai.*.max' => 'Ukuran file NILAI maksimal 2MB.',
            'cv.*.image' => 'File CV harus berupa gambar.',
            'cv.*.mimes' => 'File CV harus dalam format: jpeg, png, jpg.',
            'cv.*.max' => 'Ukuran file CV maksimal 2MB.',
            'pasfoto.*.image' => 'File PASFOTO harus berupa gambar.',
            'pasfoto.*.mimes' => 'File PASFOTO harus dalam format: jpeg, png, jpg.',
            'pasfoto.*.max' => 'Ukuran file PASFOTO maksimal 2MB.',
            'npwp.*.image' => 'File NPWP harus berupa gambar.',
            'npwp.*.mimes' => 'File NPWP harus dalam format: jpeg, png, jpg.',
            'npwp.*.max' => 'Ukuran file NPWP maksimal 2MB.',
            'paklaring.*.image' => 'File PAKLARING harus berupa gambar.',
            'paklaring.*.mimes' => 'File PAKLARING harus dalam format: jpeg, png, jpg.',
            'paklaring.*.max' => 'Ukuran file PAKLARING maksimal 2MB.',
            'bpjs.*.image' => 'File BPJS harus berupa gambar.',
            'bpjs.*.mimes' => 'File BPJS harus dalam format: jpeg, png, jpg.',
            'bpjs.*.max' => 'Ukuran file BPJS maksimal 2MB.',
            'skck.*.image' => 'File SKCK harus berupa gambar.',
            'skck.*.mimes' => 'File SKCK harus dalam format: jpeg, png, jpg.',
            'skck.*.max' => 'Ukuran file SKCK maksimal 2MB.',
            'sertifikat.*.image' => 'File SERTIFIKAT harus berupa gambar.',
            'sertifikat.*.mimes' => 'File SERTIFIKAT harus dalam format: jpeg, png, jpg.',
            'sertifikat.*.max' => 'Ukuran file SERTIFIKAT maksimal 2MB.',
            'bri.*.image' => 'File BRI harus berupa gambar.',
            'bri.*.mimes' => 'File BRI harus dalam format: jpeg, png, jpg.',
            'bri.*.max' => 'Ukuran file BRI maksimal 2MB.',
        ];
    }

    // Cara benerin email unique agar bisa di update

    public function rules()
    {
        return [
            'id_karyawan' => 'required',
            'nama' => 'required',
            'email' => 'email|nullable|unique:karyawans,email,' . $this->id,
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
            'kontak_darurat2' => 'nullable',
            'hp1' => 'nullable',
            'hp2' => 'nullable',
            'hubungan1' => 'nullable',
            'hubungan2' => 'nullable',


            // IDENTITAS
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'alamat_identitas' => 'required',
            'alamat_tinggal' => 'required',
            // KEPEGAWAIAN
            'status_karyawan' => 'required',
            'tanggal_resigned' => new RequiredIf($this->status_karyawan == 'Resigned'),
            'tanggal_blacklist' => new RequiredIf($this->status_karyawan == 'Blacklist'),
            'tanggal_bergabung' => 'date|required',
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
            // Uploads
            'ktp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ];
    }

    public function updatedKtp()
    {


        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedKk()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedIjazah()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedNilai()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedCv()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedPasfoto()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedNpwp()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedPaklaring()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedBpjs()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedSkck()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedSertifikat()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    public function updatedBri()
    {
        $this->validate([
            // 'kk' => 'required|image|max:2048'
            'ktp.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'kk.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'ijazah.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'nilai.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'cv.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'pasfoto.*' => ['required', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'skck.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],
            'bri.*' => ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension],

        ]);
    }
    private function processAndStoreFiles($files, $folderName)
    {
        // dd($files, $folderName);
        if (!$files || count($files) === 0) {
            return;
        }

        $manager = ImageManager::gd();
        $folderPath = "Applicants/{$this->applicant_id}/";

        // Ambil jumlah file yang sudah ada di storage dengan prefix yang sesuai
        $existingFiles = Storage::disk('s3')->files($folderPath);
        $existingNumbers = [];

        // Ambil nomor dari nama file yang ada (misal: "ktp-01.webp" → 1)
        foreach ($existingFiles as $file) {
            if (preg_match("/{$folderName}-(\d+)\./", $file, $matches)) {
                $existingNumbers[] = (int) $matches[1];
            }
        }

        // Mulai counter dari angka tertinggi yang sudah ada + 1
        $counter = empty($existingNumbers) ? 1 : (max($existingNumbers) + 1);

        foreach ($files as $file) {
            $fileExtension = $file->getClientOriginalExtension();

            // Pastikan tidak ada nama yang sama dengan perulangan while
            do {
                $fileName = "{$folderName}-" . str_pad($counter, 2, '0', STR_PAD_LEFT) . ".{$fileExtension}";
                $filePath = "{$folderPath}{$fileName}";
                $counter++;
            } while (Storage::disk('s3')->exists($filePath));

            // Resize dan konversi gambar ke WebP
            $image = $manager->read($file)->scale(width: 800);
            $imageData = (string) $image->toWebp(60);

            // Simpan ke storage
            // Storage::disk('s3')->put($filePath, $imageData);
            Storage::disk('s3')->put($filePath, $imageData, [
                'visibility' => 'public'
            ]);

            // Simpan informasi file ke database
            Applicantfile::create([
                'id_karyawan' => $this->applicant_id,
                'originalName' => clear_dot($file->getClientOriginalName(), $fileExtension),
                'filename' => $filePath,
            ]);
        }
    }

    public function uploadfile()
    {
        dd('sini');
        $this->validate([
            'files.*' =>  ['nullable', 'mimes:png,jpg,jpeg', new AllowedFileExtension]
        ], [
            'files.*.mimes' => ['Hanya menerima file png dan jpg'],
        ]);
        if ($this->files) {
            if (!$this->id_file_karyawan) {
                // convertTgl adalah fungsi untuk merubah format tanggal menjadi format sesuai system
                $this->id_file_karyawan = makeApplicationId($this->nama, convertTgl($this->tanggal_lahir));
                $data = Karyawan::find($this->id);
                $data->id_file_karyawan = $this->id_file_karyawan;
                dd($this->id_file_karyawan);
                $data->save();
            }

            foreach ($this->files as $file) {
                $folder = 'Applicants/' . $this->id_file_karyawan;
                $fileExension = $file->getClientOriginalExtension();

                if ($fileExension != 'pdf') {
                    $folder = 'Applicants/' . $this->id_file_karyawan . '/' . random_int(1000, 9000) . '.' . $fileExension;

                    $manager = ImageManager::gd();

                    // resize gif image
                    $image = $manager
                        ->read($file)
                        ->scale(width: 800);
                    // $imagedata = (string) $image->toJpeg();
                    $imagedata = (string) $image->toWebp(60);

                    // Storage::disk('google')->put($folder, $imagedata);
                    Storage::disk('public')->put($folder, $imagedata);
                    $this->path = $folder;
                } else {
                    // $this->path = Storage::disk('google')->put($folder, $file);
                    $this->path = Storage::disk('public')->put($folder, $file);
                }

                $this->originalFilename = $file->getClientOriginalName();
                Applicantfile::create([
                    'id_karyawan' => $this->id_file_karyawan,
                    'originalName' => clear_dot($this->originalFilename, $fileExension),
                    'filename' => $this->path,
                ]);
            }
            $this->files = [];
            // $this->dispatch('success', message: 'file berhasil di upload');
            $this->is_folder_kosong = false;
            $this->dispatch(
                'message',
                type: 'success',
                title: 'file berhasil di upload',
                position: 'center'
            );
        }
    }


    public function update1()
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
        $data = Karyawan::find($this->id);

        // $data->id_karyawan = $this->id_karyawan;
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


        // Identitas
        $data->jenis_identitas = $this->jenis_identitas;
        $data->no_identitas = $this->no_identitas;
        $data->alamat_identitas = titleCase($this->alamat_identitas);
        $data->alamat_tinggal = titleCase($this->alamat_tinggal);
        // Data Kepegawaian
        $data->status_karyawan = $this->status_karyawan;
        $data->tanggal_bergabung = $this->tanggal_bergabung;
        if ($this->status_karyawan == 'Resigned') {
            $data->tanggal_blacklist = null;
            $data->tanggal_resigned = $this->tanggal_resigned;
            $data->email = 'resigned_' . trim($this->email);
        } elseif ($this->status_karyawan == 'Blacklist') {

            $data->tanggal_resigned = null;
            $data->tanggal_blacklist = $this->tanggal_blacklist;
            $data->email = 'blacklist_' . trim($this->email);
        } else {
            $data->tanggal_blacklist = null;
            $data->tanggal_resigned = null;
        }

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
        $data->denda = $this->denda;
        // $this->id_file_karyawan = makeApplicationId($this->nama, convertTgl($this->tanggal_lahir));
        if ($data->id_file_karyawan == '') {
            $data->id_file_karyawan = makeApplicationId($data->nama, convertTgl($data->tanggal_lahir));
        }
        $this->id_file_karyawan = $data->id_file_karyawan;

        $data->save();


        $dataUser = User::where('username', $data->id_karyawan)->first();
        // if ( $dataUser->id != null ) {
        if ($dataUser->id) {
            $user = User::find($dataUser->id);
            $user->name = titleCase($this->nama);
            $user->email = trim($this->email, ' ');
            $user->save();
            $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
            $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
            // $this->dispatch('success', message: 'Data Karyawan Sudah di Update');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Karyawan Sudah di Update',
                position: 'center'
            );
        } else {
            $this->tanggal_lahir = date('d M Y', strtotime($this->tanggal_lahir));
            $this->tanggal_bergabung = date('d M Y', strtotime($this->tanggal_bergabung));
            // $this->dispatch('info', message: 'Data Karyawan Sudah di Update, User tidak terupdate');
            $this->dispatch(
                'message',
                type: 'success',
                title: 'Data Karyawan Sudah di Update, User tidak terupdate',
                position: 'center'
            );
        }


        // upload dokumen
        $documents = [
            // 'files' => "Applicants/{$this->applicant_id}",
            'ktp' => 'ktp',
            'kk' => 'kartu_keluarga',
            'ijazah' => 'ijazah',
            'nilai' => 'nilai',
            'cv' => 'cv',
            'pasfoto' => 'pasfoto',
            'npwp' => 'npwp',
            'paklaring' => 'paklaring',
            'bpjs' => 'bpjs',
            'skck' => 'skck',
            'sertifikat' => 'sertifikat',
            'bri' => 'bri'
        ];

        // Proses dokumen secara otomatis
        foreach ($documents as $property => $folder) {
            if (!empty($this->$property)) {
                $this->processAndStoreFiles($this->$property, $folder);
            }
        }

        // Dispatch pesan sukses


        $this->ktp = '';
        $this->kk = '';
        $this->ijazah = '';
        $this->nilai = '';
        $this->cv = '';
        $this->pasfoto = '';
        $this->npwp = '';
        $this->paklaring = '';
        $this->bpjs = '';
        $this->skck = '';
        $this->sertifikat = '';
        $this->bri = '';
        $this->check_isi_dokumen();
        $this->personal_files = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->get();

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Anda sudah berhasil diupdate',
        );
    }


    public function exit()
    {
        // $this->reset();
        return redirect()->to('/karyawanindex');
    }

    public function render()
    {
        // $this->personal_files = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->get();
        // $data = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->first();
        // $this->folder_name = $data->id_karyawan;

        $this->personal_files = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->get();


        $order = ['ktp', 'kk', 'ijazah', 'nilai', 'cv', 'pasfoto', 'npwp', 'paklaring', 'bpjs', 'skck', 'sertifikat', 'bri'];



        $this->personal_files = $this->personal_files->sortBy(function ($file) use ($order) {
            foreach ($order as $index => $keyword) {
                if (stripos($file->filename, $keyword) !== false) {
                    return $index;
                }
            }
            return count($order); // Jika tidak ada di daftar, letakkan di akhir
        });




        $data = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->first();

        if ($data) {
            $this->folder_name = $data->id_karyawan;
        } else {
            $this->folder_name = 'default-folder'; // Atau beri nilai default

        }

        return view('livewire.updatekaryawanwr')
            ->layout('layouts.appeloealpine');
    }
}
