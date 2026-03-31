<!-- Sidebar Menu -->
<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
with font-awesome or any other icon font library -->

        <li class="nav-item {{ 'dashboard' == request()->path() ? 'bg-secondary rounded' : '' }}">
            {{-- <a href="/dashboard" class="nav-link" wire:navigate> --}}
            <a href="/dashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>

        @if (Auth::user()->role > 1)
            <li class="nav-item {{ 'karyawanindex' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/karyawanindex" class="nav-link" wire:navigate>
                    <i class="nav-icon fa-solid fa-people-group"></i>
                    <p>
                        Data Karyawan
                    </p>
                </a>
            </li>
            <li class="nav-item {{ 'tambahan' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/tambahan" class="nav-link" wire:navigate>
                    <i class="nav-icon fa-solid fa-people-group"></i>
                    <p>
                        Bonus & Potongan
                    </p>
                </a>
            </li>
            <li class="nav-item {{ 'yfpresensiindexwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/yfpresensiindexwr" class="nav-link" wire:navigate>
                    <i class="nav-icon fas fa-clipboard-check"></i>
                    <p>Presensi</p>
                </a>
            </li>
            @if (Auth::user()->role > 2)
                <li class="nav-item {{ 'payrollindex' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/payrollindex" class="nav-link" wire:navigate>
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Build Presensi Detail
                        </p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role > 3)
                <li class="nav-item {{ 'payroll' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/payroll" class="nav-link" wire:navigate>
                        <i class="nav-icon fas fa-dollar-sign"></i>
                        <p>
                            Payroll
                        </p>
                    </a>
                </li>
            @endif


            @if (Auth::user()->role > 4)
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa-solid fa-screwdriver-wrench"></i>
                        <p>
                            Developer Tools
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li
                            class="nav-item {{ 'karyawanviewimport' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau upload karyawan?')" href="/karyawanviewimport"
                                class="nav-link" wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Karyawan Uploader</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'generateusers' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau generate user?')" href="/generateusers" class="nav-link"
                                wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Generate Users</p>
                            </a>
                        </li>

                        <li
                            class="nav-item {{ 'yfdeletetanggalpresensiwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau delete Tgl Presensi?')" href="/yfdeletetanggalpresensiwr"
                                class="nav-link" wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Delete Tgl Presensi</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'yfdeletepresensi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Truncate table Rekappresensi dan presensi?')"
                                href="/yfdeletepresensi" class="nav-link" wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p class="text-danger">Truncate Table</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'deletenoscan' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau delete No Scan?')" href="/deletenoscan" class="nav-link"
                                wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Delete No Scan</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'deletejamkerja' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau delete jam kerja?')" href="/deletejamkerja" class="nav-link"
                                wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Delete Jam Kerja</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'MissingId' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau check missing ID?')" href="/MissingId" class="nav-link"
                                wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Missing ID</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'importKaryawanExcel' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau import karyawan dari excel bersih?')"
                                href="/importKaryawanExcel" class="nav-link" wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Import Karyawan dari excel bersih</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'rubahid' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Rubah Id karyawan')" href="/rubahid" class="nav-link"
                                wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rubah ID</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'editpresensi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Edit Presensi')" href="/editpresensi" class="nav-link"
                                wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Edit Presensi</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'removepresensi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Remove Presensi')" href="/removepresensi" class="nav-link"
                                wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Remove Presensi</p>
                            </a>
                        </li>
                        <li
                            class="nav-item {{ 'removepresensiduplikat' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a onclick="return confirm('Mau Remove Duplikat Presensi')" href="/removepresensiduplikat"
                                class="nav-link" wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Remove Duplikat Presensi</p>
                            </a>
                        </li>
                        <li class="nav-item {{ 'exceluploader' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a href="/exceluploader" class="nav-link" wire:navigate>
                                <i class="far fa-circle nav-icon"></i>
                                <p>Excel Uploader</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item {{ 'test' == request()->path() ? 'bg-secondary rounded' : '' }}">
                    <a href="/test" class="nav-link" wire:navigate>
                        <i class="far fa-circle nav-icon"></i>
                        <p>test livewire aj</p>
                    </a>
                </li>
            @endif
            <li class="nav-item {{ 'informasi' == request()->path() ? 'bg-secondary rounded' : '' }}">
                <a href="/informasi" class="nav-link" wire:navigate>
                    <i class="fa-solid fa-circle-info nav-icon"></i>
                    <p> Informasi</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fa-solid fa-gear nav-icon"></i>
                    <p>Settings<i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item {{ 'changeprofilewr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                        <a href="/changeprofilewr" class="nav-link" wire:navigate>
                            <i class="fa-solid fa-address-card nav-icon"></i>
                            <p>Change Profile</p>
                        </a>
                    </li>
                    <li class="nav-item {{ 'karyawansettingwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                        <a href="/karyawansettingwr" class="nav-link" wire:navigate>
                            <i class="fa-solid fa-users-gear nav-icon"></i>
                            <p>Karyawan Settings</p>
                        </a>
                    </li>
                    @if (Auth::user()->role > 2)
                        <li
                            class="nav-item {{ 'changeuserrolewr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                            <a href="/changeuserrolewr" class="nav-link" wire:navigate>
                                <i class="fa-solid fa-user-check nav-icon"></i>
                                <p>Change User Role</p>
                            </a>
                        </li>
                    @endif
                    <li class="nav-item {{ 'informationwr' == request()->path() ? 'bg-secondary rounded' : '' }}">
                        <a href="/informationwr" class="nav-link" wire:navigate>
                            <i class="fa-solid fa-user-check nav-icon"></i>
                            <p>Add Information</p>
                        </a>
                    </li>

                </ul>
            </li>
        @endif


        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                <p>{{ __('Logout') }}</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>




</nav>
<!-- /.sidebar-menu -->
