<div>
    <div>
        <div class="flex flex-col h-screen">
            <div class=header>
                @include('mobile-header')

                {{-- <div class="w-screen bg-gray-800 h-24 shadow-xl rounded-b-3xl   ">
                    <div class="flex justify-between items-center">
                        <div>
                            <img src="{{ asset('images/logo-only.png') }}" class="ml-3"alt="Yifang Logo"
                                style="opacity: .8; width: 50px">
                        </div>
                        <div class="flex flex-col p-3 gap-5 items-end">
                            @if (auth()->user()->role < 4)
                                <div>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                                        <button
                                            class="rounded-xl shadow bg-purple-500 text-sm text-white px-3 py-1">Logout</button>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            @else
                                <div>
                                    <a href="/"><button
                                            class="rounded-xl shadow bg-green-500 text-sm text-white px-3 py-1">Dasboard</button>
                                    </a>

                                </div>
                            @endif

                            <div>
                                <h1 class="text-white text-sm">Hello, {{ auth()->user()->name }}</h1>
                            </div>

                        </div>
                    </div>
                </div> --}}
                {{-- <div class="py-2"> --}}
                <div>
                    <h2 class="bg-gray-500 text-center text-white text-xl py-2 px-5 mt-3">Informasi Terkini
                    </h2>
                </div>
                {{-- </div> --}}

            </div>
            <div class="main  flex-1 overflow-y-auto ">
                {{-- <h2 class="bg-black text-center text-white text-xl rounded-xl px-5  ">Informasi Terkini</h2> --}}
                <div class="w-screen flex px-3  mt-3  flex-col ">
                    @foreach ($data as $d)
                        <div class="bg-white shadow rounded-xl mt-3 p-3">
                            <div class="flex items-center gap-4">
                                <div class="text-3xl text-gray-500"><i class="fa-regular fa-clipboard"></i></div>
                                <div>
                                    <div class="font-bold text-gray-500">{{ $d->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $d->description }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-20"></div>
            <div class="footer w-screen flex justify-between h-16 items-center bg-gray-800 fixed bottom-0">
                <a wire:navigate href="userregulation"><button
                        class="{{ 'userregulation' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl"><i
                            class="fa-solid fa-list-check"></i></button></a>

                {{-- @endif --}}
                {{-- href="/profile" --}}
                <a wire:navigate href="profile"><button
                        class="{{ 'profile' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl"><i
                            class="fa-solid fa-user"></i>
                    </button></a>
                <a wire:navigate href="usermobile"><button
                        class="{{ 'usermobile' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl"><i
                            class="fa-solid fa-house"></i>
                    </button></a>
                @if (is_perbulan())
                    <a href="timeoff"><button
                            class="{{ 'timeoff' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
                                class="fa-brands fa-wpforms"></i>
                        </button></a>
                @else
                    {{-- href="/userinformation" --}}
                    <a wire:navigate href="userinformation"><button
                            class="{{ 'userinformation' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
                                class="fa-solid fa-circle-info"></i>
                        </button></a>
                @endif

                <div>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                        <button class="text-purple-200 px-4 py-4 rounded text-2xl "><i
                                class="fa-solid fa-power-off"></i></button>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>


            </div>
        </div>
    </div>
</div>
