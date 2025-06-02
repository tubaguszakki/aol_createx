<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\User;
use App\Models\Room;
use Midtrans\Config;
use Midtrans\Transaction;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class AdminController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    //
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                abort(403, 'Access denied');
            }
            return $next($request);
        });

        // Set Midtrans configuration
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    public function dashboard()
    {
        $totalBookings = Booking::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalRooms = Room::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        $recentBookings = Booking::with(['user', 'room'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalBookings',
            'totalUsers', 
            'totalRooms',
            'pendingBookings',
            'recentBookings'
        ));
    }

    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'room', 'addons']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings', compact('bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $booking->update(['status' => $request->status]);

        // Jika status diubah ke confirmed dan belum ada PIN, generate PIN
        if ($request->status === 'confirmed' && $booking->payment_status === 'paid' && !$booking->room_pin) {
            $booking->generateRoomPin();
        }

        return back()->with('success', 'Booking status updated successfully.');
    }

    public function checkPaymentStatus(Booking $booking)
    {
        if (!$booking->midtrans_order_id) {
            return back()->with('error', 'No Midtrans order found for this booking.');
        }

        try {
             /** @var object $status */
            $status = Transaction::status($booking->midtrans_order_id);

            if (in_array($status->transaction_status, ['capture', 'settlement'])) {
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'midtrans_transaction_id' => $status->transaction_id ?? null,
                ]);
                
                // Generate PIN setelah payment confirmed
                if (!$booking->room_pin) {
                    $booking->generateRoomPin();
                }
                
                return back()->with('success', 'Payment status updated to paid and PIN generated.');
            } elseif (in_array($status->transaction_status, ['cancel', 'deny', 'expire'])) {
                $booking->update(['status' => 'canceled']);
                return back()->with('info', 'Payment was canceled or expired.');
            } else {
                return back()->with('info', 'Payment is still pending.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Unable to check payment status: ' . $e->getMessage());
        }
    }

    public function regeneratePin(Booking $booking)
    {
        if ($booking->payment_status !== 'paid') {
            return back()->with('error', 'Cannot generate PIN for unpaid booking.');
        }

        $booking->generateRoomPin();
        
        return back()->with('success', 'New PIN generated successfully.');
    }

    public function showBooking(Booking $booking)
    {
        $booking->load(['user', 'room', 'addons']);
        return view('admin.booking-detail', compact('booking'));
    }

    public function deleteBooking(Booking $booking)
    {
        // Only allow deletion of canceled bookings
        if ($booking->status !== 'canceled') {
            return back()->with('error', 'Only canceled bookings can be deleted.');
        }

        $booking->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'booking_ids' => 'required|array',
            'booking_ids.*' => 'exists:bookings,id',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $updated = Booking::whereIn('id', $request->booking_ids)
            ->update(['status' => $request->status]);

        // Generate PINs for confirmed paid bookings
        if ($request->status === 'confirmed') {
            $bookings = Booking::whereIn('id', $request->booking_ids)
                ->where('payment_status', 'paid')
                ->whereNull('room_pin')
                ->get();

            foreach ($bookings as $booking) {
                $booking->generateRoomPin();
            }
        }

        return back()->with('success', "Updated {$updated} booking(s) successfully.");
    }

    public function exportBookings(Request $request)
    {
        $query = Booking::with(['user', 'room', 'addons']);

        // Apply same filters as bookings index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest()->get();

        $filename = 'bookings_export_' . now()->format('Y_m_d_H_i_s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'User Name',
                'User Email',
                'Room Name',
                'Room Type',
                'Start Time',
                'End Time',
                'Duration (Hours)',
                'Base Price',
                'Total Price',
                'Status',
                'Payment Status',
                'Room PIN',
                'Midtrans Order ID',
                'Created At'
            ]);

            // CSV Data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->user->name,
                    $booking->user->email,
                    $booking->room->name,
                    $booking->room->type,
                    $booking->start_time->format('Y-m-d H:i:s'),
                    $booking->end_time->format('Y-m-d H:i:s'),
                    $booking->duration_hours,
                    $booking->base_price,
                    $booking->total_price,
                    $booking->status,
                    $booking->payment_status,
                    $booking->room_pin ?? 'N/A',
                    $booking->midtrans_order_id ?? 'N/A',
                    $booking->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function stats()
    {
        $stats = [
            'total_revenue' => Booking::where('payment_status', 'paid')->sum('total_price'),
            'pending_revenue' => Booking::where('payment_status', 'unpaid')->sum('total_price'),
            'bookings_today' => Booking::whereDate('created_at', today())->count(),
            'bookings_this_month' => Booking::whereMonth('created_at', now()->month)->count(),
            'most_popular_room' => Room::withCount('bookings')->orderBy('bookings_count', 'desc')->first(),
            'recent_users' => User::where('role', 'user')->latest()->limit(5)->get(),
        ];

        return view('admin.stats', compact('stats'));
    }
}
