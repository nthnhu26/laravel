<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostCommentController extends Controller
{
    /**
     * Lưu bình luận mới
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,post_id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:post_comments,post_comment_id'
        ]);

        // Kiểm tra xem bài viết có tồn tại không
        $post = Post::findOrFail($request->post_id);

        // Xác định cấp độ của bình luận
        $level = 1; // Mặc định là bình luận gốc
        $replyToUsername = null;
        
        if ($request->parent_id) {
            $parentComment = PostComment::findOrFail($request->parent_id);
            
            // Nếu parent đã có parent (cấp 2) thì bình luận này là cấp 3
            if ($parentComment->parent_id) {
                $level = 3;
                $replyToUsername = $parentComment->user->full_name ?? 'Ẩn danh';
            } else {
                // Nếu parent là bình luận gốc thì bình luận này là cấp 2
                $level = 2;
            }
        }

        // Xử lý nội dung bình luận nếu là cấp 3 và có reply to username
        $content = $request->content;
        if ($level === 3 && $replyToUsername) {
            // Thêm @ mention vào đầu nội dung
            $content = "@{$replyToUsername} " . $content;
        }

        // Tạo bình luận mới
        $comment = new PostComment();
        $comment->post_id = $request->post_id;
        $comment->user_id = Auth::id();
        $comment->content = $content;
        $comment->parent_id = $request->parent_id;
        $comment->save();

        return redirect()->back()->with('success', 'Bình luận đã được gửi thành công.');
    }

    /**
     * Xóa bình luận
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $comment = PostComment::findOrFail($id);
        
        // Kiểm tra quyền: chỉ cho phép người dùng xóa bình luận của họ hoặc admin
        if (Auth::id() != $comment->user_id && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này.');
        }
        
        // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
        DB::beginTransaction();
        
        try {
            // Nếu là bình luận gốc (cấp 1), xóa tất cả bình luận con
            if (!$comment->parent_id) {
                // Lấy tất cả ID bình luận cấp 2
                $level2CommentIds = PostComment::where('parent_id', $comment->post_comment_id)->pluck('post_comment_id')->toArray();
                
                // Xóa tất cả bình luận cấp 3 (con của cấp 2)
                if (!empty($level2CommentIds)) {
                    PostComment::whereIn('parent_id', $level2CommentIds)->delete();
                }
                
                // Xóa tất cả bình luận cấp 2
                PostComment::where('parent_id', $comment->post_comment_id)->delete();
            } 
            // Nếu là bình luận cấp 2, xóa tất cả bình luận con cấp 3
            elseif ($comment->parent_id && !$comment->parent->parent_id) {
                PostComment::where('parent_id', $comment->post_comment_id)->delete();
            }
            
            // Xóa bình luận hiện tại
            $comment->delete();
            
            DB::commit();
            return redirect()->back()->with('success', 'Bình luận đã được xóa thành công.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xóa bình luận: ' . $e->getMessage());
        }
    }
}