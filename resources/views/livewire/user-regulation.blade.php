<div>
    <div>
        <div class="flex flex-col h-screen">
            <div class=header>
                {{-- taruh disini --}}
                @include('mobile-header')
                {{-- taruh disini --}}
                {{-- <div class="py-2"> --}}
                <div>
                    <h2 class="bg-gray-600 text-center text-white text-xl py-2 px-5 mt-3">Jam Kerja &
                        Keterlambatan
                    </h2>
                </div>
                {{-- </div> --}}

            </div>
            <div class="main  flex-1  overflow-y-auto text-gray-500 ">
                <div class="w-screen flex px-3 mt-5   flex-col gap-5 ">

                    <h2 class="font-semibold mt-4 mb-2">Ketentuan jam kerja:</h2>
                    <ul class="text-sm">
                        <li class="mb-2">Shift Pagi:
                            <ul class="list-disc ml-4">
                                <li>Jam kerja: 08:00 - 17:00</li>
                                <li>Jam Lembur: 18:00 - (mulai masuk setelah 17:30)</li>
                            </ul>
                        </li>
                        <li class="mb-2">Hari Sabtu:
                            <ul class="list-disc ml-4">
                                <li>Jam Kerja: 08:00 - 15:00</li>
                            </ul>
                        </li>
                        <li class="mb-2">Istirahat Shift 1: 11:30 - 12:30 (batas waktu keluar
                            mulai dari jam 11:30 - 11:59)</li>
                        <li class="mb-2">Istirahat Shift 2: 12:00 - 13:00 (Batas waktu keluar
                            mulai dari jam 12:00)</li>
                    </ul>

                    <ul class="mt-4 text-sm">
                        <li class="mb-2">Shift Malam:
                            <ul class="list-disc ml-4">
                                <li>Jam kerja: 20:00 - 05:00</li>
                                <li>Jam Lembur: Mulai dari jam 05:00</li>
                            </ul>
                        </li>
                        <li class="mb-2">Hari Sabtu:
                            <ul class="list-disc ml-4">
                                <li>Jam Kerja: 17:00 - 00:00</li>
                            </ul>
                        </li>
                        <li class="mb-2 text-blue-500">Istirahat 00:00 - 01:00 (batas waktu keluar mulai dari jam
                            00:00)
                        </li>
                    </ul>

                    <h2 class="font-semibold mt-10 mb-2 ">Perhitungan keterlambatan:</h2>
                    <p class="mb-2 text-sm">
                        Untuk karyawan pabrik saat bekerja pada jam kerja biasa, setiap
                        keterlambatan lebih dari 3 menit akan dikenakan potongan 1 jam.
                    </p>
                    <p class="mb-2 text-sm">Contoh:</p>
                    <p class="text-sm">Jika masuk kerja pada jam 08:04 atau lebih, maka jam kerja akan dihitung
                        mulai
                        jam
                        09:00. Jika masuk kerja sebelum jam 08:03, maka jam kerja akan dihitung mulai jam 08:00.</p>

                    <hr class="my-3">
                    <p class="mb-2 text-sm">Untuk jam lembur, dihitung dengan pembulatan 30 menit dengan toleransi 3
                        menit.</p>

                    <p class="mb-2 text-sm">Contoh:</p>
                    <p class="text-sm">Jika masuk lembur pada jam 18:04 atau lebih, maka lembur akan dihitung mulai
                        jam
                        18:30. Jika masuk lembur sebelum jam 18:03, maka lembur akan dihitung mulai jam 18:00.</p>

                    <h5 class="text-sm text-blue-500 mt-4">* Silakan informasikan kebagian terkait jika anda masuk
                        diluar dari jam yang disebutkan di atas</h5>
                </div>
            </div>
            <div class="mt-20"></div>

        </div>

        {{-- Footer --}}
        @include('mobile-footer')
    </div>
</div>
