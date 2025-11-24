@extends('layouts.sidebar')

@section('title', 'Home')

@section('content')
<div class="flex flex-col justify-between p-8 text-4xl md:flex-row">
    <h1>Applied Distributor</h1>
    <h1>Welcome, {{ Auth::user()->distributor->name }}</h1>
</div>

<div class="grid grid-cols-1 gap-8 p-8 my-4 md:grid-cols-2 lg:grid-cols-4">
    <div class="bg-[#fdebdd] rounded-xl text-center py-8">
        <h1 class="text-xl">Total Applications</h1>
        <p class="text-4xl font-bold">{{ $totalApplications }}</p>
    </div>
    <div class="bg-[#fdebdd] rounded-xl text-center py-8">
        <h1 class="text-xl">Pending</h1>
        <p class="text-4xl font-bold">{{ $pendingCount }}</p>
    </div>
    <div class="bg-[#fdebdd] rounded-xl text-center py-8">
        <h1 class="text-xl">Approved</h1>
        <p class="text-4xl font-bold">{{ $approvedCount }}</p>
    </div>
    <div class="bg-[#fdebdd] rounded-xl text-center py-8">
        <h1 class="text-xl">Rejected</h1>
        <p class="text-4xl font-bold">{{ $rejectedCount }}</p>
    </div>
</div>

<!-- Search Form -->
<div class="flex justify-end px-8">
    <form method="GET" action="{{ url()->current() }}" class="flex flex-col items-center space-x-2 space-y-2 md:flex-row md:space-y-0 ">
        <input name="search" class="rounded-2xl border-[#f590b0] shadow shadow-[#f590b0]" type="search" placeholder="Search..." value="{{ request('search') }}">
        <button class="bg-gradient-to-r from-[#f590b0] to-[#f56e98] rounded-xl w-full py-2 px-8 text-white border-2 border-white shadow-lg">SEARCH</button>
    </form>
</div>

<div class="p-8">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-white rtl:text-right ">
            <thead class="text-lg text-white  bg-gradient-to-t from-[#f590b0] to to-[#f56e98]">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Application Date
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Region
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Status
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($distributors as $distributor)
                <tr class="text-xl text-gray-900 bg-white border-b">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                        {{ $distributor->name}}
                    </th>
                    <td class="px-6 py-4">
                        {{ $distributor->created_at->format('F j, Y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $distributor->city}}
                    </td>
                    <td class="px-6 py-4">
                        @if($distributor->user)
                        {{ $distributor->user->status }}
                        @else
                        N/A
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection