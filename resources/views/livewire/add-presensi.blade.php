<div>
    <div class="container py-4">

        <div class="row justify-content-center">
            <div class="col-12 col-sm-11 col-md-8 col-lg-5">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- Header --}}
                    <div class="card-header border-0 text-white p-4"
                        style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">

                        <div class="d-flex align-items-center gap-3">

                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 52px; height: 52px; flex-shrink: 0;">

                                <i class="bi bi-person-plus-fill fs-4"></i>

                            </div>

                            <div>
                                <h4 class="mb-1 fw-bold">
                                    Add Presensi
                                </h4>

                                <small class="opacity-75">
                                    Tambahkan data presensi karyawan
                                </small>
                            </div>

                        </div>
                    </div>


                    {{-- Body --}}
                    <div class="card-body p-4 p-md-5">

                        {{-- ID User --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold" for="user_id">
                                ID User
                            </label>

                            <div class="input-group input-group-lg">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person-badge text-primary"></i>
                                </span>

                                <input id="user_id" type="text" class="form-control bg-light border-start-0"
                                    placeholder="Masukkan ID User" wire:model.live="user_id">

                            </div>

                            <div class="form-text">
                                Masukkan ID untuk mencari data karyawan.
                            </div>

                        </div>


                        {{-- Nama Karyawan --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold" for="nama">
                                Nama Karyawan
                            </label>

                            <div class="input-group input-group-lg">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-person text-secondary"></i>
                                </span>

                                <input id="nama" type="text" class="form-control bg-light border-start-0"
                                    disabled wire:model="nama">

                            </div>

                        </div>


                        {{-- ID Karyawan --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold" for="karyawan_id">
                                ID Karyawan
                            </label>

                            <div class="input-group input-group-lg">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-card-text text-secondary"></i>
                                </span>

                                <input id="karyawan_id" type="text" class="form-control bg-light border-start-0"
                                    disabled wire:model="karyawan_id">

                            </div>

                        </div>


                        {{-- Tanggal --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold" for="date">
                                Tanggal Presensi
                            </label>

                            <div class="input-group input-group-lg">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-calendar3 text-primary"></i>
                                </span>

                                <input id="date" type="date" class="form-control bg-light border-start-0"
                                    wire:model="date">

                            </div>

                        </div>


                        {{-- Save Button --}}
                        <div class="d-grid">

                            <button type="button" class="btn btn-primary btn-lg rounded-3 fw-semibold shadow-sm"
                                wire:click="save">

                                <i class="bi bi-check-circle-fill me-2"></i>
                                Simpan Presensi

                            </button>

                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="card-footer bg-light border-0 text-center py-3">

                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Pastikan data karyawan dan tanggal sudah benar.
                        </small>

                    </div>

                </div>

            </div>
        </div>

    </div>

</div>
