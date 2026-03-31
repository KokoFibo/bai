<div>
    <div class="mx-auto pt-3">
        <div class="card col-12 col-lg-4 mx-auto">
            <div class="card-header">
                <h4>Add Presensi</h4>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">ID Karyawan</label>
                    <input type="text" class="form-control" placeholder="ID" wire:model.live="user_id">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Karyawan</label>
                    <input type="text" class="form-control" disabled wire:model="nama">
                </div>
                <div class="mb-3">
                    <label class="form-label">ID Karyawan</label>
                    <input type="text" class="form-control" disabled wire:model="karyawan_id">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Presensi</label>
                    <input type="date" class="form-control" wire:model="date">
                </div>

                <button class="btn btn-primary" wire:click="save">Save</button>
            </div>
        </div>
    </div>
</div>
