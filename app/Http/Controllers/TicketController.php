<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Event;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use Exception;

class TicketController extends Controller
{
    public function tickets()
    {
        $events = Event::where('status', 'active')->orderBy('name', 'ASC')->get(['id', 'name']);
        return view('admins.Tickets', compact('events'));
    }

    public function tickets_data()
    {
        $query = Ticket::select([
            'tickets.id',
            'events.name as event_name',
            'tickets.name',
            'tickets.total',
            'tickets.price',
            'tickets.option_value',
            'tickets.created_at',
            'events.time_start',
            'events.time_end',
        ])
            ->join('events', 'tickets.event_id', '=', 'events.id');
        if (request()->event_id) {
            $query->where('tickets.event_id', request()->event_id);
        }
        $query->orderBy('events.time_start', 'DESC')
            ->orderBy('events.time_end', 'DESC')
            ->orderBy('tickets.created_at', 'DESC');

        return DataTables::of($query)
            ->addIndexColumn()
            //hitung sold dan remaining status success
            ->addColumn('sold', function ($ticket) {
                $sold = Cart::whereHas('transaction', function ($q) {
                    $q->where('status', 'success');
                })->where('ticket_id', $ticket->id)->count();
                return $sold;
            })
            ->addColumn('remaining', function ($ticket) {
                $sold = Cart::whereHas('transaction', function ($q) {
                    $q->where('status', 'success');
                })->where('ticket_id', $ticket->id)->count();
                $remaining = $ticket->total - $sold;
                return $remaining;
            })
            ->addColumn('action', function ($ticket) {
                return '<div class="d-flex">
                            <button type="button" class="btn btn-warning btn-rounded"
                                style="color: #ffffff; margin-right: 10px"
                                data-toggle="modal"
                                data-target="#EditModal"
                                data-id="' . $ticket->id . '">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-rounded"
                                style="color: #ffffff"
                                data-toggle="modal"
                                data-target="#DeleteModal"
                                data-id="' . $ticket->id . '">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>';
            })->make(true);
    }

    public function tickets_data_id($id)
    {
        return response()->json(Ticket::with('event:id,name')->where('id', $id)->first());
    }

    public function tickets_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:128|unique:tickets,name',
            'description' => 'required|string',
            'total' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'option_value' => 'nullable|string|max:255', // Assuming option_value is a string, adjust as necessary

        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            Ticket::create([
                'event_id' => $request->event_id,
                'name' => $request->name,
                'description' => $request->description,
                'total' => $request->total,
                'price' => $request->price,
                'option_value' => $request->option_value,

            ]);
        } catch (Exception $e) {
            Log::error('Failed to create ticket: ' . $e->getMessage());
            return back()->with('error', 'Failed to create ticket')->withInput();
        }

        return back()->with('success', 'Ticket created successfully');
    }

    public function tickets_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:tickets,id',
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:128|unique:tickets,name,' . $request->id,
            'description' => 'required|string',
            'total' => 'required|integer|min:1',
            'price' => 'required|integer|min:0',
            'option_value' => 'nullable|string|max:255', // Assuming option_value is a string, adjust as necessary
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $ticket = Ticket::findOrFail($request->id);
            $ticket->event_id = $request->event_id;
            $ticket->name = $request->name;
            $ticket->description = $request->description;
            $ticket->total = $request->total;
            $ticket->price = $request->price;
            $ticket->option_value = $request->option_value;
            $ticket->save();
        } catch (Exception $e) {
            Log::error('Failed to update ticket: ' . $e->getMessage());
            return back()->with('error', 'Failed to update ticket');
        }

        return back()->with('success', 'Ticket updated successfully');
    }

    public function tickets_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:tickets,id',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $ticket = Ticket::findOrFail($request->id);
            $ticket->delete();
        } catch (Exception $e) {
            Log::error('Failed to delete ticket: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete ticket');
        }

        return back()->with('success', 'Ticket deleted successfully');
    }

    public function my_tickets()
    {
        $userId = Auth::id();

        $myTickets = Cart::query()
            ->join('transactions', 'carts.transaction_id', '=', 'transactions.id')
            ->join('tickets', 'carts.ticket_id', '=', 'tickets.id')
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->where('carts.user_id', $userId)
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
            )
            ->get();

        return view('users.MyTickets', compact('myTickets'));
    }
}
