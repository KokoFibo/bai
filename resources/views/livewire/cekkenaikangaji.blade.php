<div class="card">
    <div class="card-header fw-bold">
        Cek Perkembangan Gaji Tetap
    </div>

    <div class="card-body">

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">ID Karyawan</label>
                <input type="number" class="form-control" wire:model.defer="id_karyawan" placeholder="Contoh: 1023">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tahun</label>
                <select class="form-select" wire:model.defer="tahun">
                    @for ($y = now()->year; $y >= now()->year - 6; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100" wire:click="proses" wire:loading.attr="disabled">
                    Proses
                </button>
            </div>
        </div>

        @if (count($hasil))
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Bulan</th>
                            <th>Gaji Tetap</th>
                            <th>Selisih</th>
                            <th>%</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasil as $h)
                            <tr>
                                <td>{{ $h->bulan }}</td>
                                <td>Rp {{ number_format($h->gaji_pokok, 0, ',', '.') }}</td>
                                <td
                                    class="{{ $h->selisih > 0 ? 'text-success' : ($h->selisih < 0 ? 'text-danger' : '') }}">
                                    Rp {{ number_format($h->selisih, 0, ',', '.') }}
                                </td>
                                <td>{{ $h->persen }}%</td>
                                <td>
                                    <span
                                        class="badge
                                    {{ $h->status == 'Naik' ? 'bg-success' : ($h->status == 'Turun' ? 'bg-danger' : 'bg-secondary') }}">
                                        {{ $h->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($id_karyawan)
            <div class="alert alert-warning text-center">
                Data payroll tidak ditemukan untuk ID tersebut.
            </div>
        @endif

    </div>
    @livewire('LaporanPerubahanGaji')

</div>
