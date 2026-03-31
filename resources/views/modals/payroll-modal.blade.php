<!-- Modal -->
<div wire:ignore.self class="modal fade" id="payroll" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-lg" style="padding-bottom: 200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Slip Gaji</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                <div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Slip Gaji {{ monthName($month) }} {{ $year }} </h4>

                        </div>
                        <div class="card-body">

                            <table class="table">
                                <tbody>

                                    <tr>
                                        <td>Id</td>

                                        <td>
                                            {{ $data_payroll->id_karyawan }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>
                                            {{ $data_payroll->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td>Company / Placement</td>
                                        <td>
                                            {{ $data_karyawan->company->company_name }} /
                                            {{ $data_karyawan->placement->placement_name }}</td>
                                    </tr>
                                    @if ($data_karyawan->no_npwp != null)
                                        <tr>
                                            <td>No. NPWP</td>
                                            <td>
                                                {{ $data_karyawan->no_npwp }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Nama Bank</td>
                                        <td>
                                            {{ $data_karyawan->nama_bank }}</td>
                                    </tr>
                                    <tr>
                                        <td>No. Rekening</td>
                                        <td>
                                            {{ $data_karyawan->nomor_rekening }}</td>
                                    </tr>
                                    <tr>
                                        <td>Hari Kerja</td>
                                        <td>
                                            {{ $data_payroll->hari_kerja }} hari</td>
                                    </tr>
                                    <tr>
                                        <td>Jam Kerja</td>
                                        <td>
                                            {{ $data_payroll->jam_kerja }} jam</td>
                                    </tr>
                                    <tr>
                                        <td>Jam Lembur</td>
                                        <td>
                                            {{ $data_payroll->jam_lembur }} jam</td>
                                    </tr>
                                    <tr>
                                        <td>Gaji Pokok</td>
                                        <td>
                                            Rp. {{ number_format($data_payroll->gaji_pokok) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Gaji Lembur</td>
                                        <td>
                                            Rp. {{ number_format($data_payroll->gaji_lembur) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Subtotal Gaji</td>
                                        <td>
                                            Rp. {{ number_format($data_payroll->subtotal) }}</td>
                                    </tr>
                                    @if ($data_payroll->gaji_libur != 0)
                                        <tr>
                                            <td>Gaji Libur</td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->gaji_libur) }}</td>
                                        </tr>
                                    @endif

                                    @if ($data_payroll->tambahan_shift_malam != 0)
                                        <tr>
                                            <td>Bonus Shift Malam
                                            </td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->tambahan_shift_malam) }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($data_karyawan->iuran_air != 0)
                                        <tr>
                                            <td>Iuran air minum
                                            </td>
                                            <td>
                                                Rp. {{ number_format($data_karyawan->iuran_air) }}</td>
                                        </tr>
                                    @endif
                                    @if ($data_karyawan->iuran_locker != 0)
                                        <tr>
                                            <td>Iuran Locker</td>
                                            <td>
                                                Rp. {{ number_format($data_karyawan->iuran_locker) }}</td>
                                        </tr>
                                    @endif

                                    @if ($data_payroll->bonus1x != 0)
                                        <tr>
                                            <td>Bonus
                                            </td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->bonus1x) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data_payroll->potongan1x - ($data_karyawan->iuran_air + $data_karyawan->iuran_locker) != 0)
                                        <tr>
                                            <td>Potongan</td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->potongan1x) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data_payroll->denda_lupa_absen != 0)
                                        <tr>
                                            <td>Denda Lupa Absen
                                            </td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->denda_lupa_absen) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data_payroll->jht != 0)
                                        <tr>
                                            <td>BPJS JHT</td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->jht) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data_payroll->jp != 0)
                                        <tr>
                                            <td>BPJS JP</td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->jp) }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($data_payroll->kesehatan != 0)
                                        <tr>
                                            <td>BPJS Kesehatan
                                            </td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->kesehatan) }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($data_payroll->tanggungan != 0)
                                        <tr>
                                            <td>BPJS Tanggungan
                                            </td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->tanggungan) }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if ($data_payroll->denda_resigned != 0)
                                        <tr>
                                            <td>Lama Bekerja</td>
                                            <td>

                                                {{ selisih_hari($data_karyawan->tanggal_bergabung, $data_karyawan->tanggal_resigned) }}
                                                Hari
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Denda Resigned</td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->denda_resigned) }}</td>
                                        </tr>
                                    @endif
                                    @if ($data_karyawan->ptkp != '')
                                        <tr>
                                            <td>PTKP</td>
                                            <td>
                                                {{ $data_karyawan->ptkp }}</td>
                                        </tr>
                                    @endif
                                    @if ($data_karyawan->ptkp != '')
                                        <tr>
                                            <td>PPh21</td>
                                            <td>
                                                Rp. {{ number_format($data_payroll->pph21) }}
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>Total Gaji</td>
                                        <td>
                                            Rp. {{ number_format($data_payroll->total) }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>

</div>
