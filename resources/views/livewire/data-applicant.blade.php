<div>
    @section('title', 'Data Applicant')
    <div class="p-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h2>Data Applicant</h2>
                    </div>
                    @if ($show_data)
                        <div>
                            <button class="btn btn-dark" wire:click='kembali'>Kembali</button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-4">
                <div class="mt-3 ml-3">
                    <input wire:model.live='search' type="email" class="form-control" id="exampleFormControlInput1"
                        placeholder="search...">
                </div>
            </div>

            <style>
                td,
                th {
                    white-space: nowrap;
                }
            </style>
            @if ($show_table)
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>HP</th>
                                    <th>Gender</th>
                                    @if (auth()->user()->role == 8)
                                        <th>Etnis</th>
                                    @endif
                                    <th>Tanggal Lahir</th>
                                    <th>Status Penerimaan</th>
                                    <th>Submitted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->hp }}</td>
                                        <td>{{ $d->gender }}</td>
                                        @if (auth()->user()->role == 8)
                                            <td>{{ $d->etnis }}</td>
                                        @endif
                                        <td>{{ format_tgl($d->tgl_lahir) }}</td>
                                        <td>
                                            @if ($editId === $d->id)
                                                {{-- selkect --}}
                                                <select class="form-select" aria-label="Default select example"
                                                    wire:model.live='status'>
                                                    <option value="1">1. Melamar</option>
                                                    <option value="2">2. Sedang Komunikasi</option>
                                                    <option value="3">3. Psikotest</option>
                                                    <option value="4">4. Interview</option>
                                                    <option value="5">5. Ditolak</option>
                                                    <option value="6">6. Cadangan</option>
                                                    <option value="7">7. Onboarding</option>
                                                    <option value="8">8. Diterima</option>
                                                </select>
                                            @else
                                                <span
                                                    class="badge {{ getStatusColor($d->status) }}">{{ getNamaStatus($d->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ dateTimeFormat($d->created_at) }}</td>
                                        <td>
                                            @if ($editId === $d->id)
                                                @if ($status == 8)
                                                    <button class="btn btn-sm btn-primary"
                                                        wire:click='save'>Simpan</button>
                                                @else
                                                    <button class="btn btn-sm btn-primary"
                                                        wire:click='save'>Simpan</button>
                                                @endif
                                                <button class="btn btn-sm btn-warning"
                                                    wire:click='cancel'>Cancel</button>
                                            @else
                                                @if (check_storage($d->applicant_id))
                                                    <button class="btn btn-sm btn-success"
                                                        wire:click='show({{ $d->id }})'>Show</button>
                                                @else
                                                    <button class="btn btn-sm btn-warning"
                                                        wire:click='show({{ $d->id }})'>Show</button>
                                                @endif
                                                <button class="btn btn-sm btn-danger"
                                                    wire:key="{{ $d->id }}-delete"
                                                    wire:click='deleteConfirmation({{ $d->id }})'>Delete</button>
                                                <button class="btn btn-sm btn-primary"
                                                    wire:click='edit({{ $d->id }})'>Rubah Status</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->links() }}
                </div>


            @endif
            @if ($show_data)
                <div class="d-flex mt-3">
                    <ul class="list-group">
                        <li class="list-group-item">Nama</li>
                        <li class="list-group-item">Email</li>
                        <li class="list-group-item">HP</li>
                        <li class="list-group-item">Telepon</li>
                        <li class="list-group-item">Tempat/ Tanggal Lahir</li>
                        <li class="list-group-item">Gender</li>
                        <li class="list-group-item">Status Pernikahan</li>
                        <li class="list-group-item">Golongan Darah</li>
                        <li class="list-group-item">Agama</li>
                        <li class="list-group-item">Etnis</li>
                        <li class="list-group-item">Nama Kontak Darurat</li>
                        <li class="list-group-item">No Kontak Darurat</li>
                        <li class="list-group-item">Identitas/No</li>
                        <li class="list-group-item">Alamat Identitas</li>
                        <li class="list-group-item">Alamat Tinggal Sekarang</li>

                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">{{ $personal_data->nama }}</li>
                        <li class="list-group-item">{{ $personal_data->email }}</li>
                        <li class="list-group-item">{{ $personal_data->hp }}</li>
                        <li class="list-group-item">{{ $personal_data->telp }}</li>
                        <li class="list-group-item">{{ $personal_data->tempat_lahir }}/
                            {{ format_tgl($personal_data->tgl_lahir) }}</li>
                        <li class="list-group-item">{{ $personal_data->gender }}</li>
                        <li class="list-group-item">{{ $personal_data->status_pernikahan }}</li>
                        <li class="list-group-item">{{ $personal_data->golongan_darah }}</li>
                        <li class="list-group-item">{{ $personal_data->agama }}</li>
                        <li class="list-group-item">{{ $personal_data->etnis }}</li>
                        <li class="list-group-item">{{ $personal_data->nama_contact_darurat }}</li>
                        <li class="list-group-item">{{ $personal_data->contact_darurat_1 }} /
                            {{ $personal_data->contact_darurat_2 }}
                        </li>
                        <li class="list-group-item">{{ $personal_data->jenis_identitas }}:
                            {{ $personal_data->no_identitas }}</li>
                        <li class="list-group-item">{{ $personal_data->alamat_identitas }}</li>
                        <li class="list-group-item">{{ $personal_data->alamat_tinggal_sekarang }}</li>


                    </ul>
                </div>
                {{-- Tampil gambar --}}
                <div class="mt-3">
                    <div class="row g-4">
                        @foreach ($personal_files as $key => $fn)
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="card border-0 shadow-sm hover-shadow-lg">
                                    <div class="d-flex justify-content-center align-items-center p-3"
                                        style="height: 180px; background: #f8f9fa; cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#imageModal{{ $key }}">
                                        <img src="{{ getUrl($fn->filename) }}" class="img-fluid" alt="File Image"
                                            style="max-height: 100%; object-fit: contain;">
                                    </div>
                                    <div class="card-body text-center">
                                        <p class="card-text text-dark fw-semibold text-truncate">
                                            {{ get_filename($fn->filename) }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal untuk Perbesar Gambar -->
                            <div class="modal fade" id="imageModal{{ $key }}" tabindex="-1"
                                aria-labelledby="imageModalLabel{{ $key }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="imageModalLabel{{ $key }}">
                                                {{ get_filename($fn->filename) }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <img src="{{ getUrl($fn->filename) }}" class="img-fluid rounded"
                                                alt="File Image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Kembali --}}
                    <div class="d-flex justify-content-center mt-4">
                        <button class="btn btn-dark px-4 py-2 shadow-sm" wire:click='kembali'>
                            ⬅ Kembali
                        </button>
                    </div>
                </div>




            @endif

        </div>
    </div>
    <style>
        /* Container for the responsive image */
        .responsive-container {
            width: 100%;
            max-width: 800px;
            /* Optional: Set a max-width for the container */
            margin: 0 auto;
            /* Center the container */
        }

        /* Make the image responsive */
        .responsive-container img {
            width: 100%;
            height: auto;
            display: block;
            /* Remove any extra space below the image */
        }
    </style>
    @script
        <script>
            window.addEventListener("show-delete-confirmation", (event) => {
                Swal.fire({
                    title: "Yakin mau delete data?",
                    // text: "You won't be able to revert this!",
                    text: event.detail.text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, delete",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("delete-confirmed");
                    }
                });
            });
            window.addEventListener("show-terima-confirmation", (event) => {
                Swal.fire({
                    title: "Pelamar ini akan diterima?",
                    // text: "You won't be able to revert this!",
                    text: event.detail.text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, terima",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("terima-confirmed");
                    }
                });
            });
        </script>
    @endscript
</div>
