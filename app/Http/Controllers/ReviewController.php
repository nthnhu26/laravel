<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    protected $entityMap = [
        'attraction' => \App\Models\Attraction::class,
        'hotel' => \App\Models\Hotel::class,
        'restaurant' => \App\Models\Restaurant::class,
        'dish' => \App\Models\Dish::class,
        'tour' => \App\Models\Tour::class,
        'transport' => \App\Models\Transport::class,
    ];

    public function store(Request $request)
    {
        $request->validate([
            'entity_id' => 'required|integer',
            'entity_type' => 'required|in:attraction,hotel,restaurant,dish,tour,transport',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $entityType = $request->entity_type;
        if (!isset($this->entityMap[$entityType])) {
            return redirect()->back()->with('error', 'Loại thực thể không hợp lệ.');
        }

        $modelClass = $this->entityMap[$entityType];
        $entity = $modelClass::find($request->entity_id);
        if (!$entity) {
            return redirect()->back()->with('error', 'Thực thể không tồn tại.');
        }

        $review = new Review();
        $review->user_id = Auth::id();
        $review->entity_id = $request->entity_id;
        $review->entity_type = $entityType;
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

        Log::info('Review saved: ', $review->toArray());

        return redirect()->back()->with('success', 'Đánh giá đã được gửi và đang chờ duyệt.');
    }
}