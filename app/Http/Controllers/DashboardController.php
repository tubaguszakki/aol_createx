<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Addon;
use App\Models\Booking;
use App\Models\BookingAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all rooms with their available addons
        $rooms = Room::with('addons')->get();
        
        // Get all addons
        $addons = Addon::all();
        
        // Get user's bookings with relationships
        $bookings = Booking::with(['room', 'bookingAddons.addon'])
            ->where('user_id', Auth::id())
            ->orderBy('start_time', 'desc')
            ->get();

        // Prepare existing bookings data for JavaScript (to avoid closure in Blade)
        $existingBookingsData = $bookings->map(function($booking) {
            return [
                'room_id' => $booking->room_id,
                'start_time' => $booking->start_time->format('Y-m-d\TH:i'),
                'end_time' => $booking->end_time->format('Y-m-d\TH:i'),
                'status' => $booking->status
            ];
        })->values();

        return view('dashboard.index', compact('rooms', 'addons', 'bookings', 'existingBookingsData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'duration_hours' => 'required|numeric|min:1',
            'base_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'addons' => 'sometimes|array',
            'addons.*.addon_id' => 'required|exists:addons,id',
            'addons.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Check for conflicts
            $conflict = Booking::where('room_id', $request->room_id)
                ->where('status', '!=', 'canceled')
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhere(function ($subQuery) use ($request) {
                            $subQuery->where('start_time', '<=', $request->start_time)
                                ->where('end_time', '>=', $request->end_time);
                        });
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'message' => 'This room is already booked for the selected time slot.'
                ], 422);
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'room_id' => $request->room_id,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration_hours' => $request->duration_hours,
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'base_price' => $request->base_price,
                'total_price' => $request->total_price,
            ]);

            // Add addons if any
            if ($request->has('addons')) {
                foreach ($request->addons as $addonData) {
                    BookingAddon::create([
                        'booking_id' => $booking->id,
                        'addon_id' => $addonData['addon_id'],
                        'quantity' => $addonData['quantity'],
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Booking created successfully!',
                'booking_id' => $booking->id,
                'redirect' => route('booking.payment', $booking->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'message' => 'An error occurred while creating the booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bookings()
    {
        // Get user's bookings with relationships for My Bookings page
        $bookings = Booking::with(['room', 'bookingAddons.addon'])
            ->where('user_id', Auth::id())
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('dashboard.bookings', compact('bookings'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'date' => 'required|date'
        ]);

        // Get booked slots for the specific room and date
        $bookedSlots = Booking::where('room_id', $request->room_id)
            ->where('status', '!=', 'canceled')
            ->whereDate('start_time', $request->date)
            ->select('start_time', 'end_time')
            ->get()
            ->map(function ($booking) {
                return [
                    'start' => Carbon::parse($booking->start_time)->format('H:i'),
                    'end' => Carbon::parse($booking->end_time)->format('H:i')
                ];
            });

        return response()->json([
            'booked_slots' => $bookedSlots
        ]);
    }
}