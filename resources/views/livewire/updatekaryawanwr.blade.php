<div>
    @section('title', 'Update Karyawan')

    <div class="container ">
        <div class="card mt-3 ">
            <div class="card-header bg-secondary">
                <h5 class="text-light py-2">{{ __('Update Data Karyawan') }}</h5>
            </div>

            <div class="card-body">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab">
                        <button class="nav-link active" id="nav-pribadi-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-pribadi" type="button" role="tab" aria-controls="nav-pribadi"
                            aria-selected="false"><span class="fs-5">{{ __('Data Pribadi') }}</span></button>
                        <button class="nav-link" id="nav-identitas-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-identitas" type="button" role="tab" aria-controls="nav-identitas"
                            aria-selected="false"><span class="fs-5">{{ __('Identitas') }}</span></button>
                        <button class="nav-link " id="nav-kepegawaian-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-kepegawaian" type="button" role="tab"
                            aria-controls="nav-kepegawaian" aria-selected="true"><span
                                class="fs-5">{{ __('Data Kepegawaian') }}</span></button>


                        {{-- baris dibawah ini jangan dihapus --}}
                        <button class="nav-link " id="nav-payroll-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-payroll" type="button" role="tab" aria-controls="nav-payroll"
                            aria-selected="false"><span class="fs-5">{{ __('Payroll') }}</span></button>
                        {{-- @endif --}}

                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active p-3" id="nav-pribadi" role="tabpanel"
                        aria-labelledby="nav-pribadi-tab">
                        @include('pribadi')
                    </div>

                    <div class="tab-pane fade p-3" id="nav-identitas" role="tabpanel"
                        aria-labelledby="nav-identitas-tab">
                        @include('identitas')
                    </div>

                    <div class="tab-pane fade p-3" id="nav-kepegawaian" role="tabpanel"
                        aria-labelledby="nav-kepegawaian-tab">
                        @include('kepegawaian')
                    </div>



                    {{-- baris dibawah ini jangan dihapus --}}
                    <div class="tab-pane fade p-3" id="nav-payroll" role="tabpanel" aria-labelledby="nav-payroll-tab">
                        @include('payroll')
                    </div>

                    {{-- @endif --}}


                </div>

                <div class="card m-3">
                    <div class="card-header">
                        <h4 class='pt-3'>Upload Dokumen</h4>
                        <p>Hanya menerima file png, jpg dan jpeg saja</p>
                    </div>
                    <div class="card-body">
                        {{-- upload files  --}}
                        <div class="container">
                            <div class="row g-3">
                                @foreach (['ktp' => 'KTP', 'kk' => 'Kartu Keluarga', 'ijazah' => 'Ijazah', 'nilai' => 'Transkip Nilai/SKHUN', 'cv' => 'CV', 'pasfoto' => 'Pass Foto', 'npwp' => 'NPWP', 'paklaring' => 'Paklaring', 'bpjs' => 'Kartu BPJS Ketenagakerjaan', 'skck' => 'SKCK', 'sertifikat' => 'Sertifikat', 'bri' => 'Buku Tabungan Bank BRI'] as $key => $label)
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <label class="form-label" for="upload_{{ $key }}">
                                            <p class="mb-1">{{ $label }}
                                                @if (in_array($key, ['ktp', 'kk', 'ijazah', 'nilai', 'cv', 'pasfoto']))
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </p>
                                        </label>
                                        <input wire:model="{{ $key }}" multiple class="form-control"
                                            id="upload_{{ $key }}" type="file">
                                        @error("{{ $key }}.*")
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Show Errors --}}
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><span class='text-danger'>{{ $error }}</span></li>
                    @endforeach
                </ul>


                <div wire:loading wire:target='update1'>
                    <div class="text-center">
                        <h5>Mohon tunggu sampai proses update selesai</h5>
                        <div class="spinner-border text-dark mt-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 pb-3 px-3 justify-content-center" wire:loading.remove>
                    @if (($status_karyawan != 'Resigned' && $status_karyawan != 'Blacklist') || auth()->user()->role >= 6)
                        <button wire:click="update1" class="btn btn-primary">{{ __('Update') }}</button>
                    @endif

                    <button wire:click="exit" class="btn btn-dark">{{ __('Exit') }}</button>

                    @if (!$show_arsip)
                        @if (!$is_folder_kosong)
                            <button wire:click="arsip" class="btn btn-success">{{ __('Lihat File Dokumen') }}</button>
                        @else
                            <button class="btn btn-success" disabled>Belum ada file dokumen</button>
                        @endif
                    @else
                        {{-- @if (!$is_folder_kosong) --}}
                        <button class="btn btn-success" wire:click='tutup_arsip'>Tutup File Dokumen</button>
                        {{-- @endif --}}
                    @endif

                    @if (!$is_folder_kosong)
                        <a href="{{ route('download.zip', ['folder' => $folder_name]) }}" class="btn btn-primary">
                            Download All Files
                        </a>
                        <a href="{{ route('download.merged.pdf', ['folder' => $folder_name]) }}"
                            class="btn btn-danger">
                            ALl files PDF
                        </a>
                    @endif
                </div>

            </div>


            @if ($show_arsip)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3>File Arsip {{ $nama }}</h3>
                    </div>
                    <div class="card-body">
                        @if ($personal_files->isNotEmpty())
                            <div class="row">
                                @foreach ($personal_files as $key => $fn)
                                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="card-body d-flex flex-column">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h5 class="card-title">{{ get_filename($fn->filename) }}</h5>
                                                    <div>
                                                        <button class="btn btn-danger btn-sm"
                                                            wire:confirm='Yakin mau di delete?'
                                                            wire:click="deleteFile('{{ $fn->id }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 text-center">
                                                    <!-- Gunakan Alpine.js untuk mengelola state -->
                                                    <img x-data="{ zoomed: false }" :class="zoomed ? 'zoomed' : ''"
                                                        class="img-fluid rounded-4 zoomable"
                                                        src="{{ getUrl($fn->filename) }}"
                                                        alt="{{ get_filename($fn->filename) }}"
                                                        @click="zoomed = !zoomed" @click.away="zoomed = false">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-success" wire:click='tutup_arsip'>Tutup File Dokumen</button>
                            </div>
                        @else
                            <h3 class="text-center">File tidak ditemukan</h3>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
    <style>
        .zoomable {
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }

        .zoomable.zoomed {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            max-width: 80vw;
            /* Maksimal 80% lebar layar */
            max-height: 80vh;
            /* Maksimal 80% tinggi layar */
            width: auto;
            /* Menjaga rasio aspek */
            height: auto;
            /* Menjaga rasio aspek */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        }
    </style>

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
        </script>
    @endscript
</div>
