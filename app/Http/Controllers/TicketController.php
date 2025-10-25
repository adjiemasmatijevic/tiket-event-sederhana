<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
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
            'tickets.created_at'
        ])
            ->join('events', 'tickets.event_id', '=', 'events.id')
            ->orderBy('tickets.created_at', 'DESC');

        return DataTables::of($query)
            ->addIndexColumn()
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
            ]);
        } catch (Exception $e) {
            Log::error('Gagal membuat tiket: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat tiket')->withInput();
        }

        return back()->with('success', 'Tiket berhasil dibuat');
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
            $ticket->save();
        } catch (Exception $e) {
            Log::error('Gagal memperbarui tiket: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui tiket')->withInput();
        }

        return back()->with('success', 'Tiket berhasil diperbarui');
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
            Log::error('Gagal menghapus tiket: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus tiket');
        }

        return back()->with('success', 'Tiket berhasil dihapus');
    }
}
