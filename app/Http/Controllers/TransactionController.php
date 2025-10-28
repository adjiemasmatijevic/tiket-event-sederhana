<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{
    public function transactions()
    {
        $transactions = Transaction::select([
            'id',
            'tdi_pay_id',
            'amount_total',
            'status',
            'expired_at',
            'created_at'
        ])
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'DESC')
            ->orderBy('expired_at', 'DESC')->get();

        return view('users.Transactions', compact('transactions'));
    }

    public function transaction_update(Request $request)
    {
        $payload = $request->all();
        $jsonString = $payload[0];
        $data = json_decode($jsonString);

        $status = $data->status ?? null;
        $id = $data->id ?? null;

        if ($status === 'canceled' || $status === 'expired') {
            $transaction = Transaction::where('tdi_pay_id', $id)->first();

            if ($transaction) {
                $transaction->status = 'canceled';
                $transaction->save();
            }
        } elseif ($status === 'succeeded') {
            $transaction = Transaction::where('tdi_pay_id', $id)->first();

            if ($transaction) {
                $transaction->status = 'success';
                $transaction->save();
            }
        }

        return response()->json([
            'status'  => 'success'
        ]);
    }
}
