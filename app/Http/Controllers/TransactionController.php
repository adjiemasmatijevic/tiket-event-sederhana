<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;

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
            'amount',
            'status',
            'expired_at',
            'created_at'
        ])
            ->orderRaw("FIELD(status, 'pending', 'success', 'cancelled')")
            ->orderBy('created_at', 'DESC')
            ->orderBy('expired_at', 'DESC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($ticket) {
                return '<div class="d-flex">
                            <button type="button" class="btn btn-danger btn-rounded"
                                style="color: #ffffff"
                                data-toggle="modal"
                                data-target="#DeleteModal"
                                data-id="' . $ticket->id . '">
                                <p>Cancel</p>
                            </button>
                        </div>';
            })->make(true);
    }
}
