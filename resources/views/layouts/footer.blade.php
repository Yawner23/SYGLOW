@yield('content')
<footer class="container max-w-screen-xl mx-auto">
    <div class="grid grid-cols-1 px-4 py-12 lg:grid-cols-4">
        <div class="flex flex-col items-center justify-center space-y-4 lg:items-start">
            <h1 class="text-xl font-semibold">Our Contacts</h1>
            <ul class="space-y-2 ">
                <li class="flex items-center gap-4">
                    <img src="{{asset('images/phone-call.png')}}" alt="">
                    <p>(+63) 929 838 4106</p>
                </li>
                <li class="flex items-center gap-4">
                    <img src="{{asset('images/email.png')}}" alt="">
                    <p>info@syglow.com</p>
                </li>
                <li class="flex items-center gap-4">
                    <img src="{{asset('images/route.png')}}" alt="">
                    <p>Davao City, Philippines</p>
                </li>
            </ul>
            <h1 class="text-xl font-semibold">Follow Us</h1>
            <div class="flex items-center space-x-4">
                <a href="{{url('https://www.facebook.com/louneria.putian')}}">
                    <img src="{{asset('images/icon-facebook.png')}}" alt="">
                </a>
                <a href="{{url('https://www.instagram.com/syglow_motherlou?igsh=ZTdpcGgxOWRkdmt6&utm_source=qr')}}">
                    <img src="{{asset('images/icon-instagram.png')}}" alt="">
                </a>
                <a href="{{url('https://www.lazada.com.ph/shop/sy-glow-cosmetics')}}">
                    <img src="{{asset('images/icon-lazada.png')}}" alt="">
                </a>
                <a href="{{url('https://www.tiktok.com/@motherlou?_t=8q7Wcme8Qr9&_r=1')}}">
                    <img src="{{asset('images/icon-tiktok.png')}}" alt="">
                </a>
                <a href="{{url('https://shopee.ph/shop/341628580')}}">
                    <img src="{{asset('images/icon-shopee.png')}}" alt="">
                </a>
            </div>
        </div>
        <div class="flex flex-col justify-center col-span-2">
            <div class="space-y-8">
                <div class="space-y-4">
                    <div class="flex justify-center">
                        <img src="{{asset('images/logo-footer.png')}}" alt="">
                    </div>
                    <p class="text-center">SY Glow, a Philippines-based company, believes in enhancing your natural beauty. Their products combine the power of nature with science to deliver targeted solutions for a radient. healthy glow.</p>
                </div>
                <div class="flex justify-center ">
                    <ul class="flex items-center mb-8">
                        <li><a href="{{url('/')}}">Home</a></li>
                        <li class=" md:px-6">|</li>
                        <li><a href="{{url('/about_us')}}">About Us</a></li>
                        <li class=" md:px-6">|</li>
                        <li><a href="{{url('/products')}}">Products</a></li>
                        <li class=" md:px-6">|</li>
                        <li><a href="{{url('/be_our_member')}}">Be Our Member</a></li>
                    </ul>
                </div>
            </div>

        </div>
        <div class="flex flex-col justify-center">
            <h1 class="text-xl text-center">Become A Member</h1>
            <div class="flex justify-center">
                <a href="{{url('/be_our_member_distributors')}}">
                    <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-lg px-12 py-2 text-white">SIGN UP NOW</button>
                </a>
            </div>
        </div>
    </div>
    <div class="flex flex-col items-center justify-between px-4 my-4 space-y-4 text-center md:flex-row md:space-y-0">
        <h1>Copyright © SY Glow 2024 Designed & Developed by <span><a href="{{url('https://rwebsolutions.com.ph/')}}">R Web Solutions Corp.</a></span> </h1>
        <h1>Privacy Policy | Terms and Conditions</h1>
    </div>
</footer>