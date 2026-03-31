<div>
    {{-- <h1 colspan="3" style="font-size:20px;  text-align:center">>Laporan Gaji Bulan {{ $month }} Tahun
        {{ $year }}</h1> --}}

    <table>
        <thead>
            <tr>

                <th colspan="4" style="font-size:20px;  text-align:center">
                    <h3 colspan="4" style="font-size:20px;  text-align:center">Laporan Gaji OS {{ nama_bulan($month) }}
                        {{ $year }}</h3>
                </th>
            </tr>

            <tr>
                <th style="border: 1px solid black;text-align: center;">Directorate</th>
                <th style="border: 1px solid black;text-align: center;">Company</th>
                <th style="border: 1px solid black;text-align: center;">No. Staff</th>
                <th style="border: 1px solid black;text-align: center;">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $placementId => $companies)
                @foreach ($companies as $index => $row)
                    <tr>
                        @if ($index === 0)
                            <td style="border: 1px solid black;text-align: center; vertical-align: middle;"
                                rowspan="{{ count($companies) }}">
                                {{ nama_placement($placementId) }}
                            </td>
                        @endif
                        <td style="border: 1px solid black;text-align: center">{{ nama_company($row->company_id) }}
                        </td>
                        <td style="border: 1px solid black;text-align: center">{{ $row->jumlah }}</td>
                        <td style="border: 1px solid black;text-align: center">{{ $row->total }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot class="bg-gray-100 font-semibold">
            <tr>
                <td style="border: 4px solid black;text-align: center; font-weight: bold;" colspan="2">Total</td>
                <td style="border: 4px solid black;text-align: center; font-weight: bold;">{{ $totalStaff }}</td>
                <td style="border: 4px solid black;text-align: center; font-weight: bold;">{{ $totalAmount }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- bagian kedua --}}
    <div>
        {{-- <h2 class="text-xl font-bold">Laporan Gaji Perjam per Bulan ({{ now()->year }})</h2> --}}
        <table>

            <thead>
                <tr>

                    <th colspan="13" style="font-size:20px;  text-align:center">
                        <h3 colspan="4" style="font-size:20px;  text-align:center">Laporan Gaji OS
                            {{ nama_bulan($month) }}
                            {{ $year }}</h3>
                    </th>
                </tr>
            </thead>
        </table>

        @foreach ($laporan_bulanan->groupBy('bulan') as $bulan => $laporan)
            <div class="bg-white shadow rounded-xl p-4">
                <h3 class="text-lg font-semibold mb-4">Bulan:
                    {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->translatedFormat('F') }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Directorate
                                </th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Jumlah
                                    Karyawan</th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Salary
                                </th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Tambahan
                                    Shift Malam</th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Gaji
                                    Pokok</th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Jam
                                    Kerja</th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Lemburan
                                </th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Jam
                                    Lembur</th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Bonus
                                </th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Total Potongan
                                </th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Rata-rata
                                    Gaji/Orang</th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Gaji/Perjam
                                </th>
                                <th style="border: 1px solid black;text-align: center;font-weight: bold;">Lembur/Perjam
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $row)
                                <tr class="hover:bg-gray-50">
                                    <td style="border: 1px solid black;text-align: center;font-weight: bold;">
                                        {{ nama_placement($row->placement_id) }}</td>

                                    <td style="border: 1px solid black;text-align: center;">{{ $row->jumlah_karyawan }}
                                    </td>
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->total_gaji }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->tambahan_shift_malam }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->total_gaji_pokok }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->jam_kerja }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->total_lemburan }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->jam_lembur }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->bonus1x }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->potongan1x }}
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->rata_rata_gaji }}
                                    </td>
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->rata_rata_gaji_perjam }}
                                    </td>
                                    <td style="border: 1px solid black;text-align: center;">
                                        {{ $row->rata_rata_lembur_perjam }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
