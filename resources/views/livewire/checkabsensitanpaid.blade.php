<div>
    <div class="mb-3">
        <label class="form-label">Year</label>
        <input type="number" class="form-control" wire:model.live='year'>
    </div>
    <div class="mb-3">
        <label class="form-label">Month</label>
        <input type="number" class="form-control" wire:model.live='month'>
    </div>
    {{-- <button wire:click='check'>Proses</button> --}}
    <h4>ID yang tidak ditemukan dalam database</h4>
    <button class='btn btn-primary' wire:click='delete'>Delete</button>
    @if (!empty($data))
        <ul class="list-group">
            @foreach ($data as $u)
                <li class="list-group-item">{{ $u }}</li>
            @endforeach
        </ul>
    @else
        <h4>No data found</h4>
    @endif

</div>
