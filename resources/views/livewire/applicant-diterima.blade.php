<div>
    <div class='mt-5 text-center'>
        <h1> Pembersihan data applicant yang sudah diterima</h1>
        <button class="btn btn-primary mt-3" wire:click='clean' disabled>Clean Up</button>
        <div>
            <div class="card">
                <div class="card-header">
                    Data Applicant double max 7729
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Apllicant iD</th>
                                <th>ID File Karyawan</th>
                                <th>Tabel USER</th>
                                <th>Tabel Karyawan</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->Nama }}</td>
                                    <td>{{ $d->email }}</td>

                                    <td>{{ $d->applicant_id }}</td>
                                    <td>{{ check_id_file_karyawan($d->applicant_id) }}</td>
                                    <td>{{ check_di_user($d->email) }}</td>
                                    <td>{{ check_di_karyawan($d->email) }}</td>
                                    <td><button class="btn btn-danger"
                                            wire:click='delete({{ $d->id }})'>Delete</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                {{ $data->links() }}
            </div>
        </div>

    </div>
</div>
