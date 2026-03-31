 <!-- Main Sidebar Container -->

 <aside class="main-sidebar sidebar-dark-primary elevation-4 nightowl-daylight" style="background-color: black">
     <!-- Brand Logo -->
     {{-- <a href="index3.html" class="brand-link text-center "> --}}

     <a href="/" class="brand-link nightowl-daylight">
         <img src="{{ asset('images/logo-only.png') }}" width="60px" alt="Yifang Logo" style="opacity: .8">
         {{-- class="brand-image img-circle elevation-3" --}}
         {{-- <span class="brand-text font-weight-light">Yifang CME</span> --}}
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">

             <div class="image nightowl-daylight">
                 {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
                 <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&length=1"
                     class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">

                 {{-- <a href="#" class="d-block">{{ Auth::user()->name }}</a> --}}
                 <a href="#" class="d-block">{{ namaDiAside(Auth::user()->name) }}</a>
             </div>


         </div>

         @include('layouts.menu')
     </div>
     <!-- /.sidebar -->
 </aside>
