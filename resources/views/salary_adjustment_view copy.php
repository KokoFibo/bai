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
                    <h3>{{ $header_text}}</h3>
                </th>
            </tr>

            {{-- TR untuk title --}}
            <tr>
                <th style="text-align: center;">ID Karyawan</th>
                <th style="text-align: center;">Nama Karyawan</th>

                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Placement</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">Metode Penggajian</th>

                <th style="text-align: center;">Gaji Pokok</th>
                <th style="text-align: center;">Gaji Lembur</th>
                <th style="text-align: center;">Bank</th>
                <th style="text-align: center;">No. Rekening</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
            <tr>
                <td style="text-align: center"> {{ $d->id_karyawan }}</td>
                <td> {{ $d->nama }}</td>

                <td style="text-align: center"> {{ nama_jabatan($d->id) }}</td>
                <td style="text-align: center"> {{ nama_company($d->id) }}</td>
                <td style="text-align: center"> {{ nama_placement($d->id) }}</td>
                <td style="text-align: center"> {{ nama_department($d->id) }}</td>
                <td style="text-align: center"> {{ $d->metode_penggajian }}</td>

                <td style="text-align: right"> {{ $d->gaji_pokok }}</td>
                <td style="text-align: right"> {{ $d->gaji_overtime }}</td>
                <td style="text-align: center"> {{ $d->nama_bank }}</td>
                <td style="text-align: center"> {{ strval($d->nomor_rekening) }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>