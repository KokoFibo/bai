@if (session('error'))
    toastr.error("{{ Session::get('error') }}");
@endif
@if (session('success'))
    toastr.success("{{ Session::get('success') }}");
@endif
@if (session('info'))
    toastr.success("{{ Session::get('success') }}");
@endif
