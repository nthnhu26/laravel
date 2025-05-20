<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
 
    public function create(Request $request)
    {
        $hotel_id = $request->query('hotel_id');
        return view('admin.rooms.create', [
            'resourceName' => 'Phòng',
            'hotel_id' => $hotel_id,
        ]);
    }

    public function store(RoomRequest $request)
    {
        $validated = $request->validated();
        $room = Room::create($validated);

        sweetalert()->success('Phòng được tạo thành công.');

        return redirect()->route('admin.hotels.edit', $room->hotel_id);
    }
}