<div class="card shadow-sm">
    <div class="card-header fw-bold">
        Laporan Kenaikan Gaji Pokok Per Periode
    </div>

    <div class="card-body">

        {{-- FILTER --}}
        <div class="row g-2 mb-3">

            <div class="col-6 col-md-2">
                <select class="form-select" wire:model="bulan_awal">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ $m }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select class="form-select" wire:model="tahun_awal">
                    @for ($y = now()->year; $y >= now()->year - 6; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select class="form-select" wire:model="bulan_akhir">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ $m }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-6 col-md-2">
                <select class="form-select" wire:model="tahun_akhir">
                    @for ($y = now()->year; $y >= now()->year - 6; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-12 col-md-2">
                <div class="d-grid gap-2">

                    <button class="btn btn-primary" wire:click="proses" wire:loading.attr="disabled">
                        <span wire:loading.remove>Proses</span>
                        <span wire:loading>Loading...</span>
                    </button>

                    @if (count($data))
                        <button class="btn btn-success" wire:click="exportExcel">
                            Export Excel
                        </button>
                    @endif

                </div>
            </div>

        </div>


        {{-- TABLE --}}
        @if (count($data))

            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Company</th>
                            <th>Directorate</th>
                            @foreach ($months as $m)
                                <th>{{ \Carbon\Carbon::createFromFormat('Y-m', $m)->translatedFormat('M Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr>
                                <td>{{ $row['id'] }}</td>
                                <td class="text-start">{{ $row['nama'] }}</td>
                                <td>{{ $row['company'] ?? '-' }}</td>
                                <td>{{ $row['placement'] ?? '-' }}</td>
                                @foreach ($months as $m)
                                    <td>
                                        @if ($row[$m])
                                            {{ number_format($row[$m], 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                @endforeach

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
</div>
