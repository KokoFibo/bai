<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-lg-row flex-column justify-content-between align-items-center">
                <h4>Karyawan masa kerja yang lebih dari 1 tahun</h4>
                <a href="/karyawanindex"><button class="btn btn-primary">Exit</button></a>
            </div>
        </div>
        <div class="card-body">


            <table class="table table-hover mb-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Company</th>
                        <th>Placement</th>
                        <th>Departemen</th>
                        <th>Jabatana</th>
                        <th>Tanggal Bergabung</th>
                        <th>Lama Bekerja</th>
                        <th>Iuran Locker</th>
                        <th>Status Karyawan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $d->id_karyawan }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->company }}</td>
                            <td>{{ $d->placement }}</td>
                            <td>{{ $d->departemen }}</td>
                            <td>{{ $d->jabatan }}</td>
                            <td>{{ format_tgl($d->tanggal_bergabung) }}</td>
                            <td>{{ lamaBekerja($d->tanggal_bergabung) }}</td>
                            <td>{{ number_format($d->iuran_locker) }}</td>
                            <td>{{ $d->status_karyawan }}</td>
                            <td><button class="btn btn-warning" wire:click="delete(`{{ $d->id }}`)">Hapus
                                    Iuran
                                    Locker</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $data->links() }}
        </div>
    </div>

</div>
