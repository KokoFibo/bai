<div>
    @section('title', 'Libur Nasional')

    @if ($is_create_new)
        <div class="pt-5 col-xl-6 mx-auto">
            <div class="card">
                <div class="card-header bg-success">
                    <h4>Hari libur nasional</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="namaliburnasional" class="form-label">Hari Libur Nasional</label>
                        <input wire:model="nama_hari_libur" type="text"
                            class="form-control  @error('nama_hari_libur') is-invalid @enderror" id="namaliburnasional"
                            placeholder="Tahun baru">
                        @error('nama_hari_libur')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label for="tanggalmulailibur" class="form-label">Tanggal libur</label>
                        <input wire:model.live="tanggal_mulai_hari_libur" type="date"
                            class="form-control @error('tanggal_mulai_hari_libur') is-invalid @enderror""
                            id="tanggalmulailibur">
                        @error('tanggal_mulai_hari_libur')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    {{-- <div class="mb-3">
                        <label for="tanggalselesailibur" class="form-label">Tanggal selesai libur <span
                                class="text-sm text-danger">(kosongkan jika hanya
                                libur 1 hari)</span></label>
                        <input wire:model.live="tanggal_akhir_libur" type="date"
                            class="form-control @error('tanggal_akhir_libur') is-invalid @enderror""
                            id="tanggalselesailibur">
                        @error('tanggal_akhir_libur')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div> --}}
                    {{-- <div class="mb-3">
                        <label for="jumlah_hari_libur" class="form-label">Jumlah Hari libur</label>
                        <input wire:model.live="jumlah_hari_libur" type="text" class="form-control"
                            id="jumlah_hari_libur" disabled>
                    </div> --}}
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    <div class="m-3">
                        @if ($is_edit == false)
                            <button wire:click="save" class="btn btn-primary">Save</button>
                        @else
                            <button wire:click="update" class="btn btn-primary">Update</button>
                        @endif
                        <button wire:click="cancel" class="btn btn-dark">Cancel</button>
                    </div>
                    {{-- <div class="m-3">
                        <button wire:click="exit" class="btn btn-success">Exit</button>
                    </div> --}}
                </div>

            </div>
        </div>
    @endif
    <h3 class="text-center pt-5 fw-semibold">{{ __('Libur Nasional') }}</h3>
    <div class="pt-5  d-flex flex-col flex-xl-row gap-xl-3 gap-2 justify-content-center align-items-center">
        <div>
            <select wire:model.live="year" class="form-select" aria-label="Default select example">
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
            </select>
        </div>
        <div>
            <select wire:model.live="month" class="form-select" aria-label="Default select example">
                <option value="">All</option>
                <option value="1">Januari</option>
                <option value="2">Februari</option>
                <option value="3">Maret</option>
                <option value="4">April</option>
                <option value="5">Mei</option>
                <option value="6">Juni</option>
                <option value="7">Juli</option>
                <option value="8">Agustus</option>
                <option value="9">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
            </select>
        </div>
        @if (auth()->user()->role == 8)
            <div>
                <button wire:click="create_new" class="btn btn-primary">Create New</button>
            </div>
        @endif

    </div>
    <div class="m-3">
        <style>
            td,
            th {
                white-space: nowrap;
            }
        </style>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Nama Hari Libur') }}</th>
                        <th>{{ __('Tanggal Libur') }}</th>
                        {{-- <th>Tanggal Akhir</th> --}}
                        @if (auth()->user()->role == 8)
                            <th>Jumlah Hari libur</th>
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @foreach ($data as $index => $d)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $d->nama_hari_libur }}</td>
                            {{-- <td>{{ $data->firstItem() + $index }}</td> --}}
                            <td>{{ format_tgl($d->tanggal_mulai_hari_libur) }}</td>
                            {{-- <td>{{ format_tgl($d->tanggal_akhir_libur) }}</td> --}}
                            @if (auth()->user()->role == 8)
                                <td>{{ $d->jumlah_hari_libur }}</td>
                                <td>
                                    <button wire:click="edit({{ $d->id }})"
                                        class="btn-warning btn-sm">Edit</button>
                                    <button
                                        wire:confirm.prompt="Yakin mau di delete?\n\nKetik DELETE untuk konfirmasi|DELETE"
                                        wire:click="delete({{ $d->id }})"
                                        class="btn-danger btn-sm">Delete</button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
