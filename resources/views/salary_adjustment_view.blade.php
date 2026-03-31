<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payroll Excel View</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="5" style="font-size:20px;  text-align:center">
                    <h3>{{ $header_text }}</h3>
                </th>
            </tr>

            <tr>
                <th style="text-align: center;">ID Karyawan</th>
                <th style="text-align: center;">Nama Karyawan</th>
                <th style="text-align: center;">Placement</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Gaji Pokok</th>
                <th style="text-align: center;">Perubahan Gaji Pokok</th>
                <th style="text-align: center;">Gaji Lembur</th>
                <th style="text-align: center;">Perubahan Gaji Lembur</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($data as $key => $d)
                <tr>

                    <td style="text-align: center"> {{ $d->id_karyawan }}</td>
                    <td> {{ $d->nama }}</td>

                    <td style="text-align: center"> {{ nama_placement($d->placement_id) }}</td>
                    <td style="text-align: center"> {{ nama_company($d->company_id) }}</td>
                    <td style="text-align: center"> {{ nama_department($d->department_id) }}</td>
                    <td style="text-align: center"> {{ nama_jabatan($d->jabatan_id) }}</td>

                    <td style="text-align: right"> {{ $d->gaji_pokok }}</td>
                    <td style="text-align: right"> </td>
                    <td style="text-align: right"> {{ $d->gaji_overtime }}</td>
                    <td style="text-align: right"> </td>

                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
