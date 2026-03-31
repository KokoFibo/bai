<div>
    <div>
        <div class="card">
            <div class="card-head">
                <h3>Pindah data dari Payroll</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Masukkan ID yang akan dipindah :</label>
                    <input type="text" class="form-control" wire:model='id_karyawan'>
                </div>
            </div>
            <button class="btn btn-primary" wire:click='search'>Search</button>
        </div>

        @if ($data_karyawan)
            {{-- <table class="display-6"> --}}
            <h3>

                <table>
                    <tr>
                        <td>ID</td>
                        <td class='px-3'>:</td>
                        <td>{{ $data_karyawan['id_karyawan'] }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td class='px-3'>:</td>
                        <td>{{ $data_karyawan['nama'] }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal lahir</td>
                        <td class='px-3'>:</td>
                        <td>{{ format_tgl($data_karyawan['tanggal_lahir']) }}</td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td class='px-3'>:</td>
                        <td>{{ nama_company($data_karyawan['company_id']) }}</td>
                    </tr>
                    <tr>
                        <td>Placement</td>
                        <td class='px-3'>:</td>
                        <td>{{ nama_placement($data_karyawan['placement_id']) }}</td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td class='px-3'>:</td>
                        <td>{{ nama_department($data_karyawan['department_id']) }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td class='px-3'>:</td>
                        <td>{{ $data_karyawan['status_karyawan'] }}</td>
                    </tr>


                </table>
            </h3>
            <button class="btn btn-primary mt-2" wire:click='move'>Move</button>
        @endif
    </div>
</div>
