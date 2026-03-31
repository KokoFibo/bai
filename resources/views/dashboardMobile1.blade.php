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

</div>
@endsection
