<div>

    @section('title', 'Karyawan')

    {{-- aktifkan ini supaya datanya bisa lengket --}}

    {{-- @if (auth()->user()->role == 5 || auth()->user()->role == 6)
        <div x-data="{
            search: $persist(@entangle('search').live),
            columnName: $persist(@entangle('columnName').live),
            direction: $persist(@entangle('direction').live),
            selectStatus: $persist(@entangle('selectStatus').live),
            perpage: $persist(@entangle('perpage').live),
            page: $persist(@entangle('paginators.page').live),
        }">
        </div>
    @endif --}}





    <div class="px-4 mt-3">


        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-xl-row  justify-content-between  align-items-center">
                    <div class="col-12 col-xl-3">
                        <h3 class="fw-semibold fs-5 fwfs-3-xl">{{ __('Data Karyawan') }}</h3>
                    </div>
                    <div {{-- class="col-12 d-flex flex-column flex-xl-row justify-content-end gap-xl-3 gap-2 col-12 col-xl-6"> --}}
                        class="col-12 d-flex flex-column flex-xl-row justify-content-end gap-xl-3 gap-2  col-xl-9">

                        <div class="col-12 col-xl-3">
                            <select class="form-select" wire:model.live="perpage">
                                {{-- <option selected>Open this select menu</option> --}}
                                <option value="10">10 {{ __('rows perpage') }}</option>
                                <option value="15">15 {{ __('rows perpage') }}</option>
                                <option value="20">20 {{ __('rows perpage') }}</option>
                                <option value="25">25 {{ __('rows perpage') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-xl-3">
                            <select wire:model.live="selectStatus" class="form-select"
                                aria-label="Default select example">
                                <option value="0">{{ __('All Status') }}</option>
                                <option value="1">{{ __('Aktif') }}</option>
                                <option value="2">{{ __('Resigned') }}</option>
                                <option value="3">{{ __('Blacklist') }}</option>
                            </select>
                        </div>

                        <div class="col-12 col-xl-3">
                            <button wire:click="reset_filter"
                                class="btn btn-success col-12">{{ __('Refresh') }}</button>
                        </div>
                        <div class="col-12 col-xl-3">
                            <a href="/karyawancreate"><button class="btn btn-primary col-12"><i
                                        class="fa-solid fa-plus"></i>
                                    {{ __('Karyawan baru') }}</button></a>
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
            <div class="card-body">
                <div class="table-responsive">

                    <table class="table  table-sm  table-hover mb-2 ">
                        <thead>
                            <tr>
                                <th style="width: 50px; border-style: none;"></th>
                                <th style="width: 130px; border-style: none;">

                                    <input wire:model.live="search_id_karyawan" type="text" class="form-control"
                                        placeholder="{{ __('ID Karyawan') }}">
                                </th>
                                <th style="border-style: none;">
                                    <input wire:model.live="search_nama" type="text" class="form-control"
                                        placeholder="{{ __('Nama Karyawan') }}">
                                </th>

                                <th style="width:130px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_placement" class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Placement') }}</option>
                                            @foreach ($placements as $j)
                                                {{-- <option value="{{ $j->id }}">{{ $j->placement_name }}</option> --}}
                                                <option value="{{ $j }}">{{ nama_placement($j) }}
                                                    {{-- <option value="{{ $j }}">{{ $j }} --}}
                                                </option>
                                            @endforeach
                                            {{-- <option value="YCME">YCME</option>
                                            <option value="YEV">YEV</option>
                                            <option value="YAM">YAM</option>
                                            <option value="YIG">YIG</option>
                                            <option value="YSM">YSM</option> --}}
                                            {{-- <option value="1">YCME</option>
                                            <option value="2">YEV</option>
                                            <option value="6">YAM</option>
                                            <option value="4">YIG</option>
                                            <option value="5">YSM</option> --}}


                                        </select>
                                    </div>
                                </th>

                                <th style="width: 130px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_company" class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Company') }}</option>
                                            {{-- <option value="ASB">ASB</option>
                                            <option value="DPA">DPA</option>
                                            <option value="YCME">YCME</option>
                                            <option value="YEV">YEV</option>
                                            <option value="YIG">YIG</option>
                                            <option value="YSM">YSM</option>
                                            <option value="YAM">YAM</option> --}}
                                            @foreach ($companies as $j)
                                                <option value="{{ $j }}">{{ nama_company($j) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>
                                <th style="width: 200px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_department" class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Department') }}</option>
                                            @foreach ($departments as $j)
                                                <option value="{{ $j }}">{{ nama_department($j) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>
                                <th style="width: 220px; border-style: none;">
                                    <div style="width: 130px">
                                        <select wire:model.live="search_jabatan"class="form-select"
                                            aria-label="Default select example">
                                            <option value="">{{ __('Jabatan') }}</option>
                                            @foreach ($jabatans as $j)
                                                <option value="{{ $j }}">{{ nama_jabatan($j) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </th>
                                @if (auth()->user()->role >= 7)
                                    <th style="width: 220px; border-style: none;">
                                        <div style="width: 130px">
                                            <select wire:model.live="search_etnis" class="form-select"
                                                aria-label="Default select example">
                                                <option value="">{{ __('Etnis') }}</option>
                                                {{-- <option value="Jawa">{{ __('Jawa') }}</option>
                                                <option value="Sunda">{{ __('Sunda') }}</option>
                                                <option value="Tionghoa">{{ __('Tionghoa') }}</option>
                                                <option value="China">{{ __('China') }}</option>
                                                <option value="Lainnya">{{ __('Lainnya') }}</option>
                                                <option value="kosong">{{ __('Masih Kosong') }}</option> --}}
                                                @foreach ($etnises as $j)
                                                    @if ($j == '')
                                                        <option value="kosong">{{ __('Masih Kosong') }}</option>
                                                    @else
                                                        <option value="{{ $j }}">{{ $j }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </th>
                                    @if (Auth::user()->role > 6)
                                        <th style="width: 150px; border-style: none;">
                                            <button wire:click="excel" class="btn btn-success col-12">Excel</button></a>
                                        </th>
                                    @endif

                                    {{-- <th style="width: 150px; border-style: none;">
                                        <button wire:click="excelByDepartment" class="btn btn-success btn-sm mb-1"
                                            @if ($search_placement == null || $search_department == null) disabled @endif>Excel by
                                            Departement</button>
                                    </th>
                                    <th style="width: 150px; border-style: none;">
                                        <button wire:click="excelByEtnis" class="btn btn-success btn-sm mb-1"
                                            @if ($search_etnis == null) disabled @endif>Excel by
                                            Etnis</button>
                                    </th> --}}
                                @endif
                                @if ($is_tanggal_gajian || auth()->user()->role == 5)
                                    <th style="width: 150px; border-style: none;">
                                        <a href="/iuranlocker"><button
                                                class="btn btn-primary {{ is_data_locked() ? 'd-none' : '' }}">Hapus
                                                Iuran Locker</button></a>
                                    </th>
                                @endif

                            </tr>

                            <tr>
                                <th></th>
                                <th wire:click="sortColumnName('id_karyawan')">{{ __('ID Karyawan') }}
                                </th>
                                <th wire:click="sortColumnName('nama')">{{ __('Nama') }} </th>
                                <th class="text-center" wire:click="sortColumnName('placement_id')">
                                    {{ __('Placement') }}

                                </th>
                                <th class="text-center" wire:click="sortColumnName('company_id')">
                                    {{ __('Company') }} </th>
                                <th class="text-center" wire:click="sortColumnName('department_id')">
                                    {{ __('Department') }}
                                </th>
                                <th class="text-center" wire:click="sortColumnName('jabatan_id')">
                                    {{ __('Jabatan') }} </th>
                                @if (Auth::user()->role > 6)
                                    <th class="text-center" wire:click="sortColumnName('etnis')">
                                        {{ __('Etnis') }}
                                        {{-- level jabatan smeentar di hide dulu --}}
                                        {{-- <th class="text-center" wire:click="sortColumnName('level_jabatan')">
                                        {{ __('Level Jabatan') }} --}}
                                @endif
                                </th>
                                <th class="text-center" wire:click="sortColumnName('status_karyawan')">
                                    {{ __('Status') }}
                                </th>
                                @if (Auth::user()->role > 5)
                                    <th class="text-center" wire:click="sortColumnName('tanggal_bergabung')">
                                        {{ __('Lama Bekerja') }}
                                    </th>
                                @endif
                                <th class="text-center" wire:click="sortColumnName('metode_penggajian')">
                                    {{ __('Metode Penggajian') }}
                                </th>
                                <th class="text-center" wire:click="sortColumnName('gaji_pokok')">
                                    {{ __('Gaji Pokok') }}
                                </th>
                                <th class="text-center" wire:click="sortColumnName('gaji_overtime')">
                                    {{ __('Overtime') }}
                                </th>
                                <th class="text-center" wire:click="sortColumnName('iuran_air')">
                                    {{ __('Iuran Air') }}
                                </th>
                                <th class="text-center" wire:click="sortColumnName('iuran_locker')">
                                    {{ __('Iuran Locker') }}
                                </th>
                                @if ($selectStatus == 2 && auth()->user()->role > 6)
                                    <th class="text-center">
                                        {{ __('Lama bekerja') }}
                                    </th>
                                @endif


                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>
                                        <div class="text-start">
                                            <a href="/karyawanupdate/{{ $data->id }}"><button
                                                    class="btn btn-success btn-sm {{ is_data_locked() ? 'd-none' : '' }}"><i
                                                        class="fa-regular fa-pen-to-square"></i></button></a>
                                            {{-- ok --}}
                                            @if ($selectStatus == 2)
                                                <a href="/karyawanreinstate/{{ $data->id }}"><button
                                                        class="btn btn-warning btn-sm {{ is_data_locked() ? 'd-none' : '' }}">R</button></a>
                                            @endif


                                            @if (Auth::user()->role > 7)
                                                <button wire:click="delete(`{{ $data->id }}`)"
                                                    wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE"
                                                    class="btn btn-danger btn-sm {{ is_data_locked() ? 'd-none' : '' }}"><i
                                                        class="fa-solid fa-trash-can"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $data->id_karyawan }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td class="text-center">{{ $data->placement->placement_name }}</td>
                                    <td class="text-center">{{ $data->company->company_name }}</td>
                                    <td class="text-center">{{ $data->department->nama_department }}</td>
                                    <td class="text-center">{{ $data->jabatan->nama_jabatan }}</td>

                                    @if (Auth::user()->role > 6)
                                        <td class="text-center">{{ $data->etnis }}</td>
                                        {{-- level jabatan smeentar di hide dulu --}}
                                        {{-- <td class="text-center">{{ $data->level_jabatan }}</td> --}}
                                    @endif
                                    <td class="text-center">{{ $data->status_karyawan }}</td>
                                    {{-- @if ((auth()->user()->role == 5 && $data->gaji_pokok <= 4500000) || (auth()->user()->role == 6 && $data->gaji_pokok <= 10000000) || auth()->user()->role > 6) --}}
                                    @if ((auth()->user()->role == 5 && $data->gaji_pokok <= 4500000) || auth()->user()->role >= 6)
                                        @if (Auth::user()->role > 5)
                                            <td class="text-center">{{ lamaBekerja($data->tanggal_bergabung) }}
                                            </td>
                                        @endif
                                        <td class="text-center">{{ $data->metode_penggajian }}</td>
                                        <td class="text-center">{{ number_format($data->gaji_pokok) }}</td>
                                        <td class="text-center">{{ number_format($data->gaji_overtime) }}</td>
                                        <td class="text-center">{{ number_format($data->iuran_air) }}</td>
                                        <td class="text-center">{{ number_format($data->iuran_locker) }}</td>
                                        {{-- <td class="text-center">{{ format_tgl($data->tanggal_bergabung) }}</td> --}}
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                    @if ($selectStatus == 5 && auth()->user()->role > 6)
                                        <td class="text-center">
                                            {{ lama_resign($data->tanggal_bergabung, $data->tanggal_resigned, $data->tanggal_blacklist) }}
                                        </td>
                                    @endif


                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $datas->onEachSide(0)->links() }}
                    {{-- {{ $datas->links() }} --}}
                </div>
            </div>
        </div>
    </div>
    @script
        <script>
            document.addEventListener("reinstate", (event) => {
                const data = event.detail;
                Swal.fire({
                    position: "center",
                    icon: data.type,
                    title: data.title,
                    showConfirmButton: false,
                    timer: 1500,
                });
            });
        </script>
    @endscript
</div>
