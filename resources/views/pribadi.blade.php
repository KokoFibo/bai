<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">{{ __('ID Karyawan') }} <span class="text-danger">*</span></label>
            <input wire:model="id_karyawan" type="number" class="form-control" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Nama Karyawan') }} <span class="text-danger">*</span></label>
            <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror">
            @error('nama')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Email') }} </label>
            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Handphone') }} </label>
                    <input wire:model="hp" type="text" class="form-control @error('hp') is-invalid @enderror">
                    @error('hp')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Telepon') }}</label>
                    <input wire:model="telepon" type="text" class="form-control">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Tempat Lahir') }} <span class="text-danger">*</span></label>
                    <input wire:model="tempat_lahir" type="text"
                        class="form-control @error('tempat_lahir') is-invalid @enderror">
                    @error('tempat_lahir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3 form-group">
                    <label class="form-label">{{ __('Tanggal Lahir') }} </label><span class="text-danger">*</span>
                    <div>
                        <input wire:model="tanggal_lahir" type="datetime:local" id="tanggal"
                            class="date form-control @error('tanggal_lahir') is-invalid @enderror">
                        @error('tanggal_lahir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">

                <div class="mb-3">
                    <label class="form-label">{{ __('Gender') }} <span class="text-danger">*</span></label>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input wire:model="gender"
                                    class="form-check-input @error('gender') is-invalid @enderror" type="radio"
                                    value="Laki-laki" name="flexRadioDefault" id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    {{ __('Laki-laki') }}
                                </label>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input wire:model="gender"
                                    class="form-check-input @error('gender') is-invalid @enderror" type="radio"
                                    value="Perempuan" name="flexRadioDefault" id="flexRadioDefault2">
                                <label class="form-check-label" for="flexRadioDefault2">
                                    {{ __('Perempuan') }}
                                </label>
                                @error('gender')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">{{ __('Status Pernikahan') }}</label>
                    <select wire:model="status_pernikahan" class="form-select" aria-label="Default select example">
                        <option value=" ">{{ __('Pilih status pernikahan') }}</option>
                        <option value="Belum Kawin">{{ __('Belum Kawin') }}</option>
                        <option value="Kawin">{{ __('Kawin') }}</option>
                        <option value="Cerai Hidup">{{ __('Cerai Hidup') }}</option>
                        <option value="Cerai Mati">{{ __('Cerai Mati') }}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('') }}Golongan Darah</label>
                    <select wire:model="golongan_darah" class="form-select" aria-label="Default select example">
                        <option value=" ">{{ __('Pilih golongan darah') }}</option>
                        <option value="O">O</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Agama') }}</label>
                    <select wire:model="agama" class="form-select" aria-label="Default select example">
                        <option value=" ">{{ __('Pilih agama') }}</option>
                        <option value="Islam">{{ __('Islam') }}</option>
                        <option value="Kristen">{{ __('Kristen') }}</option>
                        <option value="Hindu">{{ __('Hindu') }}</option>
                        <option value="Budha">{{ __('Budha') }}</option>
                        <option value="Katolik">{{ __('Katolik') }}</option>
                        <option value="Konghucu">{{ __('Konghucu') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Etnis') }} <span class="text-danger">*</span></label>
                    <select wire:model.live="etnis" class="form-select @error('etnis') is-invalid @enderror""
                        aria-label="Default select example">
                        <option value=" ">{{ __('Pilih Etnis') }}</option>
                        <option value="Batak">{{ __('Batak') }}</option>
                        <option value="China">{{ __('TKA') }}</option>
                        <option value="Jawa">{{ __('Jawa') }}</option>
                        <option value="Sunda">{{ __('Sunda') }}</option>
                        <option value="Lampung">{{ __('Lampung') }}</option>
                        <option value="Palembang">{{ __('Palembang') }}</option>
                        <option value="Tionghoa">{{ __('Tionghoa') }}</option>
                        <option value="Lainnya">{{ __('Lainnya') }}</option>
                    </select>
                    @error('etnis')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Nama Kontak Darurat 1') }} </label>
                    <input wire:model="kontak_darurat" type="text"
                        class="form-control @error('kontak_darurat') is-invalid @enderror">
                    @error('kontak_darurat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Handphone Kontak Darurat 1') }} </label>
                    <input wire:model="hp1" type="text" class="form-control @error('hp1') is-invalid @enderror">
                    @error('hp1')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Hubungan Kontak Darurat 1') }} </label>
                    <input wire:model="hubungan1" type="text"
                        class="form-control @error('hubungan1') is-invalid @enderror">
                    @error('hubungan1')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Nama Kontak Darurat 2') }} </label>
                    <input wire:model="kontak_darurat2" type="text"
                        class="form-control @error('kontak_darurat2') is-invalid @enderror">
                    @error('kontak_darurat2')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Handphone Kontak Darurat 2') }} </label>
                    <input wire:model="hp2" type="text" class="form-control @error('hp2') is-invalid @enderror">
                    @error('hp2')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Hubungan Kontak Darurat 2') }} </label>
                    <input wire:model="hubungan2" type="text"
                        class="form-control @error('hubungan2') is-invalid @enderror">
                    @error('hubungan2')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

</div>
