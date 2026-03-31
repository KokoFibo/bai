<div>
    <div>
        <div class="card col-6 mx-auto mt-5">
            <div class="card-header">
                <h3>Job Grade</h3>
            </div>
            <div class="card-body ">
                <div class="mb-3 ">
                    <label for="grade" class="form-label">Grade</label>
                    <input type="text" class="form-control @error('grade') is-invalid @enderror" id="grade"
                        wire:model='grade'>
                    @error('grade')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3 ">
                    <label for="grade_name" class="form-label">Grade Name</label>
                    <input type="text" class="form-control @error('grade_name') is-invalid @enderror" id="grade_name"
                        wire:model='grade_name'>
                    @error('grade_name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

            </div>
            <div class="card-body ">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Grade</th>
                            <th>Grade Name</th>
                            <th>
                                @if ($is_update)
                                    <button class="btn btn-success btn-sm" wire:click="update">Update</button>
                                    <button class="btn btn-dark btn-sm" wire:click='cancel'>Cancel</button>
                                @else
                                    <button class="btn btn-primary btn-sm" wire:click="add">Add</button>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @if ($datas->isNotEmpty())
                            @foreach ($datas as $key => $d)
                                <tr>
                                    <td>{{ $d->id }}</td>
                                    <td>{{ $d->grade }}</td>
                                    <td>{{ $d->grade_name }}</td>
                                    <td><button class="btn btn-warning btn-sm"
                                            wire:click='edit({{ $d->id }})'>Edit</button><button
                                            class="btn btn-danger btn-sm" wire:confirm='Yakin mau di delete?'
                                            wire:click='delete({{ $d->id }})'>Delete</button></td>
                                </tr>
                            @endforeach
                        @else
                            <h3>No data found</h3>
                        @endif
                    </tbody>
                </table>
            </div>
            {{ $datas->links() }}
        </div>
    </div>
</div>
