<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Karyawan Excel View</title>
</head>


<body>
    <table class="table">
        <thead>
            <tr>
                <th colspan="12" style="font-size:20px;  text-align:center">
                    <h3>{{ $header_text }}</h3>
                </th>
            </tr>
            <tr>
                <th style="text-align: center;">ID</th>
                <th style="text-align: center;">Nama Karyawan</th>
                <th style="text-align: center;">Directorate</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Level Jabatan</th>
                <th style="text-align: center;">Agama</th>
                <th style="text-align: center;">Etnis</th>
                <th style="text-align: center;">Tanggal Bergabung</th>
                <th style="text-align: center;">Status Karyawan</th>
                @if ($status_karyawan == 2)
                    <th style="text-align: center;">Tanggal Resigned</th>
                @endif

                @if ($status_karyawan == 3)
                    <th style="text-align: center;">Tanggal Blacklist</th>
                @endif
                <th style="text-align: center;">Metode Penggajian</th>
                <th style="text-align: center;">NPWP</th>
                <th style="text-align: center;">PTKP</th>
                <th style="text-align: center;">Gaji Pokok</th>
                <th style="text-align: center;">Gaji Lembur</th>
                <th style="text-align: center;">Gaji BPJS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                <tr>
                    <td style="text-align: center"> {{ $d->id_karyawan }}</td>
                    <td style="text-align: center"> {{ $d->nama }}</td>
                    <td style="text-align: center"> {{ $d->placement->placement_name }}</td>
                    <td style="text-align: center"> {{ $d->company->company_name }}</td>
                    <td style="text-align: center"> {{ $d->department->nama_department }}</td>
                    <td style="text-align: center"> {{ $d->jabatan->nama_jabatan }}</td>
                    <td style="text-align: center"> {{ getGrade($d->level_jabatan) }}</td>
                    <td style="text-align: center"> {{ $d->agama }}</td>
                    <td style="text-align: center"> {{ $d->etnis }}</td>
                    <td style="text-align: center"> {{ $d->tanggal_bergabung }}</td>
                    <td style="text-align: center"> {{ $d->status_karyawan }}</td>
                    @if ($status_karyawan == 2)
                        <th style="text-align: center;">{{ $d->tanggal_resigned }}</th>
                    @endif
                    @if ($status_karyawan == 3)
                        <th style="text-align: center;">{{ $d->tanggal_blacklist }}</th>
                    @endif
                    <td style="text-align: center"> {{ $d->metode_penggajian }}</td>
                    <td style="text-align: center"> {{ $d->no_npwp }}</td>
                    <td style="text-align: center"> {{ $d->ptkp }}</td>
                    <td style="text-align: right"> {{ $d->gaji_pokok }}</td>
                    <td style="text-align: right"> {{ $d->gaji_overtime }}</td>
                    <td style="text-align: right"> {{ $d->gaji_bpjs }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
