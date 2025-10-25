<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CheckerController extends Controller
{
    public function showGateCheck()
    {
        return view('checkers.GateCheck');
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|string',
        ]);

        $cartId = $request->input('cart_id');

        $cart = Cart::with('user')->find($cartId);

        if ($cart && $cart->user) {
            if ($cart->presence == 1) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket already used'
                ], 409);
            }

            $cart->presence = 1;
            $cart->save();

            return response()->json([
                'status' => 'success',
                'message' => $cart->user->name . ' presence success'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'invalid ticket'
            ], 404);
        }
    }
}
