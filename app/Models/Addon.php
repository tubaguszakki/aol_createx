<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    //
    protected $fillable = [
        'name',
        'price',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_addons');
    }

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class);
    }
}
