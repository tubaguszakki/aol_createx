@extends('layouts.dashboard')

@section('title', 'My Bookings - Room Booking Platform')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 min-h-screen p-6 fixed">
            <!-- Logo -->
            <div class="flex items-center mb-8">
                <img src="{{ asset('images/logo_white.png') }}" alt="Createx" class="h-8 w-auto mr-3">
                <span class="text-xl font-bold text-white">Createx</span>
            </div>
            
            <!-- User Info -->
            <div class="mb-8 p-4 bg-gray-800 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center mr-3">
                        <span class="text-white font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="space-y-2 mb-8">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Home
                </a>
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0H8v0z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('dashboard.bookings') }}" class="flex items-center px-4 py-3 text-white bg-gray-800 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    My Bookings
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-6 left-6 right-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors duration-200 w-full">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64 p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
                <p class="text-gray-600 mt-2">Manage your room reservations</p>
            </div>

            <!-- Filter Tabs -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8" x-data="{ activeFilter: 'all' }">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6">
                        <button @click="activeFilter = 'all'" 
                                :class="{ 'border-red-500 text-red-600': activeFilter === 'all', 'border-transparent text-gray-500 hover:text-gray-700': activeFilter !== 'all' }"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            All Bookings
                        </button>
                        <button @click="activeFilter = 'upcoming'" 
                                :class="{ 'border-red-500 text-red-600': activeFilter === 'upcoming', 'border-transparent text-gray-500 hover:text-gray-700': activeFilter !== 'upcoming' }"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Upcoming
                        </button>
                        <button @click="activeFilter = 'completed'" 
                                :class="{ 'border-red-500 text-red-600': activeFilter === 'completed', 'border-transparent text-gray-500 hover:text-gray-700': activeFilter !== 'completed' }"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Completed
                        </button>
                        <button @click="activeFilter = 'canceled'" 
                                :class="{ 'border-red-500 text-red-600': activeFilter === 'canceled', 'border-transparent text-gray-500 hover:text-gray-700': activeFilter !== 'canceled' }"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Canceled
                        </button>
                    </nav>
                </div>

                <!-- Bookings List -->
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($bookings as $booking)
                            <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow duration-200"
                                 x-show="activeFilter === 'all' || 
                                        (activeFilter === 'upcoming' && '{{ $booking->start_time > now() && $booking->status !== 'canceled' ? 'true' : 'false' }}' === 'true') ||
                                        (activeFilter === 'completed' && '{{ $booking->end_time < now() && $booking->status === 'confirmed' ? 'true' : 'false' }}' === 'true') ||
                                        (activeFilter === 'canceled' && '{{ $booking->status === 'canceled' ? 'true' : 'false' }}' === 'true')">
                                
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start space-x-4">
                                        <!-- Room Icon -->
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                @if($booking->room->type === 'studio')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                @elseif($booking->room->type === 'live streaming')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                @elseif($booking->room->type === 'editing room')
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                @endif
                                            </svg>
                                        </div>

                                        <!-- Booking Info -->
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <h3 class="text-xl font-bold text-gray-900">{{ $booking->room->name }}</h3>
                                                <div class="flex items-center space-x-2">
                                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Date & Time -->
                                            <div class="space-y-2 mb-4">
                                                <p class="text-gray-700">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <strong>Date & Time:</strong> {{ $booking->start_time->format('M d, Y g:i A') }} - {{ $booking->end_time->format('g:i A') }}
                                                </p>
                                                <p class="text-gray-700">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <strong>Duration:</strong> {{ $booking->duration_hours }} hour(s)
                                                </p>
                                                <p class="text-gray-700">
                                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    <strong>Room Type:</strong> {{ ucwords($booking->room->type) }}
                                                </p>
                                            </div>

                                            <!-- Room PIN -->
                                            @if($booking->room_pin && $booking->payment_status === 'paid')
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                                    <div class="flex items-center">
                                                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 12H9v4a6 6 0 01-6-6V9a6 6 0 016-6h2l2.257-2.257A6 6 0 0115 7z"></path>
                                                        </svg>
                                                        <span class="text-yellow-800 font-medium">🔑 Access PIN: </span>
                                                        <span class="font-mono text-lg font-bold text-yellow-900 bg-yellow-100 px-2 py-1 rounded ml-2">{{ $booking->room_pin }}</span>
                                                    </div>
                                                </div>
                                            @elseif($booking->payment_status === 'unpaid')
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                                    <p class="text-red-800 text-sm">
                                                        <strong>Room PIN:</strong> Available after payment
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Order ID -->
                                            <p class="text-gray-500 text-sm mb-4">
                                                <strong>Order ID:</strong> {{ $booking->midtrans_order_id }}
                                            </p>

                                            <!-- Add-ons -->
                                            @if($booking->bookingAddons->count() > 0)
                                                <div class="mb-4">
                                                    <p class="text-gray-700 font-semibold mb-2">Add-ons:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($booking->bookingAddons as $bookingAddon)
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ $bookingAddon->addon->name }} ({{ $bookingAddon->quantity }}x)
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Total & Payment Status -->
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="text-2xl font-bold text-green-600">Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                                    <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full
                                                        @if($booking->payment_status === 'paid') bg-green-100 text-green-700
                                                        @elseif($booking->payment_status === 'pending') bg-yellow-100 text-yellow-700
                                                        @else bg-red-100 text-red-700
                                                        @endif">
                                                        Payment: {{ ucfirst($booking->payment_status) }}
                                                    </span>
                                                </div>
                                                <p class="text-gray-500 text-sm">
                                                    Booked: {{ $booking->created_at->format('M d, Y g:i A') }}
                                                </p>
                                            </div>

                                            <!-- Action Button -->
                                            @if($booking->canBePaid())
                                            <div class="mt-4">
                                                <p class="text-orange-600 font-medium text-sm mb-2">
                                                    💳 Complete payment to get room PIN
                                                </p>
                                                <a href="{{ route('booking.pay', $booking->id) }}" 
                                                class="inline-flex items-center px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 text-sm font-medium">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-3a2 2 0 00-2-2H9a2 2 0 00-2 2v3a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                    </svg>
                                                    Complete Payment
                                                </a>
                                            </div>
                                            @elseif($booking->isExpired())
                                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-red-800 text-sm font-medium">
                                                    ⏰ Payment has expired. Please create a new booking.
                                                </p>
                                            </div>
                                            @elseif($booking->payment_status === 'paid')
                                            <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                <p class="text-green-800 text-sm font-medium">
                                                    ✅ Payment completed successfully!
                                                </p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No bookings found</h3>
                                <p class="text-gray-500 mb-4">You haven't made any room bookings yet.</p>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Book Your First Room
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($bookings->hasPages())
                        <div class="mt-8">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection