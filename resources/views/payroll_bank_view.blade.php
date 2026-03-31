<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> --}}

    <title>Bank Payroll Report</title>
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
                    <h4>Bank</h4>
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
                <th style="text-align: center;">Nama Karyawan</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Directorate</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">Bank</th>
                <th style="text-align: center;">No. Rekening</th>
                <th style="text-align: center;">Total Pembayaran</th>


            </tr>
        </thead>
        <tbody>
            @php
                $grandTotal = 0;
            @endphp
            @foreach ($data as $key => $d)
                @php
                    $grandTotal = $grandTotal + $d->total;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $d->nama }}</td>
                    <td style="text-align: center;">{{ nama_jabatan($d->jabatan_id) }}</td>
                    <td style="text-align: center;">{{ nama_company($d->company_id) }}</td>
                    <td style="text-align: center;">{{ nama_placement($d->placement_id) }}</td>
                    <td style="text-align: center;">{{ nama_department($d->department_id) }}</td>
                    <td style="text-align: center;">{{ $d->nama_bank }}</td>
                    <td style="text-align: center;">{{ $d->nomor_rekening }}</td>
                    <td style="text-align: center;">{{ $d->total }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">{{ $grandTotal }}</th>
            </tr>

        </tbody>
    </table>
</body>

</html>
