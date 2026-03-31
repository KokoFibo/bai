<div>
    <div class="card col-3 mx-auto">
        <div class="card-header bg-danger text-light">
            <h3>Delete Presensi</h3>
        </div>
        <div class="card-body">
            <label class="form-label">Email address</label>
            <input wire:model="tanggal" type="date" class="form-control">
            <div class="form-text">Masukkan tanggal yang akan di delete</div>

            <button class="btn btn-danger mt-3" onclick="return confirm('Yakin data nya akan dihapus ?');"
                wire:click="delete">Delete</button>
            <button class="btn btn-dark mt-3" wire:click="exit">Exit</button>
        </div>
    </div>

</div>
