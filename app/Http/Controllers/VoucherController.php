<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string'
        ]);

        $user = Auth::user();

        $voucher = Voucher::where('voucher_code', $request->voucher_code)->first();

        if (! $voucher) {
            return back()->with('error', 'Voucher not found');
        }

        if ($voucher->expired_at < now()) {
            return back()->with('error', 'The voucher has expired');
        }

        if ($voucher->limit <= 0) {
            return back()->with('error', 'Voucher has expired');
        }
        $alreadyClaimed = VoucherClaim::where('voucher_id', $voucher->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyClaimed) {
            return back()->with('error', 'Voucher has been used');
        }
        $cartItems = Cart::where('user_id', $user->id)
            ->where('status', 'available')
            ->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Cart kosong');
        }

        $total = $cartItems->sum(fn($item) => $item->ticket->price);

        $discount = $voucher->type === 'percentage'
            ? ($total * $voucher->value) / 100
            : $voucher->value;

        $discount = min($discount, $total);
        DB::transaction(function () use ($voucher, $user) {
            VoucherClaim::create([
                'voucher_id' => $voucher->id,
                'user_id' => $user->id,
            ]);

            $voucher->decrement('limit');
        });

        session([
            'voucher_id' => $voucher->id,
            'voucher_discount' => (int) $discount,
        ]);

        return back()->with('success', 'Voucher successfully used');
    }
}
