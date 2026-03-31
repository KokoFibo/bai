<div>
    <div>
        <div class="flex flex-col h-screen">
            <div class=header>
                <div class=" bg-gray-800  shadow-xl rounded-b-3xl   ">
                    <div class="flex justify-between h-36 items-center">
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

                                    <button
                                        class="rounded-xl shadow bg-purple-500 text-sm  px-3 text-white py-1">Logout</button>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                {{-- <div class="py-2"> --}}
                <div class="flex justify-center">
                    <h2 class="bg-black text-center text-white text-xl rounded-xl px-5 mt-3">Informasi Terkini
                    </h2>
                </div>
                {{-- </div> --}}

            </div>
            <div class="main  flex-1 overflow-y-auto ">
                {{-- <h2 class="bg-black text-center text-white text-xl rounded-xl px-5  ">Informasi Terkini</h2> --}}
                <div class="w-screen flex px-3 mt-5  flex flex-col gap-5 ">


                    @foreach ($data as $d)
                        <div class="bg-white shadow rounded-xl mt-5 p-3">
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

            <div class="footer flex justify-between h-16 items-center bg-gray-800 ">

                <button class="text-purple-200 px-4 py-4 rounded text-2xl"><i class="fa-solid fa-left-long"></i>
                </button>



                {{-- href="/profile" --}}
                <a wire:navigate href="profile"><button
                        class="{{ 'profile' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded text-2xl"><i
                            class="fa-solid fa-user"></i>
                    </button></a>

                <a wire:navigate href="/usermobile"><button
                        class="{{ 'usermobile' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded text-2xl"><i
                            class="fa-solid fa-house"></i>
                    </button></a>
                {{-- href="/userinformation" --}}
                <a wire:navigate href="userinformation"><button
                        class="{{ 'userinformation' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded text-2xl"><i
                            class="fa-solid fa-circle-info"></i>
                    </button></a>

                <button class="text-purple-200 px-4 py-4 rounded text-2xl"><i
                        class="fa-solid fa-right-long"></i></button>

            </div>
        </div>
    </div>
</div>
