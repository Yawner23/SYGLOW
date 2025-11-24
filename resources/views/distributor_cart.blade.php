@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Cart</h1>
    <h1>Welcome, {{ Auth::user()->distributor->name }}!</h1>
</div>
<div class="flex flex-col justify-between p-8 md:flex-row">
    <div class="flex items-center space-x-8">
        <img class="rounded-full h-[6rem] object-cover w-[6rem]" src="{{ asset('uploads/profile_pictures/' . (Auth::user()->distributor->profile_picture ?? 'default-avatar.jpg')) }}" alt="">
        <div>
            <h1 class="text-[#f590b0] text-4xl">{{ Auth::user()->distributor->name }}</h1>
            <p class="text-xl">{{ Auth::user()->id }}</p>
            <p class="text-xl">{{ Auth::user()->email }}</p>
        </div>
    </div>
    <div class="flex flex-col items-center my-4 space-y-2 md:space-y-0 lg:flex-row">
        <a href="{{url('/distributor_id')}}">
            <div>
                <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-xl w-60 py-2 text-white border-2 border-white"> VIEW ID</button>
            </div>
        </a>
        <a href="{{url('/distributor_list')}}">
            <div>
                <button class="bg-gradient-to-r from-[#f590b0] to to-[#f56e98] rounded-xl w-60 py-2 text-white border-2 border-white"> VIEW DISTRIBUTORS</button>
            </div>
        </a>
    </div>
</div>


<div class="flex justify-center p-8 mb-4 md:justify-between">
    <div>

    </div>
    <div class="flex flex-col items-center space-x-2 space-y-4 md:space-y-0 md:flex-row ">
        <input class="rounded-2xl border-[#f590b0] shadow shadow-[#f590b0]" type="search">
        <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 px-8 text-white border-2 border-white shadow-lg">SEARCH</button>
    </div>
</div>
<div class="p-8">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        @if(session('cart'))
        <table class="w-full text-sm text-left text-white rtl:text-right ">
            <thead class="text-lg text-white bg-gradient-to-t from-[#f590b0] to to-[#f56e98]">
                <tr>
                    <th scope="col" class="px-6 py-3">Product Image</th>
                    <th scope="col" class="px-6 py-3">Item</th>
                    <th scope="col" class="px-6 py-3">Qty</th>
                    <th scope="col" class="px-6 py-3">Total Amount</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('cart') as $id => $details)
                <tr class="text-xl text-gray-900 bg-white border-b">
                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        <img class="bg-[#fdebdd] object-contain p-8 h-[10rem] w-[15rem]"
                            src="{{ asset('images/uploads/product_images/' . $details['image']) }}" alt="">
                    </td>
                    <td class="px-6 py-4">{{ $details['name'] }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" name="action" value="decrease"
                                    class="px-2 py-1 text-gray-700 bg-transparent text-2xl hover:text-[#f56e98]">-</button>
                            </form>
                            <span class="text-[#f56e98]">{{ $details['quantity'] }}</span>
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" name="action" value="increase"
                                    class="px-2 py-1 text-gray-700 bg-transparent text-2xl hover:text-[#f56e98]">+</button>
                            </form>
                        </div>
                    </td>
                    <td class="px-6 py-4">₱{{ $details['price'] * $details['quantity'] }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-4xl text-black"><i class='bx bx-trash'></i></button>
                        </form>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        @else
        <p>Your cart is empty!</p>
        @endif
    </div>
</div>

<div class="flex flex-col justify-center px-4 my-4 md:justify-end md:flex-row">
    <a href="{{url('/distributor_purchase')}}">
        <div>
            <button class="bg-[#fdebdd] rounded-lg w-full md:w-60 py-2 text-black border-2 border-white">ADD PRODUCTS</button>
        </div>
    </a>
    <a href="{{ url('/checkout') }}">
        <div>
            <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-lg w-full md:w-60 py-2 text-white border-2 border-white">CONTINUE CHECKOUT</button>
        </div>
    </a>
</div>
@endsection