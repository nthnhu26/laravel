<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    /**
     * Hiển thị danh sách tiện ích
     */
    public function index()
    {
        $amenities = Amenity::all();
        return view('admin.amenities.index', compact('amenities'));
    }

    /**
     * Hiển thị form tạo tiện ích mới
     */
    public function create()
    {
        return view('admin.amenities.create');
    }

    /**
     * Lưu tiện ích mới vào database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'icon' => 'nullable|max:50'
        ]);

        Amenity::create($validated);

        return redirect()->route('admin.amenities.index')
            ->with('success', 'Thêm tiện ích thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa tiện ích
     */
    public function edit($id)
    {
        $amenity = Amenity::findOrFail($id);
        return view('admin.amenities.edit', compact('amenity'));
    }

    /**
     * Cập nhật tiện ích
     */
    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'icon' => 'nullable|max:50'
        ]);

        $amenity->update($validated);

        return redirect()->route('admin.amenities.index')
            ->with('success', 'Cập nhật tiện ích thành công!');
    }

    /**
     * Xóa tiện ích
     */
    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);
        $amenityName = $amenity->name;
        
        $amenity->delete();

        return redirect()->route('admin.amenities.index')
            ->with('success', "Xóa tiện ích '$amenityName' thành công!");
    }

}