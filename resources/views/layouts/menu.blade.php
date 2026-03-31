<!-- Sidebar Menu -->

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
with font-awesome or any other icon font library -->

        <li class="nav-item {{ '' == request()->path() ? 'bg-secondary rounded' : '' }}">
            {{-- <a href="/dashboard" class="nav-link"> --}}
            <a href="/" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    {{ __('Dashboard') }}
                </p>
            </a>
        </li>

        @if (Auth::user()->role >= 5)
            <li class="nav-item {{ 'karyawanindex' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/karyawanindex" class="nav-link">
                    <i class="nav-icon fa-solid fa-people-group"></i>
                    <p>
                        {{ __('Data Karyawan') }}
                    </p>
                </a>
            </li>
        @endif

        @if (Auth::user()->role >= 4)
            <li class="nav-item {{ 'dataapplicant' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/dataapplicant" class="nav-link">
                    <i class="nav-icon fa-solid fa-person-walking"></i>

                    <p>
                        {{ __('Data Applicant') }}
                    </p>
                </a>
            </li>
        @endif
        {{-- Personnel Request utk STI di non aktifkan --}}
        {{-- @if (Auth::user()->role > 5 || Auth::user()->role == 2)
            @if (isRequester(auth()->user()->username) || Auth::user()->role > 5)
                <li class="nav-item {{ 'permohonan-personnel' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/permohonan-personnel" class="nav-link">
                        <i class="nav-icon fa-solid fa-people-arrows"></i>
                        <p class="personnel-request">
                            {{ __('Personnel Request') }}
                            @if (auth()->user()->role >= 6 && check_for_new_approved_request() != 0)
                                <span class="badge">{{ check_for_new_approved_request() }}
                                </span>
                            @endif
                            @if (auth()->user()->role == 2 && check_for_new_applyingrequest() != 0)
                                <span class="badge">{{ check_for_new_applyingrequest() }}
                                </span>
                            @endif

                        </p>
                    </a>
                </li>
            @endif

        @endif --}}

        @if (Auth::user()->role >= 5)

            @if (Auth::user()->role > 7)
                <li class="nav-item {{ 'dataresigned' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/dataresigned" class="nav-link">
                        <i class="nav-icon fa-solid fa-person-walking"></i>
                        <p>
                            {{ __('Data Resigned') }}
                        </p>
                    </a>
                </li>
            @endif

            <li class="nav-item {{ 'tambahan' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/tambahan" class="nav-link">
                    <i class="nav-icon fa-solid fa-money-bill-transfer"></i>
                    <p>
                        {{ __('Bonus & Potongan') }}
                    </p>
                </a>
            </li>
            @if (Auth::user()->role >= 6)
                <li class="nav-item {{ 'salaryadjustment' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/salaryadjustment" class="nav-link">
                        <i class="nav-icon fa-solid fa-sliders"></i>
                        <p>
                            {{ __('Penyesuaian Gaji') }}
                        </p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role == 8)
                <li class="nav-item {{ 'yfpresensiindexwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/yfpresensiindexwr" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-check"></i>
                        <p>{{ __('Old Presensi') }}</p>
                    </a>
                </li>
            @endif

            <li class="nav-item {{ 'newpresensi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/newpresensi" class="nav-link">
                    <i class="nav-icon fas fa-clipboard-check"></i>
                    <p>{{ __('Presensi') }}</p>
                </a>
            </li>


            {{-- @if (Auth::user()->role >= 5)
                <li class="nav-item {{ 'addtimeoutrequester' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/addtimeoutrequester" class="nav-link">
                        <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                        <p>
                            {{ __('Add TimeOff Req') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ 'payrollindex' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/payrollindex" class="nav-link">
                        <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                        <p>
                            {{ __('Presensi Summary') }}
                        </p>
                    </a>
                </li>
            @endif --}}


            @if (Auth::user()->role >= 6)
                <li class="nav-item {{ 'payroll' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/payroll" class="nav-link">
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            {{ __('Payroll') }}
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ 'gajibpjs' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/gajibpjs" class="nav-link">
                        <i class="nav-icon fa-solid fa-shield-halved"></i>
                        <p>
                            {{ __('BPJS/PTKP') }}
                        </p>
                    </a>
                </li>
            @endif


            <li class="nav-item {{ 'informationwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/informationwr" class="nav-link">
                    <i class="nav-icon fa-solid fa-user-check nav-icon"></i>
                    <p>{{ __('Add Information') }}</p>
                </a>
            </li>
            <li class="nav-item {{ 'liburnasional' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/liburnasional" class="nav-link">
                    <i class="nav-icon fa-solid fa-holly-berry"></i>
                    <p>
                        {{ __('Libur Nasional') }}
                    </p>
                </a>
            </li>

            @if (Auth::user()->role >= 6)
                <li class="nav-item {{ 'usermobile' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/usermobile" class="nav-link">
                        <i class="nav-icon fa-solid fa-mobile-screen-button"></i>
                        <p>{{ __('User Mobile') }}</p>
                    </a>
                </li>
                {{-- <li class="nav-item {{ 'hitungthr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/hitungthr" class="nav-link">
                        <i class="nav-icon fa-solid fa-mobile-screen-button"></i>
                        <p>{{ __('Hitung THR') }}</p>
                    </a>
                </li> --}}
                <li class="nav-item {{ 'hitungthrlebaran' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/hitungthrlebaran" class="nav-link">
                        <i class="nav-icon fa-solid fa-mobile-screen-button"></i>
                        <p>{{ __('Hitung THR Lebaran') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ 'data-log' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/data-log" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('History Gaji') }}</p>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa-solid fa-gear nav-icon"></i>
                    <p>{{ __('Settings') }}<i class="right fas fa-angle-left"></i></p>
                </a>

                <ul class="nav nav-treeview">
                    <li class="nav-item {{ 'changeprofilewr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                        <a href="/changeprofilewr" class="nav-link">
                            <i class="fa-solid fa-address-card nav-icon"></i>
                            <p>{{ __('Change Profile') }}</p>
                        </a>
                    </li>
                    <li class="nav-item {{ 'karyawansettingwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                        <a href="/karyawansettingwr" class="nav-link">
                            <i class="fa-solid fa-users-gear nav-icon"></i>
                            <p>{{ __('Karyawan Settings') }}</p>
                        </a>
                    </li>
                    @if (Auth::user()->role > 6)
                        <li
                            class="nav-item {{ 'changeuserrolewr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a href="/changeuserrolewr" class="nav-link">
                                <i class="fa-solid fa-user-check nav-icon"></i>
                                <p>{{ __('Change User Role') }}</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>


            @if (Auth::user()->role > 7)
                <li class="nav-item {{ 'data-log' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/laporan" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Laporan') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ 'applicantditerima' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/applicantditerima" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Applicant diterima') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ 'changefield' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/changefield" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Change Field') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ 'developer-dashboard' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/developer-dashboard" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Developer Dashboard') }}</p>
                    </a>
                </li>


                <li class="nav-item {{ 'deletenoscan' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/deletenoscan" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Delete Noscan') }}</p>
                    </a>
                </li>

                <li
                    class="nav-item {{ 'yfdeletetanggalpresensiwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a onclick="return confirm('Mau delete Tgl Presensi?')" href="/yfdeletetanggalpresensiwr"
                        class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Delete Tgl Presensi') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                        <p>
                            {{ __('') }}Developer Tools
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li
                            class="nav-item {{ 'karyawanviewimport' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau upload karyawan?')" href="/karyawanviewimport"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('') }}Karyawan Uploader</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'generateusers' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau generate user?')" href="/generateusers" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('') }}Generate Users</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ 'yfdeletetanggalpresensiwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau delete Tgl Presensi?')" href="/yfdeletetanggalpresensiwr"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('') }}Delete Tgl Presensi</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'moveback' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            {{-- <a onclick="return confirm('Mau pindah data Presensi?')" href="/moveback" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Move presensi data') }}</p>
                    </a> --}}
                            <a href="/moveback" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Move Back data') }}</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'movepresensidata' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            {{-- <a onclick="return confirm('Mau pindah data Presensi?')" href="/movepresensidata" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{ __('Move presensi data') }}</p>
                    </a> --}}
                            <a href="/movepresensidata" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Move presensi data') }}</p>
                            </a>
                        </li>

                        <li class="nav-item {{ 'deletenoscan' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau delete No Scan?')" href="/deletenoscan" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Delete No Scan</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'deletejamkerja' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau delete jam kerja?')" href="/deletejamkerja"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Delete Jam Kerja</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'MissingId' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau check missing ID?')" href="/MissingId" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Missing ID</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ 'importKaryawanExcel' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau import karyawan dari excel bersih?')"
                                href="/importKaryawanExcel" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Import Karyawan dari excel bersih</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'rubahid' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Rubah Id karyawan')" href="/rubahid" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rubah ID</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'editpresensi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Edit Presensi')" href="/editpresensi" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Edit Presensi</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'removepresensi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Remove Presensi')" href="/removepresensi"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Remove Presensi</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'removepresensiduplikat' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Remove Duplikat Presensi')" href="/removepresensiduplikat"
                                class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Remove Duplikat Presensi</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'exceluploader' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a href="/exceluploader" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Excel Uploader</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'UpdatedPresensi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a href="/UpdatedPresensi" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Updated Presensi</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'UserLog' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a href="/UserLog" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User Log</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'test' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a href="/test" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>test livewire aj</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ 'test' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/test" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>test livewire aj</p>
                    </a>
                </li>
                <li class="nav-item {{ 'UserLog' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/UserLog" class="nav-link">
                        <i class="nav-icon fa-solid fa-users-between-lines"></i>
                        <p>User Log</p>
                    </a>
                </li>
            @endif
            {{-- <li class="nav-item {{ 'informasi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/informasi" class="nav-link">
                    <i class="fa-solid fa-circle-info nav-icon"></i>
                    <p> Informasi</p>
                </a>
            </li> --}}

        @endif


        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                <p>{{ __('Logout') }}</p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            </a>
            @csrf
            </form>




        </li>
    </ul>

    <style>
        .personnel-request {
            position: relative;
        }

        .personnel-request .badge {
            position: absolute;
            top: 50%;
            left: 115%;
            transform: translate(-50%, -50%);
            background-color: #dc3545;
            /* Bootstrap bg-danger color */
            color: white;
            /* To ensure text is readable */
            padding: 0.25em 0.6em;
            /* Equivalent to Bootstrap badge padding */
            border-radius: 50px;
            /* Equivalent to Bootstrap rounded-pill */
            font-size: 75%;
            /* Equivalent to Bootstrap badge font size */
        }
    </style>

</nav>
<!-- /.sidebar-menu -->
