<div class="container-fluid py-3">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-0">
                    <i class="bi bi-cash-stack me-2"></i>
                    Penyesuaian Gaji
                </h5>

                <small>
                    Periode :
                    {{ $awalBulan->translatedFormat('d M Y') }}
                    -
                    {{ $akhirBulan->translatedFormat('d M Y') }}
                </small>
            </div>

            <span class="badge bg-warning text-dark fs-6">
                {{ $data->count() }} Orang
            </span>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Bulan Bergabung
                    </label>

                    <select class="form-select" wire:model.live="bulan">

                        @foreach ($listBulan as $item)
                            <option value="{{ $item }}">
                                {{ \Carbon\Carbon::parse($item . '-01')->translatedFormat('F Y') }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered align-middle table-hover">

                    <thead class="table-light">

                        <tr>

                            <th width="60">No</th>

                            <th>Nama</th>

                            <th width="120">ID Karyawan</th>

                            <th width="140">Tanggal Bergabung</th>

                            <th class="text-center" width="120">
                                Lama Hari
                            </th>

                            <th class="text-end" width="170">
                                Gaji Pokok
                            </th>

                            <th width="360">
                                Penyesuaian
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($data as $item)
                            <tr>

                                <td class="text-center">
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    <strong>{{ $item->nama }}</strong>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ $item->id_karyawan }}
                                    </span>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_bergabung)->format('d-m-Y') }}
                                </td>

                                <td class="text-center">

                                    @if ($item->lama_hari >= 210)
                                        <span class="badge bg-success">
                                            {{ number_format($item->lama_hari) }} Hari
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ number_format($item->lama_hari) }} Hari
                                        </span>
                                    @endif

                                </td>

                                <td class="text-end">
                                    <strong>
                                        Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td>

                                    <div class="d-grid gap-2">

                                        <button class="btn btn-outline-danger btn-sm py-2"
                                            wire:click="updateGaji('{{ $item->id }}',2300000)">

                                            Sesuaikan ke Rp 2.300.000

                                        </button>

                                        <button class="btn btn-outline-warning btn-sm py-2"
                                            wire:click="updateGaji('{{ $item->id }}',2400000)">

                                            Sesuaikan ke Rp 2.400.000

                                        </button>

                                        <button class="btn btn-outline-success btn-sm py-2"
                                            wire:click="updateGaji('{{ $item->id }}',2500000)">

                                            Sesuaikan ke Rp 2.500.000

                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5 text-muted">

                                    Tidak ada data.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
