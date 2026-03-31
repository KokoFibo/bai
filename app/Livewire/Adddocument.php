<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Applicantfile;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic as Image;
use App\Rules\AllowedFileExtension;
use Livewire\Attributes\On;
use App\Rules\FileSizeLimit;
use Livewire\Attributes\Url;
use Illuminate\Validation\Rules\RequiredIf;



class Adddocument extends Component
{
    use WithFileUploads;

    public $id_file_karyawan, $id_karyawan, $personal_files;
    public $ktp = [], $kk = [], $ijazah = [], $nilai = [], $cv = [], $pasfoto = [];
    public $npwp = [], $paklaring = [], $bpjs = [], $skck = [], $sertifikat = [], $bri = [];

    public $ktp_count, $kk_count, $ijazah_count, $nilai_count, $cv_count, $pasfoto_count;
    public $npwp_count, $paklaring_count, $bpjs_count, $skck_count, $sertifikat_count, $bri_count;
    public $is_folder = true;

    // setiap file punya format seperti ktp-01.jpg ktp-02-png, kartu_keluarga-01.png dst, saya mau agar bisa di urut berdasarkan 
    //      ktp , kartu_keluarga , ijazah , nilai , cv , pasfoto , npwp , paklaring , bpjs , skck , sertifikat , bri

    private function processAndStoreFiles($files, $folderName)
    {
        // dd($files, $folderName);
        if (!$files || count($files) === 0) {
            return;
        }

        $manager = ImageManager::gd();
        $folderPath = "Applicants/{$this->id_file_karyawan}/";

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
                'id_karyawan' => $this->id_file_karyawan,
                'originalName' => clear_dot($file->getClientOriginalName(), $fileExtension),
                'filename' => $filePath,
            ]);
        }
    }
    protected function loadPersonalFiles()
    {
        // Urutan prioritas kategori
        $order = [
            'ktp',
            'kartu_keluarga',
            'ijazah',
            'nilai',
            'cv',
            'pasfoto',
            'npwp',
            'paklaring',
            'bpjs',
            'skck',
            'sertifikat',
            'bri'
        ];

        // Ambil file dari database
        $files = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->get();

        // Urutkan file berdasarkan kategori di dalam filename
        $files = $files->sort(function ($a, $b) use ($order) {
            $nameA = pathinfo($a->filename, PATHINFO_FILENAME);
            $nameB = pathinfo($b->filename, PATHINFO_FILENAME);

            // Ambil kategori dari nama file
            $categoryA = explode('-', $nameA)[0];
            $categoryB = explode('-', $nameB)[0];

            // Cari posisi di daftar prioritas
            $indexA = array_search($categoryA, $order);
            $indexB = array_search($categoryB, $order);

            return ($indexA === false ? 999 : $indexA) <=> ($indexB === false ? 999 : $indexB);
        });

        // Simpan hasil yang sudah terurut
        $this->personal_files = $files->values();
    }





    public function mount()
    {
        $this->id_karyawan = auth()->user()->username;
        $user = Karyawan::where('id_karyawan', $this->id_karyawan)->first();

        if ($user->id_file_karyawan == '') {
            $user->id_file_karyawan = makeApplicationId($user->nama, $user->tanggal_lahir);
            $user->save();
        }

        $this->id_file_karyawan = $user->id_file_karyawan;
        $this->loadPersonalFiles();
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

    public function updatedFiles($propertyName)
    {
        $this->validate([
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

        $countableProperties = [
            'ktp',
            'kk',
            'ijazah',
            'nilai',
            'cv',
            'pasfoto',
            'npwp',
            'paklaring',
            'bpjs',
            'skck',
            'sertifikat',
            'bri'
        ];

        if (in_array($propertyName, $countableProperties)) {
            $countVar = "{$propertyName}_count";
            $this->$countVar = is_array($this->$propertyName) ? count($this->$propertyName) : 0;
        }
    }


    // Panggil fungsi ini dari setiap updatedNamaDokumen()
    public function updatedKtp()
    {
        $this->updatedFiles('ktp');
    }

    public function updatedKk()
    {
        $this->updatedFiles('kk');
    }

    public function updatedIjazah()
    {
        $this->updatedFiles('ijazah');
    }

    public function updatedNilai()
    {
        $this->updatedFiles('nilai');
    }

    public function updatedCv()
    {
        $this->updatedFiles('cv');
    }

    public function updatedPasfoto()
    {
        $this->updatedFiles('pasfoto');
    }

    public function updatedNpwp()
    {
        $this->updatedFiles('npwp');
    }

    public function updatedPaklaring()
    {
        $this->updatedFiles('paklaring');
    }

    public function updatedBpjs()
    {
        $this->updatedFiles('bpjs');
    }

    public function updatedSkck()
    {
        $this->updatedFiles('skck');
    }

    public function updatedSertifikat()
    {
        $this->updatedFiles('sertifikat');
    }

    public function updatedBri()
    {
        $this->updatedFiles('bri');
    }



    public function uploadfile()
    {
        $this->validate();

        // dd('save');
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

        $this->loadPersonalFiles();

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Anda sudah berhasil diupdate',
        );
    }



    public function deleteFile($id)
    {
        $data = Applicantfile::find($id);

        if ($data) {
            try {
                $result = Storage::disk('s3')->delete($data->filename);

                if ($result) {
                    $data->delete();
                    $this->dispatch(
                        'message',
                        type: 'success',
                        title: 'File telah di delete',
                        position: 'center'
                    );

                    // Refresh the files list
                    $this->loadPersonalFiles();
                } else {
                    $this->dispatch(
                        'message',
                        type: 'error',
                        title: 'File GAGAL di delete',
                        position: 'center'
                    );
                }
            } catch (\Exception $e) {
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: 'Error: ' . $e->getMessage(),
                    position: 'center'
                );
            }
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'File tidak ditemukan',
                position: 'center'
            );
        }
        $this->loadPersonalFiles();

        // $this->redirect('/adddocument');
        // $this->mount();
    }



    public function render()
    {
        $isiFolder = Applicantfile::where('id_karyawan', $this->id_file_karyawan)->count();
        $this->is_folder = false;
        if ($isiFolder > 0) $this->is_folder = true;
        return view('livewire.adddocument')->layout('layouts.polos');
    }
}
