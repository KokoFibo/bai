<div class="m-2">
    <div class="card shadow-sm">
        <div class="card-header {{ $is_hari_libur_nasional || $is_sunday ? 'bg-success text-light' : '' }} ">
            <h4 class="fs-5">
                Data Presensi
            </h4>
            <h5 class="fs-6">
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d M Y') }}
                {{ $is_hari_libur_nasional ? '( Hari Libur Nasional )' : '' }}
            </h5>
        </div>

        <div class="d-flex align-items-center flex-wrap gap-2 mt-3 my-2 mx-3">
            <!-- ⏪ Navigasi tanggal -->
            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="prevDate">
                &larr;
            </button>

            <input type="date" wire:model.live="tanggal" class="form-control form-control-sm w-auto text-center" />

            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="nextDate">
                &rarr;
            </button>
            @if ($totalNoScanDaily > 0)
                <p class="mb-0 text-sm m-3">No Scan : {{ $noScan }} of {{ $totalNoScanDaily }}</p>
            @endif

            @if ($absensiKosong > 0)
                <button wire:click="filterKosong" class="btn btn-sm btn-danger" type="button">{{ __('Kosong : ') }}
                    {{ $absensiKosong }}</button>
                <button wire:click="delete_presensi_kosong" wire:confirm="Yakin mau di delete semua?"
                    class="btn btn-sm btn-danger" type="button">{{ __('Delete Semua Presensi Kosong') }}</button>
            @endif
            @if ($totalNoScan > 0)
                <button wire:click="filterNoScan" class="btn btn-sm btn-warning"
                    type="button">{{ __('Total No Scan :') }} {{ $totalNoScan }}</button>
                @if (Auth::user()->role == 8)
                    <button class="btn btn-sm btn-danger" wire:confirm="Yakin mau di delete semua NOSCAN?"
                        wire:click="delete_no_scan">Delete No Scan</button>
                @endif
            @else
            @endif
        </div>
        <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mx-3">
            <!-- 🔍 Search & Filter -->
            <div class="d-flex align-items-center flex-wrap gap-2">
                <!-- 🔍 Input pencarian -->
                <input type="text" wire:model.debounce.500ms.live="search" placeholder="Cari nama / user ID"
                    class="form-control form-control-sm" style="width: 200px;" />

                <!-- 🏢 Filter Placement -->
                <select wire:model.live="placementFilter" wire:change="reload_placement_jabatan"
                    class="form-select form-select-sm" style="width: 180px;">
                    <option value="">Semua Directorate</option>
                    @foreach ($placements as $p)
                        <option value="{{ $p->id }}">{{ $p->placement_name }}</option>
                    @endforeach
                </select>

                <!-- 👔 Filter Jabatan -->
                <select wire:model.live="jabatanFilter" class="form-select form-select-sm" style="width: 180px;">
                    <option value="">Semua Jabatan</option>
                    @foreach ($jabatans as $j)
                        <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                    @endforeach
                </select>

                <!-- 👔Rows perpage -->
                <select wire:model.live="rowsPerPage" class="form-select form-select-sm" style="width: 180px;">
                    <option value="10">10 Rows perpage</option>
                    <option value="15">15 Rows perpage</option>
                    <option value="20">20 Rows perpage</option>
                    <option value="25">25 Rows perpage</option>
                </select>
            </div>
            <a href="/yfupload">
                <button class="btn btn-sm btn-success">Upload Presensi</button>
            </a>
        </div>
        @if (auth()->user()->role == 8)
            <div class="d-flex align-items-center flex-wrap gap-2 mt-2 mx-3">
                <a href="/deleteduplicatepresensi">
                    <button class="btn btn-sm btn-success nightowl-daylight">{{ __('Cek Duplikat') }}</button></a>
                <a onclick="return confirm('Mau delete Tgl Presensi?')" href="/yfdeletetanggalpresensiwr">
                    <button
                        class="btn btn-sm btn-warning nightowl-daylight">{{ __('Delete Tgl Presensi') }}</button></a>
                <a href="/addpresensi">
                    <button class="btn btn-sm btn-success nightowl-daylight">{{ __('Add Presensi') }}</button></a>
            </div>
        @endif

        <style>
            .table td,
            .table th {
                vertical-align: middle !important;
            }

            .btn-xs {
                padding: 0.15rem 0.5rem;
                font-size: 1 rem;
                line-height: 1.5;
            }
        </style>


        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm align-middle text-nowrap text-sm">

                    <thead class="table-light text-center">
                        <tr>
                            <th></th>

                            <th wire:click="sortBy('user_id')" style="cursor:pointer;">
                                User ID
                                @if ($sortField === 'user_id')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('nama')" style="cursor:pointer;">
                                Nama
                                @if ($sortField === 'nama')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('date')" style="cursor:pointer;">
                                Tanggal
                                @if ($sortField === 'date')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('metode_penggajian')" style="cursor:pointer;">
                                Metode Penggajian
                                @if ($sortField === 'metode_penggajian')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('placement_id')" style="cursor:pointer;">
                                Directorate
                                @if ($sortField === 'placement_id')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('jabatan_id')" style="cursor:pointer;">
                                Jabatan
                                @if ($sortField === 'jabatan_id')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('first_in')" style="cursor:pointer;">
                                First In
                                @if ($sortField === 'first_in')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('first_out')" style="cursor:pointer;">
                                First Out
                                @if ($sortField === 'first_out')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('second_in')" style="cursor:pointer;">
                                Second In
                                @if ($sortField === 'second_in')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('second_out')" style="cursor:pointer;">
                                Second Out
                                @if ($sortField === 'second_out')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('overtime_in')" style="cursor:pointer;">
                                Overtime In
                                @if ($sortField === 'overtime_in')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('overtime_out')" style="cursor:pointer;">
                                Overtime Out
                                @if ($sortField === 'overtime_out')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('total_hari_kerja')" style="cursor:pointer;">
                                Total Hari Kerja
                                @if ($sortField === 'total_hari_kerja')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('total_jam_kerja')" style="cursor:pointer;">
                                Total Jam Kerja
                                @if ($sortField === 'total_jam_kerja')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>



                            <th wire:click="sortBy('total_jam_lembur')" style="cursor:pointer;">
                                Total Jam Lembur
                                @if ($sortField === 'total_jam_lembur')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('total_jam_kerja_libur')" style="cursor:pointer;">
                                Total Jam Kerja Libur
                                @if ($sortField === 'total_jam_kerja_libur')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('total_hari_kerja_libur')" style="cursor:pointer;">
                                Total Hari Kerja Libur
                                @if ($sortField === 'total_hari_kerja_libur')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('total_jam_lembur_libur')" style="cursor:pointer;">
                                Total Jam Lembur Libur
                                @if ($sortField === 'total_jam_lembur_libur')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('late')" style="cursor:pointer;">
                                Late
                                @if ($sortField === 'late')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('no_scan')" style="cursor:pointer;">
                                No Scan
                                @if ($sortField === 'no_scan')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('shift')" style="cursor:pointer;">
                                Shift
                                @if ($sortField === 'shift')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>

                            <th wire:click="sortBy('shift_malam')" style="cursor:pointer;">
                                Tambahan Shift Malam
                                @if ($sortField === 'shift_malam')
                                    @if ($sortDirection === 'asc')
                                        <i class="bi bi-arrow-up"></i>
                                    @else
                                        <i class="bi bi-arrow-down"></i>
                                    @endif
                                @endif
                            </th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @foreach ($datas as $data)
                            <tr class="@if ($data->no_scan > 0) table-warning @endif">
                                <td>
                                    @if (!$is_presensi_locked || Auth::user()->role == 8)
                                        <button class="btn btn-secondary btn-xs"
                                            wire:click="edit({{ $data->id }})"><i
                                                class="fa-regular
                                        fa-pen-to-square fa-xs"></i></button>
                                    @endif

                                    <button type="button" class="btn btn-secondary btn-xs"
                                        wire:click="showDetail_baru({{ $data->user_id }})" data-bs-toggle="modal"
                                        data-bs-target="#update-form-modal"><i
                                            class="fa-solid fa-magnifying-glass fa-xs"></i></button>

                                    @if (Auth::user()->role >= 6)
                                        <button class="btn btn-danger btn-xs"
                                            wire:click="delete({{ $data->id }})"
                                            wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE"><i
                                                class="fa-solid fa-trash-can confirm-delete fa-xs"></i></button>
                                    @endif
                                </td>
                                <td>{{ $data->user_id }}</td>
                                <td>{{ $data->nama }}</td>
                                {{-- <td>{{ $data->date }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($data->date)->format('d M') }}</td>
                                <td>{{ $data->metode_penggajian }}</td>
                                <td>{{ nama_placement($data->placement_id ?? '-') }}</td>
                                <td>{{ nama_jabatan($data->jabatan_id ?? '-') }}</td>

                                <td>{{ $data->first_in ? \Carbon\Carbon::parse($data->first_in)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $data->first_out ? \Carbon\Carbon::parse($data->first_out)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $data->second_in ? \Carbon\Carbon::parse($data->second_in)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $data->second_out ? \Carbon\Carbon::parse($data->second_out)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $data->overtime_in ? \Carbon\Carbon::parse($data->overtime_in)->format('H:i') : '-' }}
                                </td>
                                <td>{{ $data->overtime_out ? \Carbon\Carbon::parse($data->overtime_out)->format('H:i') : '-' }}
                                </td>

                                <td>{{ $data->total_hari_kerja }}</td>
                                <td>{{ $data->total_jam_kerja }}</td>
                                <td>{{ $data->total_jam_lembur }}</td>
                                {{-- @if (Auth::user()->role == 8) --}}
                                <td>{{ $data->total_jam_kerja_libur }}</td>
                                <td>{{ $data->total_hari_kerja_libur }}</td>
                                <td>{{ $data->total_jam_lembur_libur }}</td>
                                {{-- @endif --}}
                                <td>{{ $data->late }}</td>
                                {{-- <td>{{ $data->no_scan }}</td> --}}
                                {{-- <td>{{ $data->no_scan_history }}</td> --}}
                                <td>
                                    @if ($data->no_scan_history == 'No Scan' && $data->no_scan == 'No Scan')
                                        <h6><span class="badge bg-warning">No Scan</span></h6>
                                    @elseif ($data->no_scan_history == 'No Scan' && $data->no_scan == null)
                                        <h6><span class="badge bg-success"><i class="fa-solid fa-check"></i></span>
                                        </h6>
                                    @else
                                        {{ $data->no_scan }}
                                    @endif
                                </td>

                                <td>{{ $data->shift }}</td>
                                <td>{{ $data->shift_malam }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="px-3">
                    {{ $datas->onEachSide(0)->links() }}
                </div>
            </div>

        </div>
    </div>
    <!-- Modal Edit -->
    {{-- edit-presensi-modal --}}
    @include('modals.edit-presensi-modal')
    @include('modals.presensi-detail')

    <script>
        document.addEventListener('keydown', function(event) {
            // Panah kiri
            if (event.key === 'ArrowLeft') {
                @this.call('prevDate'); // panggil method Livewire
            }

            // Panah kanan
            if (event.key === 'ArrowRight') {
                @this.call('nextDate');
            }
        });
    </script>
</div>
