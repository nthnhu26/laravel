<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', __('Vui lòng đăng nhập để đặt phòng.'));
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,room_id',
            'hotel_id' => 'required|exists:hotels,hotel_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'number_of_people' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $room = Room::findOrFail($request->room_id);
        $hotel = Hotel::findOrFail($request->hotel_id);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $nights = $startDate->diffInDays($endDate);
        $totalPrice = $room->price_per_night * $nights;

        if ($totalPrice <= 0) {
            return redirect()->back()->with('error', __('Tổng giá không hợp lệ. Vui lòng kiểm tra ngày nhận và trả phòng.'));
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'service_type' => 'hotel',
            'service_id' => $hotel->hotel_id,
            'booking_date' => now(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'number_of_people' => $request->number_of_people,
            'special_requests' => $request->special_requests,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Chuyển hướng đến view xác nhận với thông tin booking
        return view('bookings.confirm', compact('booking', 'hotel', 'room'));
    }
}