<div>

    {{-- <p>selectedMonth: {{ $selectedMonth }}. selectedYear: {{ $selectedYear }}, CX = {{ $cx }}</p> --}}
    {{-- <p>is_perbulan : {{ is_perbulan() }}</p> --}}
    <div>
        <div class="flex flex-col h-screen">
            <div class=header>
                <div class="w-screen bg-gray-800 h-24 shadow-xl rounded-b-3xl   ">

                    <div class="flex justify-between">
                        {{-- @if ($is_slipGaji != true) --}}
                        <div class="{{ $is_slipGaji == true ? 'hidden' : '' }} ml-3 self-center">
                            <img src="{{ asset('images/logo-only.png') }}" alt="Yifang Logo"
                                style="opacity: .8; width:50px">
                        </div>

                        {{-- @endif --}}
                        {{-- sementara di disabled --}}
                        @if (auth()->user()->language == 'Cn')
                            <div class="flex items-end mb-2">
                                @if (app()->getLocale() == 'id')
                                    <a class="nav-link" href="{{ url('locale/cn') }}"><button
                                            class="text-white text-sm bg-red-500 py-1 rounded-xl px-3">{{ __('中文') }}</button></a>
                                @endif
                                @if (app()->getLocale() == 'cn')
                                    <a class="nav-link" href="{{ url('locale/id') }}"><button
                                            class="text-white text-sm bg-red-500 py-1 rounded-xl px-3">{{ __('英语') }}</button></a>
                                @endif
                            </div>
                        @endif



                        <div class="flex flex-col p-3 gap-5 items-end">
                            <div>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                                    <button class="rounded-xl shadow bg-purple-500  text-white px-3 py-1"><i
                                            class="fa-solid fa-power-off"></i></button>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                            {{-- @if (auth()->user()->role < 4)
                                <div>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                                        <button
                                            class="rounded-xl shadow bg-purple-500 text-sm text-white px-3 py-1">{{ __('Logout') }}</button>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            @else
                                <div>
                                    <a href="/"><button
                                            class="rounded-xl shadow bg-green-500 text-sm text-white px-3 py-1">{{ __('Dasboard') }}</button>
                                    </a>
                                </div>
                            @endif --}}
                            @if ($is_slipGaji != true)
                                <div>
                                    <h1 class="text-white text-sm">Hello, {{ auth()->user()->name }}</h1>
                                </div>
                            @endif




                        </div>
                    </div>
                </div>

                {{-- selection --}}
                <div class="flex px-3  justify-center ">
                    <div class="w-screen bg-teal-500 h-12 shadow-xl rounded-3xl mt-2 ">
                        <div class="h-12 flex justify-evenly items-center">
                            <div>
                                <select wire:model.live="selectedYear" class="bg-teal-500 text-white text-sm">
                                    <option value=" ">Pilih Tahun</option>
                                    @foreach ($select_year as $sy)
                                        <option value="{{ $sy }}">{{ $sy }}</option>
                                    @endforeach
                                    {{-- <option value="2023">2023</option>
                                    <option value="2024">2024</option> --}}
                                </select>
                            </div>
                            <div>
                                <select wire:model.live="selectedMonth" class="bg-teal-500 text-white text-sm">
                                    <option value=" ">Pilih Bulan</option>

                                    @foreach ($select_month as $sm)
                                        <option value="{{ $sm }}">{{ monthName($sm) }}</option>
                                    @endforeach
                                    {{-- <option value="12">Desember</option>
                                    <option value="1">Januari</option>
                                    <option value="2">Februari</option> --}}
                                    {{-- j --}}
                                </select>
                            </div>
                            <div class="{{ auth()->user()->role <= 6 && $is_slipGaji == false ? 'invisible' : '' }}">
                                {{-- <div> --}}
                                @if ($is_detail == false)
                                    <button wire:click="slip_gaji"
                                        class="bg-gray-800 text-white  px-3 py-1 rounded-xl text-sm">{{ __('Slip Gaji') }}</button>
                                @else
                                    <button wire:click="detail_gaji"
                                        class="bg-white text-black font-semibold px-3 py-1 rounded-xl text-sm">{{ __('Detail Gaji') }}</button>
                                @endif

                            </div>


                        </div>
                    </div>
                </div>
                <div>
                    @if (!$isEmergencyContact && !$isEtnis)
                        <p class="bg-red-600 text-white text-center p-2 mt-3 ">
                            {{ __('Silakan update data kontak darurat & data Etnis anda di menu profile') }} <i
                                class="fa-solid fa-user"></i></p>
                    @elseif(!$isEmergencyContact)
                        <p class="bg-red-600 text-white text-center p-2 mt-3 ">
                            {{ __('Silakan update data kontak darurat anda di menu profile') }} <i
                                class="fa-solid fa-user"></i></p>
                    @elseif(!$isEtnis)
                        <p class="bg-red-600 text-white text-center p-2 mt-3 ">
                            {{ __('Silakan update data etnis anda di menu profile') }} <i class="fa-solid fa-user"></i>
                        </p>
                    @endif
                </div>
                {{-- end selection --}}
                @if ($show)
                    {{-- Summary --}}
                    <div>
                        <div class="flex px-3 pt-2 justify-center  ">
                            <div
                                class="w-screen h-30 bg-red-200 text-gray-600  px-3  flex flex-col rounded-lg shadow text-center justify-center">
                                <h1 class="pt-1 font-semibold">{{ __('Presensi Bulan') }}
                                    {{ monthName($selectedMonth) }}
                                    {{ $selectedYear }}</h1>
                                <div class="flex justify-around text-center pb-1">

                                    <div>
                                        <p class="text-sm">{{ __('Hari') }}</p>
                                        <p class="font-bold text-green-500 text-lg">{{ $total_hari_kerja }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm">{{ __('J. Kerja') }}</p>
                                        <p class="font-bold text-green-500 text-lg">{{ $total_jam_kerja }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm">{{ __('J. Lembur') }}</p>
                                        <p class="font-bold text-green-500 text-lg">{{ $total_jam_lembur }}</p>
                                    </div>

                                    <div>
                                        <p class="text-sm">{{ __('Terlambat') }}</p>
                                        <p class="font-bold text-green-500 text-lg">{{ $total_keterlambatan }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm">{{ __('S. Malam') }}</p>
                                        <p class="font-bold text-green-500 text-lg">
                                            {{ $total_tambahan_shift_malam }}
                                        </p>
                                    </div>


                                </div>


                            </div>
                        </div>
                    </div>
                    {{-- End Summary --}}
                @endif


            </div>
            @if ($show)

                {{-- Main Table --}}
                <div class="main  flex-1 overflow-y-auto ">
                    {{-- Slip Gaji --}}

                    @if ($is_slipGaji == true && $is_detail == true)

                        @if ($data_payroll != null)
                            <div>
                                {{-- Jika BOLEH tampil slip gaji bulan lalu --}}
                                <h2 class="text-gray-900 text-lg text-center my-2">{{ __('Slip Gaji') }}
                                    {{ monthName($selectedMonth) }}
                                    {{ $selectedYear }}</h2>

                                {{-- Jika TIDAK BOLEH tampil slip gaji bulan lalu --}}
                                {{-- <h2 class="text-gray-900 text-lg text-center my-2">{{ __('Slip Gaji') }}
                                {{ monthName($latest_month) }}
                                {{ $latest_year }}</h2> --}}

                                <table class="mx-auto text-sm ">
                                    <tbody>

                                        <tr>
                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">Id</td>

                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                {{ $data_payroll->id_karyawan }}</td>
                                        </tr>
                                        {{-- <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ __('Nama') }}</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ $data_payroll->nama }}</td>
                                    </tr> --}}
                                        {{-- @if ($data_karyawan->no_npwp != null)
                                        <tr>
                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                {{ __('No. NPWP') }}
                                            </td>
                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                {{ $data_karyawan->no_npwp }}</td>
                                        </tr>
                                    @endif --}}
                                        {{-- <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ __('Nama Bank') }}</td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ $data_karyawan->nama_bank }}</td>
                                    </tr> --}}
                                        {{-- <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ __('No. Rekening') }}
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ $data_karyawan->nomor_rekening }}</td>
                                    </tr> --}}

                                        <tr>
                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                {{ __('T. Hari Kerja') }}
                                            </td>
                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                {{ $data_payroll->hari_kerja }} {{ __('hari') }}</td>
                                        </tr>
                                        @if ($data_payroll->gaji_lembur != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('T. Jam Kerja') }}</td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $data_payroll->jam_kerja }} {{ __('jam') }}</td>
                                            </tr>

                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('T. Jam Lembur') }}
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $data_payroll->jam_lembur }} {{ __('jam') }}</td>
                                            </tr>
                                        @endif

                                        {{-- <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ __('Gaji Pokok') }}
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            Rp. {{ number_format($data_payroll->gaji_pokok) }}</td>
                                    </tr> --}}
                                        {{-- <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ __('Gaji Lembur') }}
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            Rp. {{ number_format($data_payroll->gaji_lembur) }}</td>
                                    </tr> --}}
                                        {{-- <tr>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            {{ __('Subtotal') }}
                                        </td>
                                        <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                            Rp. {{ number_format($data_payroll->subtotal) }}</td>
                                    </tr> --}}
                                        @if ($data_payroll->tambahan_shift_malam != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('Bonus Shift Malam') }}
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->tambahan_shift_malam) }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($data_karyawan->iuran_air != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('Iuran air minum') }}

                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_karyawan->iuran_air) }}</td>
                                            </tr>
                                        @endif
                                        @if ($data_karyawan->iuran_locker != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('Iuran Locker') }}
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_karyawan->iuran_locker) }}</td>
                                            </tr>
                                        @endif

                                        @if ($data_payroll->bonus1x != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('Bonus') }}
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->bonus1x) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($data_payroll->potongan1x - ($data_karyawan->iuran_air + $data_karyawan->iuran_locker) != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('Potongan') }}
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->potongan1x) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($data_payroll->denda_lupa_absen != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ __('Denda Lupa Absen') }}
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->denda_lupa_absen) }}
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- @if ($data_payroll->gaji_pokok >= 4500000) --}}
                                        @if ($data_payroll->gaji_libur != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">Gaji Libur
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->gaji_libur) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($data_payroll->jht != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">BPJS JHT
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->jht) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($data_payroll->jp != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">BPJS JP
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->jp) }}
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- @if ($data_payroll->jkk != 0)
                                    <tr>
                                        <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">BPJS JKK</td>
                                        <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">Rp. {{ number_format($data_payroll->jkk) }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if ($data_payroll->jkm != 0)
                                        <tr>
                                            <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">BPJS JKM</td>
                                            <td  class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">Rp. {{ number_format($data_payroll->jkm) }}
                                            </td>
                                        </tr>
                                    @endif --}}
                                        @if ($data_payroll->kesehatan != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">BPJS
                                                    Kesehatan
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->kesehatan) }}
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($data_payroll->tanggungan != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">BPJS
                                                    Tanggungan
                                                </td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->tanggungan) }}
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- @endif --}}
                                        @if ($data_karyawan->ptkp != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">PTKP</td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    {{ $data_karyawan->ptkp }}</td>
                                            </tr>
                                        @endif
                                        @if ($data_payroll->thr != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">THR</td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->thr) }}</td>
                                            </tr>
                                        @endif
                                        @if ($data_payroll->pph21 != 0)
                                            <tr>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">PPh21</td>
                                                <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                    Rp. {{ number_format($data_payroll->pph21) }}</td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                {{ __('Total Terima') }}
                                            </td>
                                            <td class="px-6 py-1 whitespace-nowrap text-sm text-gray-600">
                                                Rp. {{ number_format($data_payroll->total) }}
                                            </td>
                                        </tr>

                                    </tbody>

                                </table>
                                <div class="mt-20"></div>


                                {{-- <button wire:click="close" class="bg-black text-white px-2 py-1 mt-2">Close</button> --}}
                            </div>

                        @endif

                    @endif

                    {{-- @else --}}
                    {{-- End slip gajiGaji --}}
                    {{-- presensi harian --}}
                    @if ($is_detail == false)

                        <div class="w-screen flex px-3  mt-3  flex-col ">
                            <table>
                                <tbody>
                                    @if ($lanjut)
                                        @foreach ($data as $index => $d)
                                            <tr
                                                class="flex justify-evenly border-pink-500 border-l-8  bg-blue-100 w-full h-18 items-center p-2 rounded-lg shadow mb-2">

                                                <td class="text-center">
                                                    <p
                                                        class="rounded-full bg-white w-10 h-10 flex justify-center items-center font-bold text-xl text-green-500">
                                                        {{ tgl_doang($d->date) }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <p class="text-gray-500 text-sm">{{ __('J. Kerja') }}</p>
                                                    <p class="font-bold text-blue-500">
                                                        @php
                                                            $tgl = tgl_doang($d->date);
                                                            $jam_kerja = hitung_jam_kerja(
                                                                $d->first_in,
                                                                $d->first_out,
                                                                $d->second_in,
                                                                $d->second_out,
                                                                $d->late,
                                                                $d->shift,
                                                                $d->date,
                                                                $d->karyawan->jabatan_id,
                                                                get_placement($d->user_id),
                                                            );
                                                            $terlambat = late_check_jam_kerja_only(
                                                                $d->first_in,
                                                                $d->first_out,
                                                                $d->second_in,
                                                                $d->second_out,
                                                                $d->shift,
                                                                $d->date,
                                                                $d->karyawan->jabatan_id,
                                                                get_placement($d->user_id),
                                                            );

                                                            if ($d->karyawan->jabatan_id === 17) {
                                                                $jam_kerja = $terlambat >= 6 ? 0.5 : $jam_kerja;
                                                            }

                                                            $langsungLembur = langsungLembur(
                                                                $d->second_out,
                                                                $d->date,
                                                                $d->shift,
                                                                $d->karyawan->jabatan_id,
                                                                get_placement($d->user_id),
                                                            );

                                                            if (is_sunday($d->date)) {
                                                                $jam_lembur =
                                                                    (hitungLembur($d->overtime_in, $d->overtime_out) /
                                                                        60) *
                                                                        2 +
                                                                    $langsungLembur * 2;
                                                            } else {
                                                                $jam_lembur =
                                                                    hitungLembur($d->overtime_in, $d->overtime_out) /
                                                                        60 +
                                                                    $langsungLembur;
                                                            }

                                                            $tambahan_shift_malam = 0;

                                                            if ($d->shift == 'Malam') {
                                                                if (is_saturday($d->date)) {
                                                                    if ($jam_kerja >= 6) {
                                                                        // $jam_lembur = $jam_lembur + 1;
                                                                        $tambahan_shift_malam = 1;
                                                                    }
                                                                } elseif (is_sunday($d->date)) {
                                                                    if ($jam_kerja >= 16) {
                                                                        // $jam_lembur = $jam_lembur + 2;
                                                                        $tambahan_shift_malam = 1;
                                                                    }
                                                                } else {
                                                                    if ($jam_kerja >= 8) {
                                                                        // $jam_lembur = $jam_lembur + 1;
                                                                        $tambahan_shift_malam = 1;
                                                                    }
                                                                }
                                                            }
                                                            if (
                                                                $jam_lembur >= 9 &&
                                                                is_sunday($d->date) == false &&
                                                                $d->karyawan->jabatan_id != 22
                                                            ) {
                                                                $jam_lembur = 0;
                                                            }
                                                            if (
                                                                $d->karyawan->placement == 'YIG' ||
                                                                $d->karyawan->placement == 'YSM' ||
                                                                $d->karyawan->jabatan_id == 17
                                                            ) {
                                                                if (is_friday($d->date)) {
                                                                    $jam_kerja = 7.5;
                                                                } elseif (is_saturday($d->date)) {
                                                                    $jam_kerja = 6;
                                                                } else {
                                                                    $jam_kerja = 8;
                                                                }
                                                            }

                                                            if ($d->karyawan->jabatan_id == 17 && is_sunday($d->date)) {
                                                                $jam_kerja = hitung_jam_kerja(
                                                                    $d->first_in,
                                                                    $d->first_out,
                                                                    $d->second_in,
                                                                    $d->second_out,
                                                                    $d->late,
                                                                    $d->shift,
                                                                    $d->date,
                                                                    $d->karyawan->jabatan_id,
                                                                    get_placement($d->user_id),
                                                                );
                                                            }

                                                            if (
                                                                $d->karyawan->jabatan_id == 17 &&
                                                                is_saturday($d->date)
                                                            ) {
                                                                $jam_lembur = 0;
                                                            }

                                                            // if (
                                                            //     is_sunday($d->date) &&
                                                            //     $d->karyawan->metode_penggajian == 'Perbulan'
                                                            // ) {
                                                            //     $jam_lembur = $jam_kerja;
                                                            //     $jam_kerja = 0;
                                                            // }

                                                            // Jika hari libur nasional
                                                            // 23 translator
                                                            if ($d->karyawan->jabatan_id != 23) {
                                                                if (
                                                                    is_libur_nasional($d->date) &&
                                                                    !is_sunday($d->date) &&
                                                                    $d->karyawan->jabatan_id != 23
                                                                ) {
                                                                    $jam_kerja *= 2;
                                                                    $jam_lembur *= 2;
                                                                }
                                                            } else {
                                                                if (is_sunday($d->date)) {
                                                                    $jam_kerja /= 2;
                                                                    $jam_lembur /= 2;
                                                                }
                                                            }

                                                            // khusus placement YAM yev yev... tgl 2024-04-07 dan 2024-04-09
                                                            $rule1 =
                                                                ($d->date == '2024-04-07' ||
                                                                    $d->date == '2024-04-09') &&
                                                                (substr($d->karyawan->placement, 0, 3) == 'YEV' ||
                                                                    $d->karyawan->placement == 'YAM');

                                                            if ($rule1) {
                                                                $jam_kerja /= 2;
                                                                $jam_lembur /= 2;
                                                            }
                                                        @endphp
                                                        {{ $jam_kerja }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <p class="text-gray-500 text-sm">{{ __('J. Lembur') }}</p>
                                                    <p class="font-bold text-blue-500">
                                                        {{ $jam_lembur }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <p class="text-gray-500 text-sm">{{ __('Terlambat') }}</p>
                                                    <p class="font-bold text-blue-500">
                                                        {{ late_check_jam_kerja_only($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date, $d->karyawan->jabatan_id, get_placement($d->user_id)) }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <p class="text-gray-500 text-sm">{{ __('S. Malam') }}</p>
                                                    <p class="font-bold text-blue-500">{{ $tambahan_shift_malam }}</p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- @endif --}}
                    {{-- End Presensi Harian --}}
                </div>.
            @endif

            {{-- End Main Table --}}


            <div class="mt-10"></div>
            {{-- Footer --}}
            @include('mobile-footer')
            {{--  end footer --}}
            {{-- <livewire:upload-popup /> --}}
        </div>
    </div>
</div>
