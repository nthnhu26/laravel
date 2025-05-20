<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Attraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();

        // Tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Lọc theo loại
        if ($request->has('topic') && !empty($request->topic)) {
            $query->where('topic', $request->topic);
        }

        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Lấy bài viết phân trang
        $posts = $query->with(['author', 'images'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $attractions = Attraction::where('status', 'active')->get();
        return view('admin.posts.form', compact('attractions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'topic' => 'required|string',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|string|max:255',
            'attraction_id' => 'nullable|exists:attractions,attraction_id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->short_description = $request->short_description;
        $post->author_id = Auth::id();
        $post->topic = $request->topic;
        $post->attraction_id = $request->attraction_id;
        $post->status = $request->status;
        $post->tags = $request->tags;
        $post->save();

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            $isFeatured = true; // Ảnh đầu tiên sẽ là ảnh đại diện
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                
                $post->addImage($path, null, $isFeatured);
                
                $isFeatured = false; // Các ảnh tiếp theo không phải ảnh đại diện
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được tạo thành công!');
    }

    public function edit($id)
    {
        $post = Post::with('images')->findOrFail($id);
        $attractions = Attraction::where('status', 'active')->get();
        
        return view('admin.posts.form', compact('post', 'attractions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'topic' => 'required|string',
            'status' => 'required|in:draft,published',
            'tags' => 'nullable|string|max:255',
            'attraction_id' => 'nullable|exists:attractions,attraction_id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->short_description = $request->short_description;
        $post->topic = $request->topic;
        $post->attraction_id = $request->attraction_id;
        $post->status = $request->status;
        $post->tags = $request->tags;
        $post->save();

        // Xử lý hình ảnh
        if ($request->hasFile('images')) {
            $isFeatured = $post->images->isEmpty(); // Nếu chưa có ảnh nào, ảnh đầu tiên sẽ là ảnh đại diện
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('posts', 'public');
                
                $post->addImage($path, null, $isFeatured);
                
                $isFeatured = false; // Các ảnh tiếp theo không phải ảnh đại diện
            }
        }

        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Xóa các hình ảnh liên quan
        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->url);
        }
        
        $post->delete();
        
        return redirect()->route('admin.posts.index')->with('success', 'Bài viết đã được xóa thành công!');
    }

    public function setFeaturedImage($postId, $imageId)
    {
        $post = Post::findOrFail($postId);
        $post->setFeaturedImage($imageId);
        
        return back()->with('success', 'Đã đặt ảnh đại diện thành công!');
    }

    public function removeImage($postId, $imageId)
    {
        $post = Post::findOrFail($postId);
        $image = $post->images()->where('image_id', $imageId)->first();
        
        if ($image) {
            Storage::disk('public')->delete($image->url);
            $post->removeImage($imageId);
        }
        
        return back()->with('success', 'Đã xóa ảnh thành công!');
    }

    public function updateImageCaption(Request $request, $postId, $imageId)
    {
        $request->validate([
            'caption' => 'nullable|string|max:255',
        ]);
        
        $post = Post::findOrFail($postId);
        $post->updateImageCaption($imageId, $request->caption);
        
        return back()->with('success', 'Đã cập nhật chú thích ảnh thành công!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        
        $path = $request->file('image')->store('posts/content', 'public');
        
        return response()->json([
            'url' => asset('storage/' . $path)
        ]);
    }
}