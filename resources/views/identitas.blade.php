<div class="card">

    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">{{ __('Jenis Identitas') }} <span class="text-danger">*</span></label>
            <select class="form-select @error('jenis_identitas') is-invalid @enderror" aria-label="Default select example"
                wire:model="jenis_identitas">
                <option value=" ">{{ __('Pilih jenis Identitas') }}</option>
                <option value="KTP">{{ __('KTP') }}</option>
                <option value="Passport">{{ __('Passport') }}</option>
            </select>
            @error('jenis_identitas')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Nomor Identitas') }} <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('no_identitas') is-invalid @enderror"
                wire:model="no_identitas">
            @error('no_identitas')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Alamat Identitas') }} <span class="text-danger">*</span></label>
            <textarea class="form-control @error('alamat_identitas') is-invalid @enderror" id="exampleFormControlTextarea1"
                rows="3" wire:model="alamat_identitas"></textarea>
            @error('alamat_identitas')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">{{ __('Alamat Tinggal Sekarang') }} <span class="text-danger">*</span></label>
            <textarea class="form-control @error('alamat_tinggal') is-invalid @enderror" id="exampleFormControlTextarea1"
                rows="3" wire:model="alamat_tinggal"></textarea>
            @error('alamat_tinggal')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>
