<div>
    <div>
        <div class="flex flex-col h-screen">
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
                @include('mobile-header')

                <div>
                    <h2 class="bg-gray-600 text-center text-white text-xl py-2 px-5 mt-3">Upload Dokumen
                    </h2>
                </div>

            </div>
            <div class="main  flex-1  overflow-y-auto text-gray-500 ">
                <div class="max-w-md mx-auto bg-white shadow-lg rounded-2xl p-4">
                    <div class="space-y-3 mt-1">
                        <div>
                            <label class="block text-gray-600 text-sm">Hanya menerima file dalam bentuk jpg, jpeg
                                dan png.</label>
                            <label class="block text-gray-600 text-sm">Limit max 2Mb/file</label>
                        </div>
                        <!-- File Input Fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ([
        'ktp' => 'KTP',
        'kk' => 'Kartu Keluarga',
        'ijazah' => 'Ijazah',
        'nilai' => 'Transkrip Nilai/SKHUN',
        'cv' => 'CV',
        'pasfoto' => 'Pas Foto',
        'npwp' => 'NPWP',
        'paklaring' => 'Paklaring',
        'bpjs' => 'Kartu BPJS Ketenagakerjaan',
        'skck' => 'SKCK',
        'sertifikat' => 'Sertifikat',
        'bri' => 'Buku Tabungan Bank BRI',
    ] as $key => $label)
                                <div
                                    class="relative border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50 text-center cursor-pointer">
                                    <input wire:model="{{ $key }}" multiple type="file"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">

                                    @if (${"{$key}_count"} > 0)
                                        <p class="text-gray-600 text-sm">{{ ${"{$key}_count"} }}
                                            {{ files(${"{$key}_count"}) }}</p>
                                    @else
                                        <p class="text-gray-600 text-sm">{{ $label }}</p>
                                    @endif

                                    @error("$key.*")
                                        <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                    </div>
                    {{-- Wire Loading --}}
                    <div wire:loading wire:target='uploadfile'
                        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <p class="mt-3 text-gray-700 text-center">Processing...</p>
                        </div>
                    </div>

                    <button wire:click='uploadfile'
                        class='bg-blue-500 text-white px-3 py-2 rounded my-3  text-center w-full'>Simpan</button>

                    <div class="max-w-md mx-auto bg-white  rounded-2xl ">
                        @if ($is_folder)
                            {{-- <h2 class="text-xl font-semibold text-gray-700 text-center mb-4">Uploaded Files
                            </h2> --}}
                            <div class="space-y-4">
                                @foreach ($personal_files as $key => $fn)
                                    <div
                                        class="p-3 border border-gray-300 rounded-lg bg-gray-50 flex flex-col items-center">
                                        <div class="w-full flex justify-between items-center mb-2">
                                            <h4 class="text-gray-700 text-sm font-semibold">
                                                {{ get_filename($fn->filename) }}</h4>
                                            {{-- <button
                                                class="bg-red-500  text-white py-1 px-3 rounded text-sm font-medium remove-button"
                                                wire:confirm="Yakin mau di delete?"
                                                wire:click='deleteFile("{{ $fn->id }}")'>
                                                <i class="fas fa-trash"></i>
                                            </button> --}}
                                        </div>
                                        <img class="rounded-lg w-full" src="{{ getUrl($fn->filename) }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class='text-center'>Belum ada dokumen</p>
                        @endif
                    </div>
                </div>

                <div class="mt-20"></div>

            </div>

            {{-- Footer --}}
            @include('mobile-footer')
        </div>

    </div>
</div>
