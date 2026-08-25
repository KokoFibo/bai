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
    <div class="dash-wrap">

        <!-- Page header -->
        <div class="dash-header">
            <p class="dash-header__eyebrow">{{ __('Overview') }}</p>
            <h1 class="dash-header__title">{{ __('Dashboard') }}</h1>
        </div>

        <div id="root">

            @if (auth()->user()->role == 8)
                <div class="dash-chip-row">
                    <div class="dash-chip dash-chip--info">
                        <i class="fa-solid fa-id-card-clip dash-chip__icon"></i>
                        <div class="dash-chip__body">
                            <span class="dash-chip__value">{{ $belum_isi_etnis }}</span>
                            <span class="dash-chip__label">{{ __('Tanpa Etnis') }}</span>
                        </div>
                    </div>
                    <div class="dash-chip dash-chip--warn">
                        <i class="fa-solid fa-phone-volume dash-chip__icon"></i>
                        <div class="dash-chip__body">
                            <span class="dash-chip__value">{{ $belum_isi_kontak_darurat }}</span>
                            <span class="dash-chip__label">{{ __('Tanpa Kontak Darurat') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Hari Ini -->
            <section class="dash-section">
                <h2 class="dash-section__title">
                    <i class="fa-regular fa-calendar-check dash-section__icon"></i>
                    {{ __('Hari Ini') }}
                </h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 g-lg-4">
                    <div class="col">
                        <div class="stat-card stat-card--positive">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-user-plus"></i></div>
                            <span class="stat-card__value">{{ $jumlah_karyawan_baru_hari_ini }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Baru') }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-card--neutral">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-person-walking-arrow-right"></i></div>
                            <span class="stat-card__value">{{ $jumlah_karyawan_Resigned_hari_ini }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Resigned') }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-card--danger">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-user-slash"></i></div>
                            <span class="stat-card__value">{{ $jumlah_karyawan_blacklist_hari_ini }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Blacklist') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Minggu Lalu -->
            <section class="dash-section">
                <h2 class="dash-section__title">
                    <i class="fa-regular fa-calendar-week dash-section__icon"></i>
                    {{ __('Minggu Lalu') }}
                </h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 g-lg-4">
                    <div class="col">
                        <div class="stat-card stat-card--positive">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-user-plus"></i></div>
                            <span class="stat-card__value">{{ $jumlah_karyawan_baru_minggu_lalu }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Baru') }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-card--neutral">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-person-walking-arrow-right"></i></div>
                            <span class="stat-card__value">{{ $jumlah_karyawan_resign_minggu_lalu }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Resigned') }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-card--danger">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-user-slash"></i></div>
                            <span class="stat-card__value">{{ $jumlah_karyawan_blacklist_minggu_lalu }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Blacklist') }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- MTD -->
            <section class="dash-section">
                <h2 class="dash-section__title">
                    <i class="fa-regular fa-calendar-days dash-section__icon"></i>
                    {{ __('Month to Date') }}
                </h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 g-lg-4">
                    <div class="col">
                        <div class="stat-card stat-card--positive">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-user-plus"></i></div>
                            <span class="stat-card__value">{{ $karyawan_baru_mtd }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Baru') }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-card--neutral">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-person-walking-arrow-right"></i></div>
                            <span class="stat-card__value">{{ $karyawan_resigned_mtd }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Resigned') }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-card--danger">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-user-slash"></i></div>
                            <span class="stat-card__value">{{ $karyawan_blacklist_mtd }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Blacklist') }}</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-card--accent">
                            <div class="stat-card__accent"></div>
                            <div class="stat-card__icon"><i class="fa-solid fa-users"></i></div>
                            <span class="stat-card__value">{{ number_format($karyawan_aktif_mtd) }}</span>
                            <span class="stat-card__label">{{ __('Karyawan Aktif') }}</span>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <section class="dash-section dash-section--widgets">
            <livewire:placementreport />
            <livewire:agamadetail />
            <livewire:turnover />
        </section>

    </div>

    <style>
        :root {
            --dash-accent: #e6b325;
            --dash-ink: #1f2430;
            --dash-muted: #7a8291;
            --dash-border: rgba(31, 36, 48, 0.08);
            --dash-bg-soft: #f6f7fb;
        }

        .dash-wrap {
            max-width: 1320px;
            margin: 0 auto;
            padding: 1.25rem 1rem 2.5rem;
        }

        /* ---------- Header ---------- */
        .dash-header {
            padding: .25rem 0 1.5rem;
        }

        .dash-header__eyebrow {
            margin: 0 0 .15rem;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--dash-accent);
        }

        .dash-header__title {
            margin: 0;
            font-size: clamp(1.4rem, 1.1rem + 1.2vw, 2rem);
            font-weight: 700;
            color: var(--dash-ink);
            text-align: left;
        }

        /* ---------- Role-8 info chips ---------- */
        .dash-chip-row {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            margin-bottom: 1.75rem;
        }

        .dash-chip {
            display: flex;
            align-items: center;
            gap: .75rem;
            background: #fff;
            border: 1px solid var(--dash-border);
            border-radius: 12px;
            padding: .7rem 1.1rem;
            box-shadow: 0 4px 14px rgba(31, 36, 48, .05);
            flex: 1 1 220px;
        }

        .dash-chip__icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            color: #fff;
        }

        .dash-chip--info .dash-chip__icon {
            background: linear-gradient(135deg, #2f7bf5, #1a4da2);
        }

        .dash-chip--warn .dash-chip__icon {
            background: linear-gradient(135deg, #a768f2, #6f3fc4);
        }

        .dash-chip__body {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .dash-chip__value {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--dash-ink);
        }

        .dash-chip__label {
            font-size: .8rem;
            color: var(--dash-muted);
        }

        /* ---------- Sections ---------- */
        .dash-section {
            margin-bottom: 2.25rem;
        }

        .dash-section__title {
            display: flex;
            align-items: center;
            gap: .55rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--dash-ink);
            text-transform: uppercase;
            letter-spacing: .04em;
            margin: 0 0 1rem;
            padding-bottom: .6rem;
            border-bottom: 1px solid var(--dash-border);
        }

        .dash-section__icon {
            color: var(--dash-accent);
            font-size: .95rem;
        }

        .dash-section--widgets {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* ---------- Stat cards ---------- */
        .stat-card {
            position: relative;
            background: #fff;
            border-radius: 14px;
            border: 1px solid var(--dash-border);
            box-shadow: 0 6px 20px rgba(31, 36, 48, .06);
            padding: 1.5rem 1.25rem 1.35rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(31, 36, 48, .1);
        }

        .stat-card__accent {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
        }

        .stat-card--positive .stat-card__accent {
            background: linear-gradient(90deg, #00c48c, #00a173);
        }

        .stat-card--neutral .stat-card__accent {
            background: linear-gradient(90deg, #0084f4, #1a4da2);
        }

        .stat-card--danger .stat-card__accent {
            background: linear-gradient(90deg, #ff647c, #d1264b);
        }

        .stat-card--accent .stat-card__accent {
            background: linear-gradient(90deg, var(--dash-accent), #b9860f);
        }

        .stat-card__icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--dash-bg-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: var(--dash-ink);
            margin-bottom: .85rem;
        }

        .stat-card--positive .stat-card__icon {
            color: #00a173;
        }

        .stat-card--neutral .stat-card__icon {
            color: #1a4da2;
        }

        .stat-card--danger .stat-card__icon {
            color: #d1264b;
        }

        .stat-card--accent .stat-card__icon {
            color: #b9860f;
        }

        .stat-card__value {
            display: block;
            font-weight: 700;
            font-size: 2.1rem;
            line-height: 1.15;
            color: var(--dash-ink);
        }

        .stat-card__label {
            display: block;
            margin-top: .3rem;
            font-size: .85rem;
            color: var(--dash-muted);
        }

        /* ---------- Mobile tuning ---------- */
        @media (max-width: 575.98px) {
            .dash-wrap {
                padding: 1rem .75rem 2rem;
            }

            .dash-header {
                padding-bottom: 1.1rem;
            }

            .stat-card {
                padding: 1.25rem 1rem 1.1rem;
            }

            .stat-card__value {
                font-size: 1.85rem;
            }

            .dash-chip {
                flex: 1 1 100%;
            }
        }
    </style>
@endsection
