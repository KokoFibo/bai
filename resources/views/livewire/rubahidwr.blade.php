<div>
    rubahID
    {{-- <button wire:click="rubah" class="btn btn-primary">Process</button> --}}

    {{-- <ul>
        @for ($i = 0; $i < count($idFromArr); $i++)
            <li>{{ $idFromArr[$i] }} to {{ $idToArr[$i] }}</li>
        @endfor
    </ul> --}}

    <h1>Rubah ID</h1>
    <h3>ID dirubah dari <input wire:model="from" type="text"> ke ID <input wire:model="to" type="text"> <button
            class="btn btn-primary" wire:click="proses">Proses</button> </h3>
</div>
