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
         <a href="timeoff"><button
                 class="{{ 'timeoff' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
                     class="fa-brands fa-wpforms"></i>
             </button></a>
     @else
         {{-- href="/userinformation" --}}
         <a wire:navigate href="userinformation"><button
                 class="{{ 'userinformation' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl "><i
                     class="fa-solid fa-circle-info"></i>
             </button></a>
     @endif
     {{-- @if (auth()->user()->role >= 5) --}}
     <a wire:navigate href="adddocument"><button
             class="{{ 'adddocument' == request()->path() ? 'bg-red-500 ' : '' }} text-purple-200 px-4 py-4 rounded  text-2xl"><i
                 class="fa-solid fa-folder-plus"></i>
         </button></a>
     {{-- @endif --}}
     {{-- <div>
         <a href="{{ route('logout') }}"
             onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">

             <button class="text-purple-200 px-4 py-4 rounded text-2xl "><i class="fa-solid fa-power-off"></i></button>
         </a>
         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
             @csrf
         </form>
     </div> --}}


 </div>
