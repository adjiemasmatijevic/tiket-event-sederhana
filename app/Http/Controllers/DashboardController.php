<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Cart;
use App\Models\Transaction;
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

        $ticketsSold = Cart::whereHas('transaction', function ($query) {
            $query->where('status', 'success');
        })
            ->count();
        $totalRevenue = Transaction::where('status', 'success')
            ->sum('amount_total');

        $net = ceil($totalRevenue - ($totalRevenue * 0.05));


        $totalUsers = User::where('role', 'user')->count();
        $totalCheckers = User::where('role', 'checker')->count();


        return view('admins.Dashboard', compact(
            'totalEvents',
            'activeEvents',
            'totalTickets',
            'ticketsSold',
            'totalUsers',
            'totalCheckers',
            'totalRevenue',
            'net'
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
