<div>
    @section('title', 'Bonus dan Potongan')
    <div class="col-12  mx-auto pt-3">
        <div class="card ">
            <div class="card-header bg-info nightowl-daylight">
                <div class="d-flex justify-content-between">

                    <label class=" col-form-label">{{ __('Bonus dan Potongan') }}</label>
                    {{-- spinner --}}
                    <div class="spinner-border text-warning" role="status" wire:loading wire:target="columnName">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-warning" role="status" wire:loading wire:target="direction">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-warning" role="status" wire:loading wire:target="year">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div class="spinner-border text-warning" role="status" wire:loading wire:target="month">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    {{-- end spinner --}}
                    <div class="">
                        {{-- <button wire:click="add"
                            class="btn btn-primary col-12 {{ is_data_locked() ? 'd-none' : '' }} nightowl-daylight"
                            {{ is_data_locked() ? 'disabled' : '' }}>
                            {{ __('Add New') }}
                        </button> --}}
                        <a href="/addtambahan"> <button
                                class="btn btn-primary col-12 {{ is_data_locked() ? 'd-none' : '' }} nightowl-daylight"
                                {{ is_data_locked() ? 'disabled' : '' }}>
                                {{ __('Add New') }}
                            </button></a>
                    </div>
                </div>
            </div>
            @if ($modal == true)
                <div class="card-body">

                    <div class="d-flex gap-2 flex-column flex-xl-row">
                        <div class="mb-3 col-12 col-xl-2">
                            <label for="user_id" class="form-label">ID Karyawan</label>
                            <input wire:model.live="user_id" type="numeric" class="form-control" id="user_id">
                        </div>
                        <div class="mb-3 col-12 col-xl-3">
                            <label for="nama_karyawan" class="form-label">{{ __('Nama Karyawan') }}</label>
                            <input wire:model.live="nama_karyawan" type="text" class="form-control"
                                id="nama_karyawan" disabled>
                        </div>


                    </div>
                    <div class="mb-3 col-12 col-xl-2">
                        <label class="form-label">{{ __('Tanggal') }} (mm/dd/yyyy)</label>
                        <input wire:model="tanggal" class="form-control" type="date">
                    </div>

                    <div class="card mt-lg-3 mt-2">
                        <div class="card-header bg-success nightowl-daylight">
                            <h5>Bonus</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-xl-row flex-column gap-xl-3 gap-2">

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Uang Makan1') }}</label>
                                    {{-- <input wire:model="uang_makan" type="number" class="form-control"> --}}
                                    <input wire:model="uang_makan" type="text" type-currency="IDR"
                                        class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('Bonus Lain') }}</label>
                                    <input wire:model="bonus_lain" type="number" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('THR') }}</label>
                                    <input wire:model="thr" type="number" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-lg-3 mt-2">
                        <div class="card-header bg-info nightowl-daylight">
                            <h5>Potongan</h5>
                        </div>
                        <div class="card-body ">
                            <div class="d-flex flex-column flex-lg-row  gap-2 justify-content-center">
                                <div class="mb-3 col-lg-3 col-12">
                                    <label class="form-label">{{ __('Baju ESD') }}</label>
                                    <input wire:model="baju_esd" type="number" class="form-control">
                                </div>

                                <div class="mb-3 col-lg-3 col-12">
                                    <label class="form-label">{{ __('Gelas') }}</label>
                                    <input wire:model="gelas" type="number" class="form-control">
                                </div>

                                <div class="mb-3 col-lg-3 col-12">
                                    <label class="form-label">{{ __('Sandal') }}</label>
                                    <input wire:model="sandal" type="number" class="form-control">
                                </div>

                                <div class="mb-3 col-lg-3 col-12">
                                    <label class="form-label">{{ __('Seragam') }}</label>
                                    <input wire:model="seragam" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="d-flex flex-column flex-lg-row  gap-2 justify-content-between">

                                <div class="mb-3 col-lg-2 col-12">
                                    <label class="form-label">{{ __('Sport Bra') }}</label>
                                    <input wire:model="sport_bra" type="number" class="form-control">

                                </div>


                                <div class="mb-3 col-lg-2 col-12">
                                    <label class="form-label">{{ __('Hijab Instan') }}</label>
                                    <input wire:model="hijab_instan" type="number" class="form-control">

                                </div>


                                <div class="mb-3 col-lg-2 col-12">
                                    <label class="form-label">{{ __('ID Card Hilang') }}</label>
                                    <input wire:model="id_card_hilang" type="number" class="form-control">
                                </div>



                                <div class="mb-3 col-lg-2 col-12">
                                    <label class="form-label">{{ __('Masker Hijau') }}</label>
                                    <input wire:model="masker_hijau" type="number" class="form-control">
                                </div>


                                <div class="mb-3 col-lg-2 col-12">
                                    <label class="form-label">{{ __('Potongan Lain') }}</label>
                                    <input wire:model="potongan_lain" type="number" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- </div> --}}
                    </div>
                    <div class="d-flex gap-5">
                        <button wire:click="save"
                            class="btn btn-success nightowl-daylight">{{ __('Save') }}</button>
                        <button wire:click="cancel"
                            class="btn btn-dark nightowl-daylight">{{ __('Cancel') }}</button>
                    </div>
                </div>
            @endif

        </div>
    </div>
    <div class="col-12  mx-auto pt-3">

        @if ($modal == false)
            <style>
                td,
                th {
                    white-space: nowrap;
                }
            </style>


            <div class="card">
                <div class="card-header">
                    <div
                        class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <!-- Search & Refresh (desktop) -->
                        <div class="flex-grow-1 col-12 col-md-4">
                            <div class="input-group">
                                <button class="btn btn-primary" type="button">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>
                                <input type="search" wire:model.live="search" class="form-control"
                                    placeholder="{{ __('Search') }} ..." />
                                <button wire:click="refresh" class="btn btn-success d-none d-md-inline ms-3"
                                    type="button">
                                    Refresh
                                </button>
                            </div>
                        </div>

                        <!-- Sorting -->
                        <div class="d-flex flex-grow-1 col-12 col-md-4 gap-2">
                            <select class="form-select" wire:model.live="columnName">
                                <option value="user_id">Id Karyawan</option>
                                <option value="id">Data Terakhir</option>
                            </select>
                            <select class="form-select" wire:model.live="direction">
                                <option value="desc">Descending</option>
                                <option value="asc">Ascending</option>
                            </select>
                        </div>

                        <!-- Year & Month -->
                        <div class="d-flex flex-grow-1 col-12 col-md-4 gap-2 pe-3">
                            <select class="form-select" wire:model.live="year">
                                @foreach ($select_year as $sy)
                                    <option value="{{ $sy }}">{{ $sy }}</option>
                                @endforeach
                            </select>
                            <select class="form-select" wire:model.live="month">
                                @foreach ($select_month as $sm)
                                    <option value="{{ $sm }}">{{ monthName($sm) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Refresh (mobile only) -->
                        <button wire:click="refresh" class="btn btn-success d-md-none w-100 mt-2 " type="button">
                            Refresh
                        </button>
                    </div>


                </div>
                <div class="col-12">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table  table-sm  table-hover mb-2">
                                <thead>
                                    <tr>
                                        <th></th>
                                        {{-- <th>{{ __('id') }}</th> --}}
                                        <th wire:click="sortColumnName('user_id')">{{ __('ID Karyawan') }}</th>
                                        <th wire:click="sortColumnName('nama')">{{ __('Nama Karyawan') }}</th>
                                        <th wire:click="sortColumnName('jabatan_id')">{{ __('Jabatan') }}</th>
                                        <th wire:click="sortColumnName('tanggal')">{{ __('Tanggal') }}</th>
                                        <th wire:click="sortColumnName('uang_makan')">{{ __('Uang Makan') }}</th>
                                        <th wire:click="sortColumnName('bonus_lain')">{{ __('Bonus Lain') }}</th>
                                        <th wire:click="sortColumnName('bonus_lain')">{{ __('THR') }}</th>
                                        <th wire:click="sortColumnName('baju_esd')">{{ __('Baju ESD') }}</th>
                                        <th wire:click="sortColumnName('gelas')">{{ __('Gelas') }}</th>
                                        <th wire:click="sortColumnName('sandal')">{{ __('Sandal') }}</th>
                                        <th wire:click="sortColumnName('seragam')">{{ __('Seragam') }}</th>
                                        <th wire:click="sortColumnName('sport_bra')">{{ __('Sport Bra') }}</th>
                                        <th wire:click="sortColumnName('hijab_instan')">{{ __('Hijab Instan') }}</th>
                                        <th wire:click="sortColumnName('id_card_hilang')">{{ __('ID Card Hilang') }}
                                        </th>
                                        <th wire:click="sortColumnName('masker_hijau')">{{ __('Masker Hijau') }}</th>
                                        <th wire:click="sortColumnName('potongan_lain')">{{ __('Seragam') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr>
                                            <td>
                                                <div class="text-center">
                                                    <div class="btn-group  gap-2 nightowl-daylight" role="group"
                                                        aria-label="Basic mixed styles example">
                                                        {{-- <button wire:click="update({{ $d->id }})"
                                                            type="button"
                                                            class="btn btn-warning btn-sm {{ is_data_locked() ? 'd-none' : '' }}"><i
                                                                class="fa-regular fa-pen-to-square"></i></button> --}}
                                                        <a href="/updatetambahan/{{ $d->id }}"><button
                                                                type="button"
                                                                class="btn btn-warning btn-sm {{ is_data_locked() ? 'd-none' : '' }}"><i
                                                                    class="fa-regular fa-pen-to-square"></i></button></a>
                                                        <button
                                                            wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE""
                                                            wire:click="delete({{ $d->id }})" type="button"
                                                            class="btn btn-danger btn-sm {{ is_data_locked() ? 'd-none' : '' }}"><i
                                                                class="fa-solid fa-trash-can"></i></button>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- <td>{{ $d->id }}</td> --}}
                                            <td>{{ $d->user_id }}</td>
                                            <td>{{ $d->karyawan->nama }}</td>
                                            <td>{{ $d->karyawan->jabatan->nama_jabatan }}</td>
                                            <td>{{ format_tgl($d->tanggal) }}</td>
                                            <td class="text-end">{{ number_format($d->uang_makan) }}</td>
                                            <td class="text-end">{{ number_format($d->bonus_lain) }}</td>
                                            <td class="text-end">{{ number_format($d->thr) }}</td>
                                            <td class="text-end">{{ number_format($d->baju_esd) }}</td>
                                            <td class="text-end">{{ number_format($d->gelas) }}</td>
                                            <td class="text-end">{{ number_format($d->sandal) }}</td>
                                            <td class="text-end">{{ number_format($d->seragam) }}</td>
                                            <td class="text-end">{{ number_format($d->sport_bra) }}</td>
                                            <td class="text-end">{{ number_format($d->hijab_instan) }}</td>
                                            <td class="text-end">{{ number_format($d->id_card_hilang) }}</td>
                                            <td class="text-end">{{ number_format($d->masker_hijau) }}</td>
                                            <td class="text-end">{{ number_format($d->potongan_lain) }}</td>

                                            {{-- <td>
                                                @if (ada_tambahan($d->id_karyawan))
                                                    <div class="text-center">
                                                        <div class="btn-group" role="group"
                                                            aria-label="Basic mixed styles example">
                                                            <button wire:click="update({{ $d->id_karyawan }})"
                                                                type="button"
                                                                class="btn btn-warning">{{ __('Edit') }}</button>
                                                            <button wire:confirm="{{ __('Yakin mau di delete?') }}"
                                                                wire:click="delete({{ $d->id_karyawan }})"
                                                                type="button"
                                                                class="btn btn-danger">{{ __('Delete') }}</button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="text-center">
                                                        <button wire:click="add({{ $d->id_karyawan }})"
                                                            type="button"
                                                            class="btn btn-success">{{ __('Add') }}</button>
                                                    </div>
                                                @endif
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->onEachSide(0)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
