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

            {{-- TR untuk title --}}

            <tr>
                <th style="text-align: center;">ID Karyawan</th>
                <th style="text-align: center;">Nama Karyawan</th>
                <th style="text-align: center;">Placement</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Etnis</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Tanggal Bergabung</th>
                <th style="text-align: center;">Lama Bergabung (Bulan)</th>
                <th style="text-align: center;">Lama Bergabung (Hari)</th>
                <th style="text-align: center;">Metode Penggajian</th>
                <th style="text-align: center;">Gaji Pokok</th>
                <th style="text-align: center;">THR</th>
                <th style="text-align: center;">Penyesuaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $k)
                <tr>
                    <td style="text-align: center;">{{ $k->id_karyawan }}</td>
                    <td style="text-align: center;">{{ $k->nama }}</td>
                    <td style="text-align: center;">{{ $k->placement->placement_name }}</td>
                    <td style="text-align: center;">{{ $k->company->company_name }}</td>
                    <td style="text-align: center;">{{ $k->department->nama_department }}</td>
                    <td style="text-align: center;">{{ $k->jabatan->nama_jabatan }}</td>
                    <td style="text-align: center;">{{ $k->etnis }}</td>
                    <td style="text-align: center;">{{ $k->status_karyawan }}</td>
                    <td style="text-align: center;">{{ format_tgl($k->tanggal_bergabung) }}</td>
                    <td style="text-align: center;">{{ selisihBulan($k->tanggal_bergabung, $cutOffDate) }}</td>
                    <td style="text-align: center;">{{ selisihHari($k->tanggal_bergabung, $cutOffDate) }}</td>
                    <td style="text-align: center;">{{ $k->metode_penggajian }}</td>
                    <td style="text-align: center;">{{ $k->gaji_pokok }}</td>
                    <td style="text-align: center;">
                        {{ hitungTHR($k->id_karyawan, $k->tanggal_bergabung, $k->gaji_pokok, $cutOffDate) }}
                    </td>
                    <td></td>

                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
