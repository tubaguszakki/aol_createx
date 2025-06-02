@extends('layouts.dashboard')

@section('title', 'Payment - Room Booking')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-6 text-center">Complete Your Payment</h1>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="font-semibold mb-3">Booking Summary:</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Room:</span>
                        <span>{{ $booking->room->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Date:</span>
                        <span>{{ $booking->start_time->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Time:</span>
                        <span>{{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Duration:</span>
                        <span>{{ $booking->duration_hours }} hour(s)</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Base Price:</span>
                        <span>Rp {{ number_format($booking->base_price, 0, ',', '.') }}</span>
                    </div>
                    
                    @if($booking->bookingAddons->count() > 0)
                        <div class="border-t pt-2 mt-2">
                            <p class="font-medium text-gray-700 mb-1">Add-ons:</p>
                            @foreach($booking->bookingAddons as $bookingAddon)
                                <div class="flex justify-between text-xs text-gray-600 ml-4">
                                    <span>• {{ $bookingAddon->addon->name }} ({{ $bookingAddon->quantity }}x)</span>
                                    <span>Rp {{ number_format($bookingAddon->addon->price * $bookingAddon->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="flex justify-between font-semibold border-t pt-2 mt-2 text-lg">
                        <span>Total:</span>
                        <span class="text-red-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <!-- Midtrans Snap Payment -->
            <div id="snap-container"></div>
            
            <div class="text-center">
                <button id="pay-button" class="w-full bg-red-600 text-white py-3 px-6 rounded-lg hover:bg-red-700 transition-colors duration-200 font-semibold">
                    Pay Now
                </button>
                
                <a href="{{ route('dashboard') }}" class="block mt-4 text-gray-600 hover:text-gray-800">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = '{{ route("booking.success", $booking->id) }}';
            },
            onPending: function(result){
                window.location.href = '{{ route("booking.pending", $booking->id) }}';
            },
            onError: function(result){
                window.location.href = '{{ route("booking.failed", $booking->id) }}';
            }
        });
    };
</script>
@endsection