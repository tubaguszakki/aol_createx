@extends('layouts.dashboard')

@section('title', 'Manage Bookings - Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="flex">
        <!-- Admin Sidebar -->
        <aside class="w-64 bg-gray-900 min-h-screen p-6 fixed">
            <!-- Logo -->
            <div class="flex items-center mb-8">
                <img src="{{ asset('images/logo_white.png') }}" alt="Createx" class="h-8 w-auto mr-3">
                <span class="text-xl font-bold text-white">Createx Admin</span>
            </div>
            
            <!-- User Info -->
            <div class="mb-8 p-4 bg-gray-800 rounded-lg">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                        <span class="text-white font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-white font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-white font-extralight text-sm truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="space-y-2 mb-8">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v0H8v0z"></path>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.bookings') }}" class="flex items-center px-4 py-3 text-white bg-gray-800 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    All Bookings
                </a>
                
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Back to Site
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
                <h1 class="text-3xl font-bold text-gray-900">Manage Bookings</h1>
                <p class="text-gray-600 mt-2">Review and manage all room bookings</p>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Filter Bookings</h2>
                <form method="GET" action="{{ route('admin.bookings') }}" class="flex flex-wrap gap-4">
                    <select name="status" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Canceled</option>
                    </select>
                    
                    <select name="payment_status" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition-colors duration-200">
                        <option value="">All Payment Statuses</option>
                        <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200 font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Filter
                    </button>
                    
                    @if(request()->hasAny(['status', 'payment_status']))
                        <a href="{{ route('admin.bookings') }}" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear Filters
                        </a>
                    @endif
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">All Bookings</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIN</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $booking->id }}
                                        @if($booking->midtrans_order_id)
                                            <div class="text-xs text-gray-400">{{ substr($booking->midtrans_order_id, -8) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-sm font-medium text-gray-600">{{ substr($booking->user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->room->name }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($booking->room->type) }}</div>
                                        @if($booking->addons->count() > 0)
                                            <div class="text-xs text-blue-600 mt-1">
                                                +{{ $booking->addons->count() }} add-on(s)
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->start_time->format('M d, Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->start_time->format('g:i A') }} - {{ $booking->end_time->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.booking.status', $booking) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" 
                                                    class="text-xs px-3 py-1 border border-gray-300 rounded-full font-semibold
                                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="canceled" {{ $booking->status === 'canceled' ? 'selected' : '' }}>Canceled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($booking->payment_status === 'paid') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                        @if($booking->payment_status === 'unpaid' && $booking->midtrans_order_id)
                                            <form method="POST" action="{{ route('admin.booking.check-payment', $booking) }}" class="inline mt-1">
                                                @csrf
                                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                    Check Payment
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($booking->room_pin)
                                            <div class="font-mono font-bold text-green-600">{{ $booking->room_pin }}</div>
                                            @if($booking->payment_status === 'paid')
                                                <form method="POST" action="{{ route('admin.booking.regenerate-pin', $booking) }}" class="inline mt-1">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-yellow-600 hover:text-yellow-800 transition-colors duration-200"
                                                            onclick="return confirm('Generate new PIN?')">
                                                        🔄 New PIN
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif($booking->payment_status === 'paid')
                                            <span class="text-orange-600 text-xs">No PIN</span>
                                            <form method="POST" action="{{ route('admin.booking.regenerate-pin', $booking) }}" class="inline mt-1">
                                                @csrf
                                                <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                    Generate PIN
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-xs">Payment required</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button onclick="showBookingDetails({{ $booking->id }})" 
                                                class="text-red-600 hover:text-red-700 font-medium transition-colors duration-200">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-gray-500 font-medium">No bookings found</p>
                                            <p class="text-gray-400 text-sm">Bookings will appear here once users start making reservations</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bookings->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>

<!-- Booking Details Modal -->
<div id="bookingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-96 overflow-y-auto border border-gray-100">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">Booking Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="bookingDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showBookingDetails(bookingId) {
    const booking = @json($bookings->items()).find(b => b.id === bookingId);
    if (!booking) return;
    
    let addonsHtml = '';
    if (booking.addons && booking.addons.length > 0) {
        addonsHtml = '<div class="mb-4"><h4 class="font-medium text-gray-900 mb-2">Add-ons:</h4><ul class="list-disc list-inside text-sm text-gray-600">';
        booking.addons.forEach(addon => {
            addonsHtml += `<li>${addon.name} (${addon.pivot.quantity}x) - Rp ${addon.price.toLocaleString()}</li>`;
        });
        addonsHtml += '</ul></div>';
    }

    let pinHtml = '';
    if (booking.room_pin) {
        pinHtml = `<div class="mb-4"><h4 class="font-medium text-gray-900">Room Access PIN</h4><p class="text-sm font-mono font-bold text-green-600">${booking.room_pin}</p></div>`;
    } else if (booking.payment_status === 'paid') {
        pinHtml = `<div class="mb-4"><h4 class="font-medium text-gray-900">Room Access PIN</h4><p class="text-sm text-orange-600">No PIN generated yet</p></div>`;
    }
    
    const detailsHtml = `
        <div class="space-y-4">
            <div>
                <h4 class="font-medium text-gray-900">User Information</h4>
                <p class="text-sm text-gray-600">${booking.user.name} (${booking.user.email})</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900">Room Information</h4>
                <p class="text-sm text-gray-600">${booking.room.name} - ${booking.room.type}</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900">Booking Details</h4>
                <p class="text-sm text-gray-600">Start: ${new Date(booking.start_time).toLocaleString()}</p>
                <p class="text-sm text-gray-600">End: ${new Date(booking.end_time).toLocaleString()}</p>
                <p class="text-sm text-gray-600">Duration: ${booking.duration_hours} hour(s)</p>
            </div>
            
            ${pinHtml}
            
            ${addonsHtml}
            
            <div>
                <h4 class="font-medium text-gray-900">Pricing</h4>
                <p class="text-sm text-gray-600">Base Price: Rp ${booking.base_price.toLocaleString()}</p>
                <p class="text-sm text-gray-600 font-medium">Total Price: Rp ${booking.total_price.toLocaleString()}</p>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900">Payment Information</h4>
                <p class="text-sm text-gray-600">Status: <span class="font-medium">${booking.payment_status}</span></p>
                ${booking.midtrans_order_id ? `<p class="text-sm text-gray-600">Midtrans Order ID: ${booking.midtrans_order_id}</p>` : ''}
                ${booking.midtrans_transaction_id ? `<p class="text-sm text-gray-600">Transaction ID: ${booking.midtrans_transaction_id}</p>` : ''}
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900">Booking Status</h4>
                <p class="text-sm text-gray-600">Status: <span class="font-medium">${booking.status}</span></p>
                <p class="text-sm text-gray-600">Created: ${new Date(booking.created_at).toLocaleString()}</p>
            </div>
        </div>
    `;
    
    document.getElementById('bookingDetails').innerHTML = detailsHtml;
    document.getElementById('bookingModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('bookingModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('bookingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection