<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;
use App\Models\Category;
use App\Models\Amenity;
use App\Models\PlaceImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PlaceController extends Controller
{
    /**
     * Hiển thị danh sách địa điểm
     */
    public function index(Request $request)
    {
        $query = Place::query();
        
        // Tìm kiếm theo từ khóa
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        // Lọc theo danh mục
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Sắp xếp
        $query->orderBy('created_at', 'desc');
        
        // Eager loading các quan hệ
        $query->with(['category', 'images', 'reviews']);
        
        // Phân trang
        $places = $query->paginate(10)->withQueryString();
        
        // Lấy danh sách danh mục
        $categories = Category::all();
        
        return view('admin.places.index', compact('places', 'categories'));
    }

    /**
     * Hiển thị form tạo địa điểm mới
     */
    public function create()
    {
        $categories = Category::all();
        $amenities = Amenity::all();
        
        return view('admin.places.create', compact('categories', 'amenities'));
    }

    /**
     * Lưu địa điểm mới
     */
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name.vi' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'address.vi' => 'required|string|max:255',
            'address.en' => 'nullable|string|max:255',
            'description.vi' => 'nullable|string',
            'description.en' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'nullable|numeric|min:0',
            'opening_hours' => 'nullable|string|max:255',
            'cancellation_policy' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,draft',
            'images.*' => 'nullable|image|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,amenity_id',
        ]);

        try {
            DB::beginTransaction();
            
            // Tạo địa điểm mới với hỗ trợ đa ngôn ngữ
            $place = new Place();
            
            // Thiết lập các trường đa ngôn ngữ
            $place->setTranslations('name', [
                'vi' => $request->input('name.vi'),
                'en' => $request->input('name.en') ?: $request->input('name.vi'),
            ]);
            
            $place->setTranslations('address', [
                'vi' => $request->input('address.vi'),
                'en' => $request->input('address.en') ?: $request->input('address.vi'),
            ]);
            
            $place->setTranslations('description', [
                'vi' => $request->input('description.vi'),
                'en' => $request->input('description.en') ?: $request->input('description.vi'),
            ]);
            
            // Thiết lập các trường thông thường
            $place->category_id = $request->category_id;
            $place->price = $request->price ?? 0;
            $place->opening_hours = $request->opening_hours;
            $place->cancellation_policy = $request->cancellation_policy;
            $place->latitude = $request->latitude;
            $place->longitude = $request->longitude;
            $place->status = $request->status;
            $place->is_featured = $request->has('is_featured') ? 1 : 0;
            $place->created_by = auth()->id();
            
            $place->save();
            
            // Xử lý hình ảnh
            if ($request->hasFile('images')) {
                $isFirst = true;
                foreach ($request->file('images') as $image) {
                    $path = $image->store('places', 'public');
                    
                    $placeImage = new PlaceImage();
                    $placeImage->place_id = $place->place_id;
                    $placeImage->image_url = $path;
                    $placeImage->is_featured = $isFirst;
                    $placeImage->sort_order = 0;
                    $placeImage->save();
                    
                    $isFirst = false;
                }
            }
            
            // Xử lý tiện ích
            if ($request->has('amenities')) {
                $place->amenities()->sync($request->amenities);
            }
            
            DB::commit();
            
            if ($request->input('continue') == '1') {
                return redirect()->route('admin.places.edit', $place->place_id)
                    ->with('success', 'Địa điểm đã được tạo thành công.');
            }
            
            return redirect()->route('admin.places.index')
                ->with('success', 'Địa điểm đã được tạo thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating place: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi tạo địa điểm: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị form chỉnh sửa địa điểm
     */
    public function edit($id)
    {
        $place = Place::with(['category', 'images', 'amenities'])->findOrFail($id);
        $categories = Category::all();
        $amenities = Amenity::all();
        
        return view('admin.places.edit', compact('place', 'categories', 'amenities'));
    }

    /**
     * Cập nhật địa điểm
     */
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $request->validate([
            'name.vi' => 'required|string|max:255',
            'name.en' => 'nullable|string|max:255',
            'address.vi' => 'required|string|max:255',
            'address.en' => 'nullable|string|max:255',
            'description.vi' => 'nullable|string',
            'description.en' => 'nullable|string',
            'category_id' => 'required|exists:categories,category_id',
            'price' => 'nullable|numeric|min:0',
            'opening_hours' => 'nullable|string|max:255',
            'cancellation_policy' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,draft',
            'images.*' => 'nullable|image|max:2048',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,amenity_id',
        ]);

        try {
            DB::beginTransaction();
            
            $place = Place::findOrFail($id);
            
            // Cập nhật các trường đa ngôn ngữ
            $place->setTranslations('name', [
                'vi' => $request->input('name.vi'),
                'en' => $request->input('name.en') ?: $request->input('name.vi'),
            ]);
            
            $place->setTranslations('address', [
                'vi' => $request->input('address.vi'),
                'en' => $request->input('address.en') ?: $request->input('address.vi'),
            ]);
            
            $place->setTranslations('description', [
                'vi' => $request->input('description.vi'),
                'en' => $request->input('description.en') ?: $request->input('description.vi'),
            ]);
            
            // Cập nhật các trường thông thường
            $place->category_id = $request->category_id;
            $place->price = $request->price ?? 0;
            $place->opening_hours = $request->opening_hours;
            $place->cancellation_policy = $request->cancellation_policy;
            $place->latitude = $request->latitude;
            $place->longitude = $request->longitude;
            $place->status = $request->status;
            $place->is_featured = $request->has('is_featured') ? 1 : 0;
            $place->updated_by = auth()->id();
            
            $place->save();
            
            // Xử lý hình ảnh mới
            if ($request->hasFile('images')) {
                $hasFeatured = $place->images()->where('is_featured', true)->exists();
                
                foreach ($request->file('images') as $image) {
                    $path = $image->store('places', 'public');
                    
                    $placeImage = new PlaceImage();
                    $placeImage->place_id = $place->place_id;
                    $placeImage->image_url = $path;
                    $placeImage->is_featured = !$hasFeatured;
                    $placeImage->sort_order = $place->images()->count();
                    $placeImage->save();
                    
                    $hasFeatured = true;
                }
            }
            
            // Xử lý tiện ích
            if ($request->has('amenities')) {
                $place->amenities()->sync($request->amenities);
            } else {
                $place->amenities()->detach();
            }
            
            DB::commit();
            
            if ($request->input('continue') == '1') {
                return redirect()->route('admin.places.edit', $place->place_id)
                    ->with('success', 'Địa điểm đã được cập nhật thành công.');
            }
            
            return redirect()->route('admin.places.index')
                ->with('success', 'Địa điểm đã được cập nhật thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating place: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật địa điểm: ' . $e->getMessage());
        }
    }

    /**
     * Xóa địa điểm
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $place = Place::findOrFail($id);
            
            // Xóa hình ảnh
            foreach ($place->images as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
            
            // Xóa các quan hệ
            $place->amenities()->detach();
            
            // Xóa địa điểm
            $place->delete();
            
            DB::commit();
            
            return redirect()->route('admin.places.index')
                ->with('success', 'Địa điểm đã được xóa thành công.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting place: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi xóa địa điểm: ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa hình ảnh
     */
    public function removeImage(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:place_images,image_id',
        ]);
        
        try {
            $image = PlaceImage::findOrFail($request->image_id);
            $placeId = $image->place_id;
            
            // Xóa file
            Storage::disk('public')->delete($image->image_url);
            
            // Nếu là ảnh chính, đặt ảnh khác làm ảnh chính
            if ($image->is_featured) {
                $nextImage = PlaceImage::where('place_id', $placeId)
                    ->where('image_id', '!=', $image->image_id)
                    ->first();
                    
                if ($nextImage) {
                    $nextImage->is_featured = true;
                    $nextImage->save();
                }
            }
            
            // Xóa record
            $image->delete();
            
            return redirect()->back()->with('success', 'Hình ảnh đã được xóa thành công.');
            
        } catch (\Exception $e) {
            Log::error('Error removing image: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xóa hình ảnh.');
        }
    }
    
    /**
     * Đặt ảnh chính
     */
    public function setFeaturedImage(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:place_images,image_id',
            'place_id' => 'required|exists:places,place_id',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Bỏ đánh dấu tất cả ảnh chính
            PlaceImage::where('place_id', $request->place_id)
                ->update(['is_featured' => false]);
                
            // Đặt ảnh mới làm ảnh chính
            $image = PlaceImage::findOrFail($request->image_id);
            $image->is_featured = true;
            $image->save();
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Đã đặt làm ảnh chính.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting featured image: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi đặt ảnh chính.');
        }
    }
}
