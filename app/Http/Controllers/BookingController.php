<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class BookingController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'duration_hours' => 'required|integer|min:1',
            'base_price' => 'required|integer|min:0',
            'total_price' => 'required|integer|min:0',
            'addons' => 'sometimes|array',
        ]);

        $room = Room::findOrFail($request->room_id);
        
        // Check if room is available
        $conflictingBooking = Booking::where('room_id', $request->room_id)
            ->where('status', '!=', 'canceled')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })
            ->exists();

        if ($conflictingBooking) {
            return back()->withErrors(['error' => 'Room is not available for the selected time slot.']);
        }

        // Generate unique order ID
        $orderId = 'ROOM-' . time() . '-' . Auth::id();

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $request->room_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_hours' => $request->duration_hours,
            'base_price' => $request->base_price,
            'total_price' => $request->total_price,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'midtrans_order_id' => $orderId,
        ]);

        // Add selected addons
        if ($request->has('addons')) {
            foreach ($request->addons as $addonId) {
                BookingAddon::create([
                    'booking_id' => $booking->id,
                    'addon_id' => $addonId,
                    'quantity' => 1,
                ]);
            }
        }

        // Prepare Midtrans transaction details
        $itemDetails = [
            [
                'id' => 'room-' . $room->id,
                'price' => (int) $request->base_price,
                'quantity' => 1,
                'name' => 'Room Booking - ' . $room->name,
            ]
        ];

        // Add addon items
        if ($request->has('addons')) {
            foreach ($request->addons as $addonId) {
                $addon = $room->addons()->find($addonId);
                if ($addon) {
                    $itemDetails[] = [
                        'id' => 'addon-' . $addon->id,
                        'price' => (int) $addon->price,
                        'quantity' => 1,
                        'name' => 'Add-on: ' . $addon->name,
                    ];
                }
            }
        }

        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $request->total_price,
        ];

        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('booking.success', ['booking' => $booking->id]),
                'error' => route('booking.failed', ['booking' => $booking->id]),
                'pending' => route('booking.pending', ['booking' => $booking->id]),
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('booking.payment', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            // Delete booking if Midtrans fails
            $booking->delete();
            return back()->withErrors(['error' => 'Payment system error: ' . $e->getMessage()]);
        }
    }

    public function generateRoomPin(Booking $booking)
    {
        $booking->update([
            'room_pin' => $booking->room->pin
        ]);
    }

    public function success(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Check payment status from Midtrans API
        try {
            $status = Transaction::status($booking->midtrans_order_id);
            
            Log::info("Checking Midtrans status for order: {$booking->midtrans_order_id}");
            Log::info("Midtrans response: " . json_encode($status));
            
            if (isset($status->transaction_status)) {
                // Update transaction ID
                $booking->update(['midtrans_transaction_id' => $status->transaction_id ?? '']);
                
                /** @var object $status */
                $transactionStatus = (string) $status->transaction_status;
                if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                    if ($booking->payment_status !== 'paid') {
                        $booking->update([
                            'payment_status' => 'paid',
                            'status' => 'confirmed',
                        ]);
                        
                        $booking->generateRoomPin();
                    }
                } else if ($transactionStatus === 'pending') {
                    $booking->update(['payment_status' => 'pending']);
                }
            }
        } catch (\Exception $e) {
            Log::error('Midtrans status check failed: ' . $e->getMessage());
            
            // Fallback: Manual update untuk testing
            if ($booking->payment_status === 'unpaid') {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'room_pin' => $booking->room->pin
                ]);
                Log::info("Booking {$booking->id} manually confirmed (fallback)");
            }
        }

        return view('booking.success', compact('booking'));
    }

    private function syncBookingStatusFromMidtrans(Booking $booking)
    {
        try {
            /** @var object $status */
            $status = Transaction::status($booking->midtrans_order_id);
            
            if (isset($status->transaction_status)) {
                $booking->update(['midtrans_transaction_id' => $status->transaction_id ?? '']);
                
                $transactionStatus = (string) $status->transaction_status;
                if (in_array($transactionStatus, ['capture', 'settlement'])) {
                    $booking->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed'
                    ]);
                    $booking->generateRoomPin();
                } else if ($transactionStatus === 'pending') {
                    $booking->update(['payment_status' => 'pending']);
                } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $booking->update([
                        'payment_status' => 'failed',
                        'status' => 'canceled'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to sync booking status: ' . $e->getMessage());
        }
    }

    public function pay(Booking $booking)
    {
        // Pastikan user hanya bisa bayar booking mereka sendiri
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Sync status dari Midtrans dulu
        $this->syncBookingStatusFromMidtrans($booking);
        
        // Refresh booking setelah sync
        $booking->refresh();
        
        // Cek apakah masih bisa dibayar
        if (!$booking->canBePaid()) {
            $midtransStatus = $booking->getMidtransStatus();
            $transactionStatus = $midtransStatus->transaction_status ?? 'unknown';
            
            if ($booking->payment_status === 'paid') {
                return redirect()->route('booking.success', $booking)
                                ->with('info', 'This booking has already been paid.');
            } elseif (in_array($transactionStatus, ['expire', 'deny', 'cancel'])) {
                return back()->withErrors('This payment has expired. Please create a new booking.');
            } else {
                return back()->withErrors('This booking cannot be paid anymore.');
            }
        }

        // Generate new Midtrans payment dengan existing order ID
        $itemDetails = [
            [
                'id' => 'room-' . $booking->room->id,
                'price' => (int) $booking->base_price,
                'quantity' => 1,
                'name' => 'Room Booking - ' . $booking->room->name,
            ]
        ];

        // Add addon items
        if ($booking->bookingAddons->count() > 0) {
            foreach ($booking->bookingAddons as $bookingAddon) {
                $itemDetails[] = [
                    'id' => 'addon-' . $bookingAddon->addon->id,
                    'price' => (int) $bookingAddon->addon->price,
                    'quantity' => $bookingAddon->quantity,
                    'name' => 'Add-on: ' . $bookingAddon->addon->name,
                ];
            }
        }

        $transactionDetails = [
            'order_id' => $booking->midtrans_order_id, // Pakai order ID yang sama
            'gross_amount' => (int) $booking->total_price,
        ];

        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('booking.success', $booking->id),
                'error' => route('booking.failed', $booking->id),
                'pending' => route('booking.pending', $booking->id),
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return view('booking.payment', compact('booking', 'snapToken'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment system error: ' . $e->getMessage()]);
        }
    }

    public function failed(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('booking.failed', compact('booking'));
    }

    public function pending(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        return view('booking.pending', compact('booking'));
    }

    public function notification(Request $request)
    {
        try {
            // Log semua data yang masuk untuk debug
            Log::info('Midtrans notification received:', $request->all());
            
            $notification = new \Midtrans\Notification();
            
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $transactionId = $notification->transaction_id;
            $fraudStatus = $notification->fraud_status ?? '';
            
            Log::info("Processing notification for order: {$orderId}, status: {$transactionStatus}");
            
            $booking = Booking::where('midtrans_order_id', $orderId)->first();
            
            if (!$booking) {
                Log::error("Booking not found for order ID: {$orderId}");
                return response('Booking not found', 404);
            }

            // Update transaction ID
            $booking->update(['midtrans_transaction_id' => $transactionId]);

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $booking->update(['payment_status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $booking->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'room_pin' => $booking->room->pin
                    ]);
                    Log::info("Booking {$booking->id} confirmed via capture");
                }
            } else if ($transactionStatus == 'settlement') {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed', 
                    'room_pin' => $booking->room->pin
                ]);
                Log::info("Booking {$booking->id} confirmed via settlement");
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $booking->update([
                    'payment_status' => 'failed',
                    'status' => 'canceled'
                ]);
                Log::info("Booking {$booking->id} canceled due to: {$transactionStatus}");
            } else if ($transactionStatus == 'pending') {
                $booking->update(['payment_status' => 'pending']);
                Log::info("Booking {$booking->id} payment pending");
            }

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
}