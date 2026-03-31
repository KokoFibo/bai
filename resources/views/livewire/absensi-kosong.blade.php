<div>
    <div class="card">
        <div class="card-header">
            <h4>Data Absensi Kosong</h4>
            <button class="btn btn-primary" wire:click="delete">Delete All</button>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Company</th>
                        <th>Placement</th>
                        <th>Metode Penggajian</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->user_id }}</td>
                            <td>{{ $d->karyawan->nama }}</td>
                            <td>{{ $d->karyawan->jabatan->nama_jabatan }}</td>
                            <td>{{ $d->karyawan->company->nama_company }}</td>
                            <td>{{ $d->karyawan->placement->placement_name }}</td>
                            <td>{{ $d->karyawan->metode_penggajian }}</td>
                            <td>{{ format_tgl($d->date) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </div>
</div>
