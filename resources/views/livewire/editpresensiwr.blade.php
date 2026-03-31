<div>
    <div class="col-4 p-5">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">

                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">User Id</label>
                        <input wire:model="user_id" type="text" class="form-control" id="exampleFormControlInput1"
                            placeholder="ID">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">Tanggal</label>
                        <input wire:model="date" type="date" class="form-control" id="exampleFormControlInput1">
                    </div>

                </div>
                <button wire:click="edit" class="btn btn-info">Cari</button>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">First In</label>
                        <input wire:model="first_in" type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">First Out</label>
                        <input wire:model="first_out" type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">Second In</label>
                        <input wire:model="second_in" type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">Second Out</label>
                        <input wire:model="second_out" type="text" class="form-control"
                            id="exampleFormControlInput1">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">Overtime In</label>
                        <input wire:model="overtime_in" type="text" class="form-control"
                            id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">Overtime Out</label>
                        <input wire:model="overtime_out" type="text" class="form-control"
                            id="exampleFormControlInput1">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">Late jgn blank</label>
                        <input wire:model="late" type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">Late History</label>
                        <input wire:model="late_history" type="text" class="form-control"
                            id="exampleFormControlInput1">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">No Scan</label>
                        <input wire:model="no_scan" type="text" class="form-control" id="exampleFormControlInput1">
                    </div>
                    <div class="mb-3 col-6">
                        <label for="exampleFormControlInput1" class="form-label">No Scan History</label>
                        <input wire:model="no_scan_history" type="text" class="form-control"
                            id="exampleFormControlInput1">
                    </div>
                </div>
                <label for="exampleFormControlInput1" class="form-label">Shift</label>
                <div class="d-flex">
                    <div class="form-check col-3">
                        <input wire:model="shift" class="form-check-input" type="radio" name="flexRadioDefault"
                            value="Pagi" id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Pagi
                        </label>
                    </div>
                    <div class="form-check col-3">
                        <input wire:model="shift" class="form-check-input" type="radio" name="flexRadioDefault"
                            value="Malam" id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            Malam
                        </label>
                    </div>
                </div>

                <button wire:click="save" class="btn btn-primary mt-3">Update</button>
            </div>
        </div>

    </div>

</div>
