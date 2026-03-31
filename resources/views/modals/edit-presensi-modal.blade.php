<div wire:ignore.self class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    {{-- <div class="modal-dialog modal-dialog-centered " role="document"> --}}
    <div class="modal-dialog " role="document">
        <div class="modal-content mt-5">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="editModalLabel">Edit Jam Kerja</h5>
                    <h6>{{ $show_name }} ({{ $show_id }})</h6>
                </div>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="closeModal">
                    <span aria-hidden="true">&times;</span>

                </button> --}}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    wire:click="closeModal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First In</label>
                        <input type="time" class="form-control" wire:model.defer="first_in" step="60"
                            placeholder="--:--" onfocus="this.showPicker()"
                            onkeydown="if(event.key==='Delete'||event.key==='Backspace'){ this.value=''; @this.set('first_in', null) }">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">First Out</label>
                        <input type="time" class="form-control" wire:model.defer="first_out" step="60"
                            placeholder="--:--" onfocus="this.showPicker()"
                            onkeydown="if(event.key==='Delete'||event.key==='Backspace'){ this.value=''; @this.set('first_out', null) }">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Second In</label>
                        <input type="time" class="form-control" wire:model.defer="second_in" step="60"
                            placeholder="--:--" onfocus="this.showPicker()"
                            onkeydown="if(event.key==='Delete'||event.key==='Backspace'){ this.value=''; @this.set('second_in', null) }">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Second Out</label>
                        <input type="time" class="form-control" wire:model.defer="second_out" step="60"
                            placeholder="--:--" onfocus="this.showPicker()"
                            onkeydown="if(event.key==='Delete'||event.key==='Backspace'){ this.value=''; @this.set('second_out', null) }">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Overtime In</label>
                        <input type="time" class="form-control" wire:model.defer="overtime_in" step="60"
                            placeholder="--:--" onfocus="this.showPicker()"
                            onkeydown="if(event.key==='Delete'||event.key==='Backspace'){ this.value=''; @this.set('overtime_in', null) }">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Overtime Out</label>
                        <input type="time" class="form-control" wire:model.defer="overtime_out" step="60"
                            placeholder="--:--" onfocus="this.showPicker()"
                            onkeydown="if(event.key==='Delete'||event.key==='Backspace'){ this.value=''; @this.set('overtime_out', null) }">
                    </div>
                </div>
                {{-- @if (auth()->user()->role > 5) --}}
                @if ($delete_no_scan_history)
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" value="1" id="checkChecked"
                            wire:model='is_delete'>
                        <label class="form-check-label" for="checkChecked">
                            Delete No Scan History
                        </label>
                    </div>
                @endif



            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                <button type="button" class="btn btn-success" wire:click="update">Save</button>
            </div>
        </div>
    </div>
</div>
<style>
    /* Pastikan override hanya untuk modal ini agar tidak merusak modal lain */
    /* Terapkan ketika modal ditampilkan (.show) */
    #editModal.show {
        display: block !important;
        /* force tampil */
        z-index: 2100 !important;
        /* lebih tinggi dari backdrop */
        pointer-events: auto !important;
    }
</style>


<script>
    window.addEventListener('show-edit-modal', () => {
        $('#editModal').modal('show');
    });

    window.addEventListener('hide-edit-modal', () => {
        $('#editModal').modal('hide');
    });
</script>
