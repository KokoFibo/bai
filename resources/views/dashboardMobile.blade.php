@extends('layouts.mobile')
@section('contents')

<div class="bg-gray-300 font-sans">
      <header class="bg-gray-800 text-white py-2 text-center sticky top-0 z-50">
            <img src="{{ asset('images/Yifang-transparant-logo.png') }}" alt="Yifang Logo" class="w-32 h-20 opacity-80">
      </header>

      <div class="dashboard p-4 m-4 bg-white rounded shadow-md">
            <div class="dashboard-item border-b-2 border-gray-300 py-4 text-center">
                  <i class="fas fa-chart-bar text-red-600 text-3xl"></i>
                  <span class="font-bold text-lg mt-2">Analytics</span>
            </div>

            <div class="dashboard-item border-b-2 border-gray-300 py-4 text-center">
                  <i class="fas fa-envelope text-red-600 text-3xl"></i>
                  <span class="font-bold text-lg mt-2">Messages</span>
            </div>

            <div class="dashboard-item border-b-2 border-gray-300 py-4 text-center">
                  <i class="fas fa-cog text-red-600 text-3xl"></i>
                  <span class="font-bold text-lg mt-2">Settings</span>
            </div>
      </div>

      <div class="icon-menu fixed bottom-0 bg-red-600 w-full flex justify-around items-center z-50">
            <a href="#"><i class="fas fa-home text-white text-2xl"></i></a>
            <a href="#"><i class="fas fa-search text-white text-2xl"></i></a>
            <a href="#"><i class="fas fa-plus text-white text-2xl"></i></a>
            <a href="#"><i class="fas fa-bell text-white text-2xl"></i></a>
            <a href="#"><i class="fas fa-user text-white text-2xl"></i></a>
      </div>
      <h3 class="text-xl p-3">Presensi karyawan</h3>
      <div class="relative overflow-x-auto p-3">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 rounded">
                  <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                              <th scope="col" class="px-6 py-3">Tanggal</th>
                              <th scope="col" class="px-6 py-3">Jam Kerja</th>
                              <th scope="col" class="px-6 py-3">Terlambat Kerja</th>
                              <th scope="col" class="px-6 py-3">Jam Lembur</th>
                              <th scope="col" class="px-6 py-3">Terlambat Lembur</th>
                              <th scope="col" class="px-6 py-3"></th>
                        </tr>
                  </thead>
                  <tbody>
                        @foreach ($data as $d )
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                              <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ format_tgl($d->date) }}</td>
                              <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ 8-late_check_jam_kerja_only ($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date) }} Jam</td>
                              <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ late_check_jam_kerja_only ($d->first_in, $d->first_out, $d->second_in, $d->second_out, $d->shift, $d->date) }} Jam</td>
                              <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ hitungLembur($d->overtime_in, $d->overtime_out)/60 }} Jam</td>
                              <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ checkOvertimeInLate ($d->overtime_in, $d->shift, $d->date) * 30/60}} Jam</td>
                              <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><button class="bg-teal-500 text-white py-2 px-3 rounded">Detail</button></td>

                        </tr>
                        @endforeach
                  </tbody>
            </table>
      </div>
</div>
@endsection
