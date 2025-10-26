<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    public function cart()
    {
        $cartItems = Cart::with('ticket')->where('user_id', Auth::user()->id)->where('status', 'available')->get();
        return view('users.Cart', compact('cartItems'));
    }
}
