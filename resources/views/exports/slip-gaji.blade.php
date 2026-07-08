<table width="100%" cellspacing="0" cellpadding="4">

    {{-- HEADER --}}
    <tr>
        <td colspan="4" style="text-align:center; font-size:16px; font-weight:bold">
            PT BINTANG ANGGUN INDONESIA
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align:center; font-size:14px; font-weight:bold">
            SLIP GAJI
        </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align:center">
            Periode {{ $periode }}
        </td>
    </tr>

    {{-- SPACER --}}
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    {{-- INFORMASI KARYAWAN --}}
    <tr>
        <td colspan="4" style="background:#D9EAD3; font-weight:bold; border:1px solid #000; padding:4px">
            INFORMASI KARYAWAN
        </td>
    </tr>
    @foreach ($informasi as $label => $isi)
        <tr>
            <td width="35%" colspan="2" style="border:1px solid #000; padding:4px">
                {{ $label }}
            </td>
            <td colspan="2" style="border:1px solid #000; padding:4px">
                {{ $isi }}
            </td>
        </tr>
    @endforeach

    {{-- SPACER --}}
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    {{-- PENERIMAAN --}}
    <tr>
        <td colspan="4" style="background:#D9EAD3; font-weight:bold; border:1px solid #000; padding:4px">
            PENERIMAAN
        </td>
    </tr>
    @php $totalPenerimaan = 0; @endphp
    @foreach ($penerimaan as $nama => $nominal)
        @if ($nama != 'BPJS tak dihitung')
            <tr>
                <td colspan="2" style="border:1px solid #000; padding:4px">
                    {{ $nama }}
                </td>
                <td align="right" colspan="2" style="border:1px solid #000; padding:4px">
                    {{ $nominal }}
                </td>
            </tr>
            @php $totalPenerimaan += $nominal; @endphp
        @else
            @php $totalPenerimaan = $totalPenerimaan - $nominal; @endphp
        @endif
    @endforeach
    <tr style="font-weight:bold">
        <td colspan="2" style="border:1px solid #000; padding:4px">
            TOTAL BRUTO
        </td>
        <td align="right" colspan="2" style="border:1px solid #000; padding:4px">
            {{ $totalPenerimaan - 1300000 }}
        </td>
    </tr>

    {{-- SPACER --}}
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    {{-- PENGURANGAN --}}
    <tr>
        <td colspan="4" style="background:#F4CCCC; font-weight:bold; border:1px solid #000; padding:4px">
            PENGURANGAN
        </td>
    </tr>
    @php $totalPotongan = 0; @endphp
    @foreach ($potongan as $nama => $nominal)
        <tr>
            <td colspan="2" style="border:1px solid #000; padding:4px">
                {{ $nama }}
            </td>
            <td align="right" colspan="2" style="border:1px solid #000; padding:4px">
                {{ $nominal }}
            </td>
        </tr>
        @php $totalPotongan += $nominal; @endphp
    @endforeach
    <tr style="font-weight:bold">
        <td colspan="2" style="border:1px solid #000; padding:4px">
            TOTAL PENGURANGAN
        </td>
        <td align="right" colspan="2" style="border:1px solid #000; padding:4px">
            {{ $totalPotongan }}
        </td>
    </tr>

    {{-- SPACER --}}
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    {{-- TOTAL --}}
    <tr style="background:#1F4E78; color:white; font-weight:bold; font-size:14px">
        <td colspan="2" style="border:1px solid #000; padding:6px">
            TOTAL YANG SEHARUSNYA DITERIMA KARYAWAN
        </td>
        <td align="right" colspan="2" style="border:1px solid #000; padding:6px">
            {{ $payroll->total }}
        </td>
    </tr>
    <tr style="background:#1F4E78; color:white; font-weight:bold; font-size:14px">
        <td colspan="2" style="border:1px solid #000; padding:6px">
            KURANG BAYAR
        </td>
        <td align="right" colspan="2" style="border:1px solid #000; padding:6px">
            {{-- isi jika ada nilai kurang bayar --}}
        </td>
    </tr>

    {{-- SPACER --}}
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    {{-- TANDA TANGAN --}}
    <tr>
        <td style="text-align:center" colspan="4">
            <i>Tangerang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</i>
        </td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td style="text-align:center" colspan="2">
            <i>Penerima</i>
        </td>
        <td style="text-align:center" colspan="2">
            <i>PT Bintang Anggun Indonesia</i>
        </td>
    </tr>

    {{-- SPACER untuk ruang tanda tangan --}}
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>

    <tr>
        <td style="text-align:center" colspan="2">
            <i>{{ $informasi['Nama'] }}</i>
        </td>
        <td style="text-align:center" colspan="2">
            <i>{{ auth()->user()->name }}</i>
        </td>
    </tr>

</table>
