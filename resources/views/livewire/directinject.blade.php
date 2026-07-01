<div class="container py-3">

    <div class="card border-0 shadow rounded-4">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Direct Inject Presensi
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-2">

                <div class="col-6">
                    <label>User ID</label>

                    <input type="number" class="form-control form-control-lg" wire:model.defer="user_id">
                </div>

                <div class="col-6">
                    <label>Tanggal</label>

                    <input type="date" class="form-control form-control-lg" wire:model.defer="date">
                </div>

                <div class="col-12">

                    <button wire:click="cari" class="btn btn-primary w-100">

                        Cari Data

                    </button>

                </div>

            </div>

            @if ($record)
                <hr>

                <div class="row g-3">

                    <div class="col-6">
                        <label>First In</label>
                        <input type="time" class="form-control" wire:model="first_in">
                    </div>

                    <div class="col-6">
                        <label>First Out</label>
                        <input type="time" class="form-control" wire:model="first_out">
                    </div>

                    <div class="col-6">
                        <label>Second In</label>
                        <input type="time" class="form-control" wire:model="second_in">
                    </div>

                    <div class="col-6">
                        <label>Second Out</label>
                        <input type="time" class="form-control" wire:model="second_out">
                    </div>

                    <div class="col-6">
                        <label>OT In</label>
                        <input type="time" class="form-control" wire:model="overtime_in">
                    </div>

                    <div class="col-6">
                        <label>OT Out</label>
                        <input type="time" class="form-control" wire:model="overtime_out">
                    </div>

                    <div class="col-4">
                        <label>Jam Kerja</label>
                        <input type="number" step="0.1" class="form-control" wire:model="total_jam_kerja">
                    </div>

                    <div class="col-4">
                        <label>Hari Kerja</label>
                        <input type="number" step="0.1" class="form-control" wire:model="total_hari_kerja">
                    </div>

                    <div class="col-4">
                        <label>Lembur</label>
                        <input type="number" step="0.1" class="form-control" wire:model="total_jam_lembur">
                    </div>

                    <div class="col-4">
                        <label>Jam Libur</label>
                        <input type="number" step="0.1" class="form-control" wire:model="total_jam_kerja_libur">
                    </div>

                    <div class="col-4">
                        <label>Hari Libur</label>
                        <input type="number" step="0.1" class="form-control" wire:model="total_hari_kerja_libur">
                    </div>

                    <div class="col-4">
                        <label>OT Libur</label>
                        <input type="number" step="0.1" class="form-control" wire:model="total_jam_lembur_libur">
                    </div>

                    <div class="col-6">
                        <label>Late</label>
                        <input type="number" class="form-control" wire:model="late">
                    </div>

                    <div class="col-6">
                        <label>Shift</label>
                        <input type="text" class="form-control" wire:model="shift">
                    </div>

                    <div class="col-12">
                        <label>No Scan</label>
                        <input type="text" class="form-control" wire:model="no_scan">
                    </div>

                    <div class="col-12">

                        <button wire:click="simpan" class="btn btn-success btn-lg w-100">

                            💾 Simpan Perubahan

                        </button>

                    </div>

                </div>
            @endif

        </div>

    </div>

</div>
