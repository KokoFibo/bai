{{-- Gaji --}}

@if ((auth()->user()->role == 5 && $gaji_pokok <= 4500000) || auth()->user()->role >= 6)
    <div wire:ignore.self class="card mt-2">
        <div class="card-header bg-secondary ">
            <h5 class="text-light">{{ __('Gaji') }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Metode Penggajian') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('metode_penggajian') is-invalid @enderror"
                            aria-label="Default select example" wire:model="metode_penggajian">
                            <option value=" ">{{ __('Pilih metode penggajian') }}</option>
                            <option value="Perjam">{{ __('Perjam') }}</option>
                            <option value="Perbulan">{{ __('Perbulan') }}</option>
                        </select>
                        @error('metode_penggajian')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                {{-- ====================================================== --}}
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Gaji pokok') }} </label>
                        <input wire:model="gaji_pokok" type="text" type-currency="IDR"
                            class="form-control @error('gaji_pokok') is-invalid @enderror">
                        @error('gaji_pokok')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Gaji Overtime') }} </label>
                        <input wire:model="gaji_overtime" type="text" type-currency="IDR"
                            class="form-control @error('gaji_overtime') is-invalid @enderror">
                        @error('gaji_overtime')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Gaji Shift Malam Satpam') }}</label>
                        <input wire:model="gaji_shift_malam_satpam" type="text" type-currency="IDR"
                            class="form-control @error('gaji_shift_malam_satpam') is-invalid @enderror">
                        @error('gaji_shift_malam_satpam')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>



            </div>


        </div>
    </div>
@endif



{{-- Tunjangan --}}

<div class="card mt-2">
    <div class="card-header bg-secondary">
        <h5 class="text-light">{{ __('Tunjangan') }}</h5>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Bonus') }}</label>
                    <input wire:model="bonus" type="text" type-currency="IDR" class="form-control">

                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Tunjangan Jabatan') }}</label>
                    <input wire:model="tunjangan_jabatan" type="text" type-currency="IDR" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Tunjangan Bahasa') }}</label>
                    <input wire:model="tunjangan_bahasa" type="text" type-currency="IDR" class="form-control">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Tunjangan Skill') }}</label>
                    <input wire:model="tunjangan_skill" type="text" type-currency="IDR" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Tunjangan Lembur Sabtu') }}</label>
                    <input wire:model="tunjangan_lembur_sabtu" type="text" type-currency="IDR" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Tunjangan Lama Kerja') }}</label>
                    <input wire:model="tunjangan_lama_kerja" type="text" type-currency="IDR" class="form-control">
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Potongan --}}
<div class="card mt-2">
    <div class="card-header bg-secondary">
        <h5 class="text-light">{{ __('Potongan') }}</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Iuran air minum') }} <span class="text-danger">*</span></label>
                    <input wire:model="iuran_air" type="text" type-currency="IDR"
                        class="form-control @error('iuran_air') is-invalid @enderror">
                    @error('iuran_air')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Denda') }}</label>
                    <input wire:model="denda" type="text" type-currency="IDR" class="form-control">

                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Iuran Locker') }}</label>
                    <input wire:model="iuran_locker" type="text" type-currency="IDR" class="form-control">
                </div>
            </div>



        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Gaji BPJS') }}</label>
                    <input wire:model="gaji_bpjs" type="text" type-currency="IDR" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">{{ __('Nomor NPWP') }}</label>
                    <input wire:model="no_npwp" type="number" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">PTKP </label>
                    <select class="form-select @error('ptkp') is-invalid @enderror"
                        aria-label="Default select example" wire:model="ptkp">
                        <option value=" ">Pilih PTKP</option>
                        <option value="TK0">TK/0</option>
                        <option value="TK1">TK/1</option>
                        <option value="TK2">TK/2</option>
                        <option value="TK3">TK/3</option>
                        <option value="K0">K/0</option>
                        <option value="K1">K/1</option>
                        <option value="K2">K/2</option>
                        <option value="K3">K/3</option>
                    </select @error('ptkp') <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="row">
            {{-- Potongan BPJS --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Potongan BPJS') }}</label>
                <div class="mb-3 d-flex gap-lg-4 flex-column flex-lg-row gap-0 border rounded py-2 px-4">
                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_JHT" class="form-check-input"
                            {{ $potongan_JHT == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            JHT
                        </label>
                    </div>
                    @if ($etnis != 'China')
                        <div class="form-check mt-2">
                            <input type="checkbox" wire:model="potongan_JP" class="form-check-input"
                                {{ $potongan_JP == 1 ? 'checked' : '' }}>
                            <label class="form-check-label">
                                JP
                            </label>
                        </div>
                    @endif

                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_JKK"
                            class="form-check-input @error('potongan_JKK') is-invalid @enderror""
                            {{ $potongan_JKK == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            JKK
                        </label>
                        @error('potongan_JKK')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_JKM" class="form-check-input"
                            {{ $potongan_JKM == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            JKM
                        </label>
                    </div>

                    <div class="form-check mt-2">
                        <input type="checkbox" wire:model="potongan_kesehatan" class="form-check-input"
                            {{ $potongan_kesehatan == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">
                            Kesehatan
                        </label>
                    </div>
                </div>
            </div>
            {{-- Tanggungan --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Tanggungan BPJS') }}</label>
                <div class="mb-3 d-flex gap-lg-4 flex-column flex-lg-row gap-0 border rounded py-2 px-4">

                    <div class="form-check mt-2">
                        <input type="radio" wire:model="tanggungan" class="form-check-input" value="0"
                            nama="tanggungan" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Tidak ada
                        </label>
                    </div>
                    <div class="form-check mt-2">
                        <input type="radio" wire:model="tanggungan" class="form-check-input" value="1"
                            nama="tanggungan" id="flexRadioDefault1"> <label class="form-check-label"
                            for="flexRadioDefault1">
                            1 Orang
                        </label>
                    </div>
                    <div class="form-check mt-2">
                        <input type="radio" wire:model="tanggungan" class="form-check-input" value="2"
                            nama="tanggungan" id="flexRadioDefault1"> <label class="form-check-label"
                            for="flexRadioDefault1">
                            2 Orang
                        </label>
                    </div>
                    <div class="form-check mt-2">
                        <input type="radio" wire:model="tanggungan" class="form-check-input" value="3"
                            nama="tanggungan" id="flexRadioDefault1"> <label class="form-check-label"
                            for="flexRadioDefault1">
                            3 Orang
                        </label>

                    </div>
                    <div class="form-check mt-2">
                        <input type="radio" wire:model="tanggungan" class="form-check-input" value="4"
                            nama="tanggungan" id="flexRadioDefault1"> <label class="form-check-label"
                            for="flexRadioDefault1">
                            4 Orang
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
