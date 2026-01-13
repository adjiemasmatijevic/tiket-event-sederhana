<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;



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
        $events = Event::orderBy('name')->get();

        return view('admins.Transaction', compact('events'));
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
    public function trxFilter(Request $request)
    {
        $request->validate([
            'event_id' => 'nullable|exists:events,id'
        ]);

        $query = Transaction::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->filled('event_id')) {
            $query->whereHas('carts.ticket.event', function ($q) use ($request) {
                $q->where('id', $request->event_id);
            });
        }

        $transactions = $query
            ->limit(500)
            ->get()
            ->map(function ($t, $i) {
                return [
                    'no' => $i + 1,
                    'trx_id' => $t->id,
                    'user_name' => $t->user->name ?? '-',
                    'phone' => $t->user->phone ?? '-',
                    'amount_total' => number_format($t->amount_total, 0, ',', '.'),
                    'status' => strtolower($t->status),
                    'expired_at' => $t->expired_at
                        ? (is_string($t->expired_at)
                            ? $t->expired_at
                            : $t->expired_at->format('Y-m-d H:i:s'))
                        : '-',
                    'created_at' => optional($t->created_at)->format('Y-m-d H:i:s') ?? '-',
                ];
            });

        return response()->json($transactions);
    }
}
