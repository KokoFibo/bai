<div>

    <div class="col-3 mx-auto pt-5">
        <div class="card">
            <div class="card-header">
                <h2>Remove Presensi Duplikat</h2>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Tanggal Presensi</label>
                    <input wire:model="tgl" type="date" class="form-control" id="exampleFormControlInput1">
                </div>
                <button wire:click="process" class="btn btn-primary">Process</button>
            </div>
        </div>
    </div>

</div>
