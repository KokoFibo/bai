<div class="container-fluid py-4">

    {{-- KETERANGAN HALAMAN --}}
    <div class="alert alert-warning mb-4">
        <h5 class="alert-heading">Penghapusan User Backend</h5>
        <p class="mb-0">
            Halaman ini khusus untuk menghapus data user yang digunakan
            untuk login ke backend. Pastikan data yang akan dihapus
            benar sebelum melanjutkan.
        </p>
    </div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Delete User</h4>

        <button type="button" class="btn btn-danger" wire:click="Deletesemua"
            wire:confirm="PERINGATAN! Semua user yang masuk dalam daftar ini akan dihapus. Apakah Anda yakin?"
            wire:loading.attr="disabled" wire:target="deleteAll" @if (!$deleteAll) disabled @endif>

            <span wire:loading.remove wire:target="deleteAll">
                <i class="bi bi-trash"></i> Delete All
            </span>

            <span wire:loading wire:target="deleteAll">
                Menghapus semua...
            </span>
        </button>
    </div>

    {{-- <button type="button" class="btn btn-secondary" disabled title="Tidak ada data tersaring untuk dihapus massal">
            <i class="bi bi-trash"></i> Delete All
        </button>

    </div>

    @if (!$isFiltered)
        <div class="alert alert-info py-2 small mb-3">
            <i class="bi bi-info-circle me-1"></i>
            Menampilkan seluruh data user karena data sesuai filter khusus tidak ditemukan.
        </div>
    @endif

    {{-- TABEL USER --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>No.</th>

                            @php
                                $columns = [
                                    'id' => 'ID',
                                    'name' => 'Nama',
                                    'email' => 'Email',
                                    'username' => 'Username',
                                    'role' => 'Role',
                                    'language' => 'Bahasa',
                                    'device' => 'Device',
                                ];
                            @endphp

                            @foreach ($columns as $column => $label)
                                <th>
                                    <button type="button"
                                        class="btn btn-link link-light text-decoration-none fw-bold p-0"
                                        wire:click="sortByColumn('{{ $column }}')"
                                        aria-label="Urutkan berdasarkan {{ $label }}">

                                        {{ $label }}

                                        @if ($sortColumn === $column)
                                            @if ($sortDirection === 'asc')
                                                <i class="bi bi-arrow-up"></i>
                                            @else
                                                <i class="bi bi-arrow-down"></i>
                                            @endif
                                        @else
                                            <i class="bi bi-arrow-down-up text-secondary"></i>
                                        @endif
                                    </button>
                                </th>
                            @endforeach

                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($data as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td>
                                    {{ $data->firstItem() + $loop->index }}
                                </td>

                                <td><small>{{ $user->id }}</small></td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email ?? '-' }}</td>
                                <td>{{ $user->username ?? '-' }}</td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $user->role ?? '-' }}
                                    </span>
                                </td>

                                <td>{{ $user->language ?? '-' }}</td>
                                <td>{{ $user->device ?? '-' }}</td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-danger"
                                        wire:click="delete('{{ $user->id }}')"
                                        wire:confirm="Yakin ingin menghapus user {{ $user->name }}?"
                                        wire:loading.attr="disabled" wire:target="delete('{{ $user->id }}')">

                                        <span wire:loading.remove wire:target="delete('{{ $user->id }}')">
                                            Delete
                                        </span>

                                        <span wire:loading wire:target="delete('{{ $user->id }}')">
                                            Menghapus...
                                        </span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        Tidak ada data user.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- FOOTER DAN PAGINATION --}}
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div class="text-muted">
                    Menampilkan {{ $data->firstItem() ?? 0 }}
                    – {{ $data->lastItem() ?? 0 }}
                    dari {{ $data->total() }} user
                </div>

                <div>
                    {{ $data->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
