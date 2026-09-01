<table>
    {{-- ========================================================= --}}
    {{-- TITLE --}}
    {{-- ========================================================= --}}

    <tr>
        <td colspan="12">
            <strong>
                PENYESUAIAN GAJI KARYAWAN
            </strong>
        </td>
    </tr>

    <tr>
        <td colspan="12">
            {{ $header_text }}
        </td>
    </tr>

    <tr>
        <td colspan="12">
            Gaji Rekomendasi:
            Rp {{ number_format($gaji_rekomendasi, 0, ',', '.') }}
        </td>
    </tr>


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <thead>
        <tr>

            <th>ID Karyawan</th>

            <th>Nama</th>

            <th>Placement</th>

            <th>Company</th>

            <th>Department</th>

            <th>Jabatan</th>

            <th>Status</th>

            <th>Metode Penggajian</th>

            <th>Tanggal Bergabung</th>

            <th>Lama Bekerja</th>

            <th>Gaji Pokok</th>

            <th>Gaji Rekomendasi</th>

        </tr>
    </thead>


    {{-- ========================================================= --}}
    {{-- DATA --}}
    {{-- ========================================================= --}}

    <tbody>

        @forelse ($data as $d)
            <tr>

                {{-- ID --}}
                <td>
                    {{ $d->id_karyawan }}
                </td>


                {{-- NAMA --}}
                <td>
                    {{ $d->nama }}
                </td>


                {{-- PLACEMENT --}}
                <td>
                    {{ optional($d->placement)->placement_name }}
                </td>


                {{-- COMPANY --}}
                <td>
                    {{ optional($d->company)->company_name }}
                </td>


                {{-- DEPARTMENT --}}
                <td>
                    {{ optional($d->department)->nama_department }}
                </td>


                {{-- JABATAN --}}
                <td>
                    {{ optional($d->jabatan)->nama_jabatan }}
                </td>


                {{-- STATUS --}}
                <td>
                    {{ $d->status_karyawan }}
                </td>


                {{-- METODE --}}
                <td>
                    {{ $d->metode_penggajian }}
                </td>


                {{-- TANGGAL BERGABUNG --}}
                <td>
                    {{ $d->tanggal_bergabung ? \Carbon\Carbon::parse($d->tanggal_bergabung)->format('d-m-Y') : '' }}
                </td>


                {{-- LAMA BEKERJA --}}
                <td>
                    {{ lama_bekerja($d->tanggal_bergabung, $today) }}
                </td>


                {{-- GAJI POKOK --}}
                <td>
                    {{ $d->gaji_pokok }}
                </td>


                {{-- GAJI REKOMENDASI --}}
                <td>
                    {{ $gaji_rekomendasi }}
                </td>

            </tr>

        @empty

            <tr>

                <td colspan="12">
                    TIDAK ADA DATA KARYAWAN
                </td>

            </tr>
        @endforelse

    </tbody>

</table>
