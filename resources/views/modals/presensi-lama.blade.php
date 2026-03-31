 <div wire:ignore.self class="modal fade" id="update-form-modal" data-bs-backdrop="static" data-bs-keyboard="false"
     tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg" style="padding-bottom: 200px;">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-4" id="staticBackdropLabel">Data Presensi Karyawan</h1>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <h4>{{ monthName($month) }} {{ $year }}</h4>
                 <div>
                     <p>User ID : {{ $user_id }}</p>
                     <p>Nama : {{ $name }}</p>
                 </div>
                 <div class="d-flex gap-3 align-items-center">
                     <div class="mb-3 row">
                         <label class="col-sm-2 col-form-label">Year</label>
                         <div class="col-sm-10">
                             <input wire:model.live="year" type="number" class="form-control">
                         </div>
                     </div>
                     <div class="mb-3 row">
                         <label class="col-sm-2 col-form-label">Month</label>
                         <div class="col-sm-10">
                             <input wire:model.live="month" type="number" class="form-control">
                         </div>
                     </div>
                     <div class="mb-3 row">
                         <button wire:click="submitPresensiDetail({{ $user_id }})"
                             class="btn btn-primary btn-sm">Submit</button>
                     </div>
                 </div>


                 <table class="table table-hover  table-bordered">
                     <thead>
                         <tr>
                             <th class="text-center">No.</th>
                             <th class="text-center">Tanggal</th>
                             <th class="text-center">Jam Kerja</th>
                             <th class="text-center">Jam Lembur</th>
                             <th class="text-center">Terlambat</th>
                             <th class="text-center">Shift Malam</th>
                         </tr>
                     </thead>
                     <tbody>


                         @foreach ($this->dataArr as $index => $d)
                             <tr class="{{ $d['table_warning'] ? 'table-warning' : '' }}">
                                 <td class="text-center">{{ $index + 1 }}</td>
                                 <td class="text-center">{{ $d['tgl'] }}</td>
                                 <td class="text-center">{{ $d['jam_kerja'] }}</td>
                                 <td class="text-center">{{ $d['jam_lembur'] }}</td>
                                 <td class="text-center">{{ $d['terlambat'] }}</td>
                                 <td class="text-center">{{ $d['tambahan_shift_malam'] }}</td>
                             </tr>
                         @endforeach
                         {{-- @foreach ($dataArr as $d)
                                <tr>
                                    <td class="text-center">{{ $d->tgl }}</td>
                                    <td class="text-center">{{ $d->jam_kerja }}</td>
                                    <td class="text-center">{{ $d->jam_lembur }}</td>
                                    <td class="text-center">{{ $d->terlambat }}</td>
                                </tr>
                            @endforeach --}}


                         <tr class="table-success">
                             <th class="text-center fs-5"></th>
                             <th class="text-center fs-5">{{ $total_hari_kerja }}</th>
                             <th class="text-center fs-5">{{ $total_jam_kerja }}</th>
                             <th class="text-center fs-5">{{ $total_jam_lembur }}</th>
                             <th class="text-center fs-5">{{ $total_keterlambatan }}</th>
                             <th class="text-center fs-5">{{ $total_tambahan_shift_malam }}</th>
                         </tr>
                     </tbody>
                 </table>

             </div>
             <div class="modal-footer">


                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>


             </div>
         </div>
     </div>
 </div>
