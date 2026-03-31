<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}

    <title>Payroll Excel View</title>
</head>


<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="5" style="font-size:20px;  text-align:center">
                    <h3>{{ $header_text }}</h3>
                </th>
            </tr>
            {{-- TR baris atas utk colspan --}}
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th colspan="5" style="text-align: center; background-color: green; color:black">
                    <h4>Karyawan</h4>
                </th>

                <th colspan="5" style="text-align: center; background-color: blue; color:white">
                    <h4>Company</h4>
                </th>

                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            {{-- TR untuk title --}}
            <tr>
                <th style="text-align: center;">ID Karyawan</th>
                <th style="text-align: center;">Nama Karyawan</th>
                <th style="text-align: center;">Bank</th>
                <th style="text-align: center;">No. Rekening</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Placement</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">Metode Penggajian</th>
                <th style="text-align: center;">Total Hari Kerja</th>
                <th style="text-align: center;">Total Jam Kerja (Bersih)</th>
                <th style="text-align: center;">Total Jam Lembur</th>
                <th style="text-align: center;">Jumlah Jam Terlambat</th>
                <th style="text-align: center;">Tambahan Shift Malam</th>
                <th style="text-align: center;">Gaji Pokok</th>
                <th style="text-align: center;">Gaji Lembur</th>
                <th style="text-align: center;">Gaji Libur</th>
                <th style="text-align: center;">Bonus/U.Makan</th>
                <th style="text-align: center;">Potongan 1X</th>
                <th style="text-align: center;">Total NoScan</th>
                <th style="text-align: center;">Denda Lupa Absen</th>
                <th style="text-align: center;">Denda Resigned</th>
                <th style="text-align: center;">Tanggungan</th>
                <th style="text-align: center;">Iuran Air</th>
                <th style="text-align: center;">Iuran Locker</th>
                <th style="text-align: center;">Status Karyawan</th>
                <th style="text-align: center;">Gaji BPJS</th>
                <th style="text-align: center;">JHT</th>
                <th style="text-align: center;">JP</th>
                <th style="text-align: center;">JKK</th>
                <th style="text-align: center;">JKM</th>
                <th style="text-align: center;">Kesehatan</th>
                <th style="text-align: center;">JHT</th>
                <th style="text-align: center;">JP</th>
                <th style="text-align: center;">JKK</th>
                <th style="text-align: center;">JKM</th>
                <th style="text-align: center;">Kesehatan</th>
                <th style="text-align: center;">Total BPJS</th>
                <th style="text-align: center;">PTKP</th>
                <th style="text-align: center;">TER</th>
                <th style="text-align: center;">Rate</th>
                <th style="text-align: center;">Pph21</th>
                <th style="text-align: center;">Total</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                @php
                    $gaji_bpjs_max = 0;
                    if ($d->gaji_bpjs >= 12000000) {
                        $gaji_bpjs_max = 12000000;
                    } else {
                        $gaji_bpjs_max = $d->gaji_bpjs;
                    }
                    $kesehatan_company = ($gaji_bpjs_max * 4) / 100;

                    $jkk_company = ($d->gaji_bpjs * 0.24) / 100;
                    $jkm_company = ($d->gaji_bpjs * 0.3) / 100;
                    $jp_company = ($gaji_bpjs_max * 2) / 100;
                    $jht_company = ($d->gaji_bpjs * 3.7) / 100;
                    $total_bpjs_company = $d->gaji_bpjs + $jkk_company + $jkm_company + $kesehatan_company;
                    $ter = '';
                    switch ($d->ptkp) {
                        case 'TK0':
                            $ter = 'A';
                            break;
                        case 'TK1':
                            $ter = 'A';
                            break;
                        case 'TK2':
                            $ter = 'B';
                            break;
                        case 'TK3':
                            $ter = 'B';
                            break;
                        case 'K0':
                            $ter = 'A';
                            break;
                        case 'K1':
                            $ter = 'B';
                            break;
                        case 'K2':
                            $ter = 'B';
                            break;
                        case 'K3':
                            $ter = 'C';
                            break;
                    }

                    $rate_pph21 = get_rate_ter_pph21($d->ptkp, $total_bpjs_company);
                    $pph21 = ($total_bpjs_company * $rate_pph21) / 100;

                @endphp
                <tr>
                    <td style="text-align: center"> {{ $d->id_karyawan }}</td>
                    <td> {{ $d->nama }}</td>
                    <td style="text-align: center"> {{ $d->nama_bank }}</td>
                    <td style="text-align: center"> {{ strval($d->nomor_rekening) }}</td>
                    <td style="text-align: center"> {{ $d->jabatan }}</td>
                    <td style="text-align: center"> {{ $d->company }}</td>
                    <td style="text-align: center"> {{ $d->placement }}</td>
                    <td style="text-align: center"> {{ $d->departemen }}</td>
                    <td style="text-align: center"> {{ $d->metode_penggajian }}</td>
                    <td> {{ $d->hari_kerja }}</td>
                    <td> {{ $d->jam_kerja }}</td>
                    <td> {{ $d->jam_lembur }}</td>
                    <td> {{ $d->jumlah_jam_terlambat }}</td>
                    <td style="text-align: right"> {{ $d->tambahan_shift_malam }}</td>
                    <td style="text-align: right"> {{ $d->gaji_pokok }}</td>
                    <td style="text-align: right"> {{ $d->gaji_lembur }}</td>
                    <td style="text-align: right"> {{ $d->gaji_libur }}</td>
                    <td style="text-align: right"> {{ $d->bonus1x }}</td>
                    <td style="text-align: right"> {{ $d->potongan1x }}</td>
                    <td> {{ $d->total_noscan }}</td>
                    <td style="text-align: right"> {{ $d->denda_lupa_absen }}</td>
                    <td style="text-align: right"> {{ $d->denda_resigned }}</td>

                    <td> {{ $d->tanggungan }}</td>
                    <td style="text-align: right"> {{ $d->iuran_air }}</td>
                    <td style="text-align: right"> {{ $d->iuran_locker }}</td>
                    <td style="text-align: center"> {{ $d->status_karyawan }}</td>
                    <td style="text-align: right"> {{ $d->gaji_bpjs }}</td>
                    <td style="text-align: right"> {{ $d->jht }}</td>
                    <td style="text-align: right"> {{ $d->jp }}</td>
                    @if ($d->jkk == 1)
                        <td style="text-align: right">Yes</td>
                    @else
                        <td style="text-align: right">No</td>
                    @endif
                    @if ($d->jkm == 1)
                        <td style="text-align: right">Yes</td>
                    @else
                        <td style="text-align: right">No</td>
                    @endif
                    {{-- <td style="text-align: right"> {{ $d->jkk }}</td>
                    <td style="text-align: right"> {{ $d->jkm }}</td> --}}
                    <td style="text-align: right"> {{ $d->kesehatan }}</td>

                    @if ($d->jht > 0)
                        <td style="text-align: right"> {{ $jht_company }}</td>
                    @else
                        <td style="text-align: right"></td>
                    @endif
                    @if ($d->jp > 0)
                        <td style="text-align: right"> {{ $jp_company }}</td>
                    @else
                        <td style="text-align: right"></td>
                    @endif

                    @if ($d->jkk == 1)
                        <td style="text-align: right"> {{ $jkk_company }}</td>
                    @else
                        <td style="text-align: right"></td>
                    @endif

                    @if ($d->jkm == 1)
                        <td style="text-align: right"> {{ $jkm_company }}</td>
                    @else
                        <td style="text-align: right"></td>
                    @endif

                    @if ($d->kesehatan > 0)
                        <td style="text-align: right"> {{ $kesehatan_company }}</td>
                    @else
                        <td style="text-align: right"></td>
                    @endif


                    <td style="text-align: right"> {{ $total_bpjs_company }}</td>
                    <td style="text-align: right"> {{ $d->ptkp }}</td>
                    <td style="text-align: right"> {{ $ter }}</td>
                    <td style="text-align: right"> {{ $rate_pph21 }}</td>
                    <td style="text-align: right"> {{ $d->pph21 }}</td>
                    <td style="text-align: right"> {{ $d->total }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
