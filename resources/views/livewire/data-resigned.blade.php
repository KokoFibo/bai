<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-evenly">
                <div>

                    <h4>Hapus data login Karyawan Resigned & Blacklist</h4>
                </div>

                <div>
                    <select class="form-select" aria-label="Default select example" wire:model.live="sama">
                        {{-- <option selected>Pilih Bulan</option> --}}
                        <option value="=">Bulan ini</option>
                        <option value=">">Bulan lalu</option>
                    </select>
                </div>
                <div>
                    <select class="form-select" aria-label="Default select example" wire:model.live="status_karyawan">
                        {{-- <option selected>Pilih Status</option> --}}
                        <option value="Resigned">Resigned</option>
                        <option value="Blacklist">Blacklist</option>
                    </select>
                </div>
                <div>
                    <button class="btn btn-primary" wire:click="delete">Delete All</button>
                </div>

            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Metode</th>
                        <th>Status Karyawan</th>
                        @if ($status_karyawan == 'Resigned')
                            <th>Resigned</th>
                        @else
                            <th>Blacklist</th>
                        @endif
                        <th>Tanggal Bergabung</th>
                        <th>Lama Bekerja</th>
                        <th>Email</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        {{-- @if ($d->email != acakEmail($d->nama, $d->id_karyawan)) --}}
                        <tr class="{{ $d->email == acakEmail($d->nama, $d->id_karyawan) ? 'table-info' : '' }}">
                            <td>{{ $d->id_karyawan }}</td>
                            <td>{{ $d->nama }}</td>
                            <td>{{ $d->metode_penggajian }}</td>
                            <td>{{ $d->status_karyawan }}</td>
                            @if ($status_karyawan == 'Resigned')
                                <td>{{ format_tgl($d->tanggal_resigned) }}</td>
                            @else
                                <td>{{ format_tgl($d->tanggal_blacklist) }}</td>
                            @endif
                            <td>{{ format_tgl($d->tanggal_bergabung) }}</td>
                            <td>{{ lamaBekerja($d->tanggal_bergabung) }}</td>
                            <td>{{ $d->email }}</td>
                        </tr>
                        {{-- @endif --}}
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $data->links() }}
    </div>
</div>
