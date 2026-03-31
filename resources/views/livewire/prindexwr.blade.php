<div>

    @section('title', 'Presensi Detail')
    <h4 class="text-center text-bold pt-2 my-3">{{ __('Presensi Detail') }}</h4>
    @if (check_rebuild_done())
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Congratulation!</strong> Payroll Succesfully rebuilt.
            <button wire:click='close_succesful_rebuilt' type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
        </div>
    @endif
    @if (check_rebuilding())
        <div class="alert alert-primary" role="alert">
            <strong>Payroll is rebuilding ...</strong> You may safely leave this page.
        </div>
    @endif
    @if ($fail = check_fail_job())
        <div class="alert alert-danger" role="alert">
            <strong>Errror building payroll</strong>
        </div>
    @endif
    <div class="d-flex flex-xl-row flex-column justify-content-xl-between gap-lg-0 gap-2 px-4">
        {{-- <div class="col-xl-8 col-12 p-2 p-xl-4 d-flex flex-xl-row flex-column  gap-xl-0 gap-2"> --}}
        <div class="col-xl-4 col-12">
            <div class="input-group">
                <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="search" wire:model.live="search" class="form-control"
                    placeholder="{{ __('Search') }} ...">
            </div>
        </div>
        <div>
            <select class="form-select" wire:model.live="perpage">
                {{-- <option selected>Open this select menu</option> --}}
                <option value="10">10 {{ __('rows perpage') }}</option>
                <option value="15">15 {{ __('rows perpage') }}</option>
                <option value="20">20 {{ __('rows perpage') }}</option>
                <option value="25">25 {{ __('rows perpage') }}</option>
            </select>
        </div>
        <div>
            <select class="form-select" wire:model.live="year">
                {{-- <option selected>Open this select menu</option> --}}
                {{-- <option value="2023">2023</option>
                <option value="2024">2024</option> --}}
                @foreach ($select_year as $sy)
                    <option value="{{ $sy }}">{{ $sy }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-12">
            <select class="form-select" wire:model.live="month">
                @foreach ($select_month as $sm)
                    <option value="{{ $sm }}">{{ monthName($sm) }}</option>
                @endforeach
            </select>
        </div>

        {{-- <a href="/presensisummaryindex"><button
                class="btn btn-success {{ auth()->user()->role < 6 ? 'invisible' : '' }} nightowl-daylight">Excel</button></a> --}}
    </div>


    <div class="p-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3 class="fw-semibold fs-5 fwfs-3-xl">{{ __('Detail Jam kerja karyawan per') }}
                        {{ monthYear($periode) }},
                        {{ __('Data Terakhir Tanggal') }}
                        {{ format_tgl($lastData) }}
                    </h3>

                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table mb-3">
                        <thead>
                            <tr>
                                <th wire:click="sortColumnName('user_id')">{{ __('User ID') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('nama')">{{ __('Name') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('jabatan')">{{ __('Jabatan') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('metode_penggajian')">{{ __('Metode') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('status_karyawan')">{{ __('Status') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th class="text-center" wire:click="sortColumnName('total_hari_kerja')">
                                    {{ __('Total Hari Kerja') }}
                                    <i class="fa-solid fa-sort"></i>
                                <th class="text-center" wire:click="sortColumnName('jumlah_jam_kerja')">
                                    {{ __('Jumlah Jam Kerja') }}
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('jumlah_menit_lembur')">
                                    {{ __('Jumlah Jam Overtime') }} <i class="fa-solid fa-sort"></i></th>

                                <th class="text-center" wire:click="sortColumnName('jumlah_jam_terlambat')">
                                    {{ __('Jumlah Jam Terlambat') }}
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('tambahan_jam_shift_malam')">
                                    {{ __('Tambahan Jam Overtime Shift Malam') }}
                                    <i class="fa-solid fa-sort"></i>
                                </th>
                                <th class="text-center" wire:click="sortColumnName('total_noscan')">
                                    {{ __('Total No Scan') }} <i class="fa-solid fa-sort"></i></th>

                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filteredData as $item)
                                {{-- {{ dd($item) }} --}}
                                <tr
                                    class="{{ $item->karyawan->status_karyawan == 'Resigned' ? 'table-warning' : '' }}">
                                    <td>{{ $item->user_id }}</td>
                                    <td>{{ $item->karyawan->nama }}</td>
                                    <td>{{ nama_jabatan($item->karyawan->jabatan_id) }}</td>
                                    <td>{{ $item->karyawan->metode_penggajian }}</td>
                                    <td>{{ $item->karyawan->status_karyawan }}</td>
                                    <td class="text-center">{{ $item->total_hari_kerja }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_jam_kerja, 1) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_menit_lembur, 1) }}</td>
                                    <td class="text-center">{{ number_format($item->jumlah_jam_terlambat, 1) }}</td>
                                    <td class="text-center">{{ number_format($item->tambahan_jam_shift_malam) }}</td>
                                    <td class="text-center">{{ number_format($item->total_noscan) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $filteredData->onEachSide(0)->links() }}
                </div>
            </div>
            <p class="px-3 text-success">{{ __('Last update') }}: {{ $last_build }} </p>
        </div>
    </div>

    <style>
        td,
        th {
            white-space: nowrap;
        }

        @media (min-width : 600px) {

            table th {
                z-index: 2;
            }

            td:first-child,
            th:first-child {
                position: sticky;
                left: 0;
                z-index: 1;
            }

            td:nth-child(2),
            th:nth-child(2) {
                position: sticky;
                left: 92px;
                z-index: 1;
            }



            th:first-child,
            th:nth-child(2) {
                z-index: 3;
            }
        }
    </style>
    <script>
        window.addEventListener("confirm", (event) => {
            Swal.fire({
                title: "Apakah yakin mau di Generate Ulang ?",
                // text: "Data yang sudah di delete tidak dapat dikembalikan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
            }).then((willDelete) => {
                if (willDelete.isConfirmed) {
                    @this.dispatch("getPayroll");
                }
            });
        });
        window.addEventListener("foundError", (event) => {
            console.log(event);
            Swal.fire({
                icon: 'error',
                title: event.detail.title,
                text: 'Ada Kesalahan',

            })
        });
    </script>
</div>
