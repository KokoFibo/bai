<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="mt-3 p-3">

        <div class="card">
            <div class="card-header">
                <h4>Create User untuk role</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">ID Karyawan</label>
                    <input wire:model.live='id_karyawan' type="text" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Nama</label>
                    <input wire:model='name' type="text" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                    <input wire:model='email' type="email" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Password tgl lahir</label>
                    <input wire:model='password' type="email" class="form-control" id="exampleFormControlInput1">
                </div>
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label">Role</label>
                    <input wire:model='role' type="text" class="form-control" id="exampleFormControlInput1">
                </div>
                @if ($is_create)
                    <button class="btn btn-primary" wire:click='create'>Create User</button>
                @else
                    <button class="btn btn-success" wire:click='resetPassword'>Reset Password (password harus diganti
                        dengan yang baru) ini hanya mengganti password di user</button>
                @endif



            </div>
        </div>
    </div>
</div>
