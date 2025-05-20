<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceProvider;
use App\Models\Transportation;
use App\Models\TransportBooking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TransportBookingController extends Controller
{
    /**
     * Display a listing of the bookings.
     */
    public function index()
    {
        $user = Auth::user();
        
        // If admin, show all bookings
        if ($user->role === 'admin') {
            $bookings = TransportBooking::with(['transportation', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } 
        // If provider, show only bookings for their transportation
        else {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Bạn cần đăng ký làm nhà cung cấp dịch vụ trước.');
        }
        
        $transportIds = Transportation::where('provider_id', $provider->provider_id)
            ->pluck('transport_id');
            
        $bookings = TransportBooking::with(['transportation', 'user'])
            ->whereIn('transport_id', $transportIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }
    
    return view('admin.transport-bookings.index', compact('bookings'));
}

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $user = Auth::user();
        
        // If admin, get all transportation options
        if ($user->role === 'admin') {
            $transportations = Transportation::where('status', 'available')->get();
        } 
        // If provider, just get their transportation options
        else {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'You need to register as a service provider first.');
            }
            
            $transportations = Transportation::where('provider_id', $provider->provider_id)
                ->where('status', 'available')
                ->get();
        }
        
        $users = User::where('role', 'user')->get();
        
        return view('admin.transport-bookings.create', compact('transportations', 'users'));
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transport_id' => 'required|exists:transportation,transport_id',
            'user_id' => 'required|exists:users,user_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        $transportation = Transportation::findOrFail($request->transport_id);
        
        // Check if user is provider and trying to book another provider's transportation
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if ($provider->provider_id != $transportation->provider_id) {
                return redirect()->back()
                    ->with('error', 'You can only book transportation from your own provider account.')
                    ->withInput();
            }
        }

        // Check if transportation is available for the selected dates
        if (!$transportation->isAvailable($request->start_date, $request->end_date)) {
            return redirect()->back()
                ->with('error', 'The selected transportation is not available for the chosen dates.')
                ->withInput();
        }

        // Calculate total price
        $startDate = new \DateTime($request->start_date);
        $endDate = new \DateTime($request->end_date);
        $interval = $startDate->diff($endDate);
        $days = $interval->days + 1; // Include both start and end days
        $totalPrice = $transportation->price_per_day * $days;

        $booking = new TransportBooking();
        $booking->transport_id = $request->transport_id;
        $booking->user_id = $request->user_id;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->pickup_location = $request->pickup_location;
        $booking->dropoff_location = $request->dropoff_location;
        $booking->total_price = $totalPrice;
        $booking->status = 'pending';
        $booking->payment_status = 'pending';
        $booking->save();

        return redirect()->route('admin.transport-bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified booking.
     */
    public function show($id)
    {
        $booking = TransportBooking::with(['transportation.provider', 'user'])
            ->findOrFail($id);
        
    // Check if user has permission to view this booking
    $user = Auth::user();
    if ($user->role === 'provider') {
        $provider = ServiceProvider::where('user_id', $user->user_id)->first();
        if (!$provider || $provider->provider_id != $booking->transportation->provider_id) {
            return redirect()->route('admin.transport-bookings.index')
                ->with('error', 'Bạn không có quyền xem đơn đặt này.');
        }
    }
    
    return view('admin.transport-bookings.show', compact('booking'));
}

    /**
     * Show the form for editing the specified booking.
     */
    public function edit($id)
    {
        $booking = TransportBooking::findOrFail($id);
        
        // Check if user has permission to edit this booking
        $user = Auth::user();
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider || $provider->provider_id != $booking->transportation->provider_id) {
                return redirect()->route('admin.transport-bookings.index')
                    ->with('error', 'You do not have permission to edit this booking.');
            }
            
            $transportations = Transportation::where('provider_id', $provider->provider_id)
                ->where('status', 'available')
                ->orWhere('transport_id', $booking->transport_id)
                ->get();
        } else {
            $transportations = Transportation::where('status', 'available')
                ->orWhere('transport_id', $booking->transport_id)
                ->get();
        }
        
        $users = User::where('role', 'user')->get();
        
        return view('admin.transport-bookings.edit', compact('booking', 'transportations', 'users'));
    }

    /**
     * Update the specified booking in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'transport_id' => 'required|exists:transportation,transport_id',
            'user_id' => 'required|exists:users,user_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'required|string|max:255',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|in:pending,paid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $booking = TransportBooking::findOrFail($id);
        $transportation = Transportation::findOrFail($request->transport_id);
        
        // Check if user has permission to update this booking
        $user = Auth::user();
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider || $provider->provider_id != $booking->transportation->provider_id) {
                return redirect()->route('admin.transport-bookings.index')
                    ->with('error', 'You do not have permission to update this booking.');
            }
            
            // Ensure provider can't change to another provider's transportation
            if ($transportation->provider_id != $provider->provider_id) {
                return redirect()->back()
                    ->with('error', 'You can only book transportation from your own provider account.')
                    ->withInput();
            }
        }

        // Check if transportation is available for the selected dates (if changing dates or transportation)
        if (($booking->transport_id != $request->transport_id || 
             $booking->start_date != $request->start_date || 
             $booking->end_date != $request->end_date) && 
            !$transportation->isAvailable($request->start_date, $request->end_date, $id)) {
            return redirect()->back()
                ->with('error', 'The selected transportation is not available for the chosen dates.')
                ->withInput();
        }

        // Calculate total price if dates or transportation changed
        if ($booking->transport_id != $request->transport_id || 
            $booking->start_date != $request->start_date || 
            $booking->end_date != $request->end_date) {
            
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $interval = $startDate->diff($endDate);
            $days = $interval->days + 1; // Include both start and end days
            $totalPrice = $transportation->price_per_day * $days;
            $booking->total_price = $totalPrice;
        }

        $booking->transport_id = $request->transport_id;
        $booking->user_id = $request->user_id;
        $booking->start_date = $request->start_date;
        $booking->end_date = $request->end_date;
        $booking->pickup_location = $request->pickup_location;
        $booking->dropoff_location = $request->dropoff_location;
        $booking->status = $request->status;
        $booking->payment_status = $request->payment_status;
        $booking->save();

        // Update transportation status if booking is confirmed or cancelled
        if ($request->status === 'confirmed' && $transportation->status !== 'booked') {
            $transportation->status = 'booked';
            $transportation->save();
        } elseif ($request->status === 'cancelled' && $transportation->status === 'booked') {
            // Check if there are other confirmed bookings for this transportation
            $otherConfirmedBookings = TransportBooking::where('transport_id', $transportation->transport_id)
                ->where('booking_id', '!=', $id)
                ->where('status', 'confirmed')
                ->count();
                
            if ($otherConfirmedBookings === 0) {
                $transportation->status = 'available';
                $transportation->save();
            }
        }

        return redirect()->route('admin.transport-bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    /**
     * Remove the specified booking from storage.
     */
    public function destroy($id)
    {
        $booking = TransportBooking::findOrFail($id);
        
        // Check if user has permission to delete this booking
        $user = Auth::user();
        if ($user->role === 'provider') {
            $provider = ServiceProvider::where('user_id', $user->user_id)->first();
            if (!$provider || $provider->provider_id != $booking->transportation->provider_id) {
                return redirect()->route('admin.transport-bookings.index')
                    ->with('error', 'You do not have permission to delete this booking.');
            }
        }
        
        // Only allow deletion of pending or cancelled bookings
        if (!in_array($booking->status, ['pending', 'cancelled'])) {
            return redirect()->route('admin.transport-bookings.index')
                ->with('error', 'Only pending or cancelled bookings can be deleted.');
        }

        $booking->delete();

        return redirect()->route('admin.transport-bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    /**
     * Update booking status.
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $booking = TransportBooking::with('transportation')->findOrFail($id);
        
    // Check if user has permission to update this booking
    $user = Auth::user();
    if ($user->role === 'provider') {
        $provider = ServiceProvider::where('user_id', $user->user_id)->first();
        if (!$provider || $provider->provider_id != $booking->transportation->provider_id) {
            return redirect()->route('admin.transport-bookings.index')
                ->with('error', 'Bạn không có quyền cập nhật đơn đặt này.');
        }
    }

    $booking->status = $request->status;
    $booking->save();

    // Update transportation status if booking is confirmed or cancelled
    $transportation = $booking->transportation;
    if ($request->status === 'confirmed' && $transportation->status !== 'booked') {
        $transportation->status = 'booked';
        $transportation->save();
    } elseif ($request->status === 'cancelled' && $transportation->status === 'booked') {
        // Check if there are other confirmed bookings for this transportation
        $otherConfirmedBookings = TransportBooking::where('transport_id', $transportation->transport_id)
            ->where('booking_id', '!=', $id)
            ->where('status', 'confirmed')
            ->count();
            
        if ($otherConfirmedBookings === 0) {
            $transportation->status = 'available';
            $transportation->save();
        }
    }

    return redirect()->route('admin.transport-bookings.show', $booking->booking_id)
        ->with('success', 'Cập nhật trạng thái đơn đặt thành công.');
}

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $booking = TransportBooking::findOrFail($id);
        
    // Check if user has permission to update this booking
    $user = Auth::user();
    if ($user->role === 'provider') {
        $provider = ServiceProvider::where('user_id', $user->user_id)->first();
        if (!$provider || $provider->provider_id != $booking->transportation->provider_id) {
            return redirect()->route('admin.transport-bookings.index')
                ->with('error', 'Bạn không có quyền cập nhật đơn đặt này.');
        }
    }

    $booking->payment_status = $request->payment_status;
    $booking->save();

    return redirect()->route('admin.transport-bookings.show', $booking->booking_id)
        ->with('success', 'Cập nhật trạng thái thanh toán thành công.');
}
}
