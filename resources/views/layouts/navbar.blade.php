<!-- Alpine.js for menu toggling -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.10.5/dist/cdn.min.js" defer></script>

<div class="bg-[#fdebdd]">
    <div class="container flex flex-col justify-between px-4 py-2 mx-auto space-y-2 text-sm md:flex-row md:space-y-0">
        <div>
            <h1>
                USE CODE: SYGLOWEBSITE
            </h1>
        </div>
        <div>
            <h1>
                OFFER UP TO 8% <span class="lg:mx-8">|</span> ON YOUR TOTAL AMOUNT
            </h1>
        </div>
        <div>
            <h1>WEBSITE OPENING</h1>
        </div>
    </div>
</div>

<div class="absolute w-full h-full" x-data="{ isOpen: false }">
    <div class="flex flex-wrap place-items-center ">

        <section class="relative z-10 mx-auto">
            <!-- navbar -->
            <nav class="container flex justify-between w-screen text-black bg-transparent ">
                <div class="flex items-center w-full px-5 py-6 xl:px-12">
                    <a class="text-3xl font-bold font-heading" href="{{url('/')}}">
                        <img src="{{asset('images/logo-header.png')}}" alt="Logo">
                    </a>
                    <!-- Nav Links -->
                    <ul class="hidden px-4 mx-auto space-x-12 font-semibold lg:flex font-heading">
                        <li><a class="hover:text-[#f56e98] {{ request()->is('/') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/')}}">Home</a></li>
                        <li><a class="hover:text-[#f56e98] {{ request()->is('about_us') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/about_us')}}">About Us</a></li>
                        <li><a class="hover:text-[#f56e98] {{ request()->is('products') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/products')}}">Products</a></li>
                        <li><a class="hover:text-[#f56e98] {{ request()->is('be_our_member') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/be_our_member')}}">Be Our Member</a></li>
                        <li><a class="hover:text-[#f56e98] {{ request()->is('blogs') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/blogs')}}">Blogs</a></li>
                        <li><a class="hover:text-[#f56e98] {{ request()->is('contact_us') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/contact_us')}}">Contact Us</a></li>
                    </ul>
                    <!-- Header Icons -->
                    <div class="items-center hidden space-x-5 lg:flex">
                        <div class="relative group">
                            <a class="flex items-center hover:text-gray-200" href="{{url('login')}}">
                                <img class="object-contain w-8 h-8" src="{{asset('images/icon-profile.png')}}" alt="Profile">
                                <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transition-opacity duration-300 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded opacity-0 left-1/2 group-hover:opacity-100">Profile</span>
                            </a>
                        </div>
                        <div class="relative group">
                            <button id="open-modal" class="flex items-center hover:text-gray-200">
                                <img class="object-contain w-8 h-8" src="{{ asset('images/icon-cart.png') }}" alt="Cart">
                                <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transition-opacity duration-300 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded opacity-0 left-1/2 group-hover:opacity-100">Cart</span>
                            </button>
                        </div>
                        <div class="relative group">
                            <a class="hover:text-gray-200" href="{{url('wishlist')}}">
                                <img class="object-contain w-8 h-8" src="{{asset('images/wishlist.png')}}" alt="Wishlist">
                                <span class="absolute top-0 px-2 py-1 text-xs text-gray-500 transition-opacity duration-300 transform -translate-x-1/2 -translate-y-full bg-gray-200 rounded opacity-0 left-1/2 group-hover:opacity-100">Wishlist</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Burger Icon -->
                <button @click="isOpen = !isOpen" class="self-center mr-12 lg:hidden navbar-burger">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </nav>

            <!-- Mobile Menu -->
            <div x-show="isOpen" class="absolute left-0 w-full bg-gradient-to-b from-transparent to-[#f590b0] xl:hidden">
                <ul class="px-4 py-4 space-y-4 font-semibold text-center">
                    <li><a class="block hover:text-[#f56e98] {{ request()->is('/') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/')}}">Home</a></li>
                    <li><a class="block hover:text-[#f56e98] {{ request()->is('about_us') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/about_us')}}">About Us</a></li>
                    <li><a class="block hover:text-[#f56e98] {{ request()->is('products') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/products')}}">Products</a></li>
                    <li><a class="block hover:text-[#f56e98] {{ request()->is('be_our_member') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/be_our_member')}}">Be Our Member</a></li>
                    <li><a class="block hover:text-[#f56e98] {{ request()->is('blogs') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/blogs')}}">Blogs</a></li>
                    <li><a class="block hover:text-[#f56e98] {{ request()->is('contact_us') ? 'text-[#f56e98]' : 'text-gray-900' }}" href="{{url('/contact_us')}}">Contact Us</a></li>

                    <!-- Mobile Icons -->
                    <li class="flex justify-between">
                        <a href="{{url('login')}}" class="flex items-center hover:text-gray-900">
                            <img class="object-contain w-6 h-6 mr-2" src="{{asset('images/icon-profile.png')}}" alt="Profile">Profile
                        </a>
                        <a href="#" class="flex items-center hover:text-gray-900">
                            <img class="object-contain w-6 h-6 mr-2" src="{{asset('images/icon-cart.png')}}" alt="Cart">Cart
                        </a>
                        <a href="{{url('wishlist')}}" class="flex items-center hover:text-gray-900">
                            <img class="object-contain w-6 h-6 mr-2" src="{{asset('images/wishlist.png')}}" alt="Wishlist">Wishlist
                        </a>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</div>

@yield('content')