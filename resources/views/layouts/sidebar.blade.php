<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> @yield('title') | S.Y. Glow</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Prata&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        .belleza {
            font-family: "Belleza", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .urbanist {
            font-family: "Urbanist", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .prata {
            font-family: "Prata", serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

    @vite('resources/css/app.css')
</head>

<body class="belleza">
    <!-- component -->
    <div class="flex-col w-full md:flex md:flex-row md:min-h-screen">
        <div @click.away="open = false" class="relative flex flex-col flex-shrink-0 w-full text-gray-700 bg-gradient-to-t from-white via-[#f590b0] to-[#f590b0] md:w-64 " x-data="{ open: false }">
            <div class="flex flex-row items-center justify-between flex-shrink-0 px-8 py-4">
                <a href="{{url('/')}}" class=""><img src="{{asset('images/logo-sidebar.png')}}" alt=""></a>
                <button class="text-white rounded-lg md:hidden" @click="open = !open">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                        <path x-show="!open" fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        <path x-show="open" fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
            <nav :class="{'block': open, 'hidden': !open}" class="flex-grow px-4 pb-4 md:block md:pb-0 md:overflow-y-auto">

                @if(Auth::user()->hasPermission('distributor'))
                <div class="flex flex-col items-center justify-center text-white">
                    <img class="w-[5rem] h-[5rem] shrink-0 inline-block rounded-full object-cover " src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="avatar image">
                    <div class="flex flex-col my-4 ">
                        <h1 class="text-2xl font-bold">{{ optional(Auth::user()->distributor)->name ?? Auth::user()->name }}</h1>

                        <p class="font-bold text-md ">{{Auth::user()->id}}</p>
                    </div>
                </div>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/')}}">Home</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/distributor_profile')}}">Profile</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/distributor_purchase')}}">Purchase</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/customer_edit_profile')}}">Delivery Address</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/distributor_ordered_items' , Auth::user()->id)}}">Ordered Items</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/distributor_applied_distributor')}}">Applied Distributor</a>
                @endif
                @if(Auth::user()->hasPermission('customer'))
                <div class="flex flex-col items-center justify-center text-white">
                    <img class="w-[5rem] h-[5rem] shrink-0 inline-block rounded-full object-cover " src="{{ asset('uploads/profile_pictures/' . (Auth::user()->customer->profile_picture ?? 'default-avatar.jpg')) }}" alt="avatar image">
                    <div class="flex flex-col my-4 ">
                        <h1 class="text-2xl font-bold ">{{ Auth::user()->customer->first_name ?? '' }} {{ Auth::user()->customer->last_name ?? '' }}</h1>
                        <p class="font-bold text-md ">{{Auth::user()->id}}</p>
                    </div>
                </div>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/')}}">Home</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/customer_profile')}}">Profile</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/customer_edit_profile')}}">Delivery Address</a>
                <a class="block px-4 py-2 mt-2 text-white text-md " href="{{url('/customer_ordered_items',  Auth::user()->id)}}">Ordered Items</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="block px-4 py-2 mt-2 text-white text-md " href="{{route('logout')}}" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                </form>
            </nav>
            <img class="absolute hidden md:block right-0 z-0 w-[1.5rem] h-full -mr-5" src="{{asset('images/Golden-Long-Line.png')}}" alt="">
        </div>
        <div class="container w-full max-w-screen-xl mx-auto">
            @yield('content')
        </div>
    </div>
    <script>
        AOS.init();
    </script>

</body>

</html>