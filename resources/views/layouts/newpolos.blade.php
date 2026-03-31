<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}





    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('favicon/favicon-32x32.png') }}">


    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>New Applicant Registration Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E="
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">




    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />




</head>

<body style="font-family: 'nunito';">
    <!--Content Wrapper.Contains page content-->
    {{-- <div style="background-image: url({{ asset('images/texture.png') }});"> --}}
    <div>
        {{ $slot }}
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @stack('script')
    {{-- falt picker bagus --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#tanggal", {
            dateFormat: "d M Y",
        });
    </script>

</body>
<script>
    $(document).ready(function() {
        toastr.options = {
            progressBar: true,
            timeOut: "2000",
            progressBar: true,
            positionClass: "toast-top-right",
            closeButton: true,
            preventDuplicates: true,
            showEasing: "swing",
            hideEasing: "linear",
            showMethod: "fadeIn",
            hideMethod: "fadeOut",
        };
        window.addEventListener("success", (event) => {
            toastr.success(event.detail.message);
        });
        window.addEventListener("warning", (event) => {
            toastr.warning(event.detail.message);
        });

        window.addEventListener("info", (event) => {
            toastr.info(event.detail.message);
        });

        window.addEventListener("error", (event) => {
            toastr.error(event.detail.message);
        });
    });

    window.addEventListener("hide-form", (event) => {
        $("#update-form-modal").modal("hide");
    });
    window.addEventListener("update-form", (event) => {
        $("#update-form-modal").modal("show");
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[type-currency="IDR"]').forEach((element) => {
            element.addEventListener('keyup', function(e) {
                let cursorPostion = this.selectionStart;
                let value = parseInt(this.value.replace(/[^,\d]/g, ''));
                let originalLenght = this.value.length;
                if (isNaN(value)) {
                    this.value = "";
                } else {
                    this.value = value.toLocaleString('id-ID', {
                        currency: 'IDR',
                        style: 'currency',
                        minimumFractionDigits: 0
                    });
                    cursorPostion = this.value.length - originalLenght + cursorPostion;
                    this.setSelectionRange(cursorPostion, cursorPostion);
                }
            });
        });

    });
</script>


</html>
