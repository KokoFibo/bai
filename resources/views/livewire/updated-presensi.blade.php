<div>
    <div class="col-lg-6 mx-auto pt-5">
        <div class="card">
            <div class="card-header">
                Updated Presensi utk check apakah presensi ada yg rubah
            </div>
            <div class="card-body">
                <div class="d-flex ">
                    <div class="mb-3 col-lg-6">
                        <label for="exampleFormControlInput1" class="form-label">Bulan</label>
                        <input type="number" class="form-control" wire:model="month">
                    </div>
                    <div class="mb-3 col-lg-6">
                        <label for="exampleFormControlInput1" class="form-label">Tahun</label>
                        <input type="number" class="form-control" wire:model="year">
                    </div>

                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Masukkan Tanggal</label>
                    <input type="date" class="form-control" wire:model="date">
                    <button class="btn btn-primary" wire:click="checkUpdatedPresensi">Check</button>
                </div>
                <div class="card-body">
                    <table class=table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->user_id }}</td>
                                    <td>{{ $d->date }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
