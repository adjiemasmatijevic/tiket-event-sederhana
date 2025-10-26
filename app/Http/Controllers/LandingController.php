<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function home()
    {
        $upcomingEvents = Event::where('time_start', '>=', Carbon::today())
            ->orderBy('time_start', 'asc')
            ->get();
        return view('welcome', compact('upcomingEvents'));
    }
}
