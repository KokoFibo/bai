<div>
    @section('title', 'Restore Presensi')
    <p>getYear: {{ $getYear }}</p>
    <p>getMonth : {{ $getMonth }}</p>
    <div class="col-xl-4 mx-auto pt-5">
        <div class="card">
            <div class="card-header bg-success">
                <h3>Move Back Data (Restore) ==> Presensi</h3>
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <select class="form-select" aria-label="Default select example" wire:model.live="getYear">
                        <option selected value=" ">Select year</option>
                        @foreach ($dataTahun as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                        @endforeach
                    </select>
                </div>
                @if ($getYear)
                    <div class="mb-3">
                        <select class="form-select" aria-label="Default select example" wire:model.live="getMonth">
                            <option selected value=" ">Select Month</option>
                            @foreach ($dataBulan as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                {{-- @if ($dataBulan) --}}
                {{-- <p>Tahun: {{ $getYear }}, Bulan : {{ $getMonth }}</p> --}}
                {{-- @endif --}}
                @if ($getMonth != null && $getYear != null)
                    <div>
                        <button wire:loading wire:target='moveBack' class="btn btn-primary" type="button" disabled>
                            <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                            <span role="status">{{ __('Moving...') }}</span>
                        </button>
                    </div>
                    <div wire:loading.class='invisible'>
                        <p>Total data {{ $getMonth }} - {{ $getYear }} : {{ $totalData }} Data</p>
                        <p>Pastikan table "rekapbackup" tersedia</p>
                        <button class="btn btn-warning" wire:click="moveBack">Move Data {{ $getMonth }} -
                            {{ $getYear }}</button>
                        <button class="btn btn-dark" wire:click="cancel">Cancel</button>
                    </div>
                @endif
            </div>
        </div>
    </div>


</div>
