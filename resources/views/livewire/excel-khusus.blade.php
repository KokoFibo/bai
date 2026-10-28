<div class="p-4 bg-white rounded shadow">
    <h4 class="mb-3">Export Data Karyawan Khusus</h4>

    {{-- <div class="mb-3">
        <label class="form-label">
            <input type="checkbox" wire:model.live="semuaData">
            Semua Data
        </label>

        <div class="text-muted small">
            Jika dipilih, semua karyawan aktif dan seluruh
            karyawan Resigned akan ditampilkan.
            Karyawan Blacklist tetap dikecualikan.
        </div>
    </div> --}}

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Mulai Bulan</label>

            <select wire:model="bulan" class="form-select" @disabled($semuaData)>
                @foreach ($daftarBulan as $nomor => $nama)
                    <option value="{{ $nomor }}">
                        {{ $nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Tahun</label>

            <select wire:model="tahun" class="form-select" @disabled($semuaData)>
                @foreach ($daftarTahun as $thn)
                    <option value="{{ $thn }}">
                        {{ $thn }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <button type="button" wire:click="export" wire:loading.attr="disabled" class="btn btn-success">
        <span wire:loading.remove wire:target="export">
            Export Excel
        </span>

        <span wire:loading wire:target="export">
            Sedang menyiapkan Excel...
        </span>
    </button>
</div>
