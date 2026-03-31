<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ url('favicon/favicon-32x32.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Yifang </title>



    <!-- Scripts -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script> --}}


    <!-- hapus 2 baris alpine ini kalau ada masalah -->
    <!-- Alpine Plugins -->
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script> --}}


    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
        crossorigin="anonymous"></script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}




    {{-- <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> --}}
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    {{-- @livewireStyles --}}

</head>
<style>
    body {
        background-color: #F4F6F9;
        /* background-color: #f7f7f7;
        background-image: url("https://www.transparenttextures.com/patterns/dimension.png"); */

    }
</style>

<body class="hold-transition sidebar-mini" style="font-family: 'nunito';">

    <div class="wrapper">

        @include('layouts.navbar')

        @include('layouts.aside')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="background-image: url({{ asset('images/texture.png') }});">

            <div class="p-3">
                <h4>ID dibawah ini tidak terdaftar dalam database.</h4>
                <ul>
                    @foreach ($data_id as $d)
                        <li>{{ $d }}</li>
                    @endforeach
                </ul>
                <a href="/yfpresensiindexwr"><button
                        class="bg-black text-white py-2 px-3 rounded text-center">Back</button></a>
            </div>

        </div>
        <!-- /.content-wrapper -->

        @include('layouts.footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        {{-- @livewireScripts --}}
        {{-- flat picker bagus --}}
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script>
            flatpickr("#tanggal", {
                dateFormat: "d M Y",
            });
        </script>

    </div>

</body>

</html>
