<div class="container py-4">

    {{-- Kotak pertanyaan --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-1">Tanya Data dengan AI</h4>
            <p class="text-muted mb-3">Tulis pertanyaan dalam bahasa sehari-hari, AI akan mengubahnya menjadi query SQL dan menampilkan hasilnya.</p>

            <form wire:submit="ask">
                <div class="input-group input-group-lg">
                    <input type="text" wire:model="prompt"
                           class="form-control @error('prompt') is-invalid @enderror"
                           placeholder="Contoh: tampilkan 10 karyawan dengan gaji tertinggi">
                    <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled" wire:target="ask,useExample">
                        <span wire:loading.remove wire:target="ask,useExample">Tanya AI</span>
                        <span wire:loading wire:target="ask,useExample">
                            <span class="spinner-border spinner-border-sm me-1"></span>Memproses...
                        </span>
                    </button>
                    @error('prompt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </form>

            <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                <span class="text-muted small">Coba:</span>
                @foreach ($examples as $i => $ex)
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill"
                            wire:click="useExample({{ $i }})" wire:loading.attr="disabled">
                        {{ $ex }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Peringatan tabel tidak terbaca --}}
    @if ($warning)
        <div class="alert alert-warning shadow-sm">{{ $warning }}</div>
    @endif

    {{-- Error --}}
    @if ($error)
        <div class="alert alert-danger shadow-sm" role="alert">
            <div>{{ $error }}</div>
            @if ($sql !== '' || $explanation !== '')
                <details class="mt-2 small">
                    <summary role="button">Lihat detail</summary>
                    @if ($explanation) <div class="mt-2">{{ $explanation }}</div> @endif
                    @if ($sql !== '') <pre class="mb-0 mt-2 p-2 bg-light border rounded text-dark">{{ $sql }}</pre> @endif
                </details>
            @endif
        </div>
    @endif

    {{-- Tabel hasil --}}
    @if ($hasRows)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <span class="fw-semibold">Hasil</span>
                        <span class="badge bg-primary ms-1">{{ number_format($total, 0, ',', '.') }} baris</span>
                    </div>
                    <div class="col-md-5">
                        <input type="search" wire:model.live.debounce.300ms="search"
                               class="form-control form-control-sm" placeholder="Cari di hasil...">
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="perPage" class="form-select form-select-sm">
                            <option value="10">10 / halaman</option>
                            <option value="25">25 / halaman</option>
                            <option value="50">50 / halaman</option>
                            <option value="100">100 / halaman</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center" style="width:60px">#</th>
                            @foreach ($columns as $col)
                                <th role="button" class="text-nowrap user-select-none {{ isset($money[$col]) ? 'text-end' : '' }}" wire:click="sort({{ \Illuminate\Support\Js::from($col) }})">
                                    {{ $labels[$col] ?? $col }}
                                    @if ($sortBy === $col)
                                        <small>{{ $sortDir === 'asc' ? '▲' : '▼' }}</small>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $n => $row)
                            <tr wire:key="row-{{ $page }}-{{ $n }}">
                                <td class="text-center text-muted">{{ $from + $n }}</td>
                                @foreach ($columns as $col)
                                    @php($v = $row[$col] ?? null)
                                @if (isset($money[$col]) && is_numeric($v))
                                    <td class="text-nowrap text-end">{{ number_format((float) $v, 0, '.', ',') }}</td>
                                @else
                                    <td class="text-nowrap">{{ $v }}</td>
                                @endif
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-4">
                                    Tidak ada baris yang cocok dengan pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                    <small class="text-muted">Menampilkan {{ $from }}–{{ $to }} dari {{ number_format($total, 0, ',', '.') }} baris</small>

                    @if ($pages > 1)
                        <nav aria-label="Pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                                    <button class="page-link" wire:click="gotoPage({{ $page - 1 }})">&laquo;</button>
                                </li>

                                @if ($start > 1)
                                    <li class="page-item"><button class="page-link" wire:click="gotoPage(1)">1</button></li>
                                    @if ($start > 2)
                                        <li class="page-item disabled"><span class="page-link">…</span></li>
                                    @endif
                                @endif

                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="page-item {{ $i === $page ? 'active' : '' }}">
                                        <button class="page-link" wire:click="gotoPage({{ $i }})">{{ $i }}</button>
                                    </li>
                                @endfor

                                @if ($end < $pages)
                                    @if ($end < $pages - 1)
                                        <li class="page-item disabled"><span class="page-link">…</span></li>
                                    @endif
                                    <li class="page-item"><button class="page-link" wire:click="gotoPage({{ $pages }})">{{ $pages }}</button></li>
                                @endif

                                <li class="page-item {{ $page >= $pages ? 'disabled' : '' }}">
                                    <button class="page-link" wire:click="gotoPage({{ $page + 1 }})">&raquo;</button>
                                </li>
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    @elseif ($asked && ! $error && $sql !== '')
        <div class="alert alert-info shadow-sm">Query berhasil dijalankan, tetapi tidak ada data yang dikembalikan.</div>
    @endif
</div>
