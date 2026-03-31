<div>
    <div class="card">
        <div class="card-header">
            <h3>Untuk menambah hari atau tanggal khusus</h3>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="exampleFormControlInput1" wire:model='date'>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkDefault" wire:model='is_friday'>
                <label class="form-check-label" for="checkDefault">
                    is Friday
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkDefault"
                    wire:model='is_saturday'>
                <label class="form-check-label" for="checkDefault">
                    is Saturday
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkDefault" wire:model='is_sunday'>
                <label class="form-check-label" for="checkDefault">
                    is Sunday
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="checkDefault"
                    wire:model='is_hari_libur_nasional'>
                <label class="form-check-label" for="checkDefault">
                    is Hari Libur Nasional
                </label>
            </div>
            <button class="btn btn-primary mt-3" wire:click='save'>Save</button>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Friday</th>
                <th>Saturday</th>
                <th>Sunday</th>
                <th>Hari Libur Nasional</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $d->date }}</td>
                    <td>{{ $d->is_friday ? 'Yes' : 'No' }}</td>
                    <td>{{ $d->is_saturday ? 'Yes' : 'No' }}</td>
                    <td>{{ $d->is_sunday ? 'Yes' : 'No' }}</td>
                    <td>{{ $d->is_hari_libur_nasional ? 'Yes' : 'No' }}</td>
                    <td><button class="btn btn-danger" wire:click='delete({{ $d->id }})'>Delete</button></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
