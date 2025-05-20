<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Amenity;
use App\Models\Post;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttractionController extends Controller
{
    public function index(Request $request)
    {
        $query = Attraction::query();

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

        $attractions = $query->with(['images'])->paginate(10);
        $amenities = Amenity::all();

        return view('frontend.attractions.index', compact('attractions', 'amenities'));
    }

    public function show($id)
    {
        $attraction = Attraction::with([
            'amenities',
            'images',
            'reviews.user', // Tải quan hệ user cho đánh giá
            'events'
        ])->findOrFail($id);

        // Lấy bài post liên quan
        $relatedPosts = Post::with(['author'])
            ->where('attraction_id', $id)
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        // Lấy địa điểm liên quan
        $relatedAttractions = Attraction::where('attraction_id', '!=', $id)
            ->where(function ($query) use ($attraction) {
                $query->where('type', $attraction->type)
                    ->orWhereRaw('ST_Distance_Sphere(
                        POINT(longitude, latitude),
                        POINT(?, ?)
                    ) < 10000', [$attraction->longitude, $attraction->latitude]);
            })
            ->with(['images'])
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('frontend.attractions.show', compact('attraction', 'relatedPosts', 'relatedAttractions'));
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'entity_id' => 'required|exists:attractions,attraction_id',
            'entity_type' => 'required|in:attraction',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $review = new Review();
        $review->user_id = Auth::id();
        $review->entity_id = $request->entity_id;
        $review->entity_type = $request->entity_type;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->status = 'pending';

        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $images[] = $path;
            }
            $review->images = json_encode($images);
        }

        $review->save();

        return redirect()->back()->with('success', 'Đánh giá đã được gửi và đang chờ duyệt.');
    }
}