<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\Attraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query()->where('status', 'published');

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

        // Lấy bài viết phân trang
        $posts = $query->with(['author', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        // Lấy bài viết nổi bật (có lượt xem cao nhất)
        $featuredPosts = Post::where('status', 'published')
            ->with(['author', 'images'])
            ->orderBy('views', 'desc')
            ->limit(3)
            ->get();

        // Lấy loại bài viết phổ biến
        $popularTypes = DB::table('posts')
            ->select('topic', DB::raw('count(*) as post_count'))
            ->where('status', 'published')
            ->groupBy('topic')
            ->orderBy('post_count', 'desc')
            ->limit(5)
            ->get();

        $totalPosts = Post::where('status', 'published')->count();

        return view('frontend.posts.index', compact('posts', 'featuredPosts', 'popularTypes', 'totalPosts'));
    }

    public function show($id)
    {
        $post = Post::with(['author', 'images', 'comments.user', 'comments.replies.user', 'attraction'])
            ->findOrFail($id);

        // Tăng lượt xem
        $post->incrementViews();

        // Lấy bài viết liên quan (cùng chủ đề hoặc có tags tương tự)
        $relatedPosts = Post::where('status', 'published')
            ->where('post_id', '!=', $post->post_id)
            ->where(function ($query) use ($post) {
                $query->where('topic', $post->topic);

                if (!empty($post->tags)) {
                    foreach ($post->tagsArray as $tag) {
                        $query->orWhere('tags', 'like', "%{$tag}%");
                    }
                }
            })
            ->with(['author', 'images'])
            ->limit(4)
            ->get();

        // Lấy bài viết phổ biến
        $popularPosts = Post::where('status', 'published')
            ->where('post_id', '!=', $post->post_id)
            ->with('images')
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        // Lấy tags phổ biến
        $popularTags = DB::table('posts')
            ->where('status', 'published')
            ->whereNotNull('tags')
            ->get()
            ->flatMap(function ($post) {
                return array_map('trim', explode(',', $post->tags));
            })
            ->countBy()
            ->map(function ($count, $name) {
                return (object) ['name' => $name, 'count' => $count];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values();

        // Lấy địa điểm liên quan
        $relatedPlace = $post->attraction;

        return view('frontend.posts.show', compact('post', 'relatedPosts', 'popularPosts', 'popularTags', 'relatedPlace'));
    }

    public function byType($type)
    {
        return $this->index(request()->merge(['topic' => $type]));
    }

    public function byTag($tag)
    {
        $posts = Post::where('status', 'published')
            ->where('tags', 'like', "%{$tag}%")
            ->with(['author', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $popularTypes = DB::table('posts')
            ->select('topic', DB::raw('count(*) as post_count'))
            ->where('status', 'published')
            ->groupBy('topic')
            ->orderBy('post_count', 'desc')
            ->limit(5)
            ->get();

        $totalPosts = Post::where('status', 'published')->count();

        return view('frontend.posts.index', compact('posts', 'popularTypes', 'totalPosts'));
    }

    // Chỉ cập nhật phương thức storeComment, giữ nguyên các phương thức khác
    public function storeComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,post_id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:post_comments,post_comment_id'
        ]);

        // Kiểm tra cấp độ của bình luận cha (nếu có)
        if ($request->parent_id) {
            $parentComment = PostComment::findOrFail($request->parent_id);

            // Nếu bình luận cha đã là cấp 3, không cho phép trả lời thêm
            if (!$parentComment->canReply()) {
                return redirect()->back()->with('error', 'Không thể trả lời bình luận này vì đã đạt giới hạn cấp độ.');
            }
        }

        $comment = new PostComment();
        $comment->post_id = $request->post_id;
        $comment->user_id = Auth::id();
        $comment->content = $request->content;
        $comment->parent_id = $request->parent_id ?: null;
        $comment->status = 'approved'; // Hoặc 'pending' nếu cần duyệt
        $comment->save();

        return redirect()->back()->with('success', 'Bình luận của bạn đã được gửi thành công!');
    }

    public function destroyComment($id)
    {
        $comment = PostComment::findOrFail($id);

        // Kiểm tra quyền: chỉ cho phép người dùng xóa bình luận của họ hoặc admin
        if (Auth::id() != $comment->user_id && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này.');
        }

        // Nếu bình luận có các trả lời, chỉ đánh dấu là đã xóa thay vì xóa hoàn toàn
        if ($comment->replies->count() > 0) {
            $comment->content = '[Bình luận đã bị xóa]';
            $comment->save();
            return redirect()->back()->with('success', 'Bình luận đã được xóa.');
        }

        // Nếu không có trả lời, xóa hoàn toàn
        $comment->delete();

        return redirect()->back()->with('success', 'Bình luận đã được xóa.');
    }
}
