<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') | SY Glow: Achieve a Flawless, Radiant Glow with Our Philippine Skincare Products"</title>
    <meta name="description" content="Discover the secret to glowing skin with SY Glow. Our Philippine-made skincare products are formulated with natural ingredients to nourish and brighten your complexion. Experience the SY Glow difference today!">
    <meta name="keywords" content="SY Glow, Philippine skincare, skincare products, glowy skin, radiant skin, skincare routine, natural skincare, skin brightening, anti-aging, beauty products, Philippine beauty">

    <!-- Open Graph Meta Tags -->
    <meta property="og:image" content="{{ asset('images/metas/meta-thumbnail.jpg') }}" />
    <meta property="og:thumbnail" content="{{ asset('images/metas/meta-thumbnail.jpg') }}" />
    <meta property="og:image:alt" content="{{ asset('images/metas/meta-thumbnail.jpg') }}">
    <meta property="og:title" content="SY Glow: Achieve a Flawless, Radiant Glow with Our Philippine Skincare Products">
    <meta property=" og:description" content="Discover the secret to glowing skin with SY Glow. Our Philippine-made skincare products are formulated with natural ingredients to nourish and brighten your complexion. Experience the SY Glow difference today!">

    <!-- Favicon and Icons -->
    <link rel="icon" href="{{asset('images/metas/favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/metas/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/metas/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/metas/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('images/metas/android-chrome-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{asset('images/metas/android-chrome-512x512.png')}}">
    <link rel="manifest" href="{{asset('images/metas/site.webmanifest')}}">



    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Belleza&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Prata&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

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

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.10.2/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" />

    <style>
        .swiper-wrapper {
            width: 100%;
            height: max-content !important;
            padding-bottom: 64px !important;
            -webkit-transition-timing-function: linear !important;
            transition-timing-function: linear !important;
            position: relative;
        }
    </style>
    @vite('resources/css/app.css')
</head>

<body class="belleza">
    <script>
        AOS.init();
    </script>
    <script>
        window.routeUrls = {
            saveCart: '{{ route("cart.save") }}',
            placeOrder: '{{ route("checkout.page") }}'
        };
    </script>
    @include('layouts.modal')
    <script src="{{ asset('js/modal.js') }}"></script>
    @yield('content')
</body>

</html>