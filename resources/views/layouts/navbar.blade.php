<!-- Navbar -->

<nav class="main-header navbar navbar-expand navbar-dark text-light py-3 nightowl-daylight"
    style="background-color: black">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <h2 class="ml-auto text-bold  fs-3">{{ __('STI Payroll System (OS)') }}</h2>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto flex gap-5">

        @if (auth()->user()->language == 'Cn')
            <li class="nav-item">
                @if (app()->getLocale() == 'id')
                    {{-- <a class="dropdown-item" href="{{ url('locale/en') }}">{{ __('english') }}</a> --}}
                    <a class="nav-link" href="{{ url('locale/cn') }}">{{ __('中文') }}</a>
                @endif

                @if (app()->getLocale() == 'cn')
                    <a class="nav-link" href="{{ url('locale/id') }}">{{ __('英语') }}</a>
                @endif
            </li>
        @endif


        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" role="button"></a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
