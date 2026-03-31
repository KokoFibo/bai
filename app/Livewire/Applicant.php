<?php

namespace App\Livewire;

use Livewire\Component;
use App\Rules\FileSizeLimit;
use Illuminate\Http\Request;
use App\Models\Applicantdata;
use App\Models\Applicantfile;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Rules\AllowedFileExtension;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Applicant extends Component
{
    use WithFileUploads;
    // #[Validate('image|max:1024')]
    public $files = [];
    public $filenames = [];

    public $is_registered, $show, $showMenu, $showSubmit, $registeredEmail, $registeredPassword, $is_update;
    public $nama, $email, $password, $confirm_password, $hp, $telp, $tempat_lahir, $tgl_lahir, $gender;
    public $status_pernikahan, $golongan_darah, $agama, $etnis, $ptkp;
    public  $nama_contact_darurat, $contact_darurat_1, $hubungan_1;
    public $nama_contact_darurat_2, $contact_darurat_2, $hubungan_2;
    public $jenis_identitas, $no_identitas;
    public $alamat_identitas, $alamat_tinggal_sekarang;
    public $applicant_id, $originalName, $filename;
    public $toggle_eye_password;
    public $id;
    public $ktp = [], $kk = [], $ijazah = [], $nilai = [], $cv = [], $pasfoto = [];
    public $npwp = [], $paklaring = [], $bpjs = [], $skck = [], $sertifikat = [], $bri = [];


    public function toggleEyePassword()
    {
        $this->toggle_eye_password = !$this->toggle_eye_password;
    }


    public function deleteFile($filename)
    {
        try {
            // Cari file dalam database, jika tidak ada, langsung lempar error
            $data = Applicantfile::where('filename', $filename)->firstOrFail();

            // Hapus file dari storage
            if (Storage::disk('s3')->delete($data->filename)) {
                // Hapus record di database
                $data->delete();

                // Beri notifikasi sukses
                $this->dispatch('message', type: 'success', title: 'File berhasil dihapus.');
                return;
            }

            // Jika gagal menghapus dari storage
            $this->dispatch('message', type: 'error', title: 'Gagal menghapus file dari penyimpanan.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Jika file tidak ditemukan dalam database
            $this->dispatch('message', type: 'error', title: 'File tidak ditemukan.');
        } catch (\Exception $e) {
            // Tangani error lain yang tidak terduga
            $this->dispatch('message', type: 'error', title: 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function submit()
    {

        $this->validate([
            'registeredEmail' => 'required|email',
            'registeredPassword' => 'required|min:6',
        ], [
            'registeredEmail.required' => 'Email wajib diisi',
            'registeredEmail.email' => 'Format email harus benar',
            'registeredPassword.required' => 'Password wajib diisi',
            'registeredPassword.min' => 'Password minimal 6 karakter',
        ]);
        $data = Applicantdata::where('email', $this->registeredEmail)->where('password', $this->registeredPassword)->first();
        if ($data != null) {
            $file_data = Applicantfile::where('id_karyawan', $data->applicant_id)->get();
            // dd($file_data);
            $this->filenames = $file_data;
            $this->showMenu = false;
            $this->show = true;
            //    ==============================
            $this->id = $data->id;
            $this->applicant_id = $data->applicant_id;
            $this->nama = $data->nama;
            $this->email = $data->email;
            $this->password = $data->password;
            $this->confirm_password = $data->password;
            $this->hp = $data->hp;
            $this->telp = $data->telp;
            $this->tempat_lahir = $data->tempat_lahir;
            $this->tgl_lahir = $data->tgl_lahir;
            $this->gender = $data->gender;
            $this->status_pernikahan = $data->status_pernikahan;
            $this->golongan_darah = $data->golongan_darah;
            $this->agama = $data->agama;
            $this->etnis = $data->etnis;
            $this->ptkp = $data->ptkp;
            $this->nama_contact_darurat = $data->nama_contact_darurat;
            $this->nama_contact_darurat_2 = $data->nama_contact_darurat_2;
            $this->contact_darurat_1 = $data->contact_darurat_1;
            $this->contact_darurat_2 = $data->contact_darurat_2;
            $this->hubungan_1 = $data->hubungan_1;
            $this->hubungan_2 = $data->hubungan_2;
            $this->jenis_identitas = $data->jenis_identitas;
            $this->no_identitas = $data->no_identitas;
            $this->alamat_identitas = $data->alamat_identitas;
            $this->alamat_tinggal_sekarang = $data->alamat_tinggal_sekarang;

            //    ==============================
            $this->showSubmit = false;
        } else {
            // $this->dispatch('error', message: 'Email atau password salah');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Email atau password salah',
            );
            $this->showSubmit = true;
        }
        $this->is_update = true;
    }



    public function messages()
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'confirm_password.required' => 'Confirm Password wajib diisi.',
            'hp.required' => 'Handphone wajib diisi.',
            'telp.required' => 'Telepon wajib diisi.',
            'tempat_lahir.required' => 'Kota Kelahiran wajib diisi.',
            'tgl_lahir.required' => 'Tanggal Lahir wajib diisi.',
            'gender.required' => 'Gender wajib diisi.',
            'status_pernikahan.required' => 'Status Pernikahan wajib diisi.',
            'golongan_darah.required' => 'Golongan Darah wajib diisi.',
            'agama.required' => 'Agama wajib diisi.',
            'etnis.required' => 'Etnis wajib diisi.',
            'ptkp.required' => 'PTKP wajib diisi.',
            'nama_contact_darurat.required' => 'Nama Kontak 1 Darurat wajib diisi.',
            'nama_contact_darurat_2.required' => 'Nama Kontak 2 Darurat wajib diisi.',
            'hubungan_1.required' => 'Hubungan 1 Kontak Darurat wajib diisi.',
            'hubungan_2.required' => 'Hubungan 2 Kontak Darurat wajib diisi.',
            'contact_darurat_1.required' => 'Kontak Darurat 1 wajib diisi.',
            'contact_darurat_2.required' => 'Kontak Darurat 2 wajib diisi.',
            'contact_darurat_1.min' => 'Kontak Darurat 1 minimal 10 karakter.',
            'contact_darurat_2.min' => 'Kontak Darurat 2 minimal 10 karakter.',
            'jenis_identitas.required' => 'Jenis Identitas wajib diisi.',
            'no_identitas.required' => 'No Identitas wajib diisi.',
            'alamat_identitas.required' => 'Alamat Identitas wajib diisi.',
            'alamat_tinggal_sekarang.required' => 'Alamat tinggal tekarang wajib diisi.',
            'files.*.max' => 'Max file size 1Mb',
            'ktp.*.required' => 'File KTP wajib diunggah.',
            'ktp.*.image' => 'File KTP harus berupa gambar.',
            'ktp.*.max' => 'Ukuran file KTP maksimal 2MB.',
            'kk.*.required' => 'File kartu keluarga wajib diunggah.',
            'kk.*.image' => 'File kartu keluarga harus berupa gambar.',
            'kk.*.max' => 'Ukuran file kartu keluarga maksimal 2MB.',
            'ijazah.*.required' => 'File IJAZAH wajib diunggah.',
            'ijazah.*.image' => 'File IJAZAH harus berupa gambar.',
            'ijazah.*.max' => 'Ukuran file IJAZAH maksimal 2MB.',
            'nilai.*.required' => 'File NILAI wajib diunggah.',
            'nilai.*.image' => 'File NILAI harus berupa gambar.',
            'nilai.*.max' => 'Ukuran file NILAI maksimal 2MB.',
            'cv.*.required' => 'File CV wajib diunggah.',
            'cv.*.image' => 'File CV harus berupa gambar.',
            'cv.*.max' => 'Ukuran file CV maksimal 2MB.',
            'pasfoto.*.required' => 'File PASFOTO wajib diunggah.',
            'pasfoto.*.image' => 'File PASFOTO harus berupa gambar.',
            'pasfoto.*.max' => 'Ukuran file PASFOTO maksimal 2MB.',
            'npwp.*.required' => 'File NPWP wajib diunggah.',
            'npwp.*.image' => 'File NPWP harus berupa gambar.',
            'npwp.*.max' => 'Ukuran file NPWP maksimal 2MB.',
            'paklaring.*.required' => 'File PAKLARING wajib diunggah.',
            'paklaring.*.image' => 'File PAKLARING harus berupa gambar.',
            'paklaring.*.max' => 'Ukuran file PAKLARING maksimal 2MB.',
            'bpjs.*.required' => 'File BPJS wajib diunggah.',
            'bpjs.*.image' => 'File BPJS harus berupa gambar.',
            'bpjs.*.max' => 'Ukuran file BPJS maksimal 2MB.',
            'skck.*.required' => 'File SKCK wajib diunggah.',
            'skck.*.image' => 'File SKCK harus berupa gambar.',
            'skck.*.max' => 'Ukuran file SKCK maksimal 2MB.',
            'sertifikat.*.required' => 'File SERTIFIKAT wajib diunggah.',
            'sertifikat.*.image' => 'File SERTIFIKAT harus berupa gambar.',
            'sertifikat.*.max' => 'Ukuran file SERTIFIKAT maksimal 2MB.',
            'bri.*.required' => 'File BRI wajib diunggah.',
            'bri.*.image' => 'File BRI harus berupa gambar.',
            'bri.*.max' => 'Ukuran file BRI maksimal 2MB.',


            'nama.min' => 'Nama minimal 5 karakter.',
            'password.min' => 'Password minimal 6 karakter.',
            'hp.min' => 'Handphone minimal 10 karakter.',
            'telp.min' => 'Telepon minimal 9 karakter.',

            'confirm_password.min' => 'Konfirmasi Password minimal 6 karakter.',
            'confirm_password.same' => 'Konfirmasi Password Berbeda',
            'email.unique' => 'Email ini sudah terdaftar dalam database',
            'tgl_lahir.date' => 'Harus berupa format tanggal yang bear.',
            'tgl_lahir.before' => 'Tanggal Lahir anda salah.',

        ];
    }

    public function rules()
    {
        return [
            'nama' => 'required|min:2',
            'email' =>
            'required|unique:App\Models\Applicantdata,email,' . $this->id,
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
            'hp' => 'required|min:10',
            'telp' => 'required|min:9',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'date|before:today|required',
            'gender' => 'required',
            'status_pernikahan' => 'required',
            'golongan_darah' => 'required',
            'agama' => 'required',
            'etnis' => 'required',
            'ptkp' => 'required',
            'nama_contact_darurat' => 'required',
            'nama_contact_darurat_2' => 'required',
            'contact_darurat_1' => 'required|min:10',
            'contact_darurat_2' => 'required|min:10',
            'hubungan_1' => 'required',
            'hubungan_2' => 'required',

            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'alamat_identitas' => 'required',
            'alamat_tinggal_sekarang' => 'required',
            // 'files.*' =>  ['nullable',  new AllowedFileExtension, new FileSizeLimit(1024)]
            // 'files.*' =>  ['nullable',  new AllowedFileExtension]
            'files.*' => ['nullable', 'image', new AllowedFileExtension],
            'ktp.*' => ['nullable', 'image', new AllowedFileExtension],
            'kk.*' => ['nullable', 'image', new AllowedFileExtension],
            'ijazah.*' => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*' => ['nullable', 'image', new AllowedFileExtension],
            'cv.*' => ['nullable', 'image', new AllowedFileExtension],
            'pasfoto.*' => ['nullable', 'image', new AllowedFileExtension],
            'npwp.*' => ['nullable', 'image', new AllowedFileExtension],
            'paklaring.*' => ['nullable', 'image', new AllowedFileExtension],
            'bpjs.*' => ['nullable', 'image', new AllowedFileExtension],
            'skck.*' => ['nullable', 'image', new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image', new AllowedFileExtension],
            'bri.*' => ['nullable', 'image', new AllowedFileExtension],
            // public $ktp, $kk, $ijazah, $nilai, $cv, $pasfoto;
            // public $npwp, $paklaring, $bpjs, $skck, $sertifikat, $bri;
        ];
    }


    public function updatedKtp()
    {


        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedKk()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedIjazah()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedNilai()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedCv()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedPasfoto()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedNpwp()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedPaklaring()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedBpjs()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedSkck()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedSertifikat()
    {
        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    public function updatedBri()
    {

        $this->validate([
            'ktp.*'        => ['nullable', 'image',  new AllowedFileExtension], // max 4MB
            'kk.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'ijazah.*'     => ['nullable', 'image', new AllowedFileExtension],
            'nilai.*'      => ['nullable', 'image',  new AllowedFileExtension],
            'cv.*'         => ['nullable', 'image',  new AllowedFileExtension],
            'pasfoto.*'    => ['nullable', 'image',  new AllowedFileExtension],
            'npwp.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'paklaring.*'  => ['nullable', 'image',  new AllowedFileExtension],
            'bpjs.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'skck.*'       => ['nullable', 'image',  new AllowedFileExtension],
            'sertifikat.*' => ['nullable', 'image',  new AllowedFileExtension],
            'bri.*'        => ['nullable', 'image',  new AllowedFileExtension],
        ]);
    }
    // public $ktp = [], $kk = [], $ijazah = [], $nilai = [], $cv = [], $pasfoto = [];
    // public $npwp = [], $paklaring = [], $bpjs = [], $skck = [], $sertifikat = [], $bri = [];

    private function processAndStoreFiles($files, $folderName)
    {
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


    public function save()
    {
        $this->validate();
        $this->is_update = true;
        $this->applicant_id = makeApplicationId($this->nama, $this->tgl_lahir);

        // Proses semua file sekaligus
        $this->processAndStoreFiles($this->ktp, 'ktp');
        $this->processAndStoreFiles($this->kk, 'kartu_keluarga');
        $this->processAndStoreFiles($this->ijazah, 'ijazah');
        $this->processAndStoreFiles($this->nilai, 'nilai');
        $this->processAndStoreFiles($this->cv, 'cv');
        $this->processAndStoreFiles($this->pasfoto, 'pasfoto');
        $this->processAndStoreFiles($this->npwp, 'npwp');
        $this->processAndStoreFiles($this->paklaring, 'paklaring');
        $this->processAndStoreFiles($this->bpjs, 'bpjs');
        $this->processAndStoreFiles($this->skck, 'skck');
        $this->processAndStoreFiles($this->sertifikat, 'sertifikat');
        $this->processAndStoreFiles($this->bri, 'bri');

        // Simpan data pelamar
        $applicantData = Applicantdata::create([
            'applicant_id' => $this->applicant_id,
            'nama' => titleCase(trim($this->nama)),
            'email' => $this->email,
            'password' => $this->password,
            'hp' => $this->hp,
            'telp' => $this->telp,
            'tempat_lahir' => titleCase($this->tempat_lahir),
            'tgl_lahir' => $this->tgl_lahir,
            'gender' => $this->gender,
            'status_pernikahan' => $this->status_pernikahan,
            'golongan_darah' => $this->golongan_darah,
            'agama' => $this->agama,
            'etnis' => $this->etnis,
            'ptkp' => $this->ptkp,
            'nama_contact_darurat' => titleCase($this->nama_contact_darurat),
            'nama_contact_darurat_2' => titleCase($this->nama_contact_darurat_2),
            'contact_darurat_1' => $this->contact_darurat_1,
            'contact_darurat_2' => $this->contact_darurat_2,
            'hubungan_1' => titleCase($this->hubungan_1),
            'hubungan_2' => titleCase($this->hubungan_2),
            'jenis_identitas' => $this->jenis_identitas,
            'no_identitas' => $this->no_identitas,
            'alamat_identitas' => titleCase($this->alamat_identitas),
            'alamat_tinggal_sekarang' => titleCase($this->alamat_tinggal_sekarang),
            'status' => 1
        ]);

        $this->id = $applicantData->id;

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

        // Dispatch pesan sukses
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Anda sudah berhasil di submit',
        );

        // $this->files = [];
    }

    public function update()
    {
        $this->validate();

        // Data yang akan diperbarui
        $dataFields = [
            'nama',
            'email',
            'password',
            'hp',
            'telp',
            'tempat_lahir',
            'tgl_lahir',
            'gender',
            'status_pernikahan',
            'golongan_darah',
            'agama',
            'etnis',
            'ptkp',
            'nama_contact_darurat',
            'nama_contact_darurat_2',
            'contact_darurat_1',
            'contact_darurat_2',
            'hubungan_1',
            'hubungan_2',
            'jenis_identitas',
            'no_identitas',
            'alamat_identitas',
            'alamat_tinggal_sekarang'
        ];

        // Gunakan array_reduce untuk membangun array data dengan key-value yang benar
        $data = array_reduce($dataFields, function ($carry, $field) {
            $carry[$field] = in_array($field, ['nama', 'tempat_lahir', 'nama_contact_darurat', 'alamat_identitas', 'alamat_tinggal_sekarang'])
                ? titleCase($this->$field)
                : $this->$field;
            return $carry;
        }, []);

        // Simpan data pelamar
        Applicantdata::updateOrCreate(
            ['applicant_id' => $this->applicant_id],
            $data
        );

        // List dokumen yang akan diproses
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

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Anda sudah berhasil diupdate',
        );
    }














    public function mount()
    {
        $this->is_registered = false;
        $this->show = false;
        $this->showMenu = true;
        $this->is_update = false;
        $this->showSubmit = false;
        $this->toggle_eye_password = false;
    }

    public function alreadyRegistered()
    {

        $this->is_registered = true;
        $this->showMenu = false;
        $this->showSubmit = true;
    }
    public function register()
    {
        $this->is_registered = false;
        $this->show = true;
        $this->showMenu = false;
    }

    public function keluar()
    {
        $this->reset();
        $this->is_registered = false;
        $this->show = false;
        $this->showMenu = true;
    }



    public function updatedIsRegistered() {}
    public function render()
    {
        $file_data = Applicantfile::where('id_karyawan', $this->applicant_id)->get();
        // $file_data = Applicantfile::where('id_karyawan', 'john_doe_2006_07_18')->get();
        // $this->filenames = $file_data;
        $order = ['ktp', 'kk', 'ijazah', 'nilai', 'cv', 'pasfoto', 'npwp', 'paklaring', 'bpjs', 'skck', 'sertifikat', 'bri'];

        $this->filenames = $file_data->sortBy(function ($file) use ($order) {
            foreach ($order as $index => $keyword) {
                if (stripos($file->filename, $keyword) !== false) {
                    return $index;
                }
            }
            return count($order); // Jika tidak ada di daftar, letakkan di akhir
        });

        // $this->filenames = $file_data;
        // dd($this->filenames, $file_data);

        return view('livewire.applicant')->layout('layouts.newpolos');
    }
}
