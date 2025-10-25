<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

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
        return view('admins.Dashboard');
    }

    private function checker()
    {
        return view('checkers.Dashboard');
    }

    private function user()
    {
        return view('users.Dashboard');
    }
}
