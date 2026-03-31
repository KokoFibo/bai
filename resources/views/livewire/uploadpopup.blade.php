<div>
    @if ($showPopup)
        <div class="fixed inset-0 flex items-center justify-center bg-black/50 p-4 z-50">
            <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm text-center relative overflow-hidden">
                <!-- Background Decorative -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0"></div>

                <!-- Tombol Close -->
                <button wire:click="closePopup" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">
                    &times;
                </button>

                <!-- Card Image -->
                <div class="w-full flex justify-center">
                    <div class="  overflow-hidden  rounded-lg">
                        <img src="{{ asset('images/ktp.png') }}" alt="Dokumen" class="w-full h-full object-cover">
                    </div>
                </div>

                <!-- Judul -->
                <h2 class="text-xl font-semibold text-gray-800 mt-4 relative z-10">Upload Dokumen Anda</h2>
                <p class="text-gray-600 mt-2 text-sm relative z-10">
                    Untuk meningkatkan keamanan dan kemudahan, mohon untuk segera mengupload dokumen anda.
                </p>

                <!-- Tombol -->
                <div class="mt-6 flex flex-col sm:flex-row gap-3 relative z-10">
                    <button wire:click="closePopup"
                        class="w-full sm:w-auto flex-1 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition">
                        Nanti Saja
                    </button>

                    <a href="/adddocument" class="w-full sm:w-auto flex-1">
                        <button
                            class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition">
                            Upload Sekarang
                        </button>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
