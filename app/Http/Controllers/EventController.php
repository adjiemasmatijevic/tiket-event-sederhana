<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\File;
use Exception;

class EventController extends Controller
{
    public function events()
    {
        return view('admins.Events');
    }

    public function events_data()
    {
        $query = Event::select([
            'id',
            'name',
            'location',
            'time_start',
            'time_end',
            'status',
            'created_at',
        ])
            ->orderBy('time_start', 'DESC')
            ->orderBy('time_end', 'DESC')
            ->orderBy('created_at', 'DESC');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($event) {
                return '<div class="d-flex">
                            <button type="button" class="btn btn-warning btn-rounded" 
                                style="color: #ffffff; margin-right: 10px" 
                                data-toggle="modal" 
                                data-target="#EditModal" 
                                data-id="' . $event->id . '">
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-rounded" 
                                style="color: #ffffff" 
                                data-toggle="modal" 
                                data-target="#DeleteModal" 
                                data-id="' . $event->id . '">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>';
            })->make(true);
    }

    public function events_data_id($id)
    {
        return response()->json(Event::where('id', $id)->first());
    }

    public function events_create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg',
            'name' => 'required|string|max:64|unique:events,name',
            'description' => 'required|string',
            'location' => 'required|string|max:128',
            'time_start' => 'required|date',
            'time_end' => 'required|date',
            'status' => 'required|in:active,not active',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        $path = public_path('storage/event_images');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
        }

        $manager = new ImageManager(new Driver());
        $attachment = $request->file('image');
        $filename   = md5(time());
        $path       = public_path('storage/event_images');
        $fullPath   = $path . '/' . $filename . '.webp';
        $img = $manager->read($attachment->getRealPath());
        $img->toWebp(50)->save($fullPath);

        try {
            Event::create([
                'image' => $filename,
                'name' => $request->name,
                'slug' => Str::slug($request->name, '-'),
                'description' => $request->description,
                'location' => $request->location,
                'status' => $request->status,
                'time_start' => $request->time_start,
                'time_end' => $request->time_end,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to create event: ' . $e->getMessage());
            return back()->with('error', 'Failed to create event')->withInput();
        }

        return back()->with('success', 'Event created successfully');
    }

    public function events_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:events,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'name' => 'required|string|max:64|unique:events,name,' . $request->id,
            'description' => 'required|string',
            'location' => 'required|string|max:128',
            'time_start' => 'required|date',
            'time_end' => 'required|date',
            'status' => 'required|in:active,not active',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $event = Event::findOrFail($request->id);

            if ($request->hasFile('image')) {
                $manager = new ImageManager(new Driver());
                $attachment = $request->file('image');
                $filename   = md5(time());
                $path       = public_path('storage/event_images');
                $fullPath   = $path . '/' . $filename . '.webp';
                $img = $manager->read($attachment->getRealPath());
                $img->toWebp(50)->save($fullPath);

                $thumbnailPath = public_path("storage/event_images/{$event->image}.webp");
                if (File::exists($thumbnailPath)) {
                    File::delete($thumbnailPath);
                }

                $event->image = $filename;
            }

            $event->name = $request->name;
            $event->slug = Str::slug($request->name, '-');
            $event->description = $request->description;
            $event->location = $request->location;
            $event->time_start = $request->time_start;
            $event->time_end = $request->time_end;
            $event->status = $request->status;
            $event->save();
        } catch (Exception $e) {
            Log::error('Failed to update event: ' . $e->getMessage());
            return back()->with('error', 'Failed to update event')->withInput();
        }

        return back()->with('success', 'Event updated successfully');
    }

    public function events_delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:events,id',
        ]);

        if ($validator->fails()) {
            return back()
                ->with('error', $validator->errors()->first())
                ->withInput();
        }

        try {
            $event = Event::findOrFail($request->id);

            $thumbnailPath = public_path("storage/event_images/{$event->image}.webp");
            if (File::exists($thumbnailPath)) {
                File::delete($thumbnailPath);
            }

            $event->delete();
        } catch (Exception $e) {
            Log::error('Failed to delete event: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete event');
        }

        return back()->with('success', 'Event deleted successfully');
    }
}
