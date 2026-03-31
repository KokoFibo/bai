<div>
    <div class="col-lg-6 pt-5 mx-auto">


        <h4>Data karyawan yg tidak terdapat di table USER</h4>
        <ul>
            @foreach ($missingKaryawanIds as $d)
                <li class="my-2">{{ $d }} -> <button class="btn btn-primary"
                        wire:click="create({{ $d }})">Create</button></li>
            @endforeach
        </ul>
        @if ($missingKaryawanIds)
            <div class="card">
                <div class="card-header bg-dark ">
                    <h4 class="">Data Karyawan</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Name</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="name">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Password</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="password">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Username</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="username">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Role</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="role">
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Language</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" wire:model="language">
                    </div>

                </div>

                <button class="btn btn-success" wire:click="save" {{ $is_save ? '' : 'disabled' }}>Save</button>
            </div>
        @else
            <h3>Tidak ada data USER NOT FOUND</h3>
        @endif
    </div>

</div>
