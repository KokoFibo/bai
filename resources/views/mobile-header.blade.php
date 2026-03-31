 <div class="w-screen bg-gray-800 h-24 shadow-xl rounded-b-3xl   ">
     <div class="flex justify-between items-center">
         <div>
             <img src="{{ asset('images/logo-only.png') }}" class="ml-3"alt="Yifang Logo"
                 style="opacity: .8; width: 50px">
         </div>


         <div class="flex flex-col p-3 gap-5 items-end">
             <div>
                 <a href="{{ route('logout') }}"
                     onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                     <button class="rounded-xl shadow bg-purple-500  text-white px-3 py-1"><i
                             class="fa-solid fa-power-off"></i></button>
                 </a>
                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                     @csrf
                 </form>
             </div>
             {{-- @if (auth()->user()->role < 4)
                 <div>
                     <a href="{{ route('logout') }}"
                         onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

                         <button class="rounded-xl shadow bg-purple-500 text-sm text-white px-3 py-1">Logout</button>
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
             @endif --}}

             <div>
                 <h1 class="text-white text-sm">Hello, {{ auth()->user()->name }}</h1>
             </div>

         </div>
     </div>
 </div>
