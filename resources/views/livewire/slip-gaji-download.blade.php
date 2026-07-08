<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">

                        <i class="bi bi-file-earmark-excel"></i>

                        Download Slip Gaji

                    </h4>

                </div>

                <div class="card-body">

                    @if (session()->has('success'))
                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger">

                            {{ session('error') }}

                        </div>
                    @endif


                    <div class="mb-3">

                        <label class="form-label">

                            Bulan

                        </label>

                        <select class="form-select" wire:model="bulan">

                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">

                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                </option>
                            @endfor

                        </select>

                    </div>

                    <div class="mb-4">

                        <label class="form-label">

                            Tahun

                        </label>

                        <select class="form-select" wire:model="tahun">

                            @for ($i = date('Y') - 3; $i <= date('Y') + 2; $i++)
                                <option value="{{ $i }}">

                                    {{ $i }}

                                </option>
                            @endfor

                        </select>

                    </div>

                    <button class="btn btn-success w-100" wire:click="download" wire:loading.attr="disabled">

                        <span wire:loading.remove>

                            <i class="bi bi-download"></i>

                            Download Slip Gaji

                        </span>

                        <span wire:loading>

                            <span class="spinner-border spinner-border-sm"></span>

                            Sedang membuat file...

                        </span>

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>
