<div>
    <div class="row g-4">
        <div class="mt-5">

            <h4>
                Karyawan No Scan — Total:
                <span class="text-danger">{{ $total_karyawan_noscan }}</span>
                <span class=" ms-2">{{ $periodeNoscan }}</span>
            </h4>




            <div class="d-flex align-items-center flex-wrap gap-2 mb-3">

                {{-- Toggle --}}
                <button class="btn btn-sm btn-primary" wire:click="toggleKaryawanNoscan">
                    {{ $showKaryawanNoscan ? 'Hide Details' : 'Show Details' }}
                </button>

                {{-- Dropdown bulan --}}
                <select wire:model.live="selectBulan" class="form-select form-select-sm w-auto">
                    <option value="0">Bulan ini</option>
                    <option value="1">Bulan lalu</option>
                </select>

                {{-- Date From --}}
                <input type="date" wire:model.live="noscanFrom" class="form-control form-control-sm w-auto">

                {{-- Date To --}}
                <input type="date" wire:model.live="noscanTo" class="form-control form-control-sm w-auto">

                {{-- Dropdown Metode Penggajian --}}
                <select wire:model.live="metode" class="form-select form-select-sm w-auto">
                    <option value="Perbulan">Perbulan</option>
                    <option value="Perjam">Perjam</option>
                    <option value="Semua">Semua</option>
                </select>

                {{-- Export Excel --}}
                <button class="btn btn-sm btn-success" wire:click="excelNoscan()">
                    Excel
                </button>

            </div>


            @if ($showKaryawanNoscan)
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>ID Karyawan</th>
                            <th>Nama</th>
                            <th>Company</th>
                            <th>Directorate</th>
                            <th>Department</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Company</th>
                            <th>Jumlah Hari No Scan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($karyawan_noscan as $key => $dtr)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $dtr->user_id }}</td>
                                <td>{{ $dtr->nama }}</td>
                                <td>{{ nama_company($dtr->company_id) }}</td>
                                <td>{{ nama_placement($dtr->placement_id) }}</td>
                                <td>{{ nama_department($dtr->department_id) }}</td>
                                <td>{{ nama_jabatan($dtr->jabatan_id) }}</td>
                                <td>{{ $dtr->status_karyawan }}</td>
                                <td>{{ nama_company($dtr->company_id) }}</td>
                                <td>{{ $dtr->total_noscan }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
                <div class="mb-2">
                    <label class="form-label">Show</label>
                    <select class="form-select form-select-sm w-auto d-inline-block" wire:model.live="perpage">
                        <option value="5">5 per page</option>
                        <option value="10">10 per page</option>
                        <option value="15">15 per page</option>
                        <option value="20">20 per page</option>
                        <option value="25">25 per page</option>
                        <option value="500">All pages</option>
                    </select>
                </div>

                {{ $karyawan_noscan->onEachSide(0)->links() }}
            @endif
        </div>
    </div>
</div>
