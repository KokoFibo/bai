<div class='p-3'>
    <div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>Time off request lists</h3>
                    <div>
                        @if (auth()->user()->role > 5)
                            <a href="/addtimeoutrequester"><button class="btn btn-primary">Add Approver</button></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($is_show)
                    <div class="d-flex">
                        <ul class="list-group">
                            <li class="list-group-item">Request Date</li>
                            <li class="list-group-item">Request By</li>
                            <li class="list-group-item">Department</li>
                            <li class="list-group-item">Request Type</li>
                            <li class="list-group-item">{{ format_tgl($end_date) ? 'From - To' : 'On' }} </li>
                            <li class="list-group-item">Description</li>
                            <li class="list-group-item">Status</li>
                        </ul>
                        <ul class="list-group">
                            <li class="list-group-item">{{ format_tgl($tanggal) }}</li>
                            <li class="list-group-item">{{ $karyawan_id }}</li>
                            <li class="list-group-item">{{ nama_department($department_id) }}</li>
                            <li class="list-group-item">{{ $request_type }}</li>
                            <li class="list-group-item">{{ format_tgl($start_date) }}
                                {{ format_tgl($end_date) ? '  to  ' : '' }} {{ format_tgl($end_date) }}</li>
                            <li class="list-group-item">{{ $description }}</li>
                            <li class="list-group-item">{{ $status }}</li>
                        </ul>
                    </div>
                    @if ($hasFile)
                        <button class='btn btn-sm btn-primary mt-2' wire:click='show_toggle({{ $id }})'>Show
                            attachments</button>
                    @endif
                    @if ($show_attachment)
                        @if ($filenames)
                            @foreach ($filenames as $fn)
                                <div class="lg:my-5 py-2 w-full lg:w-1/2 ">
                                    <div class="d-flex justify-content-between px-1 pb-2">
                                        <div>

                                            <p class="text-sm py-1 lg:text-xl font-medium lg:font-bold">
                                                {{ $fn->originalName }}
                                            </p>
                                        </div>

                                        <div role="status" wire:loading wire:target='deleteFile'>
                                            <svg aria-hidden="true"
                                                class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                    fill="currentColor" />
                                                <path
                                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                    fill="currentFill" />
                                            </svg>
                                        </div>
                                    </div>
                                    @if (strtolower(getFilenameExtension($fn->originalName)) != 'pdf')
                                        <img class="w-full rounded-xl" src="{{ getUrl($fn->filename) }}"
                                            alt="">
                                    @else
                                        <iframe class="w-full rounded-xl" src="{{ getUrl($fn->filename) }}"
                                            width="100%" height="600px"></iframe>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    @endif
                    <div class="d-flex mt-3 align-items-center">
                        @if (auth()->user()->username != '58' && auth()->user()->username != '1146')
                            <button wire:click='approve' class="btn btn-success btn-sm">Approve</button>
                            <button wire:click='disapprove' class="ml-3 btn btn-danger btn-sm">Disapprove</button>

                            <div class='ml-5'>
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked"
                                    wire:model.live='is_checked' checked>
                                <label class="form-check-label" for="flexCheckChecked">
                                    Please check to confirm
                                </label>
                            </div>
                        @endif
                        <button wire:click='close' class="ml-3 btn btn-dark btn-sm">Close</button>

                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Request by</th>
                                    <th>Request date</th>
                                    <th>Department</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>1st Approve by</th>
                                    <th>2nd Approve by</th>
                                    <th>Done by</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $d)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <div>{{ $d->karyawan->nama }}</div>
                                            <div>ID. {{ $d->karyawan->id_karyawan }}</div>

                                        </td>

                                        <td>{{ $d->tanggal }}</td>

                                        <td>{{ nama_department($d->department_id) }}</td>
                                        <td>{{ $d->request_type }}</td>
                                        <td>{{ $d->description }}</td>

                                        <td>
                                            @if ($d->approve1 == 1)
                                                <div class='text-danger'>Tidak Disetujui</div>
                                            @else
                                                <div>{{ getName($d->approve1) }}</div>
                                                <div>{{ format_tgl($d->approve1_date) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($d->approve2 == 1)
                                                <div class='text-danger'>Tidak Disetujui</div>
                                            @else
                                                <div>{{ getName($d->approve2) }}</div>
                                                <div>{{ format_tgl($d->approve2_date) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ getName($d->done_by) }}</div>
                                            <div>{{ format_tgl($d->done_date) }}</div>
                                        </td>
                                        <td>
                                            @if ($d->status == 'Done')
                                                <span
                                                    class='badge rounded-pill text-bg-success'>{{ $d->status }}</span>
                                            @elseif($d->status == 'Confirmed')
                                                <span
                                                    class='badge rounded-pill text-bg-primary'>{{ $d->status }}</span>
                                            @elseif($d->status == 'Tidak Disetujui')
                                                <span
                                                    class='badge rounded-pill text-bg-danger'>{{ $d->status }}</span>
                                            @elseif($d->status == 'Menunggu Approval')
                                                <span
                                                    class='badge rounded-pill text-bg-info'>{{ $d->status }}</span>
                                            @endif
                                        </td>
                                        <td><button wire:click='show({{ $d->id }})'
                                                class='btn btn-sm btn-warning'>Show
                                                Detail</button>
                                            @if ($is_hrd)
                                                @if ($d->status == 'Done')
                                                    <button wire:click='undone({{ $d->id }})'
                                                        class='btn btn-sm btn-dark'>Click to Undone</button>
                                                @else
                                                    <button wire:click='done({{ $d->id }})'
                                                        class='btn btn-sm btn-success'>Click to done</button>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        td,
        th {
            white-space: nowrap;
        }
    </style>
</div>
