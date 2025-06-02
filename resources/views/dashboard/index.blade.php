@extends('layouts.dashboard')

@section('title', 'Dashboard - Room Booking Platform')

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
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-white bg-gray-800 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0H8v0z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('dashboard.bookings') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors duration-200">
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
                <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-600 mt-2">Welcome back! Manage your bookings and explore our rooms.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Upcoming Bookings -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-600">Upcoming Bookings</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $bookings->where('start_time', '>', now())->where('status', '!=', 'canceled')->count() }}</p>
                        @php
                            $nextBooking = $bookings->where('start_time', '>', now())->where('status', '!=', 'canceled')->first();
                        @endphp
                        @if($nextBooking)
                            <p class="text-sm text-gray-500">Next: {{ $nextBooking->room->name }}, {{ $nextBooking->start_time->format('M d g:i A') }}</p>
                        @else
                            <p class="text-sm text-gray-500">No upcoming bookings</p>
                        @endif
                    </div>
                </div>

                <!-- Reward Points -->
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                    <div class="mb-4">
                        <h3 class="text-sm font-medium text-gray-600">Reward Points</h3>
                        <p class="text-3xl font-bold text-gray-900">{{ $bookings->where('payment_status', 'paid')->count() * 50 }} pts</p>
                        <p class="text-sm text-gray-500">{{ 1000 - ($bookings->where('payment_status', 'paid')->count() * 50) }} pts until next reward</p>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8" x-data="{ activeTab: 'overview' }">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6">
                        <button @click="activeTab = 'overview'" 
                                :class="{ 'border-red-500 text-red-600': activeTab === 'overview', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'overview' }"
                                class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200">
                            Overview
                        </button>
                        
                        <a href="{{ route('dashboard.bookings') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium text-sm transition-colors duration-200">
                            History
                        </a>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'">
                        <!-- Booking Form -->
                        <div class="mb-8" x-data="bookingForm()">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Book a Room</h2>
                            <p class="text-gray-600 mb-6">Select your preferred room and time slot</p>
                            
                            <form method="POST" action="{{ route('booking.store') }}" @submit="validateBeforeSubmit">
                                @csrf
                                <input type="hidden" name="duration_hours" value="">
                                <input type="hidden" name="base_price" value="">
                                <input type="hidden" name="total_price" value="">
                                
                                <!-- Room Selection -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Select Room Type</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        @foreach($rooms->groupBy('type') as $type => $roomsOfType)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="room_type" value="{{ $type }}" x-model="selectedRoomType" @change="updateRooms()" class="sr-only">
                                                <div class="group border border-gray-200 rounded-xl p-4 hover:border-red-200 hover:shadow-md transition-all duration-200"
                                                     :class="{ 'border-red-500 bg-red-50': selectedRoomType === '{{ $type }}' }">
                                                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-3"
                                                         :class="{ 'bg-red-200': selectedRoomType === '{{ $type }}' }">
                                                        @if($type === 'studio')
                                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                        @elseif($type === 'live streaming')
                                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                        @elseif($type === 'editing room')
                                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                    <h3 class="font-semibold text-gray-900 text-sm">{{ ucwords($type) }}</h3>
                                                    <p class="text-xs text-gray-500 mt-1">{{ $roomsOfType->count() }} available</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Specific Room Selection -->
                                <div class="mb-6" x-show="selectedRoomType" style="display: none;">
                                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-3">Select Specific Room</label>
                                    <select name="room_id" id="room_id" x-model="selectedRoom" @change="updateAddons(); checkAvailability()" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                                        <option value="">Choose a room</option>
                                        <template x-for="room in filteredRooms" :key="room.id">
                                            <option :value="room.id" x-text="`${room.name} - Rp ${room.hourly_rate.toLocaleString()}/hour (PIN: ${room.pin})`"></option>
                                        </template>
                                    </select>
                                </div>
                                
                                <!-- Date and Time -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" x-show="selectedRoom" style="display: none;">
                                    <div>
                                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                        <input type="datetime-local" name="start_time" id="start_time" x-model="startTime" 
                                               @change="calculatePrice(); checkAvailability()" required
                                               :min="new Date().toISOString().slice(0, 16)"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                                    </div>
                                    
                                    <div>
                                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                                        <input type="datetime-local" name="end_time" id="end_time" x-model="endTime" 
                                               @change="calculatePrice(); checkAvailability()" required
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                                    </div>
                                </div>

                                <!-- Availability Warning -->
                                <div x-show="!isAvailable && startTime && endTime" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-md" style="display: none;">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-sm text-red-800">This room is not available for the selected time slot. Please choose a different time.</p>
                                    </div>
                                </div>
                                
                                <!-- Add-ons -->
                                <div class="mb-6" x-show="availableAddons.length > 0" style="display: none;">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Add-ons (Optional)</label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <template x-for="addon in availableAddons" :key="addon.id">
                                            <label class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-red-200 cursor-pointer">
                                                <div class="flex items-center">
                                                    <input type="checkbox" :value="addon.id" name="addons[]" @change="calculatePrice()"
                                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500 mr-3">
                                                    <span class="text-sm font-medium text-gray-700" x-text="addon.name"></span>
                                                </div>
                                                <span class="text-sm font-medium text-green-600" x-text="'+ Rp ' + addon.price.toLocaleString()"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Price Summary -->
                                <div class="mb-6 p-4 bg-gray-50 rounded-lg" x-show="totalPrice > 0" style="display: none;">
                                    <h4 class="font-semibold text-gray-900 mb-3">Booking Summary</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Room:</span>
                                            <span class="text-gray-900" x-text="selectedRoomData?.name || 'Not selected'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Duration:</span>
                                            <span class="text-gray-900" x-text="durationHours + ' hour(s)'"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Base Price:</span>
                                            <span class="text-gray-900" x-text="'Rp ' + basePrice.toLocaleString()"></span>
                                        </div>
                                        <div class="flex justify-between" x-show="addonsPrice > 0">
                                            <span class="text-gray-600">Add-ons:</span>
                                            <span class="text-gray-900" x-text="'+ Rp ' + addonsPrice.toLocaleString()"></span>
                                        </div>
                                        <div class="border-t pt-2 mt-3">
                                            <div class="flex justify-between font-semibold text-lg">
                                                <span class="text-gray-900">Total Price:</span>
                                                <span class="text-red-600" x-text="'Rp ' + totalPrice.toLocaleString()"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" 
                                        :disabled="!isAvailable || totalPrice <= 0"
                                        :class="{ 'opacity-50 cursor-not-allowed': !isAvailable || totalPrice <= 0 }"
                                        class="w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700 transition-colors duration-200 font-semibold">
                                    Book Room & Pay
                                </button>
                            </form>
                        </div>

                        <!-- Recent Activity -->
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Activity</h2>
                            <p class="text-gray-600 mb-6">Your recent bookings and activities</p>
                            
                            <div class="space-y-4">
                                @forelse($bookings->take(3) as $booking)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-semibold text-gray-900">{{ $booking->room->name }}</h3>
                                                <p class="text-sm text-gray-500">{{ $booking->start_time->format('M d, Y g:i A') }} - {{ $booking->end_time->format('g:i A') }}</p>
                                                <p class="text-xs text-gray-400">{{ $booking->duration_hours }} hour(s) • Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 text-xs font-medium rounded-full
                                            @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-500">No recent activity</p>
                                        <p class="text-sm text-gray-400">Start by booking your first session</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Upcoming Tab -->
                    <div x-show="activeTab === 'upcoming'" style="display: none;">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Upcoming Bookings</h2>
                            <p class="text-gray-600">Your scheduled room bookings</p>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($bookings->where('start_time', '>', now())->where('status', '!=', 'canceled')->sortBy('start_time') as $booking)
                                <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
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
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg">{{ $booking->room->name }}</h3>
                                                <div class="mt-2 space-y-1">
                                                    <p class="text-sm text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ $booking->start_time->format('l, M d, Y') }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }} ({{ $booking->duration_hours }} hours)
                                                    </p>
                                                    <p class="text-sm text-green-600 flex items-center font-medium">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 12H9v4a6 6 0 01-6-6V9a6 6 0 016-6h2l2.257-2.257A6 6 0 0115 7z"></path>
                                                        </svg>
                                                        Room PIN: {{ $booking->room->pin }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        Total: Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                    </p>
                                                    @if($booking->bookingAddons->count() > 0)
                                                        <div class="mt-2">
                                                            <p class="text-xs text-gray-500 font-medium">Add-ons:</p>
                                                            @foreach($booking->bookingAddons as $bookingAddon)
                                                                <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full mr-1 mt-1">
                                                                    {{ $bookingAddon->addon->name }} ({{ $bookingAddon->quantity }}x)
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                            <span class="px-2 py-1 text-xs rounded-full
                                                @if($booking->payment_status === 'paid') bg-green-100 text-green-700
                                                @else bg-red-100 text-red-700
                                                @endif">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                            @if($booking->start_time->diffInDays(now()) < 7)
                                                <span class="text-xs text-orange-600 font-medium">
                                                    {{ $booking->start_time->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-500 mb-2">No upcoming bookings</h3>
                                    <p class="text-gray-400">Book a room to see your upcoming sessions here</p>
                                    <button @click="activeTab = 'overview'" class="mt-4 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                                        Book Now
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Room data - getting from backend based on ERD structure  
const roomsData = @json($rooms);
const addonsData = @json($addons);
const existingBookings = @json($existingBookingsData);

function bookingForm() {
    return {
        selectedRoomType: '',
        selectedRoom: '',
        startTime: '',
        endTime: '',
        availableAddons: [],
        totalPrice: 0,
        basePrice: 0,
        addonsPrice: 0,
        durationHours: 0,
        isAvailable: true,
        filteredRooms: [],
        selectedRoomData: null,

        init() {
            this.updateRooms();
        },

        updateRooms() {
            if (this.selectedRoomType) {
                this.filteredRooms = roomsData.filter(room => room.type === this.selectedRoomType);
            } else {
                this.filteredRooms = [];
            }
            this.selectedRoom = '';
            this.selectedRoomData = null;
            this.availableAddons = [];
            this.resetPrices();
        },

        updateAddons() {
            if (this.selectedRoom) {
                this.selectedRoomData = this.filteredRooms.find(room => room.id == this.selectedRoom);
                
                // Get addons available for this room based on room_addons pivot table
                this.availableAddons = addonsData.filter(addon => {
                    return this.selectedRoomData.addons && 
                           this.selectedRoomData.addons.some(roomAddon => roomAddon.id === addon.id);
                });
            } else {
                this.selectedRoomData = null;
                this.availableAddons = [];
            }
            this.calculatePrice();
        },

        calculatePrice() {
            if (!this.selectedRoomData || !this.startTime || !this.endTime) {
                this.resetPrices();
                return;
            }

            // Calculate duration
            const start = new Date(this.startTime);
            const end = new Date(this.endTime);
            this.durationHours = Math.max(0, (end - start) / (1000 * 60 * 60));

            if (this.durationHours <= 0) {
                this.resetPrices();
                return;
            }

            // Calculate base price (room rate * duration)
            this.basePrice = this.selectedRoomData.hourly_rate * this.durationHours;

            // Calculate addons price
            this.addonsPrice = 0;
            const checkedAddons = document.querySelectorAll('input[name="addons[]"]:checked');
            checkedAddons.forEach(checkbox => {
                const addon = this.availableAddons.find(a => a.id == checkbox.value);
                if (addon) {
                    this.addonsPrice += addon.price;
                }
            });

            // Calculate total price
            this.totalPrice = this.basePrice + this.addonsPrice;
        },

        resetPrices() {
            this.totalPrice = 0;
            this.basePrice = 0;
            this.addonsPrice = 0;
            this.durationHours = 0;
        },

        checkAvailability() {
            if (!this.selectedRoom || !this.startTime || !this.endTime) {
                this.isAvailable = true;
                return;
            }

            const selectedStart = new Date(this.startTime);
            const selectedEnd = new Date(this.endTime);

            // Check if end time is after start time
            if (selectedEnd <= selectedStart) {
                this.isAvailable = false;
                return;
            }

            // Check against existing bookings (excluding canceled ones)
            this.isAvailable = !existingBookings.some(booking => {
                if (booking.room_id != this.selectedRoom || booking.status === 'canceled') {
                    return false;
                }

                const bookingStart = new Date(booking.start_time);
                const bookingEnd = new Date(booking.end_time);

                // Check for overlap
                return (
                    (selectedStart >= bookingStart && selectedStart < bookingEnd) ||
                    (selectedEnd > bookingStart && selectedEnd <= bookingEnd) ||
                    (selectedStart <= bookingStart && selectedEnd >= bookingEnd)
                );
            });
        },

        validateBeforeSubmit(event) {
            // Check availability before submit
            this.checkAvailability();
            this.calculatePrice();
            
            if (!this.isAvailable || this.totalPrice <= 0) {
                event.preventDefault();
                alert('Please select valid room and time slot');
                return false;
            }
            
            // Update hidden fields with calculated values
            document.querySelector('input[name="duration_hours"]').value = Math.ceil(this.durationHours);
            document.querySelector('input[name="base_price"]').value = Math.round(this.basePrice);
            document.querySelector('input[name="total_price"]').value = Math.round(this.totalPrice);
            
            // Allow normal form submission
            return true;
        }
    }
}

// Initialize Alpine.js stores
document.addEventListener('alpine:init', () => {
    Alpine.store('toast', {
        show: false,
        message: '',
        type: 'success',
        
        showToast(msg, toastType = 'success') {
            this.message = msg;
            this.type = toastType;
            this.show = true;
            setTimeout(() => this.show = false, 4000);
        }
    });
});

// Handle session messages
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (window.Alpine && Alpine.store('toast')) {
                Alpine.store('toast').showToast('{{ session('success') }}', 'success');
            }
        }, 100);
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (window.Alpine && Alpine.store('toast')) {
                Alpine.store('toast').showToast('{{ session('error') }}', 'error');
            }
        }, 100);
    });
@endif

@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            if (window.Alpine && Alpine.store('toast')) {
                Alpine.store('toast').showToast('{{ $errors->first() }}', 'error');
            }
        }, 100);
    });
@endif
</script>
@endpush