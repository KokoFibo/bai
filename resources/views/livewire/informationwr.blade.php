<div>
    @section('title', 'Add Information')

    <div class="col-xl-6 col-12 pt-5 mx-auto">
        <div class="card">
            <div class="card-header">
                {{ __('Add Information') }}
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">{{ __('Date') }}<span class="text-danger">*</span></label>
                    <div>
                        <input type="datetime:local" id="tanggal"
                            class="date form-control @error('date') is-invalid @enderror"" placeholder="mm-dd-yyyy"
                            wire:model="date">
                        @error('date')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="Title" class="form-label">{{ __('Title') }}
                        ({{ __('Max. 30 Characters') }})</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"" wire:model="title">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description"></textarea>
                    @error('description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                @if ($is_update == false)
                    <button wire:click="add"
                        class="btn btn-outline-success  nightowl-daylight">{{ __('Simpan') }}</button>
                @else
                    <button wire:click="save"
                        class="btn btn-outline-success  nightowl-daylight">{{ __('Update') }}</button>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 px-4">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td>{{ format_tgl($d->date) }}</td>
                        <td>{{ $d->title }}</td>
                        <td>{{ $d->description }}</td>
                        <td class="text-end">
                            <button class="btn btn-warning  nightowl-daylight"
                                wire:click="update({{ $d->id }})"><i
                                    class="fa-regular fa-pen-to-square nightowl-daylight"></i></button>
                            <button class="btn btn-danger nightowl-daylight"
                                onclick="return confirm('Delete Information?')"
                                wire:click="delete({{ $d->id }})"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
