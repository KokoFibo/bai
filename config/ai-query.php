<?php

return [
    'api_key' => env('GEMINI_API_KEY'),
    'model'   => env('GEMINI_MODEL'),

    // Sebaiknya pakai koneksi DB read-only (user MySQL hanya punya GRANT SELECT)
    'connection' => env('AI_QUERY_DB_CONNECTION', null),

    // WHITELIST tabel yang boleh dilihat AI. Jangan masukkan tabel sensitif.
    'allowed_tables' => [
        'karyawans',
        'yfrekappresensis',
        'jabatans',
        'departments',
        'companies',
        'placements',
    ],

    // Kolom sensitif: tidak dikirim ke AI, ditolak jika muncul di query, dan SELALU dibuang dari hasil
    // (termasuk saat AI memakai SELECT *). Tambahkan kolom lain sesuai kebutuhan, mis. 'gaji_pokok'.
    'hidden_columns' => [
        'password', 'remember_token', 'api_token',
        'jenis_identitas', 'no_identitas', 'no_npwp',
        'nomor_rekening', 'nama_bank',
    ],

    // Kolom yang hanya disembunyikan dari TAMPILAN hasil (nama harus persis sama).
    // Tetap boleh dipakai di JOIN/WHERE/ORDER BY. 'id_karyawan' tidak ikut tersembunyi.
    'hidden_output_columns' => ['id'],

    // Status karyawan yang ditampilkan secara default. Status lain (mis. Resigned, Blacklist)
    // hanya ditampilkan jika pengguna memintanya secara eksplisit.
    'default_statuses'  => ['PKWT', 'PKWTT', 'Dirumahkan'],
    'excluded_statuses' => ['Resigned', 'Blacklist'],

    // Pembatasan data gaji. User yang kolom `role_attribute`-nya bernilai salah satu dari `roles`
    // HANYA bisa melihat karyawan dengan `column` <= `max`. Dipaksa di sisi server (bukan hanya oleh AI).
    'salary_cap' => [
        'enabled'        => true,
        'roles'          => [5],          // role = 5 adalah admin
        'role_attribute' => 'role',       // kolom role di tabel users
        'table'          => 'karyawans',
        'column'         => 'gaji_pokok',
        'max'            => 4500000,      // 4,5 juta, batas ini ikut ditampilkan (<=)
    ],

    // Tabel referensi kecil (bukan data sensitif) yang isinya dikirim ke AI sebagai contoh,
    // supaya "company das" bisa dicocokkan ke nama perusahaan yang sebenarnya.
    'lookup_tables'      => ['companies', 'departments', 'jabatans', 'placements'],
    'lookup_sample_rows' => 50,

    // (Opsional) Relasi manual jika penamaan kolom tidak mengikuti pola <nama>_id / id_<nama>.
    // Relasi otomatis sudah dideteksi dari nama kolom.
    'relations' => [
        // 'karyawans.company_id' => 'companies.id',
        // Aktifkan jika user_id (int) di absensi memang mengacu ke id_karyawan (int) di karyawans:
        // 'yfrekappresensis.user_id' => 'karyawans.id_karyawan',
    ],

    // Petunjuk tambahan untuk AI (satu kalimat per baris). Tambahkan aturan bisnis Anda di sini.
    'hints' => [
        'karyawans.id adalah UUID. yfrekappresensis.karyawan_id mengacu ke karyawans.id.',
        'Istilah "Directorate" (atau "placement") merujuk ke tabel placements, kolom placement_name. Company = companies.company_name, Jabatan = jabatans.nama_jabatan, Department = departments.nama_department.',
        'Dalam penjelasan, gunakan istilah Company, Directorate, Jabatan, dan Department.',
        'Untuk kolom hasil perhitungan uang (SUM/AVG/MAX gaji dll.), beri alias yang mengandung kata gaji, tunjangan, atau bonus, mis. total_gaji, rata_rata_gaji.',
        'Jika diminta "data karyawan" tanpa rincian, tampilkan kolom ringkas: id_karyawan, nama, status_karyawan, tanggal_bergabung, Company, Directorate, Department, dan Jabatan (ambil namanya lewat JOIN).',
    ],

    // Judul kolom tabel. Kolom yang tidak ada di sini otomatis dirapikan:
    // underscore jadi spasi + huruf kapital di awal kata (total_jam_kerja -> Total Jam Kerja).
    'column_labels' => [
        'company_name'    => 'Company',
        'placement_name'  => 'Directorate',
        'nama_jabatan'    => 'Jabatan',
        'nama_department' => 'Department',
    ],
    // Singkatan yang ditulis kapital semua (id_karyawan -> ID Karyawan)
    'label_acronyms' => ['id', 'hp', 'hp1', 'hp2', 'npwp', 'ptkp', 'bpjs', 'jht', 'jp', 'jkk', 'jkm'],

    // Kolom nominal uang: angka ditampilkan dengan pemisah ribuan koma, tanpa desimal (5,000,000).
    // Sebuah kolom dianggap uang jika salah satu kata pada namanya (dipisah underscore) ada di money_keywords,
    // atau namanya ada persis di money_columns. Contoh: gaji_pokok, tunjangan_jabatan, total_gaji.
    'money_keywords' => ['gaji', 'tunjangan', 'bonus', 'iuran', 'denda', 'upah', 'thr', 'insentif', 'biaya', 'nominal', 'salary', 'pendapatan'],
    'money_columns'  => [],

    // null = tanpa batas, tampilkan semua baris sesuai isi database.
    // PERHATIAN: hasil sangat besar (puluhan ribu baris, mis. absensi) memakai banyak memori PHP.
    'max_rows' => null,

    // Batas waktu eksekusi query dalam milidetik (MySQL 5.7.8+/8). null/0 = tanpa batas.
    'query_timeout_ms' => 30000,
];
