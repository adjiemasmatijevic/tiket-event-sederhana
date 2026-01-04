<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Cart;
use App\Models\Transaction;

class CartController extends Controller
{
    public function cart()
    {
        $cartItems = Cart::with('ticket')->where('user_id', Auth::user()->id)->where('status', 'available')->get();
        return view('users.Cart', compact('cartItems'));
    }

    public function cart_remove(Request $request)
    {
        $cartItem = Cart::find($request->cart_id);
        if ($cartItem && $cartItem->user_id == Auth::user()->id) {
            $cartItem->delete();
            return back()->with('success', 'Ticket removed from cart.');
        }
        return back()->with('error', 'Unable to remove ticket from cart.');
    }

    public function cart_checkout()
    {
        $tdiApiKey = config('tdi.api_key');

        if (empty($tdiApiKey)) {
            return back()->with('error', 'Checkout service is currently unavailable. Please try again later.');
        }

        $cartItems = Cart::where('user_id', Auth::user()->id)->where('status', 'available')->get();

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->ticket->price;
        });

        $adminFee = ceil($totalPrice * 0.05);
        $totalFull = $totalPrice + $adminFee;

        $data = [
            'key' => $tdiApiKey,
            'notif_url' => route('transaction.update'),
            'redirect_url' => route('tickets.my_tickets'),
            'notes' => 'Buy tickets - ' . Auth::user()->email,
            'items' => [],
            'total' => $totalFull,
            'fee' => $adminFee,
            'payment' => 'QRIS',
            "expiry_minutes" => 15,
        ];

        foreach ($cartItems as $item) {
            $itemName = $item->ticket->name;
            $itemPrice = (float) $item->ticket->price;

            if (isset($data['items'][$itemName])) {
                $data['items'][$itemName]['qty']++;
                $data['items'][$itemName]['total'] += $itemPrice;
            } else {
                $data['items'][$itemName] = [
                    'name' => $itemName,
                    'qty' => 1,
                    'price' => $itemPrice,
                    'total' => $itemPrice,
                ];
            }
        }

        $data['items'] = array_values($data['items']);

        try {
            $response = Http::post('https://payment.talangdigital.com/api/transaction-create', $data);
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to connect to checkout service. Please try again later.');
        }

        if ($response->failed() || !isset($response['status']) || $response['status'] !== 'success') {
            return back()->with('error', 'Failed to initiate checkout. Please try again later.');
        }

        $transaction = Transaction::create([
            'user_id' => Auth::user()->id,
            'tdi_pay_id' => $response['id'],
            'amount_total' => $totalFull,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        foreach ($cartItems as $item) {
            $item->status = 'checkout';
            $item->transaction_id = $transaction->id;
            $item->save();
        }

        return redirect('https://payment.talangdigital.com/transaction-detail/' . $response['id']);
    }
}