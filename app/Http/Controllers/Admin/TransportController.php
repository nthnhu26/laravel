<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Transport;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transport::query();

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('amenities') && is_array($request->amenities)) {
            $query->whereHas('amenities', function ($q) use ($request) {
                $q->whereIn('amenity_id', $request->amenities);
            });
        }

        $transports = $query->with(['images'])->paginate(10);
        $amenities = Amenity::all();

        return view('frontend.transports.index', compact('transports', 'amenities'));
    }

    public function show($id)
    {
        $transport = Transport::with([
            'provider',
            'amenities',
            'images',
            'reviews.user'
        ])->findOrFail($id);

        // Debug dữ liệu reviews
        Log::info('Transport ID: ' . $id);
        Log::info('Reviews count: ' . $transport->reviews->count());
        Log::info('Reviews data: ', $transport->reviews->toArray());

        // Lấy phương tiện liên quan
        $relatedTransports = Transport::where('transport_id', '!=', $id)
            ->where('type', $transport->type)
            ->with(['images'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('frontend.transports.show', compact('transport', 'relatedTransports'));
    }
}