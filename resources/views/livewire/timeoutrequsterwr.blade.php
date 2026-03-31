<div>

    <div class="col-lg-4 col-8 mx-auto mt-5">
        <div class="card">
            <div class="card-header bg-primary">
                <h3>Approver</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="requester" class="form-label">Department
                        {{ $namaDepartment ? '->' . $namaDepartment : '' }}</label>
                    {{-- <input wire:model.live='department_id' type="text" class="form-control" id="requester"> --}}
                    <select class="form-select" aria-label="Default select example" wire:model.live='department_id'>
                        <option value=" ">Silakan Pilih Department</option>
                        @foreach ($data_department as $key => $d)
                            @if ($d->nama_department != '')
                                <option value="{{ $d->id }}">{{ $d->nama_department }}</option>
                            @endif
                        @endforeach

                    </select>
                    @error('department_id')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="approval1" class="form-label">1st Approve User Id
                        {{ $namaApproveBy1 ? '->' . $namaApproveBy1 : '' }}</label>
                    <input wire:model.live='approveBy1' type="text" class="form-control" id="approval1">
                    @error('approveBy1')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror

                </div>
                <div class="mb-3">
                    <label for="approval2" class="form-label">2nd Approve User Id
                        {{ $namaApproveBy2 ? '->' . $namaApproveBy2 : '' }}</label>
                    <input wire:model.live='approveBy2' type="text" class="form-control" id="approval2">
                    @error('approveBy2')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="d-flex justify-content-between">
                    @if ($is_update == true)
                        <button class="btn btn-primary" wire:click='update'>Update</button>
                    @else
                        <button class="btn btn-primary" wire:click='save'>Save</button>
                    @endif
                    <button class="btn btn-dark" wire:click='exit'>Exit</button>
                </div>
            </div>
        </div>
    </div>
    {{-- table --}}
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
    <div class="p-5">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department</th>
                                <th>1st Approve by</th>
                                <th>2nd Approve by</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_timeoff_requester as $key => $d)
                                <tr>
                                    <th>{{ $d->id }}</th>
                                    <th>{{ $d->department->nama_department }}</th>
                                    <th>{{ $d->approve_by_1 }}->{{ getName($d->approve_by_1) }}</th>
                                    <th>{{ $d->approve_by_2 }}->{{ getName($d->approve_by_2) }}</th>
                                    <th>
                                        <button wire:click='edit({{ $d->id }})'
                                            class="btn btn-warning btn-sm">Edit</button>
                                        <button wire:click='deleteConfirmation({{ $d->id }})'
                                            class="btn btn-danger btn-sm">Delete</button>
                                    </th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
        </script>
    @endscript
</div>
