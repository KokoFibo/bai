<div>
    @section('title', 'Change Role')
    <div>
        <div class="container">
            <div class="mx-auto  pt-4">
                <button class="mx-auto col-12 btn btn-info btn-large  nightowl-daylight">
                    <h3 class="px-3">{{ __('Rubah Role Karyawan') }}</h3>
                </button>
                <div class="card mt-5  mx-auto">
                    <div class="card-header">
                        <h5>{{ __('Rubah Role Karyawan') }}</h5>
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
                                        {{-- <th>{{ __('Email') }}</th>
                                        <th>{{ __('Handphone') }}</th>
                                        <th>{{ __('Tanggal Lahir') }}</th>
                                        <th>{{ __('Company') }}</th>
                                        <th>{{ __('Departemen') }}</th>
                                        <th>{{ __('Jabatan') }}</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $data->username }}</td>
                                        <td>{{ $data->name }}</td>
                                        {{-- <td>{{ $data->email }}</td>
                                        <td>{{ $data->hp }}</td>
                                        <td>{{ format_tgl($data->tanggal_lahir) }}</td>
                                        <td>{{ $data->branch }}</td>
                                        <td>{{ $data->departemen }}</td>
                                        <td>{{ $data->jabatan->nama_jabatan }}</td> --}}
                                    </tr>
                                </tbody>
                            </table>
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input nightowl-daylight"
                                                type="radio" value="1">
                                            <label class="form-check-label">
                                                <h5>{{ __('User') }}</h5>
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input nightowl-daylight"
                                                type="radio" value="2">
                                            <label class="form-check-label">
                                                <h5>{{ __('Request') }}</h5>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input nightowl-daylight"
                                                type="radio" value="4">
                                            <label class="form-check-label">
                                                <h5>{{ __('Junior Admin') }}</h5>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input nightowl-daylight"
                                                type="radio" value="5">
                                            <label class="form-check-label">
                                                <h5>{{ __('Admin') }}</h5>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input wire:model="role" class="form-check-input nightowl-daylight"
                                                type="radio" value="6">
                                            <label class="form-check-label">
                                                <h5>{{ __('Senior Admin') }}</h5>
                                            </label>
                                        </div>
                                        @if (auth()->user()->role > 3)
                                            <div class="form-check">
                                                <input wire:model="role" class="form-check-input nightowl-daylight"
                                                    type="radio" value="7">
                                                <label class="form-check-label">
                                                    <h5>{{ __('Super Admin') }}</h5>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input wire:model="role" class="form-check-input nightowl-daylight"
                                                    type="radio" value="0">
                                                <label class="form-check-label">
                                                    <h5>{{ __('Board of Director') }}</h5>
                                                </label>
                                            </div>
                                        @endif

                                    </div>

                                </div>
                            </div>

                            <div class="col-3">
                                <button wire:click="save"
                                    class="btn btn-primary nightowl-daylight">{{ __('Save') }}</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-2 p-lg-3">
        <div class="card">
            <div class="card-header">
                <h4>List of Roles</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataRole as $d)
                            <tr>
                                <td>{{ $d->username }}</td>
                                <td>{{ $d->name }}</td>
                                <td>
                                    @if ($d->role == 0)
                                        <badge class="badge badge-primary nightowl-daylight">{{ role_name($d->role) }}
                                        </badge>
                                    @elseif($d->role == 2)
                                        <badge class="badge badge-secondary nightowl-daylight">
                                            {{ role_name($d->role) }}
                                        </badge>
                                    @elseif($d->role == 4)
                                        <badge class="badge badge-warning nightowl-daylight">
                                            {{ role_name($d->role) }}
                                        </badge>
                                    @elseif($d->role == 5)
                                        <badge class="badge badge-warning nightowl-daylight">{{ role_name($d->role) }}
                                        </badge>
                                    @elseif($d->role == 6)
                                        <badge class="badge badge-danger nightowl-daylight">{{ role_name($d->role) }}
                                        </badge>
                                    @elseif($d->role == 7)
                                        <badge class="badge badge-info nightowl-daylight">{{ role_name($d->role) }}
                                        </badge>
                                    @else
                                        <badge class="badge badge-success nightowl-daylight">{{ role_name($d->role) }}
                                        </badge>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $dataRole->links() }}
        </div>
    </div>
</div>
