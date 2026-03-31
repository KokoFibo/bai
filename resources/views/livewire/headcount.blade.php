<div>
    <div class='col-4 mx-auto mt-5'>
        <div class="card">
            <div class="card-header bg-success">
                <h3>Headcount Excel</h3>
            </div>
            <div class="card-body ">
                <select class="form-select mb-3" aria-label="Default select example" wire:model.live='year'>
                    <option selected>Select year</option>
                    @foreach ($select_year as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <select class="form-select" aria-label="Default select example" wire:model.live='month'>
                    <option selected>Select month</option>
                    @foreach ($select_month as $month)
                        <option value="{{ $month }}">{{ monthName($month) }}</option>
                    @endforeach

                </select>
                <div class="d-flex justify-content-between">
                    <button wire:click='excel' class="btn btn-success mt-3">Proses</button>
                    <a href="/payroll"><button class="btn btn-dark mt-3">Back to Payroll</button></a>
                </div>

                <div class="text-center">
                    <button wire:loading wire:target='excel' class="btn btn-primary mt-3 mx-auto" type="button">
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                        <span role="status">{{ __('Building Excel for Headcount ... PLease wait') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-8 mx-auto mt-5">
        @if ($headcount)
            <div class="card">
                <div class="card-body">
                    @include('headcount_excel')
                </div>
            </div>
        @endif
    </div>
</div>
