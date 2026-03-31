<div class="container py-4">

    <div class="card border-0 shadow-sm mb-4 bg-dark text-white rounded-3">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <h3 class="fw-bold mb-1">Turnover Karyawan</h3>
                    <p class="small mb-0 text-white-50">Laporan statistik keluar dan masuk karyawan per bulan</p>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <div class="input-group">
                        <label class="input-group-text bg-secondary text-white border-0" for="yearSelect">Tahun</label>
                        <select wire:model.live="year" id="yearSelect" class="form-select border-0 shadow-none">
                            @foreach ($years as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-md-none">
        @foreach ($this->turnoverData as $row)
            <div
                class="card mb-3 border-start border-4 {{ $row['turnover'] > 10 ? 'border-danger' : 'border-primary' }} shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-primary">{{ $row['bulan'] }}</h5>
                        <span class="badge {{ $row['turnover'] > 10 ? 'bg-danger' : 'bg-primary' }}">
                            {{ $row['turnover'] }}%
                        </span>
                    </div>
                    <div class="row g-2 text-center mb-3">
                        <div class="col-3 border-end">
                            <div class="small text-muted">Awal</div>
                            <div class="fw-bold">{{ $row['awal'] }}</div>
                        </div>
                        <div class="col-3 border-end text-success">
                            <div class="small text-muted">Masuk</div>
                            <div class="fw-bold">+{{ $row['masuk'] }}</div>
                        </div>
                        <div class="col-3 border-end text-danger">
                            <div class="small text-muted">Keluar</div>
                            <div class="fw-bold">-{{ $row['keluar'] }}</div>
                        </div>
                        <div class="col-3">
                            <div class="small text-muted">Akhir</div>
                            <div class="fw-bold">{{ $row['akhir'] }}</div>
                        </div>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar {{ $row['turnover'] > 10 ? 'bg-danger' : 'bg-success' }}"
                            style="width: {{ min($row['turnover'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-none d-md-block card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="ps-4 py-3">BULAN</th>
                        <th class="text-center py-3">AWAL</th>
                        <th class="text-center py-3">MASUK (+)</th>
                        <th class="text-center py-3">KELUAR (-)</th>
                        <th class="text-center py-3">AKHIR</th>
                        <th class="pe-4 py-3" style="width: 250px;">TURNOVER RATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->turnoverData as $row)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $row['bulan'] }}</td>
                            <td class="text-center text-muted">{{ $row['awal'] }}</td>
                            <td class="text-center fw-bold text-success">+{{ $row['masuk'] }}</td>
                            <td class="text-center fw-bold text-danger">-{{ $row['keluar'] }}</td>
                            <td class="text-center fw-bold">{{ $row['akhir'] }}</td>
                            <td class="pe-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="progress flex-grow-1" style="height: 8px;">
                                        <div class="progress-bar {{ $row['turnover'] > 10 ? 'bg-danger' : 'bg-success' }}"
                                            style="width: {{ min($row['turnover'], 100) }}%"></div>
                                    </div>
                                    <span
                                        class="small fw-bold {{ $row['turnover'] > 10 ? 'text-danger' : 'text-dark' }}">
                                        {{ $row['turnover'] }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 text-muted small px-2">
        <i class="bi bi-calculator"></i> Rumus: <strong>(Total Keluar / Rata-rata Karyawan) x 100%</strong>
    </div>

</div>
