<div wire:ignore.self class="modal fade" id="update-form-modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="padding-bottom: 200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-4" id="staticBackdropLabel">
                    Data Presensi Karyawan ({{ monthName($month) }}) {{ $year }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Tambahkan style max-height dan overflow -->
            <div class="modal-body" style="max-height: 500px; overflow-y: auto;">

                <div>
                    <p>Nama : {{ $name }} ( {{ $user_id }})</p>
                </div>

                <table class="table table-hover table-bordered mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jam Kerja</th>
                            <th class="text-center">Jam Lembur</th>
                            <th class="text-center">Jam Kerja Libur</th>
                            <th class="text-center">Jam Lembur Libur</th>
                            <th class="text-center">Terlambat</th>
                            <th class="text-center">Shift Malam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->dataArr as $index => $d)
                            <tr class="{{ $d['table_warning'] ? 'table-warning' : '' }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $d['tgl'] }}</td>
                                <td class="text-center">{{ $d['jam_kerja'] }}</td>
                                <td class="text-center">{{ $d['jam_lembur'] }}</td>
                                <td class="text-center">{{ $d['jam_kerja_libur'] }}</td>
                                <td class="text-center">{{ $d['jam_lembur_libur'] }}</td>
                                <td class="text-center">{{ $d['terlambat'] }}</td>
                                <td class="text-center">{{ $d['tambahan_shift_malam'] }} </td>
                            </tr>
                        @endforeach

                        <tr class="table-success">
                            <th class="text-center fs-5"></th>
                            @if ($total_hari_kerja_libur > 0)
                                <th class="text-center fs-5">{{ $total_hari_kerja }} + {{ $total_hari_kerja_libur }}
                                </th>
                            @else
                                <th class="text-center fs-5">{{ $total_hari_kerja }}</th>
                            @endif
                            <th class="text-center fs-5">{{ $total_jam_kerja }}</th>
                            <th class="text-center fs-5">{{ $total_jam_lembur }}</th>
                            <th class="text-center fs-5">{{ $total_jam_kerja_libur }}</th>
                            <th class="text-center fs-5">{{ $total_jam_lembur_libur }}</th>
                            <th class="text-center fs-5">{{ $total_keterlambatan }}</th>
                            <th class="text-center fs-5">{{ $total_tambahan_shift_malam }}</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
