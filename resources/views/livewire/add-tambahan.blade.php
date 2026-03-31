<div>
    <div class="container">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3>Bonus dan Potongan</h3>
            </div>
            <div class="card-body">
                <button wire:click="addRow" class="btn btn-success btn-sm mb-3">+ Tambah ID Karyawan</button>

                @foreach ($karyawan as $index => $data)
                    <div class="d-flex gap-2 flex-column flex-xl-row ">
                        <div class="mb-3 col-12 col-xl-2">
                            <label class="form-label">ID Karyawan</label>
                            <input wire:model.live="karyawan.{{ $index }}.user_id" type="number"
                                class="form-control">
                        </div>
                        <div class="mb-3 col-12 col-xl-4">
                            <div>
                                <label class="form-label">Nama Karyawan</label>
                                <div class='d-flex gap-2'>

                                    <input wire:model.live="karyawan.{{ $index }}.nama_karyawan" type="text"
                                        class="form-control" disabled>
                                    <button wire:click="removeRow({{ $index }})"
                                        class="btn btn-warning btn-sm">Delete</button>
                                </div>
                            </div>

                        </div>

                    </div>
                @endforeach

                <div class="mb-3 col-12 col-xl-2">
                    <label class="form-label">Tanggal (mm/dd/yyyy)</label>
                    <input wire:model="tanggal" class="form-control" type="date">
                </div>

                <div class="card mt-lg-3 mt-2">
                    <div class="card-header bg-success text-light nightowl-daylight">
                        <h5>Bonus</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-xl-row flex-column gap-xl-3 gap-2">

                            <div class="mb-3">
                                <label class="form-label">{{ __('Uang Makan') }}</label>
                                {{-- <input wire:model="uang_makan" type="text" type-currency="IDR" class="form-control"> --}}
                                <input wire:model="uang_makan" type="text" type-currency="IDR" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Bonus Lain') }}</label>
                                <input wire:model="bonus_lain" type="text" type-currency="IDR" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('THR') }}</label>
                                <input wire:model="thr" type="text" type-currency="IDR" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-lg-3 mt-2">
                    <div class="card-header bg-info text-light nightowl-daylight">
                        <h5>Potongan</h5>
                    </div>
                    <div class="card-body ">
                        <div class="d-flex flex-column flex-lg-row  gap-2 justify-content-center px-2">
                            <div class="mb-3 col-lg-3 col-12">
                                <label class="form-label">{{ __('Baju ESD') }}</label>
                                <input wire:model="baju_esd" type="text" type-currency="IDR" class="form-control">
                            </div>

                            <div class="mb-3 col-lg-3 col-12">
                                <label class="form-label">{{ __('Gelas') }}</label>
                                <input wire:model="gelas" type="text" type-currency="IDR" class="form-control">
                            </div>

                            <div class="mb-3 col-lg-3 col-12">
                                <label class="form-label">{{ __('Sandal') }}</label>
                                <input wire:model="sandal" type="text" type-currency="IDR" class="form-control">
                            </div>

                            <div class="mb-3 col-lg-3 col-12">
                                <label class="form-label">{{ __('Seragam') }}</label>
                                <input wire:model="seragam" type="text" type-currency="IDR" class="form-control">
                            </div>
                        </div>
                        <div class="d-flex flex-column flex-lg-row  gap-2 justify-content-between">

                            <div class="mb-3 col-lg-2 col-12">
                                <label class="form-label">{{ __('Sport Bra') }}</label>
                                <input wire:model="sport_bra" type="text" type-currency="IDR" class="form-control">

                            </div>


                            <div class="mb-3 col-lg-2 col-12">
                                <label class="form-label">{{ __('Hijab Instan') }}</label>
                                <input wire:model="hijab_instan" type="text" type-currency="IDR"
                                    class="form-control">

                            </div>


                            <div class="mb-3 col-lg-2 col-12">
                                <label class="form-label">{{ __('ID Card Hilang') }}</label>
                                <input wire:model="id_card_hilang" type="text" type-currency="IDR"
                                    class="form-control">
                            </div>



                            <div class="mb-3 col-lg-2 col-12">
                                <label class="form-label">{{ __('Masker Hijau') }}</label>
                                <input wire:model="masker_hijau" type="text" type-currency="IDR"
                                    class="form-control">
                            </div>


                            <div class="mb-3 col-lg-2 col-12">
                                <label class="form-label">{{ __('Potongan Lain') }}</label>
                                <input wire:model="potongan_lain" type="text" type-currency="IDR"
                                    class="form-control">
                            </div>
                        </div>
                    </div>

                    {{-- </div> --}}
                </div>
                <div class="d-flex gap-5 mt-3">
                    <button wire:click="save" class="btn btn-primary nightowl-daylight">{{ __('Save') }}</button>
                    <button wire:click="cancel" class="btn btn-dark nightowl-daylight">{{ __('Back') }}</button>
                    <button wire:click="resetForm"
                        class="btn btn-success nightowl-daylight">{{ __('Reset Form') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
