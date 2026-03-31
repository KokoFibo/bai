<div>

    <div class="card">

        <div class="card-header">
            <div>
                <h3>Data Karyawan Tanpa Kontak Darurat</h3>
                <h5>Status = PKWT, PKWTT, Dirumahkan</h5>
            </div>
            <div>
                <a href="/karyawanindex"><button class="btn btn-dark">Back</button></a>
                <button class="btn btn-success" wire:click="excel">Excel</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>HP</th>
                        <th>Telepon</th>
                        <th>Company</th>
                        <th>Placement</th>
                        <th>Departement</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->id_karyawan }}</td>
                            <td>{{ $data->nama }}</td>
                            <td>{{ $data->email }}</td>
                            <td>{{ $data->hp }}</td>
                            <td>{{ $data->telepon }}</td>
                            <td>{{ $data->company }}</td>
                            <td>{{ $data->placement }}</td>
                            <td>{{ $data->departemen }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-3 pb-2">
            {{ $datas->links() }}
        </div>
    </div>
</div>
