<div>
    {{-- @if (auth()->user()->role != 8) --}}
    @if (auth()->user()->role == 9)
        <div class="text-center mt-5">
            <h1>COMING SOON</h1>
            <h4>Under Construction</h4>
        </div>
    @else
        @if (!$is_add && !$is_update)
            <h4 class='bg-blue-500 text-white text-lg text-center font-semibold py-3'>Form Ijin/Cuti</h4>
            <div class='pl-3 pt-2 text-center'>
                <button wire:click='add' class='bg-indigo-500 text-white px-3 py-1 rounded-lg text-sm'>Buat Permohonan
                    Ijin/Cuti</button>
            </div>
            @if (count($data) > 0)
                @foreach ($data as $key => $d)
                    <div
                        class="{{ $d->status == 'Done' ? 'bg-green-700' : 'bg-blue-500' }}  text-white m-3 p-3 rounded-xl">
                        <div class='flex gap-5'>
                            @if ($d->status == 'Done')
                                <h5>Permohonan disetujui tanggal {{ format_tgl($d->done_date) }}</h5>
                            @else
                                <ul>
                                    <li>Tanggal Request</li>
                                    <li>Nama Pemohon</li>
                                    <li>Tipe Request</li>
                                    <li>Dari Tanggal</li>
                                    <li>Sampai Tanggal</li>
                                    <li>Keterangan</li>
                                    <li>Status</li>
                                    <li></li>
                                </ul>
                                <ul>
                                    <div>
                                        <li>{{ format_tgl($d->tanggal) }}</li>
                                        <li>{{ $d->karyawan->nama }}</li>
                                        <li>{{ $d->request_type }}</li>
                                        <li>{{ format_tgl($d->start_date) }}</li>
                                        <li>{{ format_tgl($d->end_date) }}</li>
                                        <li>{{ $d->description }}</li>
                                        @if ($d->status == 'Done')
                                            <li>{{ $d->status }} {{ format_tgl($d->done_date) }}</li>
                                        @else
                                            {{-- <li>{{ $d->status }} {{ format_tgl($d->done_date) }}</li> --}}
                                            <li>{{ $d->status }}</li>
                                        @endif
                                    </div>
                                </ul>
                            @endif

                        </div>
                        @if ($d->status != 'Done')
                            <hr class="h-px my-4 bg-gray-200 border-0 dark:bg-gray-700">
                            <div class='text-center'>
                                @if ($show_image && $d->id == $selected_id)
                                    <button wire:click='show_image_toggle({{ $d->id }})'
                                        class='bg-black text-white px-3 py-1 rounded-lg text-sm'>Sembunyikan
                                        File</button>
                                @else
                                    <button wire:click='show_image_toggle({{ $d->id }})'
                                        class='bg-purple-500 text-white px-3 py-1 rounded-lg text-sm'>Lihat
                                        File</button>
                                @endif
                                @if ($d->status != 'Done' && $d->status != 'Approved' && $d->status != 'Confirmed')
                                    <button wire:click='edit({{ $d->id }})'
                                        class='bg-orange-500 text-white px-3 py-1 rounded-lg text-sm'>Edit</button>
                                    <button wire:click='confirm_delete({{ $d->id }})'
                                        class='bg-red-500 text-white px-3 py-1 rounded-lg text-sm'>Delete</button>
                                @endif
                            </div>
                        @endif
                        @if ($show_image && $d->id == $selected_id)
                            @if ($filenames)
                                @foreach ($filenames as $fn)
                                    <div class="lg:my-5 py-2 w-full lg:w-1/2 ">
                                        <div class="d-flex justify-content-between px-1 pb-2">
                                            <div>
                                                <div>
                                                    <button class="bg-red-500 py-0 px-3 text-white rounded text-sm"
                                                        wire:click="deleteFile('{{ $fn->id }}')"
                                                        wire:loading.remove>Hapus</button>
                                                </div>
                                                <p class="text-sm py-1 lg:text-xl font-medium lg:font-bold">
                                                    {{ $fn->originalName }}
                                                </p>
                                            </div>

                                            <div role="status" wire:loading wire:target='deleteFile'>
                                                <svg aria-hidden="true"
                                                    class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                                    viewBox="0 0 100 101" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                                        fill="currentColor" />
                                                    <path
                                                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                                        fill="currentFill" />
                                                </svg>
                                            </div>
                                        </div>
                                        @if (strtolower(getFilenameExtension($fn->originalName)) != 'pdf')
                                            <img class="w-full rounded-xl" src="{{ getUrl($fn->filename) }}"
                                                alt="">
                                        @else
                                            <iframe class="w-full rounded-xl" src="{{ getUrl($fn->filename) }}"
                                                width="100%" height="600px"></iframe>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        @endif
                    </div>
                @endforeach
            @else
                <h4 class='mt-5 text-center text-xl font-bold'>Belum Ada Permintaan Time Off</h4>
            @endif
        @endif
        @if ($is_add || $is_update)
            <div class="p-4">
                <h2 class="text-xl font-bold mb-4">Request Time Off Form</h2>
                {{-- <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4"> --}}
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4">
                        <label for="requester_name" class="block text-gray-700 text-sm font-bold mb-2">Nama
                            Pemohon</label>
                        <input id="requester_name" name="requester_name" type="text" wire:model='name' disabled
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="request_type" class="block text-gray-700 text-sm font-bold mb-2">Tipe
                            Izin/Cuti</label>
                        <select id="request_type" name="request_type" wire:model='request_type'
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Silakan pilih</option>
                            <option value="Cuti">Cuti</option>
                            <option value="Ijin/Sakit">Ijin/Sakit</option>
                            <option value="Izin Datang Telat">Izin Datang Telat</option>
                        </select>
                        @error('request_type')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                    <div class="mb-4">
                        <label for="start_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                        <input id="start_date" name="start_date" type="date" wire:model='start_date'
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('start_date')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="end_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal
                            Berakhir</label>
                        <input id="end_date" name="end_date" type="date" wire:model='end_date'
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('end_date')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Keterangan</label>
                        <textarea id="description" name="description" rows="4" wire:model='description'
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Enter description"></textarea>
                        @error('description')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="file_upload" class="block text-gray-700 text-sm font-bold mb-2">Upload Surat
                            Keterangan</label>
                        <input id="file_upload" name="files" type="file" wire:model='files' multiple
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        @error('files.*')
                            <div class="text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between">
                        @if ($is_add)
                            <button wire:click='save'
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Submit
                            </button>
                        @endif
                        @if ($is_update)
                            <button wire:click='update'
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Update
                            </button>
                        @endif
                        <button wire:click='exit'
                            class="bg-black hover:bg-black text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Exit
                        </button>


                    </div>
                </div>
            </div>
        @endif
    @endif

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
            <a href="cutirequest"><button
                    class="{{ 'cutirequest' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
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
    @script
        <script>
            window.addEventListener("delete_confirmation", (event) => {
                Swal.fire({
                    title: event.detail.title,
                    // text: "You won't be able to revert this!",
                    text: event.detail.text,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, delete",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch("delete-confirmed");
                    }
                });
            });
        </script>
    @endscript

</div>
