<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\TourDetail;
use App\Models\ServiceProvider;
use App\Models\Place;
use App\Models\TourBooking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TourController extends Controller
{
    /**
     * Hiển thị danh sách tour
     */
    public function index(Request $request)
    {
        $query = Tour::query();
        
        // Tìm kiếm theo từ khóa
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo nhà cung cấp
        if ($request->has('provider_id') && $request->provider_id) {
            $query->where('provider_id', $request->provider_id);
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Sắp xếp
        $query->orderBy('created_at', 'desc');
        
        // Eager loading các quan hệ
        $query->with(['provider', 'details.place']);
        
        // Phân trang
        $tours = $query->paginate(10)->withQueryString();
        
        // Lấy danh sách nhà cung cấp
        $providers = ServiceProvider::all();
        
        return view('admin.tours.index', compact('tours', 'providers'));
    }

    /**
     * Hiển thị form tạo tour mới
     */
    public function create()
    {
        $providers = ServiceProvider::where('status', 'active')->get();
        $places = Place::where('status', 'active')->get();
        
        return view('admin.tours.create', compact('providers', 'places'));
    }

    /**
     * Lưu tour mới
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'max_people' => 'nullable|integer|min:1',
            'provider_id' => 'required|exists:service_providers,provider_id',
            'status' => 'required|in:active,inactive',
            'day_number.*' => 'required|integer|min:1',
            'day_description.*' => 'required|string',
            'day_place_id.*' => 'nullable|exists:places,place_id',
        ]);

        try {
            DB::beginTransaction();
            
            // Tạo tour mới
            $tour = new Tour();
            $tour->name = $request->name;
            $tour->description = $request->description;
            $tour->duration = $request->duration;
            $tour->price = $request->price;
            $tour->max_people = $request->max_people;
            $tour->provider_id = $request->provider_id;
            $tour->status = $request->status;
            $tour->created_by = auth()->id();
            
            $tour->save();
            
            // Lưu chi tiết lịch trình tour
            if ($request->has('day_number')) {
                foreach ($request->day_number as $key => $dayNumber) {
                    $tourDetail = new TourDetail();
                    $tourDetail->tour_id = $tour->tour_id;
                    $tourDetail->day_number = $dayNumber;
                    $tourDetail->description = $request->day_description[$key];
                    $tourDetail->place_id = $request->day_place_id[$key] ?? null;
                    $tourDetail->save();
                }
            }
            
            DB::commit();
            
            if ($request->input('continue') == '1') {
                return redirect()->route('admin.tours.edit', $tour->tour_id)
                    ->with('success', 'Tour đã được tạo thành công.');
            }
            
            return redirect()->route('admin.tours.index')
                ->with('success', 'Tour đã được tạo thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating tour: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi tạo tour: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form chỉnh sửa tour
     */
    public function edit($id)
    {
        $tour = Tour::with(['details.place'])->findOrFail($id);
        $providers = ServiceProvider::where('status', 'active')->get();
        $places = Place::where('status', 'active')->get();
        
        return view('admin.tours.edit', compact('tour', 'providers', 'places'));
    }

    /**
     * Cập nhật tour
     */
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'max_people' => 'nullable|integer|min:1',
            'provider_id' => 'required|exists:service_providers,provider_id',
            'status' => 'required|in:active,inactive',
            'day_number.*' => 'required|integer|min:1',
            'day_description.*' => 'required|string',
            'day_place_id.*' => 'nullable|exists:places,place_id',
        ]);

        try {
            DB::beginTransaction();
            
            $tour = Tour::findOrFail($id);
            
            // Cập nhật thông tin tour
            $tour->name = $request->name;
            $tour->description = $request->description;
            $tour->duration = $request->duration;
            $tour->price = $request->price;
            $tour->max_people = $request->max_people;
            $tour->provider_id = $request->provider_id;
            $tour->status = $request->status;
            
            $tour->save();
            
            // Xóa chi tiết lịch trình cũ
            TourDetail::where('tour_id', $tour->tour_id)->delete();
            
            // Lưu chi tiết lịch trình mới
            if ($request->has('day_number')) {
                foreach ($request->day_number as $key => $dayNumber) {
                    $tourDetail = new TourDetail();
                    $tourDetail->tour_id = $tour->tour_id;
                    $tourDetail->day_number = $dayNumber;
                    $tourDetail->description = $request->day_description[$key];
                    $tourDetail->place_id = $request->day_place_id[$key] ?? null;
                    $tourDetail->save();
                }
            }
            
            DB::commit();
            
            if ($request->input('continue') == '1') {
                return redirect()->route('admin.tours.edit', $tour->tour_id)
                    ->with('success', 'Tour đã được cập nhật thành công.');
            }
            
            return redirect()->route('admin.tours.index')
                ->with('success', 'Tour đã được cập nhật thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating tour: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật tour: ' . $e->getMessage());
        }
    }

    /**
     * Xóa tour
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $tour = Tour::findOrFail($id);
            
            // Kiểm tra xem tour có đặt chỗ không
            if ($tour->bookings()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa tour này vì đã có người đặt.');
            }
            
            // Xóa chi tiết lịch trình
            TourDetail::where('tour_id', $tour->tour_id)->delete();
            
            // Xóa tour
            $tour->delete();
            
            DB::commit();
            
            return redirect()->route('admin.tours.index')
                ->with('success', 'Tour đã được xóa thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting tour: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa tour: ' . $e->getMessage());
        }
    }
    
    /**
     * Quản lý đặt tour
     */
    public function bookings(Request $request)
    {
        $query = TourBooking::query();
        
        // Tìm kiếm theo từ khóa
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('booking_code', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo tour
        if ($request->has('tour_id') && $request->tour_id) {
            $query->where('tour_id', $request->tour_id);
        }
        
        // Lọc theo trạng thái
        if ($request->has('booking_status') && $request->booking_status) {
            $query->where('booking_status', $request->booking_status);
        }
        
        // Lọc theo trạng thái thanh toán
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Sắp xếp
        $query->orderBy('created_at', 'desc');
        
        // Eager loading các quan hệ
        $query->with(['tour', 'user']);
        
        // Phân trang
        $bookings = $query->paginate(10)->withQueryString();
        
        // Lấy danh sách tour
        $tours = Tour::where('status', 'active')->get();
        
        return view('admin.tours.bookings', compact('bookings', 'tours'));
    }
    
    /**
     * Xem chi tiết đặt tour
     */
    public function showBooking($id)
    {
        $booking = TourBooking::with(['tour', 'user'])->findOrFail($id);
        
        return view('admin.tours.booking-detail', compact('booking'));
    }
    
    /**
     * Cập nhật trạng thái đặt tour
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $request->validate([
            'booking_status' => 'required|in:pending,confirmed,completed,cancelled,refunded',
            'payment_status' => 'required|in:pending,paid,partial,refunded,failed',
            'admin_notes' => 'nullable|string',
        ]);
        
        try {
            $booking = TourBooking::findOrFail($id);
            $booking->booking_status = $request->booking_status;
            $booking->payment_status = $request->payment_status;
            $booking->admin_notes = $request->admin_notes;
            $booking->save();
            
            return redirect()->back()
                ->with('success', 'Cập nhật trạng thái đặt tour thành công.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật trạng thái: ' . $e->getMessage());
        }
    }
}
