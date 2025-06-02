<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $rooms = [
            [
                'name' => 'Studio A - Live Streaming',
                'type' => 'live streaming',
                'hourly_rate' => 50000,
            ],
            [
                'name' => 'Grand Hall',
                'type' => 'event hall',
                'hourly_rate' => 100000,
            ],
            [
                'name' => 'Edit Suite Pro',
                'type' => 'editing room',
                'hourly_rate' => 30000,
            ],
            [
                'name' => 'Recording Studio',
                'type' => 'studio',
                'hourly_rate' => 80000,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
