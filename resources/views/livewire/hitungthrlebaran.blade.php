<div>
    @section('title', 'Laporan Hitung THR Lebaran')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 fw-bold text-dark">Data THR Karyawan Lebaran STI</h4>
                    <small class="text-muted">Tanggal Cut Off : <strong>{{ format_tgl($cutOffDate) }}</strong></small>
                </div>

                <div class="d-flex align-items-center gap-2">
                    @if (auth()->user()->role == 8)
                        <button wire:click="pindahKeBonusPotongan" class="btn btn-danger d-flex align-items-center gap-2"
                            wire:loading.attr="disabled">
                            Pindahkan ke Bonus & Potongan hanya digunakan untuk THR saja
                        </button>
                        <div wire:loading wire:target="pindahKeBonusPotongan" class="small text-success fw-bold">
                            <div class="spinner-border spinner-border-sm" role="status"></div> Mengolah...
                        </div>
                    @endif
                    {{-- Tombol Excel --}}
                    <button wire:click='excel' class="btn btn-success d-flex align-items-center gap-2"
                        wire:loading.attr="disabled">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </button>

                    <div wire:loading wire:target="excel" class="small text-success fw-bold">
                        <div class="spinner-border spinner-border-sm" role="status"></div> Mengolah...
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="alert alert-info border-0 shadow-sm mb-0">
                        <h6 class="fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Ketentuan Perhitungan:</h6>
                        <ul class="mb-0 small">
                            <li>Masa Kerja <strong>&ge; 12 bulan</strong>: Mendapatkan 1 bulan gaji full.</li>
                            <li>Masa Kerja <strong>
                                    < 12 bulan</strong>: Perhitungan Prorate (Masa Kerja / 12) * Gaji.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light border-0 shadow-sm h-100">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <span class="text-muted small text-uppercase fw-bold">Total Pembayaran THR</span>
                            <h3 class="fw-bold text-primary mb-0">Rp {{ number_format($total) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-light text-nowrap">
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Nama Karyawan</th>
                            <th>Informasi Kerja</th>
                            <th class="text-center">Masa Kerja</th>
                            <th class="text-end">Gaji Pokok</th>
                            <th class="text-end bg-light fw-bold">Total THR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawans as $k)
                            <tr>
                                <td class="text-center fw-bold">{{ $k->id_karyawan }}</td>
                                <td>
                                    <div class="fw-bold">{{ $k->nama }}</div>
                                    <small class="text-muted">{{ $k->jabatan->nama_jabatan }}</small>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="badge bg-secondary mb-1">{{ $k->company->company_name }}</span><br>
                                        {{ $k->placement->placement_name }} | {{ $k->department->nama_department }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="small fw-bold">
                                        {{ selisihBulanBulat($k->tanggal_bergabung, $cutOffDate) }} Bln
                                    </div>
                                    <small
                                        class="text-muted italic">{{ selisihHari($k->tanggal_bergabung, $cutOffDate) }}
                                        Hari</small>
                                </td>
                                <td class="text-end">Rp {{ number_format($k->gaji_pokok) }}</td>
                                <td class="text-end fw-bold text-primary bg-light">
                                    Rp
                                    {{ number_format(hitungTHR($k->id_karyawan, $k->tanggal_bergabung, $k->gaji_pokok, $cutOffDate)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $karyawans->onEachSide(0)->links() }}
            </div>
        </div>
    </div>
</div>
