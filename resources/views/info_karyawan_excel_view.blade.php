<table>
    <thead>
        <tr>
            <th colspan="9" style="font-size: 16px; font-weight: bold; text-align: center;">
                {{ $title }}
            </th>
        </tr>

        <tr>
            <th>ID Karyawan</th>
            <th>Nama Karyawan</th>
            <th>Company</th>
            <th>Directorate</th>
            <th>Department</th>
            <th>Jabatan</th>
            <th>Status</th>
            <th>Metode Penggajian</th>
            <th>{{ $label }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $item->user_id }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ nama_company($item->company_id) }}</td>
                <td>{{ nama_placement($item->placement_id) }}</td>
                <td>{{ nama_department($item->department_id) }}</td>
                <td>{{ nama_jabatan($item->jabatan_id) }}</td>
                <td>{{ $item->status_karyawan }}</td>
                <td>{{ $item->metode_penggajian }}</td>
                <td>{{ $item->total }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
