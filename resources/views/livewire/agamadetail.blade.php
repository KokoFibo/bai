<div>
    <div class="d-flex justify-content-center">
        <div class="c-dashboardInfo col-lg-8 col-md-12">
            <div class="wrap d-flex flex-column justify-content-center align-items-center h-100">
                <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center mb-4">
                    {{ __('Agama Berdasarkan Directorate') }}
                </h4>

                {{-- SELECT DROPDOWN --}}
                <div class="w-100 mb-4">
                    <select class="form-select" aria-label="Default select example" wire:model.live="selectedPlacement">
                        <option value="">SEMUA PLACEMENT (ALL)</option> {{-- Opsi ALL --}}
                        @foreach ($placements as $placement)
                            @if ($placement != 100)
                                <option value="{{ $placement }}">
                                    {{ nama_placement($placement) }} ({{ $placement }})
                                </option>
                            @endif
                        @endforeach
                    </select>

                    {{-- Info filter yang aktif --}}
                    <div class="mt-2 text-center">
                        <span class="badge bg-info">
                            @if ($selectedPlacement)
                                Filter: {{ nama_placement($selectedPlacement) }}
                            @else
                                Filter: SEMUA PLACEMENT
                            @endif
                        </span>
                        <span class="badge bg-secondary ms-2">
                            Total Data: {{ array_sum($agamas) + ($agama_kosong ? $agama_kosong->count() : 0) }} karyawan
                        </span>
                    </div>
                </div>

                {{-- STATISTIK AGAMA --}}
                <div class="mt-3 w-100 mb-4">
                    <h5 class="text-center mb-3">Statistik Agama
                        @if ($selectedPlacement)
                            ({{ nama_placement($selectedPlacement) }})
                        @else
                            (All Placement)
                        @endif
                    </h5>

                    @if (count($agamas) > 0)
                        <div class="row">
                            @foreach ($agamas as $agama => $jumlah)
                                <div class="col-md-3 col-sm-6 mb-2">
                                    <div class="card p-2 text-center shadow-sm">
                                        <div class="hind-font caption-12">
                                            <div class="fw-bold mb-1 {{ $agama ? 'text-primary' : 'text-danger' }}">
                                                {{ $agama ?: 'Tidak Terisi' }}
                                            </div>
                                            <div class="display-6">{{ $jumlah }}</div>
                                            <div class="small text-muted">
                                                {{ number_format(($jumlah / array_sum($agamas)) * 100, 1) }}%
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Total Summary --}}
                            <div class="col-12 mt-3">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Total Karyawan: <strong>{{ array_sum($agamas) }}</strong></h6>
                                        <small class="text-muted">
                                            PKWT + PKWTT |
                                            @if ($selectedPlacement)
                                                Placement: {{ nama_placement($selectedPlacement) }}
                                            @else
                                                All Placement
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">
                            Tidak ada data agama ditemukan
                        </div>
                    @endif
                </div>

                {{-- DATA TANPA AGAMA --}}
                @if ($agama_kosong && $agama_kosong->count() > 0)
                    <div class="w-100 mt-4">
                        <h5 class="text-center mb-3 alert alert-danger">
                            ⚠️ Data Tanpa Agama: {{ $agama_kosong->count() }} karyawan
                            @if ($selectedPlacement)
                                ({{ nama_placement($selectedPlacement) }})
                            @else
                                (All Placement)
                            @endif
                        </h5>

                        @if (!$selectedPlacement)
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    Menampilkan data dari SEMUA placement. Gunakan dropdown di atas untuk filter
                                    spesifik.
                                </small>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Directorate</th>
                                        <th>Company</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                        <th>Agama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($agama_kosong as $key => $ak)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $ak->id_karyawan }}</td>
                                            <td>{{ $ak->nama }}</td>
                                            <td>{{ nama_placement($ak->placement_id) }}</td>
                                            <td>{{ nama_company($ak->company_id) }}</td>
                                            <td>{{ nama_jabatan($ak->jabatan_id) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $ak->status_karyawan == 'PKWTT' ? 'success' : 'primary' }}">
                                                    {{ $ak->status_karyawan }}
                                                </span>
                                            </td>
                                            <td class="text-danger fw-bold">
                                                <i class="fas fa-exclamation-triangle"></i> KOSONG
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Summary --}}
                        <div class="alert alert-warning mt-2">
                            <small>
                                <strong>Catatan:</strong> Data ini perlu dilengkapi.
                                @if ($selectedPlacement)
                                    Total {{ $agama_kosong->count() }} karyawan di
                                    {{ nama_placement($selectedPlacement) }} yang belum mengisi agama.
                                @else
                                    Total {{ $agama_kosong->count() }} karyawan di semua placement yang belum mengisi
                                    agama.
                                @endif
                            </small>
                        </div>
                    </div>
                @elseif($agama_kosong && $agama_kosong->count() == 0)
                    <div class="alert alert-success text-center mt-3">
                        <i class="fas fa-check-circle"></i>
                        Semua data agama sudah terisi lengkap!
                        @if ($selectedPlacement)
                            ({{ nama_placement($selectedPlacement) }})
                        @else
                            (All Placement)
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        /* di file CSS kamu */
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .c-dashboardInfo {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            transition: transform 0.2s;
            border: 1px solid #dee2e6;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</div>
