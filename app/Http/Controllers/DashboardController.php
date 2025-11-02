<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Cart;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
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

        $net = Transaction::where('status', 'success')
            ->sum('net');

        $feeAdmin = $totalRevenue - $net;

        $totalUsers = User::where('role', 'user')->count();
        $totalCheckers = User::where('role', 'checker')->count();

        $ticketsPresent = Cart::where('presence', 1)->whereHas('transaction', function ($query) {
            $query->where('status', 'success');
        })
            ->count();


        return view('admins.Dashboard', compact(
            'totalEvents',
            'activeEvents',
            'totalTickets',
            'ticketsSold',
            'totalUsers',
            'totalCheckers',
            'totalRevenue',
            'net',
            'feeAdmin',
            'ticketsPresent'
        ));
    }

    private function checker()
    {
        $ticketsPresent = Cart::where('presence', 1)->whereHas('transaction', function ($query) {
            $query->where('status', 'success');
        })
            ->count();
        return view('checkers.Dashboard', compact('ticketsPresent'));
    }

    private function user()
    {
        $upcomingEvents = Event::where('time_start', '>=', Carbon::today())
            ->orderBy('time_start', 'asc')
            ->get();
        return view('users.Dashboard', compact('upcomingEvents'));
    }

    public function present_ticket_data()
    {
        $query = Cart::query()
            ->join('transactions', 'transactions.id', '=', 'carts.transaction_id')
            ->join('tickets', 'tickets.id', '=', 'carts.ticket_id')
            ->join('events', 'events.id', '=', 'tickets.event_id')
            ->join('users', 'users.id', '=', 'carts.user_id')
            ->where('carts.presence', 1)
            ->where('transactions.status', 'success')
            ->select([
                'carts.id AS id_tiket',
                'users.name AS user_name',
                'events.name AS event_name',
                'tickets.name AS ticket_name',
            ])
            ->orderBy('carts.updated_at', 'desc');

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
}
