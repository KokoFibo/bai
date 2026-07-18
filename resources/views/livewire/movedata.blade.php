<div>
    <div class="card">
        <div class="card-head">
            <h3>Pindah Data dari Payroll</h3>
        </div>

        <div class="card-body">

            <div class="mb-3">
                <label class="form-label fw-bold">Database Sumber</label>

                <div class="d-flex flex-wrap gap-4 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="database" id="db_os" value="os">
                        <label class="form-check-label" for="db_os">
                            OS
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="database" id="db_nonos"
                            value="nonos">
                        <label class="form-check-label" for="db_nonos">
                            Non OS
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" wire:model="database" id="db_sti"
                            value="sti">
                        <label class="form-check-label" for="db_sti">
                            STI
                        </label>
                    </div>


                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Masukkan ID yang akan dipindah</label>
                <input type="text" class="form-control" wire:model="id_karyawan">
            </div>

            <button class="btn btn-primary" wire:click="search">
                Search
            </button>

        </div>
    </div>

    @if ($data_karyawan)
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="180">ID</td>
                        <td width="20">:</td>
                        <td>{{ $data_karyawan['id_karyawan'] }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $data_karyawan['nama'] }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>:</td>
                        <td>{{ format_tgl($data_karyawan['tanggal_lahir']) }}</td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>:</td>
                        <td>{{ nama_company($data_karyawan['company_id']) }}</td>
                    </tr>
                    <tr>
                        <td>Placement</td>
                        <td>:</td>
                        <td>{{ nama_placement($data_karyawan['placement_id']) }}</td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td>:</td>
                        <td>{{ nama_department($data_karyawan['department_id']) }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>:</td>
                        <td>{{ $data_karyawan['status_karyawan'] }}</td>
                    </tr>
                </table>

                <button class="btn btn-success" wire:click="move">
                    Move
                </button>
            </div>
        </div>
    @endif
</div>
