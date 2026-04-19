<div class="p-3">

    <!-- 🔍 Search + Action -->
    <div class="mb-3">
        <div class="row g-2 align-items-center">

            <!-- Search -->
            <div class="col-12 col-md-6">
                <input type="text" wire:model.live="search" class="form-control" placeholder="Cari nama / email...">
            </div>

            <!-- Button -->
            <div class="col-12 col-md-auto">
                <button wire:click="cleanEmailPro" wire:loading.attr="disabled"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <!-- Spinner -->
                    <div wire:loading wire:target="cleanEmailPro" class="spinner-border spinner-border-sm text-light"
                        role="status"></div>

                    <!-- Normal Text -->
                    <span wire:loading.remove wire:target="cleanEmailPro">
                        Bersihkan Email
                    </span>

                    <!-- Loading Text -->
                    <span wire:loading wire:target="cleanEmailPro">
                        Memproses...
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- 📊 Progress Bar -->
    @if ($isProcessing)
        <div class="progress mb-3">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                style="width: {{ $progress }}%">
                {{ $progress }}%
            </div>
        </div>
    @endif

    <!-- ✅ Alert -->
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- 📊 Table -->
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID Karyawan</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status Karyawan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td>{{ $item->id_karyawan }}</td>
                            <td>{{ $item->nama }}</td>
                            <td class="text-break">{{ $item->email }}</td>
                            <td>{{ $item->status_karyawan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-3">
        {{ $data->links() }}
    </div>

</div>
