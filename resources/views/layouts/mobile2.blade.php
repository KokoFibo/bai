<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />


    <title>STI Mobile</title>
</head>

<body style="font-family: 'nunito'; background-image: url({{ asset('images/texture.png') }});">

    <div class="h-screen">
        <div class=header>
            <div class="w-screen bg-black  shadow-xl rounded-b-3xl  sticky top-0 ">
                <div class="flex justify-between h-32 items-center">
                    <div class="flex flex-col -mt-5">
                        <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo"
                            style="opacity: .8; width:150px">
                        <div class="flex justify-left -mt-4 invisible">
                            <div class="text-right px-5 pt-2 ">
                                <select name="" id="" class="bg-black text-white text-sm">
                                    <option value="">2023</option>
                                </select>
                            </div>
                            <div class="text-right px-5 pt-2 ">
                                <select name="" id="" class="bg-black text-white text-sm">
                                    <option value="">November</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3">
                        <div>
                            <h1 class="text-white p-3  text-sm">Hello, {{ auth()->user()->name }}</h1>
                        </div>
                        <div class="text-right px-5 ">
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                                <i class="nav-icon fa-solid fa-right-from-bracket"></i>
                                <button
                                    class="rounded-xl shadow bg-purple-500 text-sm text-white px-3 py-1">Logout</button>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-screen flex px-3 mt-5 justify-center ">
            @yield('contents')
        </div>

        <div class="footer flex justify-between h-15 fixed bottom-0 left-0 right-0 ">
            {{-- @if (isset($data)) --}}
            {{-- @if ($data->currentPage() > 1) --}}
            {{-- <a href="{{ $data->previousPageUrl() }}"> --}}
            <button class="bg-opacity-0 text-purple-500 px-4 py-3 rounded shadow-xl"><i
                    class="fa-solid fa-left-long"></i>
            </button>
            {{-- </a> --}}

            {{-- @endif --}}
            {{-- href="/userslipgaji" --}}
            <a href="#"><button class="bg-opacity-0 text-purple-500 px-4 py-3 rounded shadow-xl"><i
                        class="fa-solid fa-file-invoice"></i>
                </button></a>

            <a href="/mobile"><button class="bg-opacity-0 text-purple-500 px-4 py-3 rounded shadow-xl"><i
                        class="fa-solid fa-house"></i>
                </button></a>
            {{-- href="/userinfo" --}}
            <a href="#"><button class="bg-opacity-0 text-purple-500 px-4 py-3 rounded shadow-xl"><i
                        class="fa-solid fa-circle-info"></i>
                </button></a>
            {{-- @if ($data->hasMorePages()) --}}
            {{-- <a href="{{ $data->nextPageUrl() }}"> --}}
            <button class="bg-opacity-0 text-purple-500 px-4 py-3 rounded shadow-xl"><i
                    class="fa-solid fa-right-long"></i></button>
            {{-- </a> --}}

            {{-- @endif --}}
            {{-- @endif --}}
        </div>
    </div>
</body>

</html>
