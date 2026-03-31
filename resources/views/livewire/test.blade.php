<div class="p-5">
    <h2 class="text-xl font-bold mb-4">Data Payroll Berbeda ({{ count($beda) }})</h2>

    <table class="table-auto w-full border border-gray-300 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">#</th>
                <th class="border px-2 py-1">ID Karyawan</th>
                {{-- <th class="border px-2 py-1">Hari Kerja (Payroll)</th>
                <th class="border px-2 py-1">Hari Kerja (Presensi)</th> --}}
                <th class="border px-2 py-1">Jam Kerja (Payroll)</th>
                <th class="border px-2 py-1">Jam Kerja (Presensi)</th>
                <th class="border px-2 py-1">Jam Lembur (Payroll)</th>
                <th class="border px-2 py-1">Jam Lembur (Presensi)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($beda as $i => $item)
                <tr>
                    <td class="border px-2 py-1 text-center">{{ $i + 1 }}</td>
                    <td class="border px-2 py-1 text-center">{{ $item['id_karyawan'] }}</td>
                    {{-- <td class="border px-2 py-1 text-right">{{ $item['hari_kerja_payroll'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ $item['hari_kerja_presensi'] }}</td> --}}
                    <td class="border px-2 py-1 text-right">{{ $item['jam_kerja_payroll'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ $item['jam_kerja_presensi'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ $item['jam_lembur_payroll'] }}</td>
                    <td class="border px-2 py-1 text-right">{{ $item['jam_lembur_presensi'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="border px-2 py-2 text-center text-gray-500">
                        Semua data sama ✔️
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 text-sm text-gray-700">
        <strong>Jumlah data cocok:</strong> {{ $jumlah_sama }}
    </div>
</div>
