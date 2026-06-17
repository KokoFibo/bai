<div class="container py-3">

    ```
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">

            <div class="card shadow-sm border-0">

                <div class="card-header bg-danger text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-trash-alt fa-lg me-3"></i>

                        <div>
                            <h5 class="mb-0 fw-bold">
                                Hapus Presensi Bulanan
                            </h5>

                            <small class="opacity-75">
                                Penghapusan data presensi secara permanen
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}

                            <button type="button" class="btn-close" data-bs-dismiss="alert">
                            </button>
                        </div>
                    @endif

                    <div class="alert alert-warning mb-4">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle me-2 mt-1"></i>

                            <div>
                                <strong>Peringatan</strong>
                                <div class="small mt-1">
                                    Data yang telah dihapus tidak dapat dikembalikan.
                                    Pastikan periode yang dipilih sudah benar.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Periode Presensi
                        </label>

                        <select class="form-select" wire:model.live="periode">

                            <option value="">
                                Pilih Periode
                            </option>

                            @foreach ($availablePeriods as $period)
                                <option value="{{ $period->tahun }}-{{ $period->bulan }}">
                                    {{ \Carbon\Carbon::create()->month($period->bulan)->translatedFormat('F') }}
                                    {{ $period->tahun }}
                                    ({{ number_format($period->total) }} record)
                                </option>
                            @endforeach

                        </select>

                    </div>

                    @if ($totalData > 0)
                        <div class="row g-3 mb-4">

                            <div class="col-6">

                                <div class="card border-0 bg-light">

                                    <div class="card-body text-center">

                                        <div class="text-muted small">
                                            Total Record
                                        </div>

                                        <div class="fs-3 fw-bold text-danger">
                                            {{ number_format($totalData) }}
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="col-6">

                                <div class="card border-0 bg-light">

                                    <div class="card-body text-center">

                                        <div class="text-muted small">
                                            Total Karyawan
                                        </div>

                                        <div class="fs-3 fw-bold text-primary">
                                            {{ number_format($totalKaryawan) }}
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    @endif

                    <div class="mb-3">

                        <label class="form-label fw-semibold text-danger">
                            Konfirmasi Penghapusan
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Ketik
                            </span>

                            <input type="text" class="form-control @error('confirmDelete') is-invalid @enderror"
                                wire:model.live="confirmDelete" placeholder="HAPUS">

                        </div>

                        <div class="form-text">
                            Ketik <strong>HAPUS</strong> untuk mengaktifkan tombol penghapusan.
                        </div>

                        @error('confirmDelete')
                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="d-grid">

                        <button class="btn btn-danger btn-lg" wire:click="deleteData" wire:loading.attr="disabled"
                            @disabled(!$periode || $confirmDelete !== 'HAPUS')>

                            <span wire:loading.remove>
                                <i class="fas fa-trash-alt me-2"></i>
                                Hapus Data Presensi
                            </span>

                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Menghapus Data...
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        </div>
    </div>
    ```

</div>
