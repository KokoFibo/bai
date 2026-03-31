<div>
    <div class="col-12 col-xl-6 pt-5 mx-auto">
        <div class="card">
            <div class="card-header bg-success">
                <h3>Yifang Payroll Activity Logs </h3>
                <h5>Today's Logins : {{ $today_logs }} </h5>
                <h5>Yesterday's Logins : {{ $yesterday_log }} </h5>
                <h5>Total Logins : {{ $total_logs }} </h5>
                <h5>Total Created Logs : {{ $total_created_logs }} </h5>
                <h5>Number of Admin Logins : {{ $cx }} </h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Created At</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($data as $d)
                            <tr>
                                @php
                                    $contains = Str::contains($d->description, [
                                        'Admin',
                                        'Senior Admin',
                                        'Super Admin',
                                        'BOD',
                                        'Developer',
                                    ]);
                                @endphp
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->created_at }}</td>
                                <td class="{{ $contains ? 'table-warning' : '' }}">{{ $d->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $data->onEachSide(0)->links() }}
    </div>
    {{-- tabel per jam  --}}
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Statistik Aktivitas per Hari & Jam</h5>

            <div class="d-flex gap-2 align-items-center">
                <!-- 🔹 Filter Periode -->
                <select wire:model="period" class="form-select form-select-sm" style="width: auto;">
                    <option value="week">1 Minggu</option>
                    <option value="2weeks">2 Minggu</option>
                    <option value="month">1 Bulan</option>
                </select>

                <!-- 🔹 Select Bulan yang tersedia -->
                <select wire:model="deleteMonth" class="form-select form-select-sm" style="width: auto;">
                    <option value="">Pilih Bulan</option>
                    @foreach ($availableMonths as $m)
                        <option value="{{ $m['value'] }}">{{ $m['label'] }}</option>
                    @endforeach
                </select>

                <!-- 🔹 Tombol hapus -->
                <button wire:click="deleteByMonth"
                    onclick="return confirm('Yakin ingin hapus semua data pada bulan ini?')"
                    class="btn btn-danger btn-sm">
                    Hapus Bulan
                </button>
            </div>
        </div>

        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success py-2">{{ session('success') }}</div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger py-2">{{ session('error') }}</div>
            @endif

            @if (!empty($data_per_hari) && count($data_per_hari) > 0)
                @foreach ($data_per_hari as $hari)
                    <div class="mb-4 border rounded">
                        <div class="bg-light p-2 fw-bold">
                            📅 {{ $hari['tanggal'] }}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 150px;">Jam</th>
                                        <th>Jumlah Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hari['jam'] as $row)
                                        <tr>
                                            <td>{{ $row['hour'] }}</td>
                                            <td>{{ $row['total'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center text-muted mt-3 mb-0">Tidak ada data untuk periode ini.</p>
            @endif
        </div>
    </div>




</div>
