<div class='col-3 m-5'>
    <div class="mb-3">
        <label class="form-label">ID Lama </label>
        <input class="form-control" wire:model.live='idLama'><i>{{ getName($idLama) }}</i>
    </div>
    <div class="mb-3">
        <label class="form-label">ID Baru</label>
        <input class="form-control" wire:model.live='idBaru'><i>{{ getName($idBaru) }}</i>
    </div>
    <button type="button" class="btn btn-primary" wire:click='rubah_id'>Rubah</button>
</div>
