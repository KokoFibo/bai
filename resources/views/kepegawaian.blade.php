<div class="card">
    <div class="card-body">
        <div class="card">
            <div class="card-header bg-secondary">
                <h5 class="text-light">{{ __('Gaji') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Status Karyawan') }} <span
                                    class="text-danger">*</span></label>
                            {{-- ok1 --}}
                            <select class="form-select @error('status_karyawan') is-invalid @enderror" name="status"
                                id="statusid" onchange="getStatusValue()"
                                @if ($status_off) wire:model="status_karyawan"
                                @else
                                wire:model="status_karyawan" @endif>
                                <option value=" ">{{ __('Pilih status karyawan') }}</option>
                                <option value="PKWT">PKWT</option>
                                <option value="PKWTT">PKWTT</option>
                                @if ($status_off == false)
                                    <option value="Dirumahkan">Dirumahkan</option>
                                    <option value="Resigned">Resigned</option>
                                    <option value="Blacklist">Blacklist</option>
                                @endif
                            </select>
                            @error('status_karyawan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3" style="{{ $status_karyawan == 'Resigned' ? '' : 'display: none' }}"
                            id="resignedid">
                            <label class="form-label">{{ __('Tanggal Resigned') }}</label>
                            <div>
                                <input type="date"
                                    class="date form-control @error('tanggal_resigned') is-invalid @enderror""
                                    placeholder="mm-dd-yyyy" wire:model="tanggal_resigned">
                                @error('tanggal_resigned')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        {{-- @if ($status_karyawan == 'Blacklist') --}}
                        <div class="mb-3" style="{{ $status_karyawan == 'Blacklist' ? '' : 'display: none' }}"
                            id="blacklistid">
                            <label class="form-label">{{ __('Tanggal Blacklist') }}</label>
                            <div>
                                <input type="date"
                                    class="date form-control @error('tanggal_blacklist') is-invalid @enderror"
                                    placeholder="mm-dd-yyyy" wire:model="tanggal_blacklist">
                                @error('tanggal_blacklist')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        {{-- @endif --}}

                    </div>
                    <div class="col-md-4">
                        {{-- <div class="col-md-4"> --}}
                        <div class="mb-3">
                            <label class="form-label">{{ __('Tanggal Bergabung') }} <span
                                    class="text-danger">*</span></label>
                            <div>

                                @if (auth()->user()->role > 2)
                                    <input type="datetime:local" id="tanggal"
                                        class="date form-control @error('tanggal_bergabung') is-invalid @enderror"
                                        placeholder="mm-dd-yyyy" wire:model="tanggal_bergabung">
                                    @error('tanggal_bergabung')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                @else
                                    <input type="datetime:local" id="tanggal" {{ $is_update ? 'disabled' : '' }}
                                        class="date form-control @error('tanggal_bergabung') is-invalid @enderror"
                                        placeholder="mm-dd-yyyy" wire:model="tanggal_bergabung">
                                    @error('tanggal_bergabung')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Company') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('company_id') is-invalid @enderror"
                                aria-label="Default select example" wire:model="company_id">
                                <option value=" ">{{ __('Pilih company') }}</option>
                                @foreach ($pilih_company as $key => $nj)
                                    @if ($nj->id != 100)
                                        <option value="{{ $nj->id }}">{{ $nj->company_name }}</option>
                                    @endif
                                @endforeach
                                {{-- <option value="ASB">ASB</option>
                                <option value="DPA">DPA</option>
                                <option value="YCME">YCME</option>
                                <option value="YEV">YEV</option>
                                <option value="YIG">YIG</option>
                                <option value="YSM">YSM</option>
                                <option value="YAM">YAM</option>
                                <option value="GAMA">GAMA</option>
                                <option value="WAS">WAS</option> --}}

                            </select>
                            @error('company_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Department') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('department_id') is-invalid @enderror"
                                aria-label="Default select example" wire:model="department_id">
                                <option value=" ">{{ __('Pilih Department') }}</option>
                                @foreach ($pilih_department as $key => $nj)
                                    @if ($nj->id != 100)
                                        <option value="{{ $nj->id }}">{{ $nj->nama_department }}</option>
                                    @endif
                                @endforeach

                                {{-- <option value="BD">BD</option>
                                <option value="Engineering">Engineering</option>
                                <option value="EXIM">EXIM</option>
                                <option value="Finance Accounting">Finance Accounting</option>
                                <option value="GA">GA</option>
                                <option value="Gudang">Gudang</option>
                                <option value="HR">HR</option>
                                <option value="Legal">Legal</option>
                                <option value="Procurement">Procurement</option>
                                <option value="Produksi">Produksi</option>
                                <option value="Quality Control">Quality Control</option>
                                <option value="Board of Director">Board of Director</option> --}}

                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Jabatan') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('jabatan_id') is-invalid @enderror"
                                aria-label="Default select example" wire:model="jabatan_id">
                                <option value=" ">{{ __('Pilih jabatan') }}</option>
                                @foreach ($pilih_jabatan as $key => $nj)
                                    @if ($nj->id != 100)
                                        <option value="{{ $nj->id }}">{{ $nj->nama_jabatan }}</option>
                                    @endif
                                @endforeach
                            </select>
                            {{-- <select class="form-select @error('jabatan') is-invalid @enderror"
                                aria-label="Default select example" wire:model="jabatan">
                                <option value=" ">{{ __('Pilih jabatan') }}</option>
                                <option value="Admin">{{ __('') }}Admin</option>
                                <option value="Asisten Direktur">Asisten Direktur</option>
                                <option value="Asisten Kepala">Asisten Kepala</option>
                                <option value="Asisten Manager">Asisten Manager</option>
                                <option value="Asisten Pengawas">Asisten Pengawas</option>
                                <option value="Asisten Wakil Presiden">Asisten Wakil Presiden</option>
                                <option value="Design Grafis">Design Grafis</option>
                                <option value="Director">Director</option>
                                <option value="Kepala">Kepala</option>
                                <option value="Manager">Manager</option>
                                <option value="Pengawas">Pengawas</option>
                                <option value="President">President</option>
                                <option value="Senior Staff">Senior Staff</option>
                                <option value="Staff">Staff</option>
                                <option value="Supervisor">Supervisor</option>
                                <option value="Vice President">Vice President</option>
                                <option value="Satpam">Satpam</option>
                                <option value="Koki">Koki</option>
                                <option value="Dapur Kantor">Dapur Kantor</option>
                                <option value="Dapur Pabrik">Dapur Pabrik</option>
                                <option value="QC Aging">QC Aging</option>
                                <option value="Driver">Driver</option>
                                <option value="Translator">Translator</option>
                                <option value="Senior SPV">Senior SPV</option>

                            </select> --}}
                            @error('jabatan_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Job Grade') }}</label>
                            <select class="form-select" aria-label="Default select example" wire:model="level_jabatan">
                                <option value=" ">{{ __('Pilih Job Grade') }}</option>
                                @foreach ($jobgrades as $jobgrade)
                                    <option value="{{ $jobgrade->id }}">{{ $jobgrade->grade }} -
                                        {{ $jobgrade->grade_name }}</option>
                                @endforeach
                                {{-- <option value="M1">M1</option>
                                <option value="M2">M2</option>
                                <option value="M3">M3</option>
                                <option value="M4">M4</option>
                                <option value="M5">M5</option>
                                <option value="M6">M6</option>
                                <option value="M7">M7</option>
                                <option value="M8">M8</option>
                                <option value="M9">M9</option>
                                <option value="M10">M10</option>
                                <option value="P1">P1</option>
                                <option value="P2">P2</option>
                                <option value="P3">P3</option>
                                <option value="P4">P4</option>
                                <option value="P5">P5</option>
                                <option value="P6">P6</option>
                                <option value="P7">P7</option>
                                <option value="P8">P8</option> --}}
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Directorate') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('placement_id') is-invalid @enderror"
                                aria-label="Default select example" wire:model="placement_id">
                                <option value=" ">{{ __('Pilih Directorate') }}</option>
                                @foreach ($pilih_placement as $key => $nj)
                                    @if ($nj->id != 100)
                                        <option value="{{ $nj->id }}">{{ $nj->placement_name }}</option>
                                    @endif
                                @endforeach
                                {{-- <option value="ASB">ASB</option>
                                <option value="DPA">DPA</option>
                                <option value="YCME">YCME</option>
                                <option value="YIG">YIG</option>
                                <option value="YSM">YSM</option>
                                <option value="YAM">YAM</option>
                                <option value="GAMA">GAMA</option>
                                <option value="WAS">WAS</option>
                                <option value="YEV">YEV</option>
                                <option value="YEV SMOOT">YEV SMOOT</option>
                                <option value="YEV OFFERO">YEV OFFERO</option>
                                <option value="YEV SUNRA">YEV SUNRA</option>
                                <option value="YEV AIMA">YEV AIMA</option> --}}

                            </select>
                            @error('placement_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-2">
            <div class="card-header bg-secondary">
                <h5 class="text-light">{{ __('Data Bank') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Nama Bank') }}</label>
                            <select class="form-select" aria-label="Default select example" wire:model="nama_bank">
                                <option value=" ">{{ __('Pilih nama bank') }}</option>
                                <option value="BRI">BRI</option>
                                <option value="BCA">BCA</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="BNI">BNI</option>
                                <option value="Permata">Permata</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('Nomor Rekening') }}</label>
                            <input wire:model="nomor_rekening" type="number" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ok1 --}}
    <script>
        function getStatusValue() {
            let status = document.getElementById("statusid");
            let resigned = document.getElementById("resignedid");
            let blacklist = document.getElementById("blacklistid");
            if (status.value == "Resigned") {
                resigned.style.display = "block";
                blacklist.style.display = "none";
                console.log(status.value)
            } else if (status.value == "Blacklist") {
                blacklist.style.display = "block";
                resigned.style.display = "none";
                console.log(status.value)
            } else {
                blacklist.style.display = "none";
                resigned.style.display = "none";
            }
        }
    </script>

</div>
