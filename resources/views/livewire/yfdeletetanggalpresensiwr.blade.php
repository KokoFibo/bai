<div class="container py-4">

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="card-header border-0 bg-danger text-white p-4">
                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px; flex-shrink: 0;">
                            <i class="bi bi-calendar-x fs-4"></i>
                        </div>

                        <div>
                            <h4 class="mb-1 fw-bold">
                                Delete Tanggal Presensi
                            </h4>
                            <small class="opacity-75">
                                Hapus data presensi berdasarkan tanggal
                            </small>
                        </div>

                    </div>
                </div>

                {{-- Body --}}
                <div class="card-body p-4 p-md-5">

                    {{-- Warning --}}
                    <div class="alert alert-warning border-0 rounded-3 d-flex align-items-start gap-3 mb-4"
                        role="alert">

                        <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>

                        <div>
                            <div class="fw-semibold mb-1">
                                Perhatian
                            </div>
                            <small>
                                Data presensi pada tanggal yang dipilih akan dihapus
                                dan tindakan ini tidak dapat dibatalkan.
                            </small>
                        </div>

                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold" for="tanggal">
                            Tanggal Presensi
                        </label>

                        <div class="input-group input-group-lg">

                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-calendar3 text-danger"></i>
                            </span>

                            <input id="tanggal" wire:model="tanggal" class="form-control bg-light border-start-0">

                        </div>

                        <div class="form-text mt-2">
                            Pilih tanggal presensi yang ingin dihapus.
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="d-grid gap-2">

                        <button type="button" class="btn btn-danger btn-lg rounded-3 fw-semibold"
                            wire:confirm.prompt="Yakin data nya akan dihapus?\n\nType DELETE to confirm|DELETE"
                            wire:click="delete">
                            <i class="bi bi-trash3 me-2"></i>
                            Delete Presensi

                        </button>

                        <button type="button" class="btn btn-light border btn-lg rounded-3 fw-semibold"
                            wire:click="exit">

                            <i class="bi bi-arrow-left me-2"></i>
                            Kembali

                        </button>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-light border-0 text-center py-3">
                    <small class="text-muted">
                        <i class="bi bi-shield-check me-1"></i>
                        Pastikan tanggal yang dipilih sudah benar
                    </small>
                </div>

            </div>

        </div>
    </div>

    @include('toastr')

</div>
