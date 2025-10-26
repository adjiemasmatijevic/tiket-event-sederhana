<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role === 'admin') {
            return $this->admin();
        } else if (Auth::user()->role === 'checker') {
            return $this->checker();
        } else {
            return $this->user();
        }
    }

    private function admin()
    {
        $totalEvents = Event::count();
        $activeEvents = Event::where('status', 'active')->count();
        $totalTickets = Ticket::sum('total');

        $ticketsSold = 0;
        $totalRevenue = 0;

        $totalUsers = User::where('role', 'user')->count();
        $totalCheckers = User::where('role', 'checker')->count();


        return view('admins.Dashboard', compact(
            'totalEvents',
            'activeEvents',
            'totalTickets',
            'ticketsSold',
            'totalUsers',
            'totalCheckers',
            'totalRevenue'
        ));
    }

    private function checker()
    {
        return view('checkers.Dashboard');
    }

    private function user()
    {
        $upcomingEvents = Event::where('time_start', '>=', Carbon::today())
            ->orderBy('time_start', 'asc')
            ->get();
        return view('users.Dashboard', compact('upcomingEvents'));
    }
}
