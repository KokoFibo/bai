<table style="border-collapse: collapse; width: 100%;">
    <!-- Judul -->
    <tr>
        <td></td>
        <td colspan="13" style="font-size:22px; text-align: center;">员工工资调薪单</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="13" style="font-size:22px; text-align: center;">Slip Penyesuaian Gaji
            Karyawan</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="13" style="text-align: right;">
            日期 / Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}
        </td>
    </tr>

    <!-- Header Mandarin -->
    <tr>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            工号</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            姓名</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            部门</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            职职位 </th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            职位等级 </th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            入职时间</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            上次调薪月份</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            调薪原因</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            调整前</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            调薪金额</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            调整后</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            原加班费</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            调整加班费</th>
        <th
            style="text-align: center; font-size:16px; border-top: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            奖金</th>
    </tr>

    <!-- Header Indonesia -->
    <tr>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            ID Employee</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Nama</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Departemen</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Posisi Jabatan</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Job Grade</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Waktu Gabung</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Bulan Penyesuaian Sebelumnya</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Alasan</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Gaji Sebelum</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Jumlah Penyesuaian</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Gaji Sesudah</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Lemburan Awal</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Perubahan Lemburan</th>
        <th
            style="text-align: center; font-size:16px; border-bottom: 1px solid #000; border-left: 1px solid #000; border-right: 1px solid #000;">
            Bonus</th>
    </tr>

    <!-- Data Karyawan -->
    @foreach ($karyawans as $karyawan)
        <tr>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">{{ $karyawan->id_karyawan }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">{{ $karyawan->nama }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">
                {{ nama_department($karyawan->department_id) }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">
                {{ nama_jabatan($karyawan->jabatan_id) }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">
                {{ getGrade($karyawan->level_jabatan) }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">
                {{ format_tgl($karyawan->tanggal_bergabung) }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">
                {{ format_tgl($karyawan->tanggal_update) }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;"></td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">{{ $karyawan->gaji_pokok }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;"></td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;"></td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">{{ $karyawan->gaji_overtime }}</td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;"></td>
            <td style="text-align: center; font-size:12px; border: 1px solid #000;">{{ $karyawan->bonus }}</td>
        </tr>
    @endforeach
</table>
