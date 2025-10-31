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
  public function adminTransactions()
{
    return view('admins.Transaction');
}
public function transactionAll()
{
    $transactions = Transaction::with('user')
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($t, $i) {
            return [
                'no' => $i + 1,
                'tdi_pay_id' => $t->tdi_pay_id,
                'user_name' => $t->user->name ?? '-',
                'amount_total' => number_format($t->amount_total, 0, ',', '.'),
                'status' => ucfirst($t->status),
                'expired_at' => $t->expired_at 
                    ? (is_string($t->expired_at) 
                        ? $t->expired_at 
                        : $t->expired_at->format('Y-m-d H:i:s')) 
                    : '-',
                'created_at' => $t->created_at 
                    ? $t->created_at->format('Y-m-d H:i:s') 
                    : '-',
            ];
        });

    return response()->json($transactions);
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