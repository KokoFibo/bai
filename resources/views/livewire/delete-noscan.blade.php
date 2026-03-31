<div class="container">
    <h3 class="mt-5">Aplikasi untuk mendelete data No Scan History pada presensi berdasarkan tanggal</h3>
    <div class="mt-5">
        <button wire:click='show_noscan_button' class="btn btn-primary" disabled>Show No Scan (sementar di
            disabled)</button>
        <button wire:click='show_noscan_history_button' class="btn btn-primary">Show No Scan History</button>
    </div>
    <div class="col-3 mt-3">
        <label class="form-label">Masukan tanggal no scan</label>
        <input class='form-control' type="date" wire:model.live='tgl_noscan'>
    </div>


    @if ($data != null)
        <div>
            <div class="card mt-5">
                <div class="card-header">
                    @if ($show_noscan)
                        <div class="d-flex justify-content-between">
                            <h3>Data No Scan</h3>
                            <button wire:click='delete_noscan' class="btn btn-danger">Delete Data No Scan</button>
                        </div>
                    @else
                        <div class="d-flex justify-content-between">
                            <h3>Data No Scan History</h3>
                            <button wire:click='delete_noscan_history' class="btn btn-danger">Delete Data No Scan
                                History</button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Karyawan</th>
                                <th>date</th>
                                <th>no_scan </th>
                                <th>no_scan_history</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $d->karyawan_id }}</td>
                                    <td>{{ $d->date }}</td>
                                    <td>{{ $d->no_scan }}</td>
                                    <td>{{ $d->no_scan_history }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $data->links() }}
                </div>
            </div>
        </div>
    @else
        <h1>No data found!!</h1>
    @endif

</div>
