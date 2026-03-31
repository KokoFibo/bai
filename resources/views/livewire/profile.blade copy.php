<div>
    <div>
        <div class="h-screen">
            <div class=header>
                <div class="w-screen bg-gray-800  shadow-xl rounded-b-3xl  sticky top-0 ">
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
                        <div class="flex flex-col gap-2">
                            <div>
                                <h1 class="text-white p-3  text-sm">Hello, {{ auth()->user()->name }}</h1>
                            </div>
                            <div class="text-right px-5 ">
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center">
                <h2 class="bg-purple-500 text-center text-white text-xl rounded-xl px-5 mt-3">User
                    Profile
                </h2>
            </div>

            {{-- Ubah Password --}}
            <div class=" bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">
                <div>
                    {{-- <label class="block text-sm font-medium  text-gray-900">Password Lama</label> --}}
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <input type="password" wire:model="old_password" placeholder="Password Lama"
                            class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                        @error('old_password')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div>
                    {{-- <label class="block text-sm font-medium  text-gray-900">Password Baru</label> --}}
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <input type="password" wire:model="new_password" placeholder="Password Baru"
                            class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                        @error('new_password')
                            <div class="text-red-500">
                                {{ $message }}

                            </div>
                        @enderror
                    </div>
                </div>
                <div>
                    {{-- <label class="block text-sm font-medium  text-gray-900">Konfirmasi</label> --}}
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <input type="password" wire:model="confirm_password" placeholder="Konfirmasi"
                            class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                        @error('confirm_password')
                            <div class="text-red-500">
                                {{ $message }}

                            </div>
                        @enderror
                    </div>
                </div>
                <button wire:click="changePassword"
                    class="bg-purple-500 text-sm text-white px-1 py-1 w-1/3 rounded shadow">Ubah Password</button>
            </div>



            {{-- Ubah Email --}}
            <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">

                <div>
                    {{-- <label class="block text-sm font-medium  text-gray-900">Email Baru</label> --}}
                    <div class="relative mt-1 rounded-md shadow-sm">
                        <input type="text" wire:model="email" placeholder="Email Baru"
                            class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                        @error('email')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <button class="bg-purple-500 text-sm text-white px-1 py-1 w-1/3 rounded shadow"
                    wire:click="changeEmail">Ubah
                    Email</button>
            </div>


            {{-- Ubah Bahasa --}}
            <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">

                {{-- <label class="block text-sm font-medium  text-gray-900">Bahasa</label> --}}
                <div class="flex gap-5">
                    <div>
                        <input wire:model="language" value="Id" type="radio">
                        <label class="form-check-label" for="flexRadioDefault1">Indonesia</label>
                    </div>
                    <div>
                        <input wire:model="language" value="Cn" type="radio">
                        <label class="form-check-label" for="flexRadioDefault1">Mandarin</label>
                    </div>
                </div>
                <button class="bg-purple-500 text-sm text-white px-1 py-1 w-1/3 rounded shadow"
                    wire:click="changeLanguage">Ubah
                    Bahasa</button>
            </div>


            {{-- Footer --}}
            <div class="footer bg-slate-800 flex justify-between h-16 items-center fixed bottom-0 left-0 right-0 ">
                {{-- @if (isset($data)) --}}
                {{-- @if ($data->currentPage() > 1) --}}
                {{-- <a href="{{ $data->previousPageUrl() }}"> --}}
                <button class="text-purple-200 px-4 py-4 rounded text-2xl"><i class="fa-solid fa-left-long"></i>
                </button>
                {{-- </a> --}}

                {{-- @endif --}}
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
                {{-- @if ($data->hasMorePages()) --}}
                {{-- <a href="{{ $data->nextPageUrl() }}"> --}}
                <button class="text-purple-200 px-4 py-4 rounded text-2xl"><i
                        class="fa-solid fa-right-long"></i></button>
                {{-- </a> --}}

                {{-- @endif --}}
                {{-- @endif --}}
            </div>
        </div>
    </div>

</div>
