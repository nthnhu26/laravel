<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Place;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Hiển thị danh sách sự kiện trong admin
     */
    public function index(Request $request)
    {
        $query = Event::with('place');
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Lọc theo địa điểm
        if ($request->has('place_id') && $request->place_id != '') {
            $query->where('place_id', $request->place_id);
        }
        
        // Lọc theo thời gian
        if ($request->has('date_range') && $request->date_range != '') {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = Carbon::createFromFormat('d/m/Y', $dates[0])->startOfDay();
                $endDate = Carbon::createFromFormat('d/m/Y', $dates[1])->endOfDay();
                
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q2) use ($startDate, $endDate) {
                          $q2->where('start_date', '<=', $startDate)
                             ->where('end_date', '>=', $endDate);
                      });
                });
            }
        }
        
        // Tìm kiếm theo từ khóa
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%");
            });
        }
        
        // Sắp xếp
        $sortField = $request->sort_by ?? 'start_date';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);
        
        $events = $query->paginate(10)->withQueryString();
        $places = Place::orderBy('name')->get();
        
        return view('admin.events.index', compact('events', 'places'));
    }

    /**
     * Hiển thị form tạo sự kiện mới
     */
    public function create()
    {
        $places = Place::orderBy('name')->get();
        return view('admin.events.create', compact('places'));
    }

    /**
     * Lưu sự kiện mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'place_id' => 'nullable|exists:places,place_id',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);
        
        // Tạo sự kiện mới
        Event::create($validated);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Sự kiện đã được tạo thành công.');
    }

    /**
     * Hiển thị chi tiết sự kiện
     */
    public function show($id)
    {
        $event = Event::with('place')->findOrFail($id);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Hiển thị form chỉnh sửa sự kiện
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $places = Place::orderBy('name')->get();
        return view('admin.events.edit', compact('event', 'places'));
    }

    /**
     * Cập nhật sự kiện
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'place_id' => 'nullable|exists:places,place_id',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);
        
        // Cập nhật sự kiện
        $event->update($validated);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Sự kiện đã được cập nhật thành công.');
    }

    /**
     * Xóa sự kiện
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Sự kiện đã được xóa thành công.');
    }

    /**
     * Cập nhật trạng thái sự kiện dựa trên thời gian
     */
    public function updateStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->updateStatusBasedOnTime();
        
        return response()->json([
            'success' => true,
            'status' => $event->status
        ]);
    }

    /**
     * Cập nhật trạng thái tất cả sự kiện dựa trên thời gian
     */
    public function updateAllStatuses()
    {
        $events = Event::all();
        $updated = 0;
        
        foreach ($events as $event) {
            $oldStatus = $event->status;
            $event->updateStatusBasedOnTime();
            
            if ($oldStatus != $event->status) {
                $updated++;
            }
        }
        
        return redirect()->route('admin.events.index')
            ->with('success', "Đã cập nhật trạng thái cho {$updated} sự kiện.");
    }

    /**
     * Xuất dữ liệu sự kiện ra CSV
     */
    public function export()
    {
        $events = Event::with('place')->get();
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="events.csv"',
        ];
        
        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add headers
            fputcsv($file, [
                'ID', 'Tiêu đề', 'Mô tả', 'Thời gian bắt đầu', 'Thời gian kết thúc',
                'Địa điểm', 'Địa điểm liên quan', 'Trạng thái', 'Ngày tạo', 'Cập nhật lần cuối'
            ]);
            
            // Add data
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->event_id,
                    $event->title,
                    $event->description,
                    $event->start_date,
                    $event->end_date,
                    $event->location,
                    $event->place ? $event->place->name : '',
                    $event->status,
                    $event->created_at,
                    $event->updated_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Thống kê sự kiện
     */
    public function statistics()
    {
        $stats = [
            'total' => Event::count(),
            'upcoming' => Event::where('status', 'upcoming')->count(),
            'ongoing' => Event::where('status', 'ongoing')->count(),
            'completed' => Event::where('status', 'completed')->count(),
            'by_month' => DB::table('events')
                ->select(DB::raw('MONTH(start_date) as month, YEAR(start_date) as year, COUNT(*) as count'))
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
            'by_place' => DB::table('events')
                ->join('places', 'events.place_id', '=', 'places.place_id')
                ->select('places.name', DB::raw('COUNT(*) as count'))
                ->groupBy('places.name')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
        ];
        
        return view('admin.events.statistics', compact('stats'));
    }
}
