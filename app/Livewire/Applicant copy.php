<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Applicantdata;
use App\Models\Applicantfile;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;

class Applicant extends Component
{
    use WithFileUploads;
    // #[Validate('image|max:1024')]
    public $files = [];
    public $filenames = [];

    public $is_registered, $show, $registeredEmail, $registeredPassword, $is_update;
    public $nama, $email, $password, $confirm_password, $hp, $telp, $tempat_lahir, $tgl_lahir, $gender;
    public $status_pernikahan, $golongan_darah, $agama, $etnis, $nama_contact_darurat;
    public $contact_darurat_1, $contact_darurat_2, $jenis_identitas, $no_identitas;
    public $alamat_identitas, $alamat_tinggal_sekarang;
    public $applicant_id, $originalName, $filename;

    public function deleteFile($filename)
    {
        try {
            $result = Storage::disk('google')->delete($filename);
            $result2 = Storage::disk('public')->delete($filename);
            dd($filename, $result2);
            if ($result && $result2) {
                // File was deleted successfully
                $this->dispatch('success', message: 'File telah di delete');

                return 'File deleted successfully.';
            } else {
                // File could not be deleted
                // return 'Failed to delete file.';
                $this->dispatch('errro', message: 'File GAGAL di delete');
            }
        } catch (\Exception $e) {
            // An error occurred while deleting the file
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    public function submit()
    {
        // validate submit
        // $data = Applicantdata::where('email', $this->registeredEmail)->where('password', $this->registeredPassword)->first();
        $data = Applicantdata::where('email', 'kokofibo@gmail.com')->where('password', '898989')->first();
        if ($data != null) {
            $file_data = Applicantfile::where('id_karyawan', $data->applicant_id)->get();
            $this->filenames = $file_data;
            $this->show = true;
            //    ==============================

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
            $this->nama_contact_darurat = $data->nama_contact_darurat;
            $this->contact_darurat_1 = $data->contact_darurat_1;
            $this->contact_darurat_2 = $data->contact_darurat_2;
            $this->jenis_identitas = $data->jenis_identitas;
            $this->no_identitas = $data->no_identitas;
            $this->alamat_identitas = $data->alamat_identitas;
            $this->alamat_tinggal_sekarang = $data->alamat_tinggal_sekarang;

            //    ==============================


        } else {
            dd('Email Salah atau password salah');
        }
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
            'nama_contact_darurat.required' => 'Nama Konta Darurat wajib diisi.',
            'contact_darurat_1.required' => 'Kontak Darurat 1 wajib diisi.',
            'jenis_identitas.required' => 'Jenis Identitas wajib diisi.',
            'no_identitas.required' => 'No Identitas wajib diisi.',
            'alamat_identitas.required' => 'Alamat Identitas wajib diisi.',
            'alamat_tinggal_sekarang.required' => 'Alamat tinggal tekarang wajib diisi.',
            'files.mimes' => 'Hanya menerima file png, jpg, jpeg dan pdf',
            'files.max' => 'Max file size 1Mb',

            'nama.min' => 'Nama minimal 5 karakter.',
            'password.min' => 'Password minimal 6 karakter.',
            'hp.min' => 'Handphone minimal 10 karakter.',
            'telp.min' => 'Telepon minimal 9 karakter.',
            'contact_darurat_1.min' => 'Kontak Darurat 1 minimal 10 karakter.',
            'contact_darurat_2.min' => 'Kontak Darurat 2 minimal 10 karakter.',
            'confirm_password.min' => 'Konfirmasi Password minimal 6 karakter.',
            'confirm_password.same' => 'Konfirmasi Password Berbeda',
            'email.unique' => 'Email ini sudah terdaftar dalam database'
        ];
    }

    public function rules()
    {
        return [
            'nama' => 'required|min:5',
            'email' => 'required|unique:App\Models\User,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6|same:password',
            'hp' => 'required|min:10',
            'telp' => 'required|min:9',
            'tempat_lahir' => 'required',
            'tgl_lahir' => 'required',
            'gender' => 'required',
            'status_pernikahan' => 'required',
            'golongan_darah' => 'required',
            'agama' => 'required',
            'etnis' => 'required',
            'nama_contact_darurat' => 'required',
            'contact_darurat_1' => 'required|min:10',
            'contact_darurat_2' => 'nullable|min:10',
            'jenis_identitas' => 'required',
            'no_identitas' => 'required',
            'alamat_identitas' => 'required',
            'alamat_tinggal_sekarang' => 'required',
            'files.*' => 'nullable|mimes:png,jpg,jpeg,pdf|max:1024'
            // 'files' => 'image|max:1024'
        ];
    }

    public function save()
    {
        $validated = $this->validate();



        if ($this->is_update == false) {

            $this->is_update = true;

            $this->applicant_id = makeApplicationId($this->nama, $this->tgl_lahir);
            // if ($this->files != null) {
            $folder = 'Applicants/' . $this->applicant_id;
            if ($this->files) {
                foreach ($this->files as $file) {

                    $fileExension = $file->getClientOriginalExtension();
                    if ($fileExension != 'pdf') {
                        $img = ImageManager::imagick()->read($file);
                        $img->resize(100, 100, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        // $resource = $img->toFilePointer()->detach();
                        // $resource = (string) $img->toJpeg();
                        $pointer = $img->toJpeg()->toFilePointer();
                        $this->path = Storage::disk('google')->put($folder, $pointer);
                        // $this->path = Storage::disk('public')->put($folder, $pointer);
                    } else {

                        // $this->path = $file->store($folder, 'google');
                        // $this->path = $file->store($folder, 'public');
                        $this->path = Storage::disk('google')->put($folder, $file);
                        $this->path = Storage::disk('public')->put($folder, $file);
                    }

                    $this->originalFilename = $file->getClientOriginalName();
                    Applicantfile::create([
                        'id_karyawan' => $this->applicant_id,
                        'originalName' => $this->originalFilename,
                        'filename' => $this->path,
                    ]);
                }
                Applicantdata::create([
                    'applicant_id' => $this->applicant_id,
                    'nama' => titleCase($this->nama),
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
                    'nama_contact_darurat' => titleCase($this->nama_contact_darurat),
                    'contact_darurat_1' => $this->contact_darurat_1,
                    'contact_darurat_2' => $this->contact_darurat_2,
                    'jenis_identitas' => $this->jenis_identitas,
                    'no_identitas' => $this->no_identitas,
                    'alamat_identitas' => titleCase($this->alamat_identitas),
                    'alamat_tinggal_sekarang' => titleCase($this->alamat_tinggal_sekarang),


                ]);


                $this->dispatch('success', message: 'Data Anda sudah berhasil di submit');



                // return response()->json(['success' => true]);
            } else {

                // Handle if no file is uploaded
                Applicantdata::create([
                    'applicant_id' => $this->applicant_id,
                    'nama' => titleCase($this->nama),
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                    'hp' => $this->hp,
                    'telp' => $this->telp,
                    'tempat_lahir' => titleCase($this->tempat_lahir),
                    'tgl_lahir' => $this->tgl_lahir,
                    'gender' => $this->gender,
                    'status_pernikahan' => $this->status_pernikahan,
                    'golongan_darah' => $this->golongan_darah,
                    'agama' => $this->agama,
                    'etnis' => $this->etnis,
                    'nama_contact_darurat' => titleCase($this->nama_contact_darurat),
                    'contact_darurat_1' => $this->contact_darurat_1,
                    'contact_darurat_2' => $this->contact_darurat_2,
                    'jenis_identitas' => $this->jenis_identitas,
                    'no_identitas' => $this->no_identitas,
                    'alamat_identitas' => titleCase($this->alamat_identitas),
                    'alamat_tinggal_sekarang' => titleCase($this->alamat_tinggal_sekarang),
                    // 'originalName' => $this->originalFilename,
                    // 'filename' => $this->path,

                ]);
                $this->dispatch('success', message: 'Data Anda sudah berhasil di submit tanpa file');
            }
        } else {
            dd('updates');
        }
    }

    public function mount()
    {
        $this->is_registered = false;
        $this->show = false;
        $this->is_update = false;
    }

    public function alreadyRegistered()
    {
        $this->is_registered = true;
        $this->show = false;
    }
    public function register()
    {
        $this->is_registered = false;
        $this->show = true;
    }

    public function updatedIsRegistered()
    {
    }
    public function render()
    {
        return view('livewire.applicant')->layout('layouts.newpolos');
    }
}
