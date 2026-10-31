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
                <th style="text-align: center;">NIK/Passport</th>
                <th style="text-align: center;">No Identitas</th>
                <th style="text-align: center;">Tempat Lahir</th>
                <th style="text-align: center;">Tanggal Lahir</th>
                <th style="text-align: center;">Jenis Kelamin</th>
                <th style="text-align: center;">Alamat ID</th>
                <th style="text-align: center;">Alamat Tinggal</th>
                <th style="text-align: center;">Email</th>
                <th style="text-align: center;">No HP</th>
                <th style="text-align: center;">Company</th>
                <th style="text-align: center;">Directorate</th>
                <th style="text-align: center;">Departement</th>
                <th style="text-align: center;">Placement</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Tanggal Bergabung</th>
                <th style="text-align: center;">Tanggal Resigned/Blacklist</th>
                <th style="text-align: center;">No rekening</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                <tr>
                    <td style="text-align: center"> {{ $d->id_karyawan }}</td>
                    <td style="text-align: center"> {{ $d->nama }}</td>
                    <td style="text-align: center"> {{ $d->jenis_identitas }}</td>
                    {{-- <td style="text-align: center"> {{ $d->no_identitas }}</td> --}}
                    {{-- <td style="text-align: center">{{ str_pad((string) $d->no_identitas, 16, '0', STR_PAD_LEFT) }}</td> --}}
                    {{-- <td style="text-align: center">{{ "'" . str_pad($d->no_identitas, 16, '0', STR_PAD_LEFT) }}</td> --}}
                    {{-- <td style="text-align: center">{{ str_pad($d->no_identitas, 16, '0', STR_PAD_LEFT) }}</td> --}}
                    {{-- <td style="text-align: center">{{ (string) $d->no_identitas }}</td> --}}
                    <td style="text-align: center">'{{ $d->no_identitas }}</td>
                    {{-- <td style="mso-number-format:\@">{{ $d->no_identitas_str }}</td> --}}
                    {{-- <td style="text-align: center">{{ "'" . $d->no_identitas }}</td> --}}






                    <td style="text-align: center"> {{ $d->tempat_lahir }}</td>
                    <td style="text-align: center"> {{ $d->tanggal_lahir }}</td>
                    <td style="text-align: center"> {{ $d->gender }}</td>
                    <td style="text-align: center"> {{ $d->alamat_identitas }}</td>
                    <td style="text-align: center"> {{ $d->alamat_tinggal }}</td>
                    <td style="text-align: center"> {{ $d->email }}</td>
                    <td style="text-align: center"> {{ $d->hp }}</td>
                    <td style="text-align: center">
                        {{ optional($d->company)->company_name ?? '-' }}
                    </td>

                    <td style="text-align: center">
                        {{ optional($d->placement)->placement_name ?? '-' }}
                    </td>

                    <td style="text-align: center">
                        {{ optional($d->department)->nama_department ?? '-' }}
                    </td>
                    <td style="text-align: center">
                        {{ $d->placement2->nama_placement ?? '-' }}
                    </td>
                    <td style="text-align: center"> {{ $d->jabatan->nama_jabatan }}</td>
                    <td style="text-align: center"> {{ $d->status_karyawan }}</td>
                    <td style="text-align: center"> {{ format_tgl($d->tanggal_bergabung) }}</td>
                    <td style="text-align: center">
                        @if ($d->status_karyawan === 'Resigned')
                            {{ $d->tanggal_resigned ? format_tgl($d->tanggal_resigned) : '-' }}
                        @elseif ($d->status_karyawan === 'Blacklist')
                            {{ $d->tanggal_blacklist ? format_tgl($d->tanggal_blacklist) : '-' }}
                        @else
                            -
                        @endif
                    </td>
                    <td style="text-align: center">'{{ $d->nomor_rekening }}</td>

                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html>
