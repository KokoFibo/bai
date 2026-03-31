<div>
    <div>
        {{-- <div class="h-screen"> --}}
        <div class="flex flex-col h-screen mb-[100px]">

            <div class=header>
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
                                            class="rounded-xl shadow bg-blue-500 text-sm text-white px-3 py-1">Logout</button>
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
                @include('mobile-header')

                <div>
                    <h2 class="bg-blue-500 text-center text-white text-xl py-2 px-5 mt-3">
                        {{ __('User Profile') }}</h2>
                </div>
            </div>
            <div class="main  flex-1 overflow-y-auto ">

                {{-- Ubah Password --}}
                <div class=" bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">
                    <div>
                        {{-- <label class="block text-sm font-medium  text-gray-900">Password Lama</label> --}}
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="password" wire:model="old_password" placeholder="{{ __('Password Lama') }}"
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
                            <input type="password" wire:model="new_password" placeholder="{{ __('Password Baru') }}"
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
                            <input type="password" wire:model="confirm_password" placeholder="{{ __('Konfirmasi') }}"
                                class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                            @error('confirm_password')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <button wire:click="changePassword"
                        class="bg-blue-500 text-sm text-white px-1 py-1 w-full rounded shadow">{{ __('Ubah Password') }}</button>
                </div>

                {{-- Ubah Email --}}
                <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">

                    <div>
                        {{-- <label class="block text-sm font-medium  text-gray-900">Email Baru</label> --}}
                        <div class="relative mt-1 rounded-md shadow-sm">
                            <input type="text" wire:model="email" placeholder="{{ __('Email Baru') }}"
                                class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                            @error('email')
                                <div class="text-red-500">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <button class="bg-blue-500 text-sm text-white px-1 py-1 w-full rounded shadow"
                        wire:click="changeEmail">{{ __('Ubah Email') }}</button>
                </div>

                {{-- Ubah Bahasa --}}
                <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">

                    {{-- <label class="block text-sm font-medium  text-gray-900">Bahasa</label> --}}
                    <div class="flex gap-5">
                        <div>
                            <input wire:model="language" value="Id" type="radio">
                            <label class="form-check-label" for="flexRadioDefault1">{{ __('Indonesia') }}</label>
                        </div>
                        <div>
                            <input wire:model="language" value="Cn" type="radio">
                            <label class="form-check-label" for="flexRadioDefault1">中文</label>
                        </div>
                    </div>
                    <button class="bg-blue-500 text-sm text-white px-1 py-1 w-full rounded shadow"
                        wire:click="changeLanguage">{{ __('Ubah Bahasa') }}</button>
                </div>

                {{-- Update Etnis --}}
                <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">

                    {{-- <label class="block text-sm font-medium  text-gray-900">Bahasa</label> --}}
                    <div class="flex gap-5">
                        <div>
                            <div>
                                <input wire:model="etnis" value="China" type="radio">
                                <label class="form-check-label" for="flexRadioDefault1">{{ __('China') }}</label>
                            </div>
                            <div>
                                <input wire:model="etnis" value="Jawa" type="radio">
                                <label class="form-check-label" for="flexRadioDefault1">{{ __('Jawa') }}</label>
                            </div>

                        </div>
                        <div>
                            <div>
                                <input wire:model="etnis" value="Sunda" type="radio">
                                <label class="form-check-label" for="flexRadioDefault1">{{ __('Sunda') }}</label>
                            </div>
                            <div>
                                <input wire:model="etnis" value="Tionghoa" type="radio">
                                <label class="form-check-label" for="flexRadioDefault1">{{ __('Tionghoa') }}</label>
                            </div>
                        </div>
                        <div>
                            <div>
                                <input wire:model="etnis" value="Lainnya" type="radio">
                                <label class="form-check-label" for="flexRadioDefault1">{{ __('Lainnya') }}</label>
                            </div>
                        </div>

                    </div>
                    <button class="bg-blue-500 text-sm text-white px-1 py-1 w-full rounded shadow"
                        wire:click="updateEtnis">{{ __('Update Etnis') }}</button>
                </div>
                {{-- Update Kontak Darurat --}}
                <div class="bg-white mx-3 px-3 py-3 mt-3  flex flex-col gap-2 rounded-xl shadow-xl">
                    <div class="flex flex-col gap-3">

                        <div>
                            <div class="relative mt-1 rounded-md shadow-sm mb-2">
                                <input type="text" wire:model="kontak_darurat"
                                    placeholder="{{ __('Nama kontak darurat') }}"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                                @error('kontak_darurat')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="relative mt-1 rounded-md shadow-sm mb-2">
                                <input type="text" wire:model="hp1" placeholder="{{ __('Handphone') }} 1"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                                @error('hp1')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="relative mt-1 rounded-md shadow-sm mb-2">
                                <input type="text" wire:model="hubungan1"
                                    placeholder="{{ __('Hubungan dengan Kontak Darurat 1') }}"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                                @error('hubungan1')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <div class="relative mt-1 rounded-md shadow-sm mb-2">
                                <input type="text" wire:model="kontak_darurat2" placeholder="Nama Kontak Darurat 2"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                                @error('kontak_darurat2')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="relative mt-1 rounded-md shadow-sm mb-1">
                                <input type="text" wire:model="hp2" placeholder="Nomor HP Kontak Darurat 2"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                                @error('hp2')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="relative mt-1 rounded-md shadow-sm mb-2">
                                <input type="text" wire:model="hubungan2"
                                    placeholder="Hubungan dengan Kontak Darurat 2"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:">
                                @error('hubungan2')
                                    <div class="text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button class="bg-blue-500 text-sm text-white px-1 py-1 w-full rounded shadow"
                        wire:click="update_kontak_darurat">{{ __('Update Data Kontak Darurat') }}</button>
                </div>
            </div>


            {{-- Footer --}}
            @include('mobile-footer')

        </div>
    </div>

</div>
