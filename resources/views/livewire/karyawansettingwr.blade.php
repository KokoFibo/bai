<div>
    @section('title', ' Karyawan Setting')

    <div>

        <div class="container">
            <div class="mx-auto  pt-4">
                <button class="mx-auto col-12 btn btn-primary btn-large nightowl-daylight">
                    <h3 class="px-3">{{ __('Karyawan Settings') }}</h3>
                </button>
                <div class="card mt-5  mx-auto">
                    <div class="card-header">
                        <h5>{{ __('Reset Password Karyawan') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group col-xl-6 col-12 ">
                            <button class="btn btn-primary" type="button"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                            <input type="search" wire:model.live="search" class="form-control"
                                placeholder="{{ __('Masukkan Nama/ID Karyawan') }}">
                        </div>
                        @if ($data)
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Nama') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Handphone') }}</th>
                                        <th>{{ __('Tanggal Lahir') }}</th>
                                        <th>{{ __('Company') }}</th>
                                        <th>{{ __('Departemen') }}</th>
                                        <th>{{ __('Jabatan') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data->id_karyawan }}</td>
                                        <td>{{ $data->nama }}</td>
                                        <td>{{ $data->email }}</td>
                                        <td>{{ $data->hp }}</td>
                                        <td>{{ format_tgl($data->tanggal_lahir) }}</td>
                                        <td>{{ $data->branch }}</td>
                                        <td>{{ $data->departemen }}</td>
                                        <td>{{ $data->jabatan->nama_jabatan }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="col-3 mt-3">
                                <button wire:click="resetPassword"
                                    class="btn btn-primary">{{ __('Reset Password') }}</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
