@extends('layouts.app4')

@section('title', 'Dashboard')

{{-- Charts --}}
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js"
        integrity="sha512-+UYTD5L/bU1sgAfWA0ELK5RlQ811q8wZIocqI7+K0Lhh8yVdIoAMEs96wJAIbgFvzynPm36ZCXtkydxu1cs27w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@endsection

@section('content')
    <h1 class="pt-3  text-xl lg:text-4xl text-center font-semibold">{{ __('Dashboard') }}</h1>
    <div>
    </div>



    {{-- Dashboard device = {{ isDesktop() }} --}}
    <div id="root">

        @if (auth()->user()->role == 8)
            <div class="container">
                <button class="bg-blue-500 text-white px-3 py-2 rounded-md shadow-sm">Tanpa Etnis :
                    {{ $belum_isi_etnis }}</button>
                <button class="bg-violet-500 text-white px-3 py-2 rounded-md shadow-sm">Tanpa Kontak Darurat :
                    {{ $belum_isi_kontak_darurat }}</button>
            </div>
        @endif
        <div class="container pt-5">
            <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Baru Hari Ini') }}
                        </h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_baru_hari_ini }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Resigned Hari Ini') }}</h4>
                        <span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_Resigned_hari_ini }}</span>
                        {{-- <span class="hind-font caption-12 c-dashboardInfo__subInfo">Last month: €30</span> --}}
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center">
                            {{ __('Karyawan Blacklist Hari Ini') }}</h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_blacklist_hari_ini }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pt-5">
            <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Baru Minggu Lalu') }}
                        </h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_baru_minggu_lalu }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Resigned Minggu Lalu') }}</h4>
                        <span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_resign_minggu_lalu }}</span>
                        {{-- <span class="hind-font caption-12 c-dashboardInfo__subInfo">Last month: €30</span> --}}
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center">
                            {{ __('Karyawan Blacklist Minggu Lalu') }}</h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_blacklist_minggu_lalu }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="container pt-5">
            <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Baru MTD') }}
                        </h4>
                        <span class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_baru_mtd }}</span>

                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Resigned MTD') }}</h4>
                        <span class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_resigned_mtd }}</span>
                        {{-- <span class="hind-font caption-12 c-dashboardInfo__subInfo">Last month: €30</span> --}}
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center">
                            {{ __('Karyawan Blacklist MTD') }}</h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_blacklist_mtd }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Aktif MTD') }}
                        </h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ number_format($karyawan_aktif_mtd) }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <livewire:placementreport />
    <livewire:agamadetail />

    <livewire:turnover />
















    <style>
        .c-dashboardInfo {
            margin-bottom: 15px;
        }

        .c-dashboardInfo .wrap {
            background: #ffffff;
            box-shadow: 2px 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 7px;
            text-align: center;
            position: relative;
            overflow: hidden;
            padding: 40px 25px 20px;
            height: 100%;
        }

        .c-dashboardInfo__title,
        .c-dashboardInfo__subInfo {
            color: #6c6c6c;
            font-size: 1.18em;
        }

        .c-dashboardInfo span {
            display: block;
        }

        .c-dashboardInfo__count {
            font-weight: 600;
            font-size: 2.5em;
            line-height: 64px;
            color: #323c43;
        }

        .c-dashboardInfo .wrap:after {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            content: "";
        }

        .c-dashboardInfo:nth-child(1) .wrap:after {
            background: linear-gradient(82.59deg, #00c48c 0%, #00a173 100%);
        }

        .c-dashboardInfo:nth-child(2) .wrap:after {
            background: linear-gradient(81.67deg, #0084f4 0%, #1a4da2 100%);
        }

        .c-dashboardInfo:nth-child(3) .wrap:after {
            background: linear-gradient(69.83deg, #0084f4 0%, #00c48c 100%);
        }

        .c-dashboardInfo:nth-child(4) .wrap:after {
            background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
        }

        .c-dashboardInfo__title svg {
            color: #d7d7d7;
            margin-left: 5px;
        }

        .MuiSvgIcon-root-19 {
            fill: currentColor;
            width: 1em;
            height: 1em;
            display: inline-block;
            font-size: 24px;
            transition: fill 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
            user-select: none;
            flex-shrink: 0;
        }
    </style>
@endsection
