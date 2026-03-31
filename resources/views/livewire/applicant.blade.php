<div>
    @if ($showMenu)
        <div class='h-screen flex  flex-col  justify-center items-center px-5 mx-auto gap-10'>
            <div>
                <h3 class="text-4xl font-bold text-center mb-5">Formulir Pendaftaran Karyawan Baru</h3>
                <p class="text-lg mb-3">Selamat datang,</p>
                <p class="text-lg">jika anda belum pernah mendaftar sebelumnya, silakan pilih menu
                    "Register",</p>
                <p class="text-lg">
                    jika anda ingin merubah data atau mengupload file, silakan pilih menu "Sudah Pernah Register".
                </p>
                <p class="text-lg mb-3 mt-3">
                    Sebelum mulai register silakan siapkan terlebih softcopy dalam format jpg/png/pdf, untuk mempermudah
                    proses registrasi</p>
                <div class="flex flex-col gap-5 md:flex-row ">
                    <div>
                        <p class="text-xl mb-2 underline">Wajib:</p>
                        <ul>
                            <li class="text-lg">KTP / Pasport </li>
                            <li class="text-lg">Passfoto </li>
                            <li class="text-lg">CV</li>
                            <li class="text-lg">Ijasah</li>
                            <li class="text-lg">Transkip Nilai/SKHUN</li>
                            <li class="text-lg">Kartu Keluarga</li>
                        </ul>
                    </div>
                    <div>
                        <p class="text-xl mb-2 underline">Jika ada</p>
                        <ul>
                            <li class="text-lg">NPWP</li>
                            <li class="text-lg">Sertifikat</li>
                            <li class="text-lg">Paklaring (bagi yang pernah kerja)</li>
                            <li class="text-lg">kartu BPJS Ketenagakerjaan (Yang belum di klaim)</li>
                            <li class="text-lg">Paklaring (bagi yang pernah kerja)</li>
                            <li class="text-lg">Scan Buku tabungan depan untuk No Rekening BRI</li>
                        </ul>

                    </div>
                </div>







            </div>

            <div class="flex  lg:flex-row flex-col w-full justify-center items-center  gap-3">
                <button
                    class="text-lg lg:text-2xl hover:bg-green-700 bg-green-500 text-white px-3 py-2 rounded-xl w-full lg:h-20"
                    wire:click="register">Register</button>
                <button
                    class="text-lg lg:text-2xl hover:bg-blue-700 bg-blue-500 text-white px-3 py-2 rounded-xl w-full lg:h-20"
                    wire:click="alreadyRegistered">Sudah
                    Pernah
                    Register</button>
            </div>
        </div>
    @endif

    @if ($is_registered && $showSubmit)
        <div>
            <div class="h-screen flex flex-col justify-center items-center bg-blue-100  px-3 ">
                <div class="bg-gray-50 p-5 rounded-lg shadow-lg flex flex-col gap-5 w-full lg:w-1/4">
                    <h3 class="text-xl py-3 text-center">Silakan login
                        untuk
                        merubah data
                        anda</h3>
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                        <div class="mt-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 ">
                                <input type="text" for="email" wire:model="registeredEmail"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6">
                            </div>
                            @error('registeredEmail')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        <div class="mt-2">
                            <div
                                class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                                <input type="password" for="password" wire:model="registeredPassword"
                                    class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6">
                            </div>
                            @error('registeredPassword')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <button class="bg-blue-500 text-white py-2 rounded-lg lg:text-lg" wire:click="submit">Masuk</button>
                </div>
            </div>
        </div>
    @endif

    @if ($show)
        <div>
            @if ($is_update == false)
                <form wire:submit='save'>
                @else
                    <form wire:submit='update'>
            @endif
            <h1 class="mt-10 text-center text-blue-500 lg:text-4xl text-2xl lg:font-semibold">Form Pendaftaran
                Calon Karyawan
            </h1>
            <h3 class="text-center lg:text-2xl my-2 mb-4">Mohon dilengkapi dan diperiksa sebelum tekan submit</h3>
            <div class="lg:w-2/3 w-full px-2 mx-auto">
                {{-- @if ($is_update == false)
                    <form wire:submit='save'>
                    @else
                        <form wire:submit='update'>
                @endif --}}
                <div class="p-3 grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900 ">Nama
                            Lengkap<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='nama' type="text" id="nama_lengkap"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('nama')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email<span
                                class="text-red-500 ml-1">*</span></label>
                        <input wire:model='email' type="email" id="email"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('email')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password<span
                                class="text-red-500 ml-1">*</span></label>
                        <div class="relative">
                            <input wire:model='password' type="{{ $toggle_eye_password ? 'text' : 'password' }}"
                                id="password"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                            <button type='button' wire:click="toggleEyePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900">Confirm
                            Password<span class="text-red-500 ml-1">*</span></label>
                        <div class="relative">

                            <input wire:model='confirm_password' type="{{ $toggle_eye_password ? 'text' : 'password' }}"
                                id="confirm_password"
                                class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                            <button type='button' wire:click="toggleEyePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                                <i id="eyeIcon" class="fas fa-eye"></i>
                            </button>
                        </div>

                        @error('confirm_password')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="hp" class="block mb-2 text-sm font-medium text-gray-900">Handphone<span
                                class="text-red-500 ml-1">*</span></label>
                        <input wire:model='hp' type="text" id="hp"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('hp')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="telp" class="block mb-2 text-sm font-medium text-gray-900">Telepon<span
                                class="text-red-500 ml-1">*</span></label>
                        <input wire:model='telp' type="text" id="telp"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('telp')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="tempat_lahir" class="block mb-2 text-sm font-medium text-gray-900">Kota
                            Kelahiran<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='tempat_lahir' type="text" id="tempat_lahir"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('tempat_lahir')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="tgl_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal
                            Lahir<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='tgl_lahir' type="date" id="tgl_lahir"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('tgl_lahir')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="gender" class="block mb-2 text-sm font-medium text-gray-900">Gender<span
                                class="text-red-500 ml-1">*</span></label>
                        <div class="flex w-full gap-3">
                            <div class="flex w-1/2 items-center ps-4 border border-gray-200 rounded">
                                <input wire:model='gender' id="bordered-radio-1" type="radio" value="Laki-laki"
                                    name="bordered-radio"
                                    class=" text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="bordered-radio-1"
                                    class="w-full py-2.5 ms-2 text-sm font-medium text-gray-900">Pria</label>
                            </div>
                            <div class="flex w-1/2 items-center ps-4 border border-gray-200 rounded">
                                <input wire:model='gender' id="bordered-radio-2" type="radio" value="Perempuan"
                                    name="bordered-radio"
                                    class=" text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="bordered-radio-2"
                                    class="w-full py-2.5 ms-2 text-sm font-medium text-gray-900">Wanita</label>
                            </div>
                        </div>
                        @error('gender')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="status_pernikahan" class="block mb-2 text-sm font-medium text-gray-900">Status
                            Pernikahan<span class="text-red-500 ml-1">*</span></label>
                        <select id="status_pernikahan" wire:model='status_pernikahan'
                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="" selected>Pilih Status Pernikahan</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                        @error('status_pernikahan')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="golongan_darah" class="block mb-2 text-sm font-medium text-gray-900">Golongan
                            Darah<span class="text-red-500 ml-1">*</span></label>
                        <select id="golongan_darah" wire:model='golongan_darah'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value=" ">{{ __('Pilih golongan darah') }}</option>
                            <option value="O">O</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="AB">AB</option>
                        </select>
                        @error('golongan_darah')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label for="agama" class="block mb-2 text-sm font-medium text-gray-900">Agama<span
                                class="text-red-500 ml-1">*</span></label>
                        <select id="agama" wire:model='agama'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value=" ">{{ __('Pilih agama') }}</option>
                            <option value="Islam">{{ __('Islam') }}</option>
                            <option value="Kristen">{{ __('Kristen') }}</option>
                            <option value="Hindu">{{ __('Hindu') }}</option>
                            <option value="Budha">{{ __('Budha') }}</option>
                            <option value="Katolik">{{ __('Katolik') }}</option>
                            <option value="Konghucu">{{ __('Konghucu') }}</option>
                        </select>
                        @error('agama')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="etnis" class="block mb-2 text-sm font-medium text-gray-900">Etnis<span
                                class="text-red-500 ml-1">*</span></label>
                        <select id="etnis" wire:model='etnis'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value=" ">{{ __('Pilih Etnis') }}</option>
                            <option value="Batak">{{ __('Batak') }}</option>
                            {{-- <option value="China">{{ __('China') }}</option> --}}
                            <option value="Jawa">{{ __('Jawa') }}</option>
                            <option value="Sunda">{{ __('Sunda') }}</option>
                            <option value="Lampung">{{ __('Lampung') }}</option>
                            <option value="Palembang">{{ __('Palembang') }}</option>
                            <option value="Tionghoa">{{ __('Tionghoa') }}</option>
                            <option value="Lainnya">{{ __('Lainnya') }}</option>
                        </select>
                        @error('etnis')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="ptkp" class="block mb-2 text-sm font-medium text-gray-900">PTKP<span
                                class="text-red-500 ml-1">*</span></label>
                        <select id="ptkp" wire:model='ptkp'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value=" ">{{ __('Pilih PTKP') }}</option>
                            <option value="TK0">TK/0</option>
                            <option value="TK1">TK/1</option>
                            <option value="TK2">TK/2</option>
                            <option value="TK3">TK/3</option>
                            <option value="K0">K/0</option>
                            <option value="K1">K/1</option>
                            <option value="K2">K/2</option>
                            <option value="K3">K/3</option>
                        </select>
                        @error('ptkp')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                {{-- kontak --}}
                <div class="p-3 grid gap-6 mb-6 md:grid-cols-3">
                    <div>
                        <label for="nama_contact_darurat" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Kontak
                            Darurat 1<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='nama_contact_darurat' type="text" id="nama_contact_darurat"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('nama_contact_darurat')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_darurat_1" class="block mb-2 text-sm font-medium text-gray-900">Handphone
                            Kontak Darurat 1<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='contact_darurat_1' type="text" id="contact_darurat_1"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('contact_darurat_1')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="hubungan1" class="block mb-2 text-sm font-medium text-gray-900">Hubungan dengan
                            Kontak Darurat 1<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='hubungan_1' type="text" id="hubungan1"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('hubungan_1')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_contact_darurat" class="block mb-2 text-sm font-medium text-gray-900">Nama
                            Kontak
                            Darurat 2<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='nama_contact_darurat_2' type="text" id="nama_contact_darurat"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('nama_contact_darurat_2')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_darurat_2" class="block mb-2 text-sm font-medium text-gray-900">Handphone
                            Kontak Darurat 2<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='contact_darurat_2' type="text" id="contact_darurat_2"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('contact_darurat_2')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="hubungan2" class="block mb-2 text-sm font-medium text-gray-900">Hubungan dengan
                            Kontak Darurat 2<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='hubungan_2' type="text" id="hubungan2"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('hubungan_2')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                </div>
                <div class="p-3 grid gap-6 mb-6 md:grid-cols-2">

                    <div>
                        <label for="jenis_identitas" class="block mb-2 text-sm font-medium text-gray-900">Jenis
                            Identitas<span class="text-red-500 ml-1">*</span></label>
                        <select id="jenis_identitas" wire:model='jenis_identitas'
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value=" ">{{ __('Pilih jenis Identitas') }}</option>
                            <option value="KTP">{{ __('KTP') }}</option>
                            <option value="Passport">{{ __('Passport') }}</option>
                        </select>
                        @error('jenis_identitas')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="no_identitas" class="block mb-2 text-sm font-medium text-gray-900">Nomor
                            Identitas<span class="text-red-500 ml-1">*</span></label>
                        <input wire:model='no_identitas' type="text" id="no_identitas"
                            class="p-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full" />
                        @error('no_identitas')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_identitas" class="block mb-2 text-sm font-medium text-gray-900">Alamat
                            Identitas<span class="text-red-500 ml-1">*</span></label>
                        <textarea id="message" rows="4" wire:model='alamat_identitas'
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-50"></textarea>
                        @error('alamat_identitas')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <label for="alamat_tinggal_sekarang"
                            class="block mb-2 text-sm font-medium text-gray-900">Alamat
                            Tinggal
                            Sekarang<span class="text-red-500 ml-1">*</span></label>
                        <textarea id="message" rows="4" wire:model='alamat_tinggal_sekarang'
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-50"></textarea>
                        @error('alamat_tinggal_sekarang')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <p>Untuk upload hanya menerima file png, jpg dan jpeg saja</p>
                <div class="p-3 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ([
        'ktp' => 'Upload KTP',
        'kk' => 'Upload Kartu Keluarga',
        'ijazah' => 'Upload Ijazah',
        'nilai' => 'Upload Transkip Nilai/SKHUN',
        'cv' => 'Upload CV',
        'pasfoto' => 'Upload Pass Foto',
        'npwp' => 'Upload NPWP',
        'paklaring' => 'Upload Paklaring',
        'bpjs' => 'Upload Kartu BPJS Ketenagakerjaan',
        'skck' => 'Upload SKCK',
        'sertifikat' => 'Upload Sertifikat',
        'bri' => 'Upload Buku Tabungan Bank BRI',
    ] as $field => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-900 mb-2">
                                <p>{{ $label }} @if (in_array($field, ['ktp', 'kk', 'ijazah', 'nilai', 'cv', 'pasfoto']))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </p>
                            </label>
                            <input wire:model='{{ $field }}' multiple
                                class="filepond block w-full px-3 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:ring focus:ring-blue-300"
                                type="file">

                            @error($field . '.*')
                                <div class="text-red-500 text-xs mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    @endforeach
                </div>


            </div>
            <div class="flex justify-evenly w-full lg:w-1/2 ">
                @if ($is_update)
                    <div role="status" wire:loading wire:target='update'>
                    @else
                        <div role="status" wire:loading wire:target='save'>
                @endif
                <div class="flex justify-evenly items-center w-full mb-2 gap-5 px-3">
                    <p class='lg:text-xl text-normal'>Mohon ditunggu sampai proses upload selesai</p>

                    <svg aria-hidden="true"
                        class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>

                </div>
            </div>

        </div>
        <div class='px-3 w-full lg:w-1/2 mt-3'>
            <ul class="list-group">
                @foreach ($errors->all() as $error)
                    <li class="list-group-item"><span class='text-danger'>* {{ $error }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="flex justify-evenly w-full lg:w-1/2 mt-3">
            <div class="md:px-0 px-3">
                @if ($is_update == false)
                    <button type="submit" onClick="clear_file()"
                        class="w-full md:mx-0 mb-5 px-5 py-2.5 md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm    text-center ">
                        <span>Submit</span>
                    </button>
                @else
                    <button type="submit" onClick="clear_file()"
                        class="w-full md:mx-0 mb-5 px-5 py-2.5 md:w-auto text-white bg-orange-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm    text-center ">
                        <span>Update</span>
                    </button>
                @endif
            </div>
            <div>
                <button type="button" wire:click='keluar'
                    class="me-3 w-full md:mx-0 mb-5 px-5 py-2.5 md:w-auto text-white bg-black hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm    text-center ">
                    <span>Keluar</span>
                </button>
            </div>

        </div>
        </form>
        @if ($filenames)
            <div class="mt-3">
                <div class="row g-4">
                    @foreach ($filenames as $key => $fn)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm hover-shadow-lg">
                                <!-- Nama File & Tombol Hapus -->
                                <div class="d-flex justify-content-between align-items-center p-3">
                                    <p class="mb-0 fw-semibold text-truncate">{{ get_filename($fn->filename) }}</p>
                                    <button class="btn btn-danger btn-sm"
                                        wire:click="deleteFile('{{ $fn->filename }}')" wire:loading.attr="disabled">
                                        Hapus
                                    </button>
                                </div>

                                <!-- Gambar -->
                                <div class="d-flex justify-content-center align-items-center p-3"
                                    style="height: 200px; background: #f8f9fa; cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#imageModal{{ $key }}">
                                    <img src="{{ getUrl($fn->filename) }}" class="img-fluid rounded"
                                        alt="File Image" style="max-height: 100%; object-fit: contain;">
                                </div>
                            </div>
                        </div>

                        <!-- Modal Perbesar Gambar -->
                        <div class="modal fade" id="imageModal{{ $key }}" tabindex="-1"
                            aria-labelledby="imageModalLabel{{ $key }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel{{ $key }}">
                                            {{ get_filename($fn->filename) }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ getUrl($fn->filename) }}" class="img-fluid rounded"
                                            alt="File Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Tombol Kembali -->
                <div class="d-flex justify-content-center mt-4">
                    {{-- <button class="btn btn-dark px-4 py-2 shadow-sm" wire:click='kembali'>
                        ⬅ Kembali
                    </button> --}}
                </div>
            </div>

        @endif

    @endif

</div>
