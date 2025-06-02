<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Room::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $rooms = $query->get();
        $roomTypes = Room::select('type')->distinct()->pluck('type');

        return view('home', compact('rooms', 'roomTypes'));
    }
}
