<div class="p-4 text-sm">
    <h1 class="text-lg font-bold mb-4">Laporan Gaji Bulan {{ $month }} Tahun {{ $year }}</h1>

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead class="bg-gray-200">
            <tr>
                <th class="border px-2 py-1 text-left">Placement</th>
                <th class="border px-2 py-1 text-left">Company</th>
                <th class="border px-2 py-1 text-right">No. Staff</th>
                <th class="border px-2 py-1 text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $placementId => $companies)
                @foreach ($companies as $index => $row)
                    <tr>
                        <td class="border px-2 py-1">
                            @if ($index === 0)
                                {{ nama_placement($placementId) }}
                            @endif
                        </td>
                        <td class="border px-2 py-1">{{ nama_company($row->company_id) }}</td>
                        <td class="border px-2 py-1 text-right">{{ $row->jumlah }}</td>
                        <td class="border px-2 py-1 text-right">Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot class="bg-gray-100 font-semibold">
            <tr>
                <td class="border px-2 py-1 text-center" colspan="2">Total</td>
                <td class="border px-2 py-1 text-right">{{ $totalStaff }}</td>
                <td class="border px-2 py-1 text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="p-4 text-sm space-y-4">
        <h1 class="text-lg font-bold">Laporan Gaji Per Bulan Tahun {{ $year }}</h1>

        <table class="table-auto w-full border border-black text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border border-black px-3 py-2 text-left">Bulan</th>
                    <th class="border border-black px-3 py-2 text-right">Total Pegawai</th>
                    <th class="border border-black px-3 py-2 text-right">Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotalStaff = 0;
                    $grandTotalGaji = 0;
                    $namaBulan = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember',
                    ];
                @endphp

                @foreach ($bulanans as $row)
                    <tr>
                        <td class="border border-black px-3 py-2">{{ $namaBulan[$row->bulan] }}</td>
                        <td class="border border-black px-3 py-2 text-right">{{ $row->total_pegawai }}</td>
                        <td class="border border-black px-3 py-2 text-right">Rp
                            {{ number_format($row->total_gaji, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $grandTotalStaff += $row->total_pegawai;
                        $grandTotalGaji += $row->total_gaji;
                    @endphp
                @endforeach
            </tbody>
            <tfoot class="bg-gray-100 font-semibold">
                <tr>
                    <td class="border border-black px-3 py-2 text-center">Total</td>
                    <td class="border border-black px-3 py-2 text-right">{{ $grandTotalStaff }}</td>
                    <td class="border border-black px-3 py-2 text-right">Rp
                        {{ number_format($grandTotalGaji, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
    {{-- bagian kedua --}}
    <div class="p-4 space-y-6">
        <h2 class="text-xl font-bold">Laporan Gaji Perjam per Bulan ({{ now()->year }})</h2>

        @foreach ($laporan_bulanan->groupBy('bulan') as $bulan => $laporan)
            <div class="bg-white shadow rounded-xl p-4">
                <h3 class="text-lg font-semibold mb-4">Bulan:
                    {{ \Carbon\Carbon::create()->month($bulan)->locale('id')->translatedFormat('F') }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">Placement</th>
                                <th class="px-4 py-2 border">Jumlah Karyawan</th>
                                <th class="px-4 py-2 border">Total Salary</th>
                                <th class="px-4 py-2 border">Total Tambahan Shift Malam</th>
                                <th class="px-4 py-2 border">Total Gaji Pokok</th>
                                <th class="px-4 py-2 border">Total Jam Kerja</th>
                                <th class="px-4 py-2 border">Total Lemburan</th>
                                <th class="px-4 py-2 border">Total Jam Lembur</th>
                                <th class="px-4 py-2 border">Total Bonus</th>
                                <th class="px-4 py-2 border">Total Potongan</th>
                                <th class="px-4 py-2 border">Rata-rata Gaji/Orang</th>
                                <th class="px-4 py-2 border">Gaji/Perjam</th>
                                <th class="px-4 py-2 border">Lembur/Perjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporan as $row)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">{{ nama_placement($row->placement_id) }}</td>

                                    <td class="px-4 py-2 border">{{ $row->jumlah_karyawan }}</td>
                                    <td class="px-4 py-2 border">{{ number_format($row->total_gaji) }}
                                    <td class="px-4 py-2 border">{{ number_format($row->tambahan_shift_malam) }}
                                    <td class="px-4 py-2 border">{{ number_format($row->total_gaji_pokok) }}
                                    <td class="px-4 py-2 border">{{ number_format($row->total_gaji) }}
                                    <td class="px-4 py-2 border">{{ number_format($row->total_lemburan) }}
                                    <td class="px-4 py-2 border">{{ number_format($row->jam_lembur) }}
                                    <td class="px-4 py-2 border">{{ number_format($row->bonus1x) }}
                                    <td class="px-4 py-2 border">{{ number_format($row->potongan1x) }}
                                    <td class="px-4 py-2 border">
                                        {{ number_format($row->rata_rata_gaji) }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ number_format($row->rata_rata_gaji_perjam) }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        {{ number_format($row->rata_rata_lembur_perjam) }}
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
