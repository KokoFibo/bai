<div>
    @section('title', 'BPJS/PTKP')

    <div>
        <h1 class='text-center mt-3'>Daftar Karyawan yang ada gaji BPJS</h1>
    </div>
    <div class="p-5">
        <style>
            td,
            th {
                white-space: nowrap;
            }
        </style>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>HP</th>
                        <th>Company</th>
                        <th>Placement</th>
                        <th>Department</th>
                        <th>Gaji BPJS</th>
                        <th>PTKP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $d)
                        <tr>
                            <td>{{ $d->id_karyawan }}</td>
                            <td> {{ $d->nama }}</td>
                            <td> {{ $d->hp }}</td>
                            <td> {{ $d->company->company_name }}</td>
                            <td> {{ $d->placement->placement_name }}</td>
                            <td> {{ $d->department->nama_department }}</td>
                            <td> {{ number_format($d->gaji_bpjs) }}</td>
                            <td> {{ $d->ptkp }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $data->links() }}
    </div>
</div>
