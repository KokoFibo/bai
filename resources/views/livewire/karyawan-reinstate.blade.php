<div>
    <div class='col-4 mx-auto mt-5'>
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h3>Reinstate Karyawan</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="id_karyawan" class="form-label">Id Karyawan</label>
                    <input wire:model='id_karyawan' type="text" class="form-control" id="id_karyawan" disabled>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input wire:model='nama' type="text" class="form-control" id="nama" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status Karyawan</label>
                    <select wire:model='status_karyawan' class="form-select" aria-label="Default select example">
                        <option selected>Pilih Status Karyawan</option>
                        <option value="PKWT">PKWT</option>
                        <option value="PKWTT">PKWTT</option>
                        <option value="Dirumahkan">Dirumahkan</option>
                    </select>
                    @error('status_karyawan')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <button wire:click="reinstateConfirmation" class="btn btn-primary">Reinstate</button>
                    <button wire:click="cancel" class="btn btn-dark">Cancel/Exit</button>
                </div>
            </div>
        </div>
    </div>
    @script
        <script>
            window.addEventListener("show-reinstate-confirmation", (event) => {
                Swal.fire({
                    title: "Data Karyawan ini akan di reinstate?",
                    // text: "You won't be able to revert this!",
                    text: event.detail.text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, reinstate",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("reinstate-confirmed");
                    }
                });
            });
        </script>
    @endscript
</div>
