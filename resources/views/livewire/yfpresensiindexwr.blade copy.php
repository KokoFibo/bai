<div>
    @section('title', 'Presensi')
    {{-- <p>lock_presensi: {{ $lock_presensi }}</p>
    
    <p>tanggal: {{ $tanggal }}</p> --}}
    <div class="d-flex  flex-column flex-xl-row  col-12 col-xl-12  justify-content-xl-between gap-1 px-4  pt-4">
        <div class="col-12 col-xl-3 bg-success py-2" style=" border-radius: 10px;">
            @if ($absensiKosong == 0)
                <div class="d-flex flex-row">
                    <div class="col-4 text-center">{{ __('Hadir') }}</div>
                    <div class="col-4 text-center">{{ __('Pagi') }}</div>
                    <div class="col-4 text-center">{{ __('Malam') }}</div>
                </div>
                <div class="d-flex flex-row ">
                    <div class="col-4 text-center">{{ $totalHadir }}</div>
                    <div class="col-4 text-center">{{ $totalHadirPagi }}</div>
                    <div class="col-4 text-center">{{ $totalHadir - $totalHadirPagi }}</div>
                </div>
            @else
                <div class="d-flex flex-row">
                    <div class="col-3 text-center">{{ __('Hadir') }}</div>
                    <div class="col-3 text-center">{{ __('Pagi') }}</div>
                    <div class="col-3 text-center">{{ __('Malam') }}</div>
                    <div class="col-3 text-center">{{ __('Kosong') }}</div>
                </div>
                <div class="d-flex flex-row ">
                    <div class="col-3 text-center">{{ $totalHadir }}</div>
                    <div class="col-3 text-center">{{ $totalHadirPagi }}</div>
                    <div class="col-3 text-center">{{ $totalHadir - $totalHadirPagi }}</div>
                    <div class="col-3 text-center text-danger text-bold">{{ $absensiKosong }}</div>
                </div>
            @endif
        </div>

        <div class="col-12 col-xl-3 bg-warning py-2" style=" border-radius: 10px;">
            <div class="d-flex flex-row ">
                <div class="col-4 text-center">{{ __('No scan') }}</div>
                <div class="col-4 text-center">{{ __('Pagi') }}</div>
                <div class="col-4 text-center">{{ __('Malam') }}</div>
            </div>
            <div class="d-flex flex-row ">
                <div class="col-4 text-center">{{ $totalNoScan }} / {{ $overallNoScan }}</div>
                <div class="col-4 text-center">{{ $totalNoScanPagi }}</div>
                <div class="col-4 text-center">{{ $totalNoScan - $totalNoScanPagi }}</div>
            </div>
        </div>
        <div class="col-12 col-xl-3 bg-info py-2" style=" border-radius: 10px;">
            <div class="d-flex flex-row ">
                <div class="col-4 text-center">{{ __('Late') }}</div>
                <div class="col-4 text-center">{{ __('Pagi') }}</div>
                <div class="col-4 text-center">{{ __('Malam') }}</div>
            </div>
            <div class="d-flex flex-row ">
                <div class="col-4 text-center">{{ $totalLate }}</div>
                <div class="col-4 text-center">{{ $totalLatePagi }}</div>
                <div class="col-4 text-center">{{ $totalLate - $totalLatePagi }}</div>
            </div>
        </div>
        <div class="col-12 col-xl-3 bg-primary py-2" style=" border-radius: 10px;">
            <div class="d-flex flex-row ">
                <div class="col-4 text-center">{{ __('Overtime') }}</div>
                <div class="col-4 text-center">{{ __('Pagi') }}</div>
                <div class="col-4 text-center">{{ __('Malam') }}</div>
            </div>
            <div class="d-flex flex-row ">
                <div class="col-4 text-center">{{ $overtime }}</div>
                <div class="col-4 text-center">{{ $overtimePagi }}</div>
                <div class="col-4 text-center">{{ $overtime - $overtimePagi }}</div>
            </div>
        </div>
    </div>



    <div class="d-flex  flex-column flex-xl-row  col-12 col-xl-12 gap-2 gap-xl-0 justify-content-xl-between px-3  pt-3">

        {{-- <div class="d-flex flex-column flex-xl-row  gap-2 gap-xl-0 "> --}}
        <div class="col-xl-4 col-12">
            <div class="input-group">
                <button class="btn btn-primary" type="button"><i class="fa-solid fa-magnifying-glass"></i></button>
                <input type="search" wire:model.live="search" class="form-control"
                    placeholder="{{ __('Search') }} ...">
            </div>
        </div>
        <div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark btn-sm" wire:click="prev"> <i
                        class="fa-solid fa-arrow-left"></i></button>

                <div class="input-group">
                    <button class="btn btn-primary" type="button"><i class="fa-solid fa-calendar-days"></i></button>
                    <input type="date" wire:model.live="tanggal" class="form-control">
                </div>
                <button class="btn btn-outline-dark btn-sm" wire:click="next"><i
                        class="fa-solid fa-arrow-right"></i></button>
            </div>
        </div>
        {{-- </div> --}}
        <div class="text-center">
            {{-- <div class="col-2"> --}}
            <button wire:click="resetTanggal" class="btn btn-success" type="button">{{ __('Refresh') }}</button>
            <button wire:click="filterNoScan" class="btn btn-warning" type="button">{{ __('No Scan') }}</button>
            <button wire:click="filterLate" class="btn btn-info" type="button">{{ __('Late') }}</button>
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
            <select class="form-select" wire:model.live="location">
                {{-- <option selected>Open this select menu</option> --}}
                <option value="All">{{ __('All') }}</option>
                <option value="Kantor">{{ __('Kantor') }}</option>
                <option value="Pabrik 1">{{ __('Pabrik 1') }}</option>
                <option value="Pabrik 2">{{ __('Pabrik 2') }}</option>
            </select>
        </div>
    </div>
    @if ($data_kosong != '')
        <div class="bg-danger text-light pt-1 mt-3 px-3  text-center text-align-center">
            <h5>Data kosong ID : {{ $data_kosong }}</h5>
        </div>
    @endif
    <div class="p-3 px-xl-4">
        <div class="card">
            <div class="card-header" @if (is_sunday($tanggal)) style="background-color: #EEB8C5" @endif>
                <div class="d-flex flex-column flex-xl-row justify-content-xl-between align-items-center">
                    <h3 class="fw-semibold fs-5 fwfs-3-xl">
                        {{ __('Data Presensi') }} {{ format_tgl_hari($tanggal) }}
                    </h3>
                    <div>
                        @if (auth()->user()->role == 5)
                            <a onclick="return confirm('Mau delete Tgl Presensi?')" href="/yfdeletetanggalpresensiwr">
                                <button class="btn btn-warning">{{ __('Delete Tgl Presensi') }}</button></a>
                            <a href="/addpresensi">
                                <button class="btn btn-success">{{ __('Add Presensi') }}</button></a>
                        @endif
                        <a href="/yfupload">
                            <button class="btn btn-primary">{{ __('Upload Presensi') }}</button></a>
                    </div>
                </div>
            </div>
            <style>
                td,
                th {
                    white-space: nowrap;
                }
            </style>
            <div class="card-body ">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-4">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th wire:click="sortColumnName('user_id')">{{ __('ID') }} <i
                                        class=" fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('nama')">{{ __('Nama') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('placement')">{{ __('Placement') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('jabatan')">{{ __('Jabatan') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('date')">{{ __('Working Date') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('first_in')">{{ __('First in') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('first_out')">{{ __('First out') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('second_in')">{{ __('Second in') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('second_out')">{{ __('Second out') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('overtime_in')">{{ __('Overtime in') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('overtime_out')">{{ __('Overtime out') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('late')">{{ __('Late') }} <i
                                        class="fa-solid fa-sort"></i></th>
                                <th wire:click="sortColumnName('no_scan')">{{ __('No scan') }} <i
                                        class="fa-solid fa-sort"></i>
                                </th>
                                <th wire:click="sortColumnName('shift')">{{ __('Shift') }} <i
                                        class="fa-solid fa-sort"></i></th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($datas->isNotEmpty())


                                @foreach ($datas as $data)
                                    {{-- {{ dd($data) }} --}}

                                    <tr x-data="{ edit: false }"
                                        class="{{ $data->no_scan ? 'table-warning' : '' }} {{ absen_kosong($data->first_in, $data->first_out, $data->second_in, $data->second_out, $data->overtime_in, $data->overtime_out) ? 'table-danger' : '' }}">
                                        <td>

                                            @if ($btnEdit == true)
                                                {{-- @if ($lock_presensi == true && Auth::user()->role <= 3) --}}
                                                <button @click="edit = !edit"
                                                    wire:click="update({{ $data->id }})"
                                                    class="btn btn-success btn-sm"
                                                    {{ $lock_presensi == true && Auth::user()->role <= 3 ? 'disabled' : '' }}><i
                                                        class="fa-regular fa-pen-to-square"></i></button>
                                                {{-- @endif --}}
                                            @else
                                                @if ($data->id == $selectedId)
                                                    <button @click="edit = !edit" wire:click="save"
                                                        class="btn btn-primary btn-sm"><i
                                                            class="fa-solid fa-floppy-disk"></i></button>
                                                @else
                                                    <button @click="edit = !edit" disabled wire:click="save"
                                                        class="btn btn-success btn-sm"><i
                                                            class="fa-regular fa-pen-to-square"></i></button>
                                                @endif
                                            @endif
                                            <button type="button" class="btn btn-warning btn-sm"
                                                wire:click="showDetail({{ $data->user_id }})" data-bs-toggle="modal"
                                                data-bs-target="#update-form-modal"><i
                                                    class="fa-solid fa-magnifying-glass"></i></button>

                                            @if (Auth::user()->role > 2)
                                                <button {{-- wire:click="confirmDelete(`{{ $data->id }}`)" --}} wire:click="delete({{ $data->id }})"
                                                    wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE"
                                                    {{ Auth::user()->role == 3 && $lock_presensi == true ? 'disabled' : '' }}
                                                    class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash-can confirm-delete"></i></button>
                                            @endif

                                        </td>
                                        <td>{{ $data->user_id }}</td>
                                        <td>{{ $data->karyawan->nama }}</td>

                                        {{-- <td>{{ $data->karyawan->departemen }}</td> --}}
                                        <td>{{ $data->karyawan->placement }}</td>
                                        <td>{{ $data->karyawan->jabatan }}</td>
                                        <td>{{ format_tgl($data->date) }}</td>
                                        <td x-show="!edit"
                                            class="{{ checkFirstInLate($data->first_in, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                            {{ format_jam($data->first_in) }} </td>
                                        <td x-show="edit"><input
                                                style="width:100px; background-color: #ffeeba;; background-color: #ffeeba"
                                                class="form-control @error('first_in') is-invalid @enderror"
                                                id="first_in" type="text" wire:model="first_in">
                                            @error('first_in')
                                                <span>Format jam harus sesuai HH:MM</span>
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit"
                                            @if (is_jabatan_khusus($data->karyawan->jabatan) == 1) class="{{ checkFirstOutLate($data->first_out, $data->shift, $data->date, $data->karyawan->jabatan) ? 'text-danger' : '' }}" @endif>
                                            {{ format_jam($data->first_out) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('first_out') is-invalid @enderror"
                                                id="first_out" type="text" wire:model="first_out">
                                            @error('first_out')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror

                                        </td>
                                        <td x-show="!edit"
                                            @if (is_jabatan_khusus($data->user_id) == 0) class="{{ checkSecondInLate($data->second_in, $data->shift, $data->first_out, $data->date, $data->karyawan->jabatan) ? 'text-danger' : '' }}" @endif>
                                            {{ format_jam($data->second_in) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('second_in') is-invalid @enderror"
                                                id="second_in" type="text" wire:model="second_in">
                                            @error('second_in')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit"
                                            @if (is_jabatan_khusus($data->user_id) == 0) class="{{ checkSecondOutLate($data->second_out, $data->shift, $data->date, $data->karyawan->jabatan) ? 'text-danger' : '' }}" @endif>
                                            {{ format_jam($data->second_out) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('second_out') is-invalid @enderror"
                                                id="second_out" type="text" wire:model="second_out">
                                            @error('second_out')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit">{{ format_jam($data->overtime_in) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('overtime_in') is-invalid @enderror"
                                                id="overtime_in" type="text" wire:model="overtime_in">
                                            @error('overtime_in')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        <td x-show="!edit">
                                            {{ format_jam($data->overtime_out) }} </td>
                                        <td x-show="edit"><input style="width:100px; background-color: #ffeeba;"
                                                class="form-control @error('overtime_out') is-invalid @enderror"
                                                id="overtime_out" type="text" wire:model="overtime_out">
                                            @error('overtime_out')
                                                <div class="invalid-feedback">
                                                    Format jam harus sesuai HH:MM
                                                </div>
                                            @enderror
                                        </td>
                                        {{-- <td
                                            class="{{ checkFirstOutLate($data->first_out, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                                {{ format_jam($data->first_out) }}</td> --}}
                                        {{-- <td
                                        class="{{ checkSecondInLate($data->second_in, $data->shift, $data->first_out, $data->date) ? 'text-danger' : '' }}">
                                                {{ format_jam($data->second_in) }}</td> --}}
                                        {{-- <td
                                            class="{{ checkSecondOutLate($data->second_out, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                                {{ format_jam($data->second_out) }}</td> --}}
                                        {{-- <td
                                            class="{{ checkOvertimeInLate($data->overtime_in, $data->shift, $data->date) ? 'text-danger' : '' }}">
                                                {{ format_jam($data->overtime_in) }}</td> --}}
                                        {{-- <td>
                                            {{ format_jam($data->overtime_out) }}</td> --}}
                                        <td>
                                            @if ($data->late_history >= 1 && $data->late >= 1)
                                                <h6><span class="badge bg-info">Late</span>
                                                </h6>
                                            @elseif ($data->late_history >= 1 && $data->late == null)
                                                <h6><span class="badge bg-success"><i class="fa-solid fa-check"></i>
                                                        {{ $data->late_history }}
                                                    </span>
                                                </h6>
                                            @else
                                                {{-- {{ $data->late }} --}}
                                                <span></span>
                                            @endif

                                        </td>
                                        <td x-show="!edit">
                                            @if ($data->no_scan_history == 'No Scan' && $data->no_scan == 'No Scan')
                                                <h6><span class="badge bg-warning">No Scan</span></h6>
                                            @elseif ($data->no_scan_history == 'No Scan' && $data->no_scan == null)
                                                <h6><span class="badge bg-success"><i
                                                            class="fa-solid fa-check"></i></span>
                                                </h6>
                                            @else
                                                {{ $data->no_scan }}
                                            @endif
                                            </i>
                                        </td>
                                        <td x-show="edit">
                                            @if ($data->no_scan_history == 'No Scan' && $data->no_scan == '')
                                                <button wire:click="delete_no_scan({{ $data->id }})"
                                                    wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE"
                                                    class="btn btn-danger btn-sm"><i
                                                        class="fa-solid fa-trash-can confirm-delete"></i>
                                                </button>
                                            @endif
                                        </td>
                                        <td>{{ $data->shift }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <h4>Tidak ada data yang ditemukan</h4>
                            @endif
                        </tbody>
                    </table>
                    {{ $datas->onEachSide(0)->links() }}
                </div>

            </div>
        </div>
    </div>
    {{-- <style>
        [] {
            display: none !important;
        }
    </style> --}}

    @include('modals.presensi')
    {{-- Modal ook --}}

    {{-- End  --}}
    {{-- <script>
        window.addEventListener("swal:confirm_delete_presensi", (event) => {
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
    </script> --}}

</div>
