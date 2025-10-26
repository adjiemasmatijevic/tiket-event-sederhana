<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;


class TransactionController extends Controller
{
    public function transactions()
    {
        return view('users.Transactions');
    }

    public function transactions_data()
    {
        $query = Transaction::select([
            'id',
            'tdi_pay_id',
            'amount_total',
            'status',
            'expired_at',
            'created_at'
        ])
            ->orderBy('created_at', 'DESC')
            ->orderBy('expired_at', 'DESC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($transaction) {
                return '<div class="d-flex gap-2">
                            <a href="https://payment.talangdigital.com/transaction-detail/' . $transaction->tdi_pay_id . '" class="btn btn-primary btn-rounded btn-sm"
                                style="color: #ffffff;"
                                target="_blank"
                               <p>Detail</p>
                            </a>

                            <button type="button" class="btn btn-danger btn-rounded btn-sm" 
                                style="color: #ffffff" 
                                data-bs-toggle="modal" 
                                data-bs-target="#DeleteModal" 
                                data-bs-id="' . $transaction->id . '">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>';
            })->make(true);
    }

    public function transactions_cancel(Request $request)
    {
        $transaction = Transaction::find($request->id);

        if (!$transaction) {
            return back()->with('error', 'Transaction not found');
        }

        $tdiApiKey = config('tdi.api_key');

        if (empty($tdiApiKey)) {
            return back()->with('error', 'Checkout service is currently unavailable. Please try again later.');
        }

        $data = [
            "id" => $transaction->tdi_pay_id
        ];

        try {
            $response = Http::post('https://payment.talangdigital.com/api/transaction-cancel', $data);
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to connect to checkout service. Please try again later.');
        }

        if ($response->failed() || !isset($response['status']) || $response['status'] !== 'success') {
            return back()->with('error', 'Failed to initiate checkout. Please try again later.');
        }

        $transaction->status = 'canceled';
        $transaction->save();
        return back()->with('success', 'Transaction has been cancelled');
    }
}
