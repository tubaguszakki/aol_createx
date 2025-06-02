<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Booking extends Model
{
    //
    protected $fillable = [
        'user_id',
        'room_id',
        'start_time',
        'end_time',
        'duration_hours',
        'status',
        'payment_status',
        'base_price',
        'total_price',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'room_pin',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function bookingAddons(): HasMany
    {
        return $this->hasMany(BookingAddon::class);
    }

    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'booking_addons')->withPivot('quantity');
    }

    /**
     * Generate random 6-digit PIN untuk room access
     */
    public function generateRoomPin()
    {
        do {
            $pin = str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('room_pin', $pin)->where('status', '!=', 'canceled')->exists());
        
        $this->room_pin = $pin;
        $this->save();
        
        return $pin;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($booking) {
            if ($booking->start_time && $booking->end_time) {
                $booking->duration_hours = (int) ceil(Carbon::parse($booking->start_time)
                    ->diffInHours(Carbon::parse($booking->end_time)));
            }
        });
    }
    public function isExpired()
    {
        try {
            // Check dari Midtrans API
            $status = \Midtrans\Transaction::status($this->midtrans_order_id);
            
            // Cek apakah transaction expired di Midtrans
            return in_array($status->transaction_status ?? '', ['expire', 'deny', 'cancel']);
            
        } catch (\Exception $e) {
            // Fallback: kalau API error, cek dari waktu booking
            Log::warning("Midtrans status check failed for order {$this->midtrans_order_id}: " . $e->getMessage());
            
            // Transaction expired setelah 24 jam (default Midtrans)
            return $this->created_at->addHours(24) < now();
        }
    }

    public function canBePaid()
    {
        // Basic checks dulu
        if ($this->payment_status === 'paid' || $this->status === 'canceled') {
            return false;
        }
        
        try {
            // Check status dari Midtrans
            $status = \Midtrans\Transaction::status($this->midtrans_order_id);
            
            // Bisa dibayar kalau masih pending
            return in_array($status->transaction_status ?? '', ['pending']);
            
        } catch (\Exception $e) {
            Log::warning("Midtrans status check failed for order {$this->midtrans_order_id}: " . $e->getMessage());
            
            // Fallback: cek basic conditions
            return $this->payment_status === 'unpaid' && 
                $this->status !== 'canceled' && 
                $this->created_at->addHours(24) > now(); // 24 jam expiry
        }
    }

    public function getMidtransStatus()
    {
        try {
            return \Midtrans\Transaction::status($this->midtrans_order_id);
        } catch (\Exception $e) {
            Log::warning("Failed to get Midtrans status for order {$this->midtrans_order_id}: " . $e->getMessage());
            return null;
        }
    }
}
