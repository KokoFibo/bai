<div>
    {{-- <p>is_rubah_status = {{ $is_rubah_status }}</p>
    <p>status = {{ $status }}</p>
    <p>id_status : {{ $id_status }}</p> --}}
    <div class="p-3">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h2>Data Applicant</h2>
                    </div>
                    @if ($show_data)
                        <div>
                            <button class="btn btn-dark" wire:click='kembali'>Kembali</button>
                        </div>
                    @endif
                </div>
            </div>
            @if ($show_table)
                <div class="card-body">
                    <div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>HP</th>
                                    <th>Gender</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Status Penerimaan</th>
                                    <th>Submitted</th>
                                    @if ($is_rubah_status)
                                        <th>
                                            <div>

                                                <select class="form-select" aria-label="Default select example"
                                                    wire:model.live='status'>
                                                    <option value="1">1. Melamar</option>
                                                    <option value="2">2. Sedang Komunikasi</option>
                                                    <option value="3">3. Psikotest</option>
                                                    <option value="4">4. Interview</option>
                                                    <option value="5">5. Ditolak</option>
                                                    <option value="6">6. Cadangan</option>
                                                    <option value="7">7. Onboarding</option>
                                                    <option value="8">8. Diterima</option>
                                                </select>

                                                <button class="btn btn-sm btn-primary mt-2"
                                                    wire:click="rubah({{ $id_status }})">Rubah
                                                    Status
                                                </button>

                                                <button class="btn btn-sm btn-success mt-2"
                                                    wire:click='cancelUpdateStatus'>Cancel</button>

                                            </div>
                                        </th>
                                    @else
                                        <th></th>
                                    @endif

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $d)
                                    <tr>
                                        {{-- <td>{{ $key + 1 }}</td> --}}
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->email }}</td>
                                        <td>{{ $d->hp }}</td>
                                        <td>{{ $d->gender }}</td>
                                        <td>{{ $d->tgl_lahir }}</td>

                                        <td>
                                            <span
                                                class="badge {{ getStatusColor($d->status) }}">{{ getNamaStatus($d->status) }}</span>
                                        </td>

                                        <td>{{ $d->created_at }}</td>
                                        @if (!$is_rubah_status)
                                            <td>
                                                <button
                                                    class="btn
                                            btn-sm btn-warning"
                                                    wire:click='show({{ $d->id }})'>Show</button>
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click='delete({{ $d->id }})'
                                                    wire:confirm='Apakah yakin data applicant ini akan di delete?'>Delete</button>

                                                <button class="btn btn-sm btn-primary"
                                                    wire:click='rubahstatus({{ $d->id }}, {{ $d->status }})'>Rubah
                                                    Status</button>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $data->links() }}
                </div>
            @endif
            @if ($show_data)
                <div class="d-flex">
                    <ul class="list-group">
                        <li class="list-group-item">Nama</li>
                        <li class="list-group-item">Email</li>
                        <li class="list-group-item">HP</li>
                        <li class="list-group-item">Telepon</li>
                        <li class="list-group-item">Tempat/ Tanggal Lahir</li>
                        <li class="list-group-item">Gender</li>
                        <li class="list-group-item">Status Pernikahan</li>
                        <li class="list-group-item">Golongan Darah</li>
                        <li class="list-group-item">Agama</li>
                        <li class="list-group-item">Etnis</li>
                        <li class="list-group-item">Nama Kontak Darurat</li>
                        <li class="list-group-item">No Kontak Darurat</li>
                        <li class="list-group-item">Identitas/No</li>
                        <li class="list-group-item">Alamat Identitas</li>
                        <li class="list-group-item">Alamat Tinggal Sekarang</li>
                        <li class="list-group-item">Status</li>

                    </ul>
                    <ul class="list-group">
                        <li class="list-group-item">{{ $personal_data->nama }}</li>
                        <li class="list-group-item">{{ $personal_data->email }}</li>
                        <li class="list-group-item">{{ $personal_data->hp }}</li>
                        <li class="list-group-item">{{ $personal_data->telp }}</li>
                        <li class="list-group-item">{{ $personal_data->tempat_lahir }}/
                            {{ $personal_data->tgl_lahir }}</li>
                        <li class="list-group-item">{{ $personal_data->gender }}</li>
                        <li class="list-group-item">{{ $personal_data->status_pernikahan }}</li>
                        <li class="list-group-item">{{ $personal_data->golongan_darah }}</li>
                        <li class="list-group-item">{{ $personal_data->agama }}</li>
                        <li class="list-group-item">{{ $personal_data->etnis }}</li>
                        <li class="list-group-item">{{ $personal_data->nama_contact_darurat }}</li>
                        <li class="list-group-item">{{ $personal_data->contact_darurat_1 }} /
                            {{ $personal_data->contact_darurat_2 }}
                        </li>
                        <li class="list-group-item">{{ $personal_data->jenis_identitas }}:
                            {{ $personal_data->no_identitas }}</li>
                        <li class="list-group-item">{{ $personal_data->alamat_identitas }}</li>
                        <li class="list-group-item">{{ $personal_data->alamat_tinggal_sekarang }}</li>
                        <li class="list-group-item">{{ $personal_data->status }}</li>
                        @foreach ($personal_files as $f)
                            @if (strtolower(getFilenameExtension($f->originalName)) == 'pdf')
                                <li class="list-group-item">
                                    <div>{{ $f->originalName }}</div>
                                    <iframe class="my-3 rounded-4" src="{{ getUrl($f->filename) }}" width="100%"
                                        height="600px"></iframe>

                                </li>
                            @endif
                        @endforeach
                        @foreach ($personal_files as $key => $fn)
                            @if (strtolower(getFilenameExtension($fn->originalName)) != 'pdf')
                                <li class="list-group-item">
                                    <div class="flex flex-col">
                                        <div> {{ $fn->originalName }}</div>
                                        <img class="my-3 rounded-4" src="{{ getUrl($fn->filename) }}" alt="">
                                    </div>
                                </li>
                            @endif
                        @endforeach
                        <li class=" list-group-item" style="text-decoration: none">
                            <div class='w-1/5 text-center '>
                                <button class="btn btn-dark" wire:click='kembali'>Kembali</button>
                            </div>
                        </li>

                    </ul>
                </div>

            @endif

        </div>
    </div>

</div>
