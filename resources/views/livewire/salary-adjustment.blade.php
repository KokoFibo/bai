<div>
    @section('title', 'Salary Adjustment')

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-lg-row align-items-center justify-content-between">
                <div>
                    <h4>{{ __('Penyesuaian Gaji Karyawan') }}</h4>
                </div>
                <div class="col-12 col-lg-2">
                    <select class="form-select" wire:model.live="pilihLamaKerja">
                        {{-- <option value=" ">{{ __('Pilih lama bekerja') }}</option> --}}
                        <option value="3">{{ __('3 bulan') }}</option>
                        <option value="4">{{ __('4 Bulan') }}</option>
                        <option value="5">{{ __('5 Bulan') }}</option>
                        <option value="6">{{ __('6 Bulan') }}</option>
                        <option value="7">{{ __('7 Bulan') }}</option>
                        {{-- <option value="8">{{ __('8 Bulan') }}</option> --}}
                        {{-- <option value="9">{{ __('9 Bulan') }}</option> --}}
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 130px;">

                                <input wire:model.live="search_id_karyawan" type="text" class="form-control"
                                    placeholder="{{ __('ID') }}">
                            </th>
                            <th>
                                <input wire:model.live="search_nama" type="text" class="form-control"
                                    placeholder="{{ __('Nama Karyawan') }}">
                            </th>
                            <th style="width: 130px;">
                                <div style="width: 130px">
                                    <select wire:model.live="search_placement" class="form-select"
                                        aria-label="Default select example">
                                        <option value="">{{ __('All Placement') }}</option>
                                        @foreach ($placements as $j)
                                            <option value="{{ $j }}">{{ nama_placement($j) }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </th>
                            <th style="width: 130px;">
                                <div style="width: 130px">
                                    <select wire:model.live="search_company" class="form-select d-none"
                                        aria-label="Default select example">
                                        <option value="">{{ __('Company') }}</option>
                                        @foreach ($companies as $j)
                                            <option value="{{ $j }}">{{ nama_company($j) }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </th>


                            <th style="width: 200px;">
                                <div style="width: 130px">
                                    <select wire:model.live="search_department" class="form-select d-none"
                                        aria-label="Default select example">
                                        <option value="">{{ __('Department') }}</option>
                                        @foreach ($departments as $j)
                                            <option value="{{ $j }}">{{ $j }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </th>
                            <th style="width: 220px;">
                                <div style="width: 130px">
                                    <select wire:model.live="search_jabatan"class="form-select d-none"
                                        aria-label="Default select example">
                                        <option value="">{{ __('Jabatan') }}</option>
                                        @foreach ($jabatans as $j)
                                            <option value="{{ $j }}">{{ nama_jabatan($j) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </th>
                            <th><button class="btn btn-primary nightowl-daylight"
                                    wire:click="refresh">{{ __('Refresh') }}</button>
                            </th>
                            <th><button class="btn btn-success nightowl-daylight"
                                    wire:click="excel">{{ __('Excel') }}</button>
                            </th>
                            <th><button type='button' class="btn btn-warning nightowl-daylight" wire:click="adjust"
                                    wire:confirm="Apakah yakin semuanya akan ditambah 100 ribu?">{{ __('+ Rp.100 Ribu') }}</button>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>


                        </tr>
                        <tr>
                            <th>{{ __('ID Karyawan') }}</th>
                            <th>{{ __('Nama') }}</th>
                            <th wire:click="sortColumnName('placement_id')">
                                {{ __('Placement') }}</th>
                            <th wire:click="sortColumnName('company_id')">
                                {{ __('Company') }}</th>

                            <th wire:click="sortColumnName('department_id')">
                                {{ __('Department') }}</th>
                            <th wire:click="sortColumnName('jabatanid')">
                                {{ __('Jabatan') }}</th>
                            <th wire:click="sortColumnName('status_karyawan')">
                                {{ __('Status') }}</th>
                            <th wire:click="sortColumnName('metode_penggajian')">
                                {{ __('Metode Penggajian') }}</th>
                            <th wire:click="sortColumnName('tanggal_bergabung')">
                                {{ __('Tanggal Bergabung') }}</th>
                            <th wire:click="sortColumnName('tanggal_bergabung')">
                                {{ __('Lama Bekerja') }}</th>
                            <th>{{ __('Gaji Pokok') }}</th>
                            <th>{{ __('Gaji Rekomendasi') }} : Rp {{ number_format($gaji_rekomendasi) }}</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $d->id_karyawan }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->placement->placement_name }}</td>
                                <td>{{ $d->company->company_name }}</td>
                                <td>{{ $d->department->nama_department }}</td>
                                <td>{{ $d->jabatan->nama_jabatan }}</td>
                                <td>{{ $d->status_karyawan }}</td>
                                <td>{{ $d->metode_penggajian }}</td>
                                <td>{{ format_tgl($d->tanggal_bergabung) }}</td>
                                <td>{{ number_format(lama_bekerja($d->tanggal_bergabung, $today)) }}</td>
                                <td>{{ number_format($d->gaji_pokok) }}</td>
                                <td class="text-center">
                                    @if (auth()->user()->role >= 6)
                                        <button data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                                            wire:click="edit(`{{ $d->id }}`)" @click="open_modal()"
                                            class="btn btn-warning btn-sm nightowl-daylight d-none"
                                            {{ is_data_locked() ? 'disabled' : '' }}>{{ __('Edit') }}</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ __('Penyesuaian Gaji Karyawan') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="d-flex flex-column">
                            <p class="form-label">{{ __('Nama') }} : {{ $nama }} </p>
                            <p class="form-label">{{ __('Rekomendasi') }} : Rp{{ number_format($gaji_rekomendasi) }}
                            </p>
                        </div>
                        <div class="d-flex gap-2 mt-2 align-items-center">
                            <input type="text" class="form-control" value="{{ number_format($gaji_pokok) }}">
                            <span>=></span>
                            <input type="text" class="form-control" wire:model.live="gaji" type-currency="IDR">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        wire:confirm="Yakin mau dirubah menjadi Rp{{ number_format(convert_numeric($gaji)) }}?"
                        wire:click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
</div>
