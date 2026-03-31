<div>

    @section('title', 'Karyawan')
    @if (auth()->user()->role == 2 || auth()->user()->role == 3)
        <div x-data="{
            search: $persist(@entangle('search').live),
            columnName: $persist(@entangle('columnName').live),
            direction: $persist(@entangle('direction').live),
            selectStatus: $persist(@entangle('selectStatus').live),
            perpage: $persist(@entangle('perpage').live),
            page: $persist(@entangle('paginators.page').live),
        
        }">


        </div>
    @endif

    <div class="d-flex flex-column flex-xl-row gap-2 justify-content-between align-items-center p-4">
        <div class="col-12 col-xl-8 gap-2 d-flex flex-column flex-xl-row gap-xl-3">
            <div class="input-group col-12 col-xl-7  ">
                <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="search" wire:model.live="search" class="form-control" placeholder="{{ __('Search') }} ...">
            </div>
            <div class="col-12 col-xl-5  d-flex align-items-center gap-3  mb-xl-0">
                <select class="form-select" wire:model.live="perpage">
                    {{-- <option selected>Open this select menu</option> --}}
                    <option value="10">10 {{ __('rows perpage') }}</option>
                    <option value="15">15 {{ __('rows perpage') }}</option>
                    <option value="20">20 {{ __('rows perpage') }}</option>
                    <option value="25">25 {{ __('rows perpage') }}</option>
                </select>
            </div>
        </div>

        <div class="col-12 col-xl-3  d-flex flex-column flex-xl-row gap-2  gap-xl-3 justify-content-end">
            <div class="col-xl-6 col-12">
                <select wire:model.live="selected_company" class="form-select" aria-label="Default select example">
                    <option value="0"selected>{{ __('All Companies') }}</option>
                    <option value="1">{{ __('Pabrik 1') }}</option>
                    <option value="2">{{ __('Pabrik 2') }}</option>
                    <option value="3">{{ __('Kantor') }}</option>
                    <option value="4">ASB</option>
                    <option value="5">DPA</option>
                    <option value="6">YCME</option>
                    <option value="7">YEV</option>
                    <option value="8">YIG</option>
                    <option value="9">YSM</option>
                </select>
            </div>
            <div class="col-12 col-xl-4">
                <select wire:model.live="selectStatus" class="form-select" aria-label="Default select example">
                    <option value="0">{{ __('All Status') }}</option>
                    <option value="1">{{ __('Aktif') }}</option>
                    <option value="2">{{ __('Non Aktif') }}</option>
                </select>
            </div>

        </div>
        <div class="col-12 col-xl-1">
            @if (Auth::user()->role > 3)
                <div class="col-12">
                    <button wire:click="excel" class="btn btn-success col-12">Excel</button></a>
                </div>
            @endif
        </div>
        {{-- </div> --}}
    </div>

    <div class="px-4">


        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-semibold fs-5 fwfs-3-xl">{{ __('Data Karyawan') }}</h3>
                    </div>
                    <a href="/karyawancreate"><button class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                            {{ __('Karyawan baru') }}</button></a>
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
                    <table class="table  table-sm  table-hover mb-2">
                        <thead>
                            <tr>
                                <th></th>
                                <th wire:click="sortColumnName('id_karyawan')">{{ __('Id Karyawan') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('nama')">{{ __('Nama') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('company')">{{ __('Company') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('placement')">{{ __('Placement') }}
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('departemen')">
                                    {{ __('Departemen') }} <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('jabatan')">{{ __('Jabatan') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                @if (Auth::user()->role > 3)
                                    <th class="text-center" wire:click="sortColumnName('level_jabatan')">
                                        {{ __('Level Jabatan') }}
                                        <i class="fa-solid fa-sort"></i>
                                @endif
                                </th>
                                <th class="text-center" wire:click="sortColumnName('status_karyawan')">
                                    {{ __('Status') }} <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('metode_penggajian')">
                                    {{ __('Metode Penggajian') }} <i class="fa-solid fa-sort"></i>
                                </th>
                                @if (Auth::user()->role > 3)
                                    <th class="text-center" wire:click="sortColumnName('tanggal_bergabung')">
                                        {{ __('Lama Bekerja') }} <i class="fa-solid fa-sort"></i>
                                    </th>
                                @endif
                                <th class="text-center" wire:click="sortColumnName('gaji_pokok')">
                                    {{ __('Gaji Pokok') }} <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('gaji_overtime')">
                                    {{ __('Overtime') }} <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('gaji_overtime')">
                                    {{ __('Iuran Air') }} <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('gaji_overtime')">
                                    {{ __('Iuran Locker') }} <i class="fa-solid fa-sort"></i>
                                </th>



                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                <tr>
                                    <td>
                                        <div class="text-start">
                                            {{-- @if (karyawan_allow_edit($data->id, Auth::user()->role)) --}}
                                            <a href="/karyawanupdate/{{ $data->id }}"><button
                                                    class="btn btn-success btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i></button></a>
                                            {{-- @else --}}
                                            {{-- <button wire:click="no_edit" class="btn btn-success btn-sm"><i --}}
                                            {{-- class="fa-regular fa-pen-to-square"></i></button> --}}
                                            {{-- @endif --}}

                                            @if (Auth::user()->role > 4)
                                                <button wire:click="delete(`{{ $data->id }}`)"
                                                    wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE"
                                                    class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash-can"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $data->id_karyawan }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td class="text-center">{{ $data->company }}</td>
                                    <td class="text-center">{{ $data->placement }}</td>
                                    <td class="text-center">{{ $data->departemen }}</td>
                                    <td class="text-center">{{ $data->jabatan }}</td>
                                    @if (Auth::user()->role > 3)
                                        <td class="text-center">{{ $data->level_jabatan }}</td>
                                    @endif
                                    <td class="text-center">{{ $data->status_karyawan }}</td>
                                    <td class="text-center">{{ $data->metode_penggajian }}</td>
                                    @if (
                                        (auth()->user()->role == 2 && $data->gaji_pokok <= 4500000) ||
                                            (auth()->user()->role == 3 && $data->gaji_pokok <= 10000000) ||
                                            auth()->user()->role > 3)
                                        @if (Auth::user()->role > 3)
                                            <td class="text-center">{{ lamaBekerja($data->tanggal_bergabung) }}</td>
                                        @endif
                                        <td class="text-center">{{ number_format($data->gaji_pokok) }}</td>
                                        <td class="text-center">{{ number_format($data->gaji_overtime) }}</td>
                                        <td class="text-center">{{ number_format($data->iuran_air) }}</td>
                                        <td class="text-center">{{ number_format($data->iuran_locker) }}</td>
                                        {{-- <td class="text-center">{{ format_tgl($data->tanggal_bergabung) }}</td> --}}
                                    @endif


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $datas->onEachSide(0)->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener("swal:confirm", (event) => {
            Swal.fire({
                title: "Apakah yakin mau di delete",
                text: "Data yang sudah di delete tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Delete",
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    @this.dispatch("delete", event.detail.id);
                }
            });
        });
    </script>

</div>
