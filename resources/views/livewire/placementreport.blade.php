<div class="container">
    {{-- Header --}}
    <div class="card mt-5 my-3">
        <div class="card-header" style="background-color: #466deb; color: white">
            @if ($placement_id != '')
                <div class='text-right'>
                    <button wire:click='close' class='btn btn-sm btn-info'>{{ __('Close Detail') }}</button>
                </div>
            @endif
            <p class="text-center lg:text-2xl">{{ __('Placement Report') }} </p>
        </div>
        <div class="mt-3">
            <div class="px-4 pb-3 d-flex flex-lg-row flex-column align-items-center lg:gap-0 gap-3">
                <input type="date" wire:model.live='last_date'
                    class="form-control text-center col-8 col-lg-3 mr-0 lg:mr-5 " id="exampleFormControlInput1">
                <div>
                    <select wire:model.live="placement_id" class="form-select" aria-label="Default select example">
                        <option value="">{{ __('Pilih Placement') }}</option>
                        @foreach ($placements as $j)
                            <option value="{{ $j }}">{{ nama_placement($j) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button wire:loading wire:target='placement_id' class="btn btn-primary " type="button">
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    </button>
                    <button wire:loading wire:target='last_date' class="btn btn-primary " type="button">
                        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if ($placement_id != '')
        <div class="card my-3">
            <div class="card-header" style="background-color: #608ed3; color: white">
                <h2 class="py-1 px-3 text-center text-lg lg:text-2xl">{{ __('Penempatan Bagian Karyawan Pabrik') }}
                </h2>
            </div>
            <div class="card-body">
                <div class='d-none d-lg-block'>
                    <div class=' d-flex flex-lg-row justify-content-lg-evenly '>
                        <div class='text-center col-3'>
                            <p class="text-md lg:text-2xl">{{ __('Jumlah Karyawan') }}</p>
                            <p class="fs-3 fw-semibold">{{ $jumlah_karyawan }}</p>
                        </div>
                        <div class='text-center col-3'>
                            <p class="text-md lg:text-2xl">{{ __('Laki laki') }}</p>
                            <p class="fs-3 fw-semibold">{{ $jumlah_laki_laki }}</p>
                        </div>
                        <div class='text-center col-3'>
                            <p class="text-md lg:text-2xl">{{ __('Perempuan') }}</p>
                            <p class="fs-3 fw-semibold">{{ $jumlah_perempuan }}</p>
                        </div>
                        <div class='col-3 text-center'>
                            <p class="text-md lg:text-2xl ">{{ __('Shift Malam') }}</p>
                            <p class="fs-3 fw-semibold ">{{ $jumlah_shift_malam }}</p>
                        </div>
                        <div class="d-flex lg:gap-0 gap-3 flex-lg-column flex-row justify-content-center  ">
                            <div class="d-flex  flex-lg-row justify-content-lg-evenly  flex-column  ">
                            </div>
                            <div class="d-flex flex-lg-row justify-content-lg-evenly  flex-column">
                            </div>
                        </div>
                    </div>
                </div>
                {{-- beda --}}
                <div class="d-lg-none d-flex lg:gap-0 gap-3 flex-lg-column flex-row justify-content-center  ">
                    <div class="d-flex  flex-lg-row justify-content-lg-evenly  flex-column  ">
                        <p class="fs-5 fw-normal">{{ __('Jumlah Karyawan') }}</p>
                        <p class="fs-5 fw-normal">{{ __('Laki laki') }}</p>
                        <p class="fs-5 fw-normal">{{ __('Perempuan') }}</p>
                        <p class="fs-5 fw-normal">{{ __('Shift Malam') }}</p>
                    </div>
                    <div class="d-flex flex-lg-row justify-content-lg-evenly  flex-column">
                        <p class="fs-5 fw-semibold text-right">{{ $jumlah_karyawan }}</p>
                        <p class="fs-5 fw-semibold text-right">{{ $jumlah_laki_laki }}</p>
                        <p class="fs-5 fw-semibold text-right">{{ $jumlah_perempuan }}</p>
                        <p class="fs-5 fw-semibold text-right">{{ $jumlah_shift_malam }}</p>
                    </div>
                </div>


            </div>
        </div>
    @endif




    @if ($placement_id != '')
        {{-- Penempatan Bagian Karyawan Pabrik --}}
        <div class="card my-3">
            <div class="card-header" style="background-color: #608ed3; color: white">
                <h2 class="py-1 px-3 text-center text-lg lg:text-2xl">{{ __('Penempatan Bagian Karyawan Pabrik') }}
                </h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">{{ __('Shift') }}</th>
                                <th class="text-end">{{ __('Produksi') }}</th>
                                <th class="text-end">{{ __('Quality Control') }}</th>
                                <th class="text-end">{{ __('Gudang') }}</th>
                                <th class="text-end">{{ __('Engineering') }}</th>
                                <th class="text-end">{{ __('GA') }}</th>
                                <th class="text-end">{{ __('Exim') }}</th>
                                <th class="text-end">{{ __('BD') }}</th>
                                <th class="text-end">{{ __('Procurement') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>


                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ __('Shift Pagi') }}</td>
                                <td class="text-end">{{ $shift_pagi_produksi }}</td>
                                <td class="text-end">{{ $shift_pagi_quality_control }}</td>
                                <td class="text-end">{{ $shift_pagi_gudang }}</td>
                                <td class="text-end">{{ $shift_pagi_engineering }}</td>
                                <td class="text-end">{{ $shift_pagi_ga }}</td>
                                <td class="text-end">{{ $shift_pagi_exim }}</td>
                                <td class="text-end">{{ $shift_pagi_bd }}</td>
                                <td class="text-end">{{ $shift_pagi_procurement }}</td>
                                <td class="text-end">{{ $shift_pagi_total }}</td>
                            </tr>
                            <tr>
                                <td>{{ __('Shift Malam') }}</td>
                                <td class="text-end">{{ $shift_malam_produksi }}</td>
                                <td class="text-end">{{ $shift_malam_quality_control }}</td>
                                <td class="text-end">{{ $shift_malam_gudang }}</td>
                                <td class="text-end">{{ $shift_malam_engineering }}</td>
                                <td class="text-end">{{ $shift_malam_ga }}</td>
                                <td class="text-end">{{ $shift_malam_exim }}</td>
                                <td class="text-end">{{ $shift_malam_bd }}</td>
                                <td class="text-end">{{ $shift_pagi_procurement }}</td>
                                <td class="text-end">{{ $shift_malam_total }}</td>
                            </tr>

                        </tbody>
                    </table>
                    <div>
                        <h6>{{ __('Produksi + Teknisi + Office') }}</h6>
                        <h6>{{ __('QC + QC AGING') }}</h6>
                    </div>
                </div>
            </div>
        </div>


        {{-- Posisi karyawan resign Harian --}}
        <div class="card my-3">
            <div class="card-header" style="background-color: #608ed3; color: white">
                <h2 class="py-1 px-3 text-center text-lg lg:text-2xl">{{ __('Posisi karyawan resign per tanggal') }}
                    {{ format_tgl($last_date) }}
                </h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-end">{{ __('Produksi') }}</th>
                                <th class="text-end">{{ __('Quality Control') }}</th>
                                <th class="text-end">{{ __('Gudang') }}</th>
                                <th class="text-end">{{ __('Engineering') }}</th>
                                <th class="text-end">{{ __('GA') }}</th>
                                <th class="text-end">{{ __('Exim') }}</th>
                                <th class="text-end">{{ __('BD') }}</th>
                                <th class="text-end">{{ __('Procurement') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>


                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end">{{ $resign_produksi }}</td>
                                <td class="text-end">{{ $resign_quality_control }}</td>
                                <td class="text-end">{{ $resign_gudang }}</td>
                                <td class="text-end">{{ $resign_engineering }}</td>
                                <td class="text-end">{{ $resign_ga }}</td>
                                <td class="text-end">{{ $resign_exim }}</td>
                                <td class="text-end">{{ $resign_bd }}</td>
                                <td class="text-end">{{ $resign_procurement }}</td>
                                <td class="text-end">{{ $resign_total }}</td>
                            </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{-- Posisi karyawan blacklist Harian --}}
        <div class="card my-3">
            <div class="card-header" style="background-color: #608ed3; color: white">
                <h2 class="py-1 px-3 text-center text-lg lg:text-2xl">
                    {{ __('Posisi karyawan blacklist per tanggal') }}
                    {{ format_tgl($last_date) }}
                </h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-end">{{ __('Produksi') }}</th>
                                <th class="text-end">{{ __('Quality Control') }}</th>
                                <th class="text-end">{{ __('Gudang') }}</th>
                                <th class="text-end">{{ __('Engineering') }}</th>
                                <th class="text-end">{{ __('GA') }}</th>
                                <th class="text-end">{{ __('Exim') }}</th>
                                <th class="text-end">{{ __('BD') }}</th>
                                <th class="text-end">{{ __('Procurement') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>


                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end">{{ $blacklist_produksi }}</td>
                                <td class="text-end">{{ $blacklist_quality_control }}</td>
                                <td class="text-end">{{ $blacklist_gudang }}</td>
                                <td class="text-end">{{ $blacklist_engineering }}</td>
                                <td class="text-end">{{ $blacklist_ga }}</td>
                                <td class="text-end">{{ $blacklist_exim }}</td>
                                <td class="text-end">{{ $blacklist_bd }}</td>
                                <td class="text-end">{{ $blacklist_procurement }}</td>
                                <td class="text-end">{{ $blacklist_total }}</td>
                            </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        {{-- Posisi karyawan baru harian --}}
        <div class="card my-3">
            <div class="card-header" style="background-color: #608ed3; color: white">
                <h2 class="py-1 px-3 text-center text-lg lg:text-2xl">{{ __('Posisi karyawan baru per tanggal') }}
                    {{ format_tgl($last_date) }} </h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-end">{{ __('Produksi') }}</th>
                                <th class="text-end">{{ __('Quality Control') }}</th>
                                <th class="text-end">{{ __('Gudang') }}</th>
                                <th class="text-end">{{ __('Engineering') }}</th>
                                <th class="text-end">{{ __('GA') }}</th>
                                <th class="text-end">{{ __('Exim') }}</th>
                                <th class="text-end">{{ __('BD') }}</th>
                                <th class="text-end">{{ __('Procurement') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>


                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end">{{ $baru_produksi }}</td>
                                <td class="text-end">{{ $baru_quality_control }}</td>
                                <td class="text-end">{{ $baru_gudang }}</td>
                                <td class="text-end">{{ $baru_engineering }}</td>
                                <td class="text-end">{{ $baru_ga }}</td>
                                <td class="text-end">{{ $baru_exim }}</td>
                                <td class="text-end">{{ $baru_bd }}</td>
                                <td class="text-end">{{ $baru_procurement }}</td>
                                <td class="text-end">{{ $baru_total }}</td>
                            </tr>


                        </tbody>
                    </table>

                </div>
            </div>
        </div>


        {{-- Karyawan cuti/izin --}}
        {{-- <div class="card my-3">
            <div class="card-header" style="background-color: #608ed3; color: white">
                <h2 class="py-1 px-3 text-center text-lg lg:text-2xl">{{ __('Karyawan cuti/izin') }}</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-end">{{ __('Produksi') }}</th>
                                <th class="text-end">{{ __('Quality Control') }}</th>
                                <th class="text-end">{{ __('Gudang') }}</th>
                                <th class="text-end">{{ __('Engineering') }}</th>
                                <th class="text-end">{{ __('GA') }}</th>
                                <th class="text-end">{{ __('Exim') }}</th>
                                <th class="text-end">{{ __('BD') }}</th>
                                <th class="text-end">{{ __('Procurement') }}</th>
                                <th class="text-end">{{ __('Total') }}</th>


                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-end">{{ $shift_pagi_produksi }}</td>
                                <td class="text-end">{{ $shift_pagi_quality_control }}</td>
                                <td class="text-end">{{ $shift_pagi_gudang }}</td>
                                <td class="text-end">{{ $shift_pagi_engineering }}</td>
                                <td class="text-end">{{ $shift_pagi_ga }}</td>
                                <td class="text-end">{{ $shift_pagi_exim }}</td>
                                <td class="text-end">{{ $shift_pagi_bd }}</td>
                                <td class="text-end">{{ $shift_pagi_procurement }}</td>
                                <td class="text-end">{{ $shift_pagi_total }}</td>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

        <div class="flex lg:flex-row flex-col gap-3">
            <div class="lg:w-1/3 w-full text-center">
                <div class="card">
                    <div class="card-header" style="background-color: #608ed3; color: white">
                        <p>{{ __('Karyawan masa kerja > 1 tahun') }}</p>
                    </div>
                    <div class="card-body">
                        <h1 class="font-bold">{{ $karyawan_lebih_1_tahun }}</h1>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/3 w-full text-center">
                <div class="card">
                    <div class="card-header" style="background-color: #608ed3; color: white">
                        <p>{{ __('Karyawan masa kerja 3-12 bulan') }}</p>
                    </div>
                    <div class="card-body">
                        <h1 class="font-bold">{{ $karyawan_3_12_bulan }}</h1>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/3 w-full text-center">
                <div class="card">
                    <div class="card-header" style="background-color: #608ed3; color: white">
                        <p>{{ __('Karyawan masa kerja < 3 bulan') }}</p>
                    </div>
                    <div class="card-body">
                        <h1 class="font-bold">{{ $karyawan_dibawah_3_bulan }}</h1>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
