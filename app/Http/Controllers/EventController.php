<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('attraction_id') && $request->attraction_id) {
            $query->where('attraction_id', $request->attraction_id);
        }

        $events = $query->with(['images', 'attraction'])->paginate(10);
        $attractions = \App\Models\Attraction::all();

        return view('frontend.events.index', compact('events', 'attractions'));
    }

    public function show($id)
    {
        $event = Event::with([
            'attraction',
            'images',
            'reviews.user'
        ])->findOrFail($id);

        // Debug dữ liệu reviews
        Log::info('Event ID: ' . $id);
        Log::info('Reviews count: ' . $event->reviews->count());
        Log::info('Reviews data: ', $event->reviews->toArray());

        // Lấy sự kiện liên quan
        $relatedEvents = Event::where('event_id', '!=', $id)
            ->where(function ($query) use ($event) {
                $query->where('attraction_id', $event->attraction_id)
                    ->orWhere('status', $event->status);
            })
            ->with(['images'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('frontend.events.show', compact('event', 'relatedEvents'));
    }
}