<div>
    @section('title', 'Hitung THR')

    <div class="card p-3">
        <div class="card-header">
            <div class="d-flex align-items-center gap-5">
                <h4>Data THR Karyawan OS sampai {{ format_tgl($cutOffDate) }}</h4>
                <button wire:click='excel' class="btn btn-success" wire:loading.attr="disabled">Excel</button>
                <div wire:loading wire:target="excel">
                    Generating Excell...
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <h3>Total Rp {{ number_format($total) }}</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Placement</th>
                        <th>Company</th>
                        <th>Department</th>
                        <th>Jabatan</th>
                        <th>Etnis</th>
                        <th>Status</th>
                        <th>Tanggal Bergabung</th>
                        <th>Lama Bergabung (Bulan)</th>
                        <th>Lama Bergabung (Hari)</th>
                        <th>Metode Penggajian</th>
                        <th>Gaji Pokok</th>
                        <th>THR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawans as $k)
                        <tr>
                            <td>{{ $k->id_karyawan }}</td>
                            <td>{{ $k->nama }}</td>
                            <td>{{ $k->placement->placement_name }}</td>
                            <td>{{ $k->company->company_name }}</td>
                            <td>{{ $k->department->nama_department }}</td>
                            <td>{{ $k->jabatan->nama_jabatan }}</td>
                            <td>{{ $k->etnis }}</td>
                            <td>{{ $k->status_karyawan }}</td>
                            <td>{{ $k->tanggal_bergabung }}</td>
                            <td>{{ selisihBulan($k->tanggal_bergabung, $cutOffDate) }}</td>
                            <td>{{ selisihHari($k->tanggal_bergabung, $cutOffDate) }}</td>
                            <td>{{ $k->metode_penggajian }}</td>
                            <td>{{ number_format($k->gaji_pokok) }}</td>
                            <td>{{ number_format(hitungTHR($k->id_karyawan, $k->tanggal_bergabung, $k->gaji_pokok, $cutOffDate)) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                {{ $karyawans->onEachSide(0)->links() }}
            </div>

        </div>
    </div>
</div>
