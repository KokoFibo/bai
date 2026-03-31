<div class='p-3'>
    @section('title', 'Personnel Request')

    <div>
        {{-- <h5>Hello, {{ auth()->user()->name }}</h5> --}}
        @if (!$is_add && !$is_update && $is_requester)
            <button class='btn btn-primary mb-3' wire:click='add'>New Request</button>
        @endif
    </div>
    @if ($is_add || $is_update)
        <div>
            <div class="card p-3">
                <div class="card-header">
                    <h3>{{ __('Form Permohonan Personnel') }}</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex">

                        <div class="mb-3 col-6">
                            <label for="posisi" class="form-label">{{ __('Posisi') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='posisi' type="text"
                                class="form-control" id="posisi">
                            @error('posisi')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 col-6">
                            <label for="jumlah_yang_dibutuhkan"
                                class="form-label">{{ __('Jumlah yang dibutuhkan') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='jumlah_dibutuhkan' type="text"
                                class="form-control" id="jumlah_yang_dibutuhkan">
                            @error('jumlah_dibutuhkan')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="level_posisi" class="form-label">{{ __('Level posisi') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='level_posisi' type="text"
                                class="form-control" id="level_posisi">
                            @error('level_posisi')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="manpower_posisi" class="form-label">{{ __('Manpower posisi') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='manpower_posisi' type="text"
                                class="form-control" id="manpower_posisi">
                            @error('manpower_posisi')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="jumlah_manpower_saat_ini"
                                class="form-label">{{ __('Jumlah manpower saat ini') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='jumlah_manpower_saat_ini'
                                type="text" class="form-control" id="jumlah_manpower_saat_ini">
                            @error('jumlah_manpower_saat_ini')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="waktu_masuk_kerja" class="form-label">{{ __('Waktu masuk kerja') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='waktu_masuk_kerja' type="text"
                                class="form-control" id="waktu_masuk_kerja">
                            @error('waktu_masuk_kerja')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="job_desc" class="form-label">{{ __('Job description') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='job_description' type="text"
                                class="form-control" id="job_desc">
                            @error('job_description')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="usia" class="form-label">{{ __('Usia') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='usia' type="text"
                                class="form-control" id="usia">
                            @error('usia')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="pendidikan" class="form-label">{{ __('Pendidikan') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='pendidikan' type="text"
                                class="form-control" id="pendidikan">
                            @error('pendidikan')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="pengalaman_kerja"
                                class="form-label">{{ __('Pengalaman kerja (tahun)') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='pengalaman_kerja' type="text"
                                class="form-control" id="pengalaman_kerja">
                            @error('pengalaman_kerja')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="mb-3 col-6">
                            <label for="kualifikasi_lain" class="form-label">{{ __('Kualifikasi lain') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='kualifikasi_lain' type="text"
                                class="form-control" id="kualifikasi_lain">
                            @error('kualifikasi_lain')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="mb-3 col-6">
                            <label for="kisaran_gaji" class="form-label">{{ __('Kisaran gaji') }}</label>
                            <input {{ !$is_requester ? 'disabled' : '' }} wire:model='kisaran_gaji' type="text"
                                class="form-control" id="kisaran_gaji">
                            @error('kisaran_gaji')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex">
                        {{--  Gender --}}
                        <div class="mb-3 col-4">
                            <label class="form-label">{{ __('Gender') }}</label>

                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model='gender' value='Pria'
                                    class="form-check-input" type="radio" name="flexRadioDefault" id="pria">
                                <label class="form-check-label" for="pria">
                                    {{ __('Pria') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model='gender' value='Wanita'
                                    class="form-check-input" type="radio" name="flexRadioDefault" id="wanita">
                                <label class="form-check-label" for="wanita">
                                    {{ __('Wanita') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model='gender' value='Bebas'
                                    class="form-check-input" type="radio" name="flexRadioDefault" id="bebas">
                                <label class="form-check-label" for="bebas">
                                    {{ __('Bebas') }}
                                </label>
                            </div>
                            @error('gender')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Skill wajib --}}
                        <div class="mb-3 col-4">
                            <label for="skill_wajib" class="form-label">{{ __('Skill wajib') }}</label>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='skil_wajib'
                                    class="form-check-input" type="checkbox" value="Bahasa inggris"
                                    id="bahasa_inggris">
                                <label class="form-check-label" for="bahasa_inggris">
                                    {{ __('Bahasa inggris') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='skil_wajib'
                                    class="form-check-input" type="checkbox" value="Bahasa mandarin"
                                    id="bahasa_mandarin">
                                <label class="form-check-label" for="bahasa_mandarin">
                                    {{ __('Bahasa mandarin') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='skil_wajib'
                                    class="form-check-input" type="checkbox" value="Komputer" id="komputer">
                                <label class="form-check-label" for="komputer">
                                    {{ __('Komputer') }}
                                </label>
                            </div>
                            @error('skil_wajib')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        {{-- Alasan permohonan --}}
                        <div class="mb-3 col-4">
                            <label for="alasan_permohonan" class="form-label">{{ __('Alasan permohonan') }}</label>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Menggantikan yang resign"
                                    id="menggantikan_yang_resign">
                                <label class="form-check-label" for="menggantikan_yang_resign">
                                    {{ __('Menggantikan yang resign') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Menggantikan yang dimutasi"
                                    id="menggantikan_yang_dimutasi">
                                <label class="form-check-label" for="menggantikan_yang_dimutasi">
                                    {{ __('Menggantikan yang dimutasi') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Beban kerja bertambah"
                                    id="beban_kerja_bertambah">
                                <label class="form-check-label" for="beban_kerja_bertambah">
                                    {{ __('Beban kerja bertambah') }}
                                </label>
                            </div>
                            <div class="form-check">
                                <input {{ !$is_requester ? 'disabled' : '' }} wire:model.live='alasan_permohonan'
                                    class="form-check-input" type="checkbox" value="Pengembangan bisnis"
                                    id="pengembangan_bisnis">
                                <label class="form-check-label" for="pengembangan_bisnis">
                                    {{ __('Pengembangan bisnis') }}
                                </label>
                            </div>
                            @error('alasan_permohonan')
                                <div class="text-danger">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    @if ($is_requester)

                        @if ($is_add)
                            <div wire:click='save' class="button btn btn-primary">Submit</div>
                        @endif
                        @if ($is_update && $status == 'Applying')
                            <div wire:click='update' class="button btn btn-primary">Update</div>
                        @endif
                        <div wire:click='exit' class="ml-5 button btn btn-dark">Exit/Cancel</div>
                    @endif



                    {{-- Request By  --}}
                    <div class="card-body rounded my-3"
                        style="background-color: {{ $is_request_approved ? 'rgb(124, 180, 107)' : 'rgb(191,191,191)' }} ">
                        <div class="d-flex">
                            <div class="mb-3 col-4">
                                <label for="approved_1" class="form-label">{{ __('Request by') }}</label>
                                {{-- <input wire:model='requester_id' type="text" class="form-control"
                                    id="approved_1"> --}}
                                <input value='{{ getName($requester_id) }}' type="text" class="form-control"
                                    id="approved_1" disabled>
                            </div>

                            <div class="mb-3 col-4">
                                <label for="approved_1" class="form-label">{{ __('Date of request') }}</label>
                                {{-- <input wire:model='tgl_request' type="text" class="form-control" id="approved_1"> --}}
                                <input value='{{ format_tgl($tgl_request) }}' type="text" class="form-control"
                                    id="approved_1" disabled>
                            </div>
                        </div>
                    </div>

                    {{-- Approved 1 --}}
                    @if ($is_approval_1 || $is_admin)
                        <div class="card-body rounded my-3"
                            style="background-color: {{ $is_approved_1 ? 'rgb(124, 180, 107)' : 'rgb(191,191,191)' }} ">
                            <div class="d-flex">
                                <div class="mb-3 col-4">
                                    <label for="approved_1" class="form-label">{{ __('1st Approval by') }}</label>
                                    <input disabled {{ $is_admin ? 'disabled' : '' }} wire:model='approve_1'
                                        type="text" class="form-control" id="approved_1">
                                </div>
                                <div class="mb-3 col-4">
                                    <div class="form-check text-center ">
                                        <div>
                                            <label for="approved_1" class="form-label">{{ __('Signature') }}</label>
                                        </div>
                                        @if (!$is_approved_1)
                                            <div class="mt-1">
                                                <input {{ $is_admin ? 'disabled' : '' }} wire:model.live='signature1'
                                                    class="form-check-input" type="checkbox" value="true"
                                                    id="approved_1">
                                                <label class="form-check-label" for="approved_1">
                                                    {{ __('Approve') }}
                                                </label>
                                            </div>
                                            @error('signature1')
                                                <div class="text-danger">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        @else
                                            <h4>{{ __('Approved') }}</h4>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="approved_1" class="form-label">{{ __('Date of approval') }}</label>
                                    <input disabled {{ $is_admin ? 'disabled' : '' }} value='{{ $approve_date_1 }}'
                                        type="text" class="form-control" id="approved_1">
                                </div>
                            </div>
                        </div>
                        @if ($is_approval_1 && $is_approved_1 == false)
                            <button wire:click='save_approve_1' class="btn btn-primary">{{ __('Approve') }}</button>
                        @endif
                        @if (!$is_admin)
                            <button wire:click='exit_approval_by' class="btn btn-dark">{{ __('Exit') }}</button>
                        @endif
                    @endif

                    {{-- Approved 2 --}}
                    @if ($is_approval_2 || $is_admin)
                        <div class="card-body rounded my-3"
                            style="background-color: {{ $is_approved_2 ? 'rgb(124, 180, 107)' : 'rgb(191,191,191)' }}">
                            <div class="d-flex">
                                <div class="mb-3 col-4">
                                    <label for="approved_2" class="form-label">{{ __('2nd Approval by') }}</label>
                                    <input disabled {{ $is_admin ? 'disabled' : '' }} wire:model='approve_2'
                                        type="text" class="form-control" id="approved_2">
                                </div>
                                <div class="mb-3 col-4">
                                    <div class="form-check text-center ">
                                        <div>
                                            <label for="approved_2" class="form-label">{{ __('Signature') }}</label>
                                        </div>
                                        @if (!$is_approved_2)
                                            <div class="mt-1">
                                                <input {{ $is_admin ? 'disabled' : '' }} wire:model.live='signature2'
                                                    class="form-check-input" type="checkbox" value="true"
                                                    id="approved_2">
                                                <label class="form-check-label" for="approved_2">
                                                    {{ __('Approve') }}
                                                </label>
                                            </div>
                                            @error('signature2')
                                                <div class="text-danger">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        @else
                                            <h4>{{ __('Approved') }}</h4>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 col-4">
                                    <label for="approved_2" class="form-label">{{ __('Date of approval') }}</label>
                                    <input disabled {{ $is_admin ? 'disabled' : '' }} value='{{ $approve_date_2 }}'
                                        type="text" class="form-control" id="approved_2">
                                </div>
                            </div>
                        </div>
                        @if ($is_approval_2 && $is_approved_2 == false)
                            <button wire:click='save_approve_2'
                                class="btn btn-primary">{{ __('Click to Approve') }}</button>
                        @endif
                        @if (!$is_admin)
                            <button wire:click='exit_approval_by' class="btn btn-dark">{{ __('Exit') }}</button>
                        @endif
                    @endif
                    @if ($is_admin)
                        <button wire:click='exit' class="btn btn-dark">{{ __('Exit') }}</button>
                    @endif
                </div>
            </div>
        </div>
    @endif
    {{-- table --}}
    @if (!$is_add && !$is_update)
        <div>
            <div class="card">
                <div class="card-header">
                    <div class='d-flex justify-content-between'>
                        <h3>{{ __('Personnel request lists') }}</h3>
                        @if (auth()->user()->role >= 6)
                            <a href="/addrequester"><button
                                    class="btn btn-primary">{{ __('Add Requester') }}</button></a>
                        @endif
                    </div>
                </div>
                <style>
                    td,
                    th {
                        white-space: nowrap;
                    }
                </style>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Position') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Requested by') }}</th>
                                    <th>{{ __('1st Approved by') }}</th>
                                    <th>{{ __('2nd Approved by') }}</th>
                                    <th>{{ __('Done by') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $d->posisi }}</td>
                                        <td>
                                            @if ($d->status == 'Applying')
                                                <span class="badge bg-warning text-dark">{{ $d->status }}</span>
                                            @elseif ($d->status == 'Approved')
                                                <span class="badge bg-primary">{{ $d->status }}</span>
                                            @elseif ($d->status == 'Done')
                                                <span class="badge bg-success">{{ $d->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                {{ getName($d->requester_id) }}
                                            </div>
                                            <div>
                                                {{ format_tgl($d->tgl_request) }}
                                            </div>
                                        </td>

                                        <td>
                                            <div>
                                                {{ getName($d->approve_by_1) }}
                                            </div>
                                            <div>
                                                {{ format_tgl($d->approve_date_1) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ getName($d->approve_by_2) }}
                                            </div>
                                            <div>
                                                {{ format_tgl($d->approve_date_2) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                {{ getName($d->done_by) }}
                                            </div>
                                            <div>
                                                {{ format_tgl($d->done_date) }}
                                            </div>
                                        </td>
                                        <td>
                                            <button wire:click='edit({{ $d->id }})'
                                                class="btn btn-warning btn-sm">{{ $is_requester && $d->status == 'Applying' ? 'Edit' : 'Show' }}</button>
                                            @if ($is_requester && $d->status == 'Applying')
                                                <button wire:click='deleteConfirmation({{ $d->id }})'
                                                    class="btn btn-danger btn-sm">Delete</button>
                                            @endif
                                            @if ($is_admin && $d->status != 'Done')
                                                <button wire:click='DoneConfirmation({{ $d->id }})'
                                                    class="btn btn-success btn-sm">Done Personnel Request</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $data->links() }}
                    </div>
                </div>

            </div>
        </div>
    @endif

    @script
        <script>
            window.addEventListener("show-delete-confirmation", (event) => {
                Swal.fire({
                    title: "Yakin mau delete data Requester ini?",
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

            window.addEventListener("show-done-confirmation", (event) => {
                Swal.fire({
                    title: "Rubah status Personnel Request Menjadi DONE?",
                    text: "",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Done",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("done-confirmed");
                    }
                });
            });
        </script>
    @endscript
</div>
