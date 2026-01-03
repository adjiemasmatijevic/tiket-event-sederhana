<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Ots;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Exception;

class OTSController extends Controller
{
    public function ticket_ots_tickets($id)
    {
        $Tickets = Cart::query()
            ->join('transactions', 'carts.transaction_id', '=', 'transactions.id')
            ->join('tickets', 'carts.ticket_id', '=', 'tickets.id')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->join('ots', 'carts.ots_id', '=', 'ots.id')
            ->where('carts.id', $id)
            ->whereNotNull('carts.ots_id')
            ->where('transactions.status', 'success')
            ->select(
                'carts.id as id_tiket',
                'carts.presence as presence',
                'events.id as event_id',
                'events.image as event_image',
                'events.name as event_name',
                'events.location as event_location',
                'events.time_start as events_time_start',
                'events.time_end as events_time_end',
                'tickets.name as ticket_name',
                'ots.name as ots_name',
                'ots.phone',
            )
            ->first();

        return view('Tickets', compact('Tickets'));
    }

    public function ticket_ots()
    {
        $tickets = \App\Models\Ticket::select('tickets.id', 'tickets.name', 'tickets.price', 'tickets.total', 'events.name as event_name')
            ->join('events', 'events.id', '=', 'tickets.event_id')
            ->orderBy('events.name', 'asc')
            ->orderBy('tickets.name', 'asc')
            ->get();

        return view('admins.OTS', compact('tickets'));
    }

    public function ticket_ots_data()
    {
        $query = Cart::query()
            ->join('tickets', 'tickets.id', '=', 'carts.ticket_id')
            ->join('events', 'events.id', '=', 'tickets.event_id')
            ->join('ots', 'ots.id', '=', 'carts.ots_id')
            ->whereNotNull('carts.ots_id')
            ->orderBy('carts.created_at', 'desc')
            ->orderBy('events.name', 'asc')
            ->orderBy('tickets.name', 'asc')
            ->orderBy('ots.name', 'asc')
            ->select([
                'carts.id',
                'tickets.name AS ticket_name',
                'events.name AS event_name',
                'ots.name AS ots_name',
                'ots.phone',
                'carts.created_at',
            ])
            ->with(['ticket.event', 'ots']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($cart) {
                $url = route('ticket_ots.tickets', $cart->id);
                $message = "Halo! Berikut link tiket Anda:\n$url";

                // ambil nomor hp
                $phone = preg_replace('/\D/', '', $cart->phone); // hanya angka
                if (str_starts_with($phone, '0')) {
                    $phone = '62' . substr($phone, 1);
                }

                // jika belum ada kode negara dan tidak diawali 0
                if (!str_starts_with($phone, '62')) {
                    $phone = '62' . $phone;
                }

                $encodedMessage = urlencode($message);
                $waLink = "https://wa.me/{$phone}?text={$encodedMessage}";

                return '<a href="' . $waLink . '" target="_blank" class="btn btn-success btn-rounded" style="color: #fff;">
                Kirim Tiket
            </a>';
            })
            ->make(true);
    }

    public function ticket_ots_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
            'phone' => 'required|string|max:20',
            'ticket_id' => 'required|exists:tickets,id',
            'payment_method' => 'required|in:cash,qris',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        $ticket = Ticket::where('id', $request->ticket_id)->first();

        if (!$ticket) {
            return back()->with('error', 'Ticket not found');
        }

        $totalSameTicketInCart = Cart::where('ticket_id', $ticket->id)
            ->whereHas('transaction', function ($query) {
                $query->where('status', 'success');
            })
            ->count();

        if ($totalSameTicketInCart + 1 > $ticket->total) {
            return back()->with('error', 'Ticket limit exceeded');
        }

        try {
            $ots = OTS::create([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            $user_ots = User::where('role', 'ots')->first();

            if (!$user_ots) {
                return back()->with('error', 'OTS user not found');
            }

            $cart = new Cart();
            $cart->user_id = $user_ots->id;
            $cart->ticket_id = $ticket->id;
            $cart->ots_id = $ots->id;
            $cart->status = 'checkout';
            $cart->save();

            if ($request->payment_method == 'cash') {
                $transaction = Transaction::create([
                    'user_id' => $user_ots->id,
                    'tdi_pay_id' => Str::uuid(),
                    'amount_total' => $ticket->price,
                    'status' => 'success',
                    'category' => 'cash',
                    'expired_at' => now()->addDays(1),
                ]);

                $cart->transaction_id = $transaction->id;
                $cart->save();

                return back()->with('success', 'OTS created successfully');
            } else if ($request->payment_method == 'qris') {
                $tdiApiKey = config('tdi.api_key');

                if (empty($tdiApiKey)) {
                    return back()->with('error', 'Checkout service is currently unavailable. Please try again later.');
                }

                $totalPrice = $ticket->price;

                $adminFee = ceil($totalPrice * 0.05);
                $totalFull = $totalPrice + $adminFee;

                $data = [
                    'key' => $tdiApiKey,
                    'notif_url' => route('transaction.update'),
                    'redirect_url' => route('ticket_ots'),
                    'notes' => 'Buy tickets - On The Spot',
                    'items' => [],
                    'total' => $totalFull,
                    'fee' => $adminFee,
                    'payment' => 'QRIS',
                    "expiry_minutes" => 15,
                ];

                $data['items'][$ticket->name] = [
                    'name' => $ticket->name,
                    'qty' => 1,
                    'price' => $ticket->price,
                    'total' => $ticket->price,
                ];

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
                    'user_id' => $user_ots->id,
                    'tdi_pay_id' => $response['id'],
                    'amount_total' => $totalFull,
                    'status' => 'pending',
                    'category' => 'payment gateway',
                    'expired_at' => now()->addMinutes(15),
                ]);

                $cart->transaction_id = $transaction->id;
                $cart->save();

                return back()
                    ->with('success', 'OTS created successfully')
                    ->with('payment', 'https://payment.talangdigital.com/transaction-detail/' . $transaction->tdi_pay_id);
            } else {
                return back()->with('error', 'Invalid payment method');
            }
        } catch (Exception $e) {
            Log::error('Failed to create ots: ' . $e->getMessage());
            return back()->with('error', 'Failed to create ots')->withInput();
        }

        return back()->with('success', 'OTS created successfully');
    }

    public function ticket_fisik(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $ots = OTS::create([
                'name' => $request->name,
                'phone' => $request->phone,
            ]);

            $user_ots = User::where('role', 'ots')->first();

            if (!$user_ots) {
                return back()->with('error', 'OTS user not found');
            }

            $cart = new Cart();
            $cart->user_id = $user_ots->id;
            $cart->ticket_id = Ticket::first()->id;
            $cart->ots_id = $ots->id;
            $cart->status = 'checkout';
            $cart->save();

            $transaction = Transaction::create([
                'user_id' => $user_ots->id,
                'tdi_pay_id' => Str::uuid(),
                'amount_total' => 50000,
                'status' => 'success',
                'category' => 'cash',
                'expired_at' => now()->addDays(1),
            ]);

            $cart->transaction_id = $transaction->id;
            $cart->save();
        } catch (Exception $e) {
            Log::error('Failed to create ots: ' . $e->getMessage());
            return back()->with('error', 'Failed to create physical ticket')->withInput();
        }

        return back()->with('success', 'Physical ticket created successfully');
    }
}
