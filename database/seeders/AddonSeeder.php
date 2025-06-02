<?php

namespace Database\Seeders;

use App\Models\Addon;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $addons = [
            ['name' => 'Professional Lighting', 'price' => 25000],
            ['name' => 'Sound System', 'price' => 35000],
            ['name' => 'Camera Equipment', 'price' => 45000],
            ['name' => 'Microphone Set', 'price' => 15000],
            ['name' => 'Green Screen', 'price' => 20000],
            ['name' => 'Projector', 'price' => 30000],
        ];

        foreach ($addons as $addonData) {
            $addon = Addon::create($addonData);
            
            // Attach addons to all rooms
            $rooms = Room::all();
            foreach ($rooms as $room) {
                $room->addons()->attach($addon->id);
            }
        }
    }
}
