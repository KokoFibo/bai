<div>

    <div class="card col-3 mx-auto">
        <div class="card-header bg-danger text-light">
            <h3>Delete Tanggal Presensi</h3>
        </div>
        <div class="card-body">
            <label class="form-label">Tanggal</label>
            <input wire:model="tanggal" type="date" class="form-control">
            <div class="form-text">Masukkan tanggal yang akan di delete</div>

            <button class="btn btn-danger mt-3" wire:confirm='Yakin data nya akan dihapus ?'
                wire:click="delete">Delete</button>
            <button class="btn btn-dark mt-3" wire:click="exit">Exit</button>
        </div>
    </div>
    <div class="card col-4 mx-auto">
        <div class="card-header bg-danger text-light text-center">
            <h3>Delete Tanggal Presensi By Lokasi Pabrik</h3>
        </div>
        <div class="card-body">

            <a href="/yfuploaddelete"><button class="btn btn-danger mt-3">Go to upload data presensi yang mau
                    di delete</button></a>

            <button class="btn btn-dark mt-3" wire:click="exit">Exit</button>
        </div>

    </div>
    <div class="card col-4 mx-auto">
        <div class="card-header bg-success text-light text-center">
            <h3>Compare Tanggal presensi untuk cari data kembar</h3>
        </div>
        <div class="card-body">

            <a href="/yfuploadcompare"><button class="btn btn-success mt-3">Go to upload Compare</button></a>

            <button class="btn btn-dark mt-3" wire:click="exit">Exit</button>
        </div>

    </div>



    @include('toastr')

</div>
