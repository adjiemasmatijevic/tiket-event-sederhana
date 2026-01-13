<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Voucher;
use App\Models\Event;

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

        if (! $cartItem || $cartItem->user_id !== Auth::id()) {
            return back()->with('error', 'Unable to remove ticket.');
        }

        $cartItem->delete();

        $remainingCart = Cart::where('user_id', Auth::id())
            ->where('status', 'available')
            ->count();
        if ($remainingCart === 0 && session()->has('voucher_id')) {
            $voucherId = session('voucher_id');
            Voucher::where('id', $voucherId)->increment('limit');
            session()->forget(['voucher_id', 'voucher_discount']);
        }

        return back()->with('success', 'Ticket removed from cart.');
    }


    public function cart_checkout()
    {
        $tdiApiKey = config('tdi.api_key');

        if (empty($tdiApiKey)) {
            return back()->with('error', 'Checkout service is currently unavailable. Please try again later.');
        }

        $cartItems = Cart::where('user_id', Auth::user()->id)->where('status', 'available')->get();

        $totalPrice = $cartItems->sum(fn($item) => $item->ticket->price);

        $discount = session('voucher_discount', 0);
        $subtotalAfterDiscount = max($totalPrice - $discount, 0);
        $adminFee = ceil($totalPrice * 0.05);
        // $totalFull = $subtotalAfterDiscount + $adminFee;
        // $net = $subtotalAfterDiscount;
        $amountTotal = $subtotalAfterDiscount;
        $net = $subtotalAfterDiscount + $adminFee;

        $data = [
            'key' => $tdiApiKey,
            'notif_url' => route('transaction.update'),
            'redirect_url' => route('tickets.my_tickets'),
            'notes' => 'Buy tickets - ' . Auth::user()->email,
            'items' => [],
            'total' => $net, //$totalFull.
            'fee' => $adminFee,
            'payment' => 'QRIS',
            "expiry_minutes" => 15,
        ];

        foreach ($cartItems as $item) {

            $itemName = $item->ticket->name;

            // ambil notes dari cart item
            if (!empty($item->notes)) {
                $itemName .= ' - ' . $item->notes;
            }

            $itemPrice = (float) $item->ticket->price;

            if (isset($data['items'][$itemName])) {
                $data['items'][$itemName]['qty']++;
                $data['items'][$itemName]['total'] += $itemPrice;
            } else {
                $data['items'][$itemName] = [
                    'name'  => $itemName,
                    'qty'   => 1,
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
            'amount_total' => $amountTotal, //$totalFull
            'discount' => $discount,
            'admin_fee' => $adminFee,
            'net' => $net,
            'status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        foreach ($cartItems as $item) {
            $item->status = 'checkout';
            $item->transaction_id = $transaction->id;
            $item->save();
        }
        session()->forget(['voucher_id', 'voucher_discount']);

        return redirect('https://payment.talangdigital.com/transaction-detail/' . $response['id']);
    }
    public function adminCart()
    {
        $events = Event::orderBy('name')->get();

        return view('admins.Cart', compact('events'));
    }
    public function cartData(Request $request)
    {
        $query = Cart::with(['user', 'ticket.event', 'transaction'])
            ->whereIn('status', ['checkout', 'available'])
            ->whereHas('transaction', function ($q) {
                $q->where('status', 'success');
            });

        if ($request->filled('event_id')) {
            $query->whereHas('ticket', function ($q) use ($request) {
                $q->where('event_id', $request->event_id);
            });
        }

        return response()->json(
            $query->latest()->get()
        );
    }
}
