<div class="max-w-4xl mx-auto p-4">
    <h4>Year = 2025</h4>
    <select wire:model.live="month" class="border rounded p-1">
        <option value="">Pilih Bulan</option>
        <option value="10">Oktober</option>
        <option value="11">November</option>
        <option value="12">Desember</option>
    </select>


    <h1>OS yang jam kerja dibawah 8 jam dan gaji hariannya tidak tercatat</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Total Jam Kerja</th>
                <th>Total hari kerja</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $d)
                <tr>
                    <td>{{ $d->user_id }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->total_jam_kerja }}</td>
                    <td>{{ $d->total_hari_kerja }}</td>
                    <td>{{ $d->date }}</td>
                    {{-- <td>{{ $d->total_jam_kerja_libur }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
    <h1>total = {{ $total }}</h1>
</div>
