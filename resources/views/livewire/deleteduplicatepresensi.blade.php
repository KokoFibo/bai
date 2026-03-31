<div>
    <div class="card">
        <div class="card-header">
            <h3>Data Presensi Duplikat {{ $duplicates_count }} data</h3>
            <div class="mb-3">
                <label class="form-label">Year</label>
                <input type="number" class="form-control" wire:model='year'>
            </div>
            <div class="mb-3">
                <label class="form-label">Month</label>
                <input type="number" class="form-control" wire:model='month'>
            </div>
            <button class="btn btn-primary" wire:click='search_duplicate'>Search for duplicate</button>
            <button class="btn btn-primary" wire:click='delete_duplikat'>Delete</button>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($duplicates as $d)
                        <tr>
                            <td>{{ $d->user_id }}</td>
                            <td>{{ $d->user_id }}</td>
                            <td>{{ $d->date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
