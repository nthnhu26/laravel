<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục
     */
    public function index()
    {
        $categories = Category::with('parent')->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Hiển thị form tạo danh mục mới
     */
    public function create()
    {
        $parentCategories = Category::all();
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Lưu danh mục mới vào database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,category_id'
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa danh mục
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        
        // Lấy tất cả danh mục có thể làm danh mục cha
        $parentCategories = Category::all();
        
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Cập nhật danh mục
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|max:100',
            'description' => 'nullable',
            'parent_id' => 'nullable|exists:categories,category_id'
        ]);
        
        // Kiểm tra không cho phép chọn chính nó làm danh mục cha
        if ($validated['parent_id'] == $id) {
            return back()->withErrors(['parent_id' => 'Không thể chọn chính danh mục này làm danh mục cha'])
                        ->withInput();
        }
        
        // Kiểm tra không chọn danh mục con làm danh mục cha (tránh lặp vô hạn)
        if ($validated['parent_id']) {
            $childIds = $this->getAllChildIds($id);
            if (in_array($validated['parent_id'], $childIds)) {
                return back()->withErrors(['parent_id' => 'Không thể chọn danh mục con làm danh mục cha'])
                            ->withInput();
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa danh mục
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Cập nhật các danh mục con: set parent_id = null
            Category::where('parent_id', $id)->update(['parent_id' => null]);
            
            // Xóa danh mục
            $category->delete();
            
            DB::commit();
            
            return redirect()->route('admin.categories.index')
                ->with('success', 'Xóa danh mục thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi xóa danh mục: ' . $e->getMessage());
        }
    }
    
    /**
     * Lấy tất cả ID của danh mục con (bao gồm cả con của con)
     */
    private function getAllChildIds($categoryId)
    {
        $childIds = [];
        $directChildren = Category::where('parent_id', $categoryId)->pluck('category_id')->toArray();
        
        $childIds = array_merge($childIds, $directChildren);
        
        foreach ($directChildren as $childId) {
            $childIds = array_merge($childIds, $this->getAllChildIds($childId));
        }
        
        return $childIds;
    }
}