<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\Applicantdata;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;








class ApplicantController extends Controller
{


    public function mergeFilesToPdf($folder)
    {
        $folderPath = "Applicants/{$folder}"; // Path folder di S3
        $files = Storage::disk('s3')->files($folderPath);
        $data = [];
        $tempFiles = []; // Menyimpan path file sementara

        // Ambil data karyawan berdasarkan id_file_karyawan
        $data_karyawan = Karyawan::where('id_file_karyawan', $folder)->first();
        $nama_karyawan = $data_karyawan ? $data_karyawan->nama : 'Nama Tidak Ditemukan';

        // Pastikan folder penyimpanan lokal ada
        $tempFolder = storage_path("app/public/temp/");
        if (!file_exists($tempFolder)) {
            mkdir($tempFolder, 0777, true);
        }

        foreach ($files as $file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $tempUrl = Storage::disk('s3')->temporaryUrl($file, now()->addMinutes(10));

            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                // Download file dari S3 dan simpan ke storage lokal sementara
                $contents = file_get_contents($tempUrl);
                $localPath = $tempFolder . basename($file);
                file_put_contents($localPath, $contents);
                $finalPath = $localPath;

                // Simpan ke array untuk dihapus nanti
                $tempFiles[] = $localPath;
            } else {
                $finalPath = $tempUrl; // Pakai URL langsung untuk teks
            }

            $data[] = [
                'name' => basename($file),
                'type' => in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? 'image' : 'text',
                'path' => $finalPath, // Path lokal kalau gambar
            ];
        }

        // Generate PDF dengan data yang sudah diproses
        $pdf = Pdf::loadView('pdf.merged-files', compact('folder', 'data', 'nama_karyawan'));
        $pdfOutput = $pdf->output();

        // Hapus semua file sementara setelah PDF dibuat
        foreach ($tempFiles as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }

        // Return PDF ke browser
        return response()->streamDownload(
            fn() => print($pdfOutput),
            "{$folder}.pdf"
        );
    }



    public function download($folder)
    {
        // Ambil data karyawan berdasarkan id_file_karyawan
        $data = Karyawan::where('id_file_karyawan', $folder)->first();
        $zip_nama = $data ? $data->nama : 'Data_Karyawan';

        // Path folder di S3
        $folderPath = "Applicants/{$folder}";

        // Nama file ZIP
        $zipFileName = "{$zip_nama}.zip";
        $zipFilePath = storage_path("app/temp/$zipFileName");

        // Pastikan folder temp ada
        if (!is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        // Ambil semua file dari folder S3
        $files = Storage::disk('s3')->files($folderPath);

        // Buat file ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                // Nama file dalam ZIP tanpa path folder
                $relativePath = basename($file);

                // Download file sementara dari S3
                $tempFile = storage_path("app/temp/{$relativePath}");
                file_put_contents($tempFile, Storage::disk('s3')->get($file));

                // Tambahkan ke ZIP
                $zip->addFile($tempFile, $relativePath);
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Could not create zip file'], 500);
        }

        // Kirim file ZIP untuk didownload dan hapus setelah dikirim
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([

            'email' => 'required|email',
            'password' => 'required|min:8'
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email harus benar',
            'password.required' => 'Passward wajib diisi',
            'password.min' => 'Password harus diisi minimal 8 karakter'
        ]);

        $infoLogin = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($infoLogin)) {
            dd('sukses');
        } else {
            dd('gagal');
        }
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|min:3',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.min' => 'Nama harus diisi minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email harus benar',
            'password.required' => 'Passward wajib diisi',
            'password.min' => 'Password harus diisi minimal 8 karakter'
        ]);

        Applicantdata::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return view('applicant.login')->with('success', 'Berhasil di register');
    }
}
