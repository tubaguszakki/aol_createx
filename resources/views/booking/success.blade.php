@extends('layouts.dashboard')

@section('title', 'Booking Success')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Booking Successful!</h1>
            <p class="text-gray-600 mb-6">Your room has been booked successfully.</p>
            
            <div class="bg-gray-50 rounded-lg p-4 text-left mb-6">
                <h3 class="font-semibold mb-2">Booking Details:</h3>
                <p><strong>Room:</strong> {{ $booking->room->name }}</p>
                <p><strong>Date:</strong> {{ $booking->start_time->format('M d, Y') }}</p>
                <p><strong>Time:</strong> {{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}</p>
                <p><strong>Duration:</strong> {{ $booking->duration_hours }} hour(s)</p>
                <p><strong>Room PIN:</strong> {{ $booking->room_pin }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
            </div>
            
            <div class="space-y-3">
                <a href="{{ route('dashboard') }}" class="block w-full bg-red-600 text-white py-3 px-4 rounded-lg hover:bg-red-700">
                    Back to Dashboard
                </a>
                <a href="{{ route('dashboard.bookings') }}" class="block w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-50">
                    View All Bookings
                </a>
            </div>
        </div>
    </div>
</div>
@endsection