@extends('layouts.app')

@section('title', $post->title)

@section('styles')
<style>
    .post-header {
        margin-bottom: 2rem;
    }

    .post-title {
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 1rem;
    }

    .post-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
        color: #6c757d;
    }

    .post-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .post-featured-image {
        border-radius: 1rem;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .post-featured-image img {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
    }

    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #343a40;
    }

    .post-content p {
        margin-bottom: 1.5rem;
    }

    .post-content h2,
    .post-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .post-content img {
        max-width: 100%;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
    }

    .post-content blockquote {
        border-left: 4px solid #0078D4;
        padding-left: 1.5rem;
        font-style: italic;
        margin: 1.5rem 0;
    }

    .post-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 2rem 0;
    }

    .post-tag {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background-color: #f8f9fa;
        color: #495057;
        border-radius: 2rem;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .post-tag:hover {
        background-color: #0078D4;
        color: white;
    }

    .post-share {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem 0;
        border-top: 1px solid #e9ecef;
        border-bottom: 1px solid #e9ecef;
        margin: 2rem 0;
    }

    .post-share-title {
        font-weight: 600;
        margin-bottom: 0;
    }

    .post-share-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .post-share-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .author-bio {
        background-color: #f8f9fa;
        border-radius: 1rem;
        padding: 2rem;
        margin: 2rem 0;
    }

    .author-bio-image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
    }

    .author-bio-name {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .author-bio-role {
        color: #6c757d;
        margin-bottom: 1rem;
    }

    .related-posts {
        margin: 3rem 0;
    }

    .related-post-card {
        height: 100%;
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .related-post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .related-post-image {
        height: 180px;
        object-fit: cover;
    }

    .comments-section {
        margin: 3rem 0;
    }

    .comment-form {
        margin-bottom: 2rem;
    }

    .comment-form textarea {
        border-radius: 0.5rem;
        padding: 1rem;
        resize: vertical;
    }

    .comment-item {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .comment-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-content {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-top: 0.5rem;
    }

    .comment-reply-button {
        color: #0078D4;
        background: none;
        border: none;
        padding: 0;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .child-comment {
        margin-top: 1rem;
        margin-left: 3rem;
        padding-left: 1rem;
        border-left: 2px solid #0078D4;
    }

    .sidebar {
        position: sticky;
        top: 2rem;
    }

    .sidebar-widget {
        background-color: white;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .sidebar-title {
        position: relative;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .sidebar-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 50px;
        height: 2px;
        background: linear-gradient(45deg, #0078D4, #00C4B4);
    }

    .popular-post {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .popular-post:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .popular-post-image {
        width: 70px;
        height: 70px;
        border-radius: 0.5rem;
        object-fit: cover;
    }

    .popular-post-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .popular-post-date {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .tags-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tag-cloud-item {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background-color: #f8f9fa;
        color: #495057;
        border-radius: 2rem;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .tag-cloud-item:hover {
        background-color: #0078D4;
        color: white;
    }

    .related-place {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .related-place-image {
        width: 70px;
        height: 70px;
        border-radius: 0.5rem;
        object-fit: cover;
    }

    .related-place-title {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .related-place-address {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .related-place-rating {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .star-filled {
        color: #ffc107;
    }

    .star-empty {
        color: #e9ecef;
    }

    @media (max-width: 991.98px) {
        .sidebar {
            position: static;
            margin-top: 3rem;
        }
    }

    .reply-form {
        margin-top: 1rem;
        margin-bottom: 1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        border-left: 2px solid #0078D4;
        display: none;
        /* Ẩn mặc định */
    }

    .reply-form textarea {
        border-radius: 0.5rem;
        padding: 0.75rem;
        resize: vertical;
    }

    .reply-form-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 0.75rem;
    }

    /* CSS cho các cấp bình luận */
    .comment-level-1 {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .comment-level-2 {
        margin-top: 1rem;
        margin-left: 3rem;
        padding-left: 1rem;
        border-left: 2px solid #0078D4;
    }

    .comment-level-3 {
        margin-top: 1rem;
        margin-left: 2rem;
        padding-left: 1rem;
        border-left: 2px solid #00C4B4;
    }

    .reply-indicator {
        color: #0078D4;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .reply-indicator i {
        transform: rotate(180deg);
        display: inline-block;
    }

    .comment-actions {
        display: flex;
        gap: 1rem;
    }

    .comment-delete-button {
        color: #dc3545;
        background: none;
        border: none;
        padding: 0;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .comment-delete-button:hover {
        text-decoration: underline;
    }

    /* CSS cho modal xác nhận xóa */
    .modal-confirm {
        color: #636363;
    }

    .modal-confirm .modal-content {
        padding: 20px;
        border-radius: 5px;
        border: none;
    }

    .modal-confirm .modal-header {
        border-bottom: none;
        position: relative;
    }

    .modal-confirm .modal-title {
        text-align: center;
        font-size: 26px;
        margin: 30px 0 -15px;
    }

    .modal-confirm .modal-footer {
        border: none;
        text-align: center;
        border-radius: 5px;
        font-size: 13px;
    }

    .modal-confirm .icon-box {
        color: #fff;
        position: absolute;
        margin: 0 auto;
        left: 0;
        right: 0;
        top: -70px;
        width: 95px;
        height: 95px;
        border-radius: 50%;
        z-index: 9;
        background: #dc3545;
        padding: 15px;
        text-align: center;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.1);
    }

    .modal-confirm .icon-box i {
        font-size: 58px;
        position: relative;
        top: 3px;
    }
    
    /* Highlight cho @mention */
    .mention {
        color: #0078D4;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">Bài viết</a></li>
            <li class="breadcrumb-item"><a href="{{ route('posts.byType', $post->topic) }}">{{ $post->topicLabel }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <article class="post">
                <header class="post-header">
                    <h1 class="post-title">{{ $post->title }}</h1>
                    <div class="post-meta">
                        <div class="post-meta-item">
                            <i class="bi bi-person-circle"></i>
                            <span>{{ $post->author->full_name ?? 'Ẩn danh' }}</span>
                        </div>
                        <div class="post-meta-item">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ $post->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="post-meta-item">
                            <i class="bi bi-folder"></i>
                            <span>{{ $post->topicLabel }}</span>
                        </div>
                        <div class="post-meta-item">
                            <i class="bi bi-chat-left-text"></i>
                            <span>{{ $post->comments->count() }} bình luận</span>
                        </div>
                        <div class="post-meta-item">
                            <i class="bi bi-eye"></i>
                            <span>{{ $post->views }} lượt xem</span>
                        </div>
                    </div>

                    @if($post->tagsArray)
                    <div class="post-tags">
                        @foreach($post->tagsArray as $tag)
                        <a href="{{ route('posts.byTag', $tag) }}" class="post-tag">
                            #{{ $tag }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </header>

                @if($post->images->isNotEmpty())
                <figure class="post-featured-image">
                    <img src="{{ asset('storage/' . ($post->images->where('is_featured', true)->first()?->url ?? $post->images->first()->url)) }}"
                        alt="{{ $post->title }}">
                    @if($post->images->where('is_featured', true)->first()?->caption)
                    <figcaption class="text-center mt-2 text-muted">
                        {{ $post->images->where('is_featured', true)->first()->caption }}
                    </figcaption>
                    @endif
                </figure>
                @endif

                @if($post->short_description)
                <div class="lead p-4 bg-light rounded mb-4">
                    {{ $post->short_description }}
                </div>
                @endif

                <div class="post-content">
                    {{ $post->content }}
                </div>

                <div class="post-share">
                    <h5 class="post-share-title">Chia sẻ bài viết:</h5>
                    <div class="post-share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            class="post-share-button btn btn-outline-primary" target="_blank">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                            class="post-share-button btn btn-outline-info" target="_blank">
                            <i class="bi bi-twitter"></i> Twitter
                        </a>
                        <a href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode(request()->url()) }}"
                            class="post-share-button btn btn-outline-secondary">
                            <i class="bi bi-envelope"></i> Email
                        </a>
                    </div>
                </div>

                <div class="author-bio">
                    <div class="row">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <img src="{{ $post->author->avatar ?? asset('images/avatar-placeholder.jpg') }}"
                                class="author-bio-image" alt="{{ $post->author->full_name }}">
                        </div>
                        <div class="col-md-10">
                            <h5 class="author-bio-name">{{ $post->author->full_name ?? 'Ẩn danh' }}</h5>
                            <p class="author-bio-role">Tác giả</p>
                            <p class="mb-0">{{ $post->author->bio ?? 'Tác giả chuyên viết về các địa điểm du lịch và chia sẻ kinh nghiệm du lịch.' }}</p>
                        </div>
                    </div>
                </div>

                @if($relatedPosts->isNotEmpty())
                <div class="related-posts">
                    <h3 class="section-title">Bài viết liên quan</h3>
                    <div class="row">
                        @foreach($relatedPosts as $relatedPost)
                        <div class="col-md-6 mb-4">
                            <div class="card related-post-card">
                                @if($relatedPost->images->isNotEmpty())
                                <img src="{{ asset('storage/' . ($relatedPost->images->where('is_featured', true)->first()?->url ?? $relatedPost->images->first()->url)) }}"
                                    class="card-img-top related-post-image" alt="{{ $relatedPost->title }}">
                                @else
                                <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top related-post-image" alt="Placeholder">
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2 text-muted small">
                                        <div>
                                            <i class="bi bi-calendar3 me-1"></i> {{ $relatedPost->created_at->format('d/m/Y') }}
                                        </div>
                                        <div>
                                            <i class="bi bi-chat-left-text me-1"></i> {{ $relatedPost->comments->count() }}
                                        </div>
                                    </div>
                                    <h5 class="card-title">{{ Str::limit($relatedPost->title, 60) }}</h5>
                                    <p class="card-text">{{ Str::limit(strip_tags($relatedPost->content), 80) }}</p>
                                    <a href="{{ route('posts.show', $relatedPost->post_id) }}" class="btn btn-outline-primary w-100">Đọc tiếp</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Phần bình luận đã được cập nhật -->
                <div class="comments-section">
                    <h3 class="section-title">Bình luận ({{ $post->comments->count() }})</h3>

                    <!-- Form bình luận chính -->
                    <div class="comment-form" id="main-comment-form">
                        @auth
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                            <div class="mb-3">
                                <label for="main-comment-content" class="form-label">Để lại bình luận của bạn</label>
                                <textarea class="form-control" id="main-comment-content" name="content" rows="4" required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Gửi bình luận
                                </button>
                            </div>
                        </form>
                        @else
                        <div class="alert alert-info">
                            <a href="{{ route('login') }}" class="alert-link">Đăng nhập</a> để gửi bình luận.
                        </div>
                        @endauth
                    </div>

                    <!-- Danh sách bình luận -->
                    <div class="comments-list mt-4">
                        @forelse($post->comments->where('parent_id', null) as $comment)
                        <div class="comment-item comment-level-1" id="comment-{{ $comment->post_comment_id }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <img src="{{ $comment->user->avatar ?? asset('images/avatar-placeholder.jpg') }}"
                                        class="comment-avatar" alt="{{ $comment->user->full_name }}">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">{{ $comment->user->full_name ?? 'Ẩn danh' }}</h6>
                                        <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <div class="comment-content">
                                        <p class="mb-0">{{ $comment->content }}</p>
                                    </div>
                                    <div class="comment-actions mt-2">
                                        @auth
                                        <button class="comment-reply-button"
                                            onclick="showReplyForm('comment-{{ $comment->post_comment_id }}-reply', {{ $comment->post_comment_id }}, '{{ addslashes($comment->user->full_name ?? 'Ẩn danh') }}')">
                                            <i class="bi bi-reply"></i> Trả lời
                                        </button>

                                        @if(Auth::id() == $comment->user_id || (Auth::user() && Auth::user()->isAdmin()))
                                        <button class="comment-delete-button"
                                            data-bs-toggle="modal" data-bs-target="#deleteCommentModal{{ $comment->post_comment_id }}">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>

                                        <!-- Modal xác nhận xóa -->
                                        <div class="modal fade" id="deleteCommentModal{{ $comment->post_comment_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Xác nhận xóa</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Bạn có chắc chắn muốn xóa bình luận này?</p>
                                                        <p class="text-muted small">Lưu ý: Khi xóa bình luận gốc, tất cả bình luận trả lời cũng sẽ bị xóa.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                        <form action="{{ route('comments.destroy', $comment->post_comment_id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Xóa</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endauth
                                    </div>

                                    <!-- Form trả lời inline cho bình luận cấp 1 -->
                                    @auth
                                    <div class="reply-form" id="comment-{{ $comment->post_comment_id }}-reply">
                                        <div class="reply-indicator">
                                            <i class="bi bi-reply"></i> Đang trả lời <strong id="replying-to-{{ $comment->post_comment_id }}">{{ $comment->user->full_name ?? 'Ẩn danh' }}</strong>
                                        </div>
                                        <form action="{{ route('comments.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                                            <input type="hidden" name="parent_id" value="{{ $comment->post_comment_id }}">
                                            <div class="mb-2">
                                                <textarea class="form-control" name="content" rows="3" required></textarea>
                                            </div>
                                            <div class="reply-form-buttons">
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="hideReplyForm('comment-{{ $comment->post_comment_id }}-reply')">
                                                    <i class="bi bi-x-circle"></i> Hủy
                                                </button>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-send"></i> Gửi trả lời
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    @endauth

                                    <!-- Bình luận cấp 2 -->
                                    @foreach($comment->replies as $level2Comment)
                                    <div class="comment-level-2" id="comment-{{ $level2Comment->post_comment_id }}">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-2">
                                                <img src="{{ $level2Comment->user->avatar ?? asset('images/avatar-placeholder.jpg') }}"
                                                    class="rounded-circle" alt="{{ $level2Comment->user->full_name }}" width="40" height="40">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0">{{ $level2Comment->user->full_name ?? 'Ẩn danh' }}</h6>
                                                    <small class="text-muted">{{ $level2Comment->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <div class="comment-content">
                                                    <p class="mb-0">{{ $level2Comment->content }}</p>
                                                </div>
                                                <div class="comment-actions mt-2">
                                                    @auth
                                                    <button class="comment-reply-button"
                                                        onclick="showReplyForm('comment-{{ $level2Comment->post_comment_id }}-reply', {{ $level2Comment->post_comment_id }}, '{{ addslashes($level2Comment->user->full_name ?? 'Ẩn danh') }}')">
                                                        <i class="bi bi-reply"></i> Trả lời
                                                    </button>

                                                    @if(Auth::id() == $level2Comment->user_id || (Auth::user() && Auth::user()->isAdmin()))
                                                    <button class="comment-delete-button"
                                                        data-bs-toggle="modal" data-bs-target="#deleteCommentModal{{ $level2Comment->post_comment_id }}">
                                                        <i class="bi bi-trash"></i> Xóa
                                                    </button>

                                                    <!-- Modal xác nhận xóa -->
                                                    <div class="modal fade" id="deleteCommentModal{{ $level2Comment->post_comment_id }}" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Xác nhận xóa</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Bạn có chắc chắn muốn xóa bình luận này?</p>
                                                                    <p class="text-muted small">Lưu ý: Khi xóa bình luận, tất cả bình luận trả lời cũng sẽ bị xóa.</p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                    <form action="{{ route('comments.destroy', $level2Comment->post_comment_id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @endauth
                                                </div>

                                                <!-- Form trả lời inline cho bình luận cấp 2 -->
                                                @auth
                                                <div class="reply-form" id="comment-{{ $level2Comment->post_comment_id }}-reply">
                                                    <div class="reply-indicator">
                                                        <i class="bi bi-reply"></i> Đang trả lời <strong id="replying-to-{{ $level2Comment->post_comment_id }}">{{ $level2Comment->user->full_name ?? 'Ẩn danh' }}</strong>
                                                    </div>
                                                    <form action="{{ route('comments.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="post_id" value="{{ $post->post_id }}">
                                                        <input type="hidden" name="parent_id" value="{{ $level2Comment->post_comment_id }}">
                                                        <div class="mb-2">
                                                            <textarea class="form-control" name="content" rows="3" required></textarea>
                                                        </div>
                                                        <div class="reply-form-buttons">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                onclick="hideReplyForm('comment-{{ $level2Comment->post_comment_id }}-reply')">
                                                                <i class="bi bi-x-circle"></i> Hủy
                                                            </button>
                                                            <button type="submit" class="btn btn-sm btn-primary">
                                                                <i class="bi bi-send"></i> Gửi trả lời
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                                @endauth

                                                <!-- Bình luận cấp 3 -->
                                                @foreach($level2Comment->replies as $level3Comment)
                                                <div class="comment-level-3" id="comment-{{ $level3Comment->post_comment_id }}">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-2">
                                                            <img src="{{ $level3Comment->user->avatar ?? asset('images/avatar-placeholder.jpg') }}"
                                                                class="rounded-circle" alt="{{ $level3Comment->user->full_name }}" width="35" height="35">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <h6 class="mb-0">{{ $level3Comment->user->full_name ?? 'Ẩn danh' }}</h6>
                                                                <small class="text-muted">{{ $level3Comment->created_at->format('d/m/Y H:i') }}</small>
                                                            </div>
                                                            <div class="comment-content">
                                                                <p class="mb-0">{!! preg_replace('/@([^\s]+)/', '<span class="mention">@$1</span>', $level3Comment->content) !!}</p>
                                                            </div>
                                                            <div class="comment-actions mt-2">
                                                                @auth
                                                                @if(Auth::id() == $level3Comment->user_id || (Auth::user() && Auth::user()->isAdmin()))
                                                                <button class="comment-delete-button"
                                                                    data-bs-toggle="modal" data-bs-target="#deleteCommentModal{{ $level3Comment->post_comment_id }}">
                                                                    <i class="bi bi-trash"></i> Xóa
                                                                </button>

                                                                <!-- Modal xác nhận xóa -->
                                                                <div class="modal fade" id="deleteCommentModal{{ $level3Comment->post_comment_id }}" tabindex="-1" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">Xác nhận xóa</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <p>Bạn có chắc chắn muốn xóa bình luận này?</p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                                                <form action="{{ route('comments.destroy', $level3Comment->post_comment_id) }}" method="POST">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @endauth
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="bi bi-chat-square-text text-muted display-4"></i>
                            <p class="mt-3">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </article>
        </div>

        <div class="col-lg-4">
            <div class="sidebar">
                <div class="sidebar-widget">
                    <h4 class="sidebar-title">Tìm kiếm</h4>
                    <form action="{{ route('posts.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm bài viết...">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="sidebar-widget">
                    <h4 class="sidebar-title">Bài viết phổ biến</h4>
                    @foreach($popularPosts as $popularPost)
                    <div class="popular-post">
                        @if($popularPost->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $popularPost->images->first()->url) }}"
                            class="popular-post-image" alt="{{ $popularPost->title }}">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="width: 70px; height: 70px;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                        @endif
                        <div>
                            <h6 class="popular-post-title">
                                <a href="{{ route('posts.show', $popularPost->post_id) }}" class="text-decoration-none">
                                    {{ Str::limit($popularPost->title, 50) }}
                                </a>
                            </h6>
                            <div class="popular-post-date">
                                <i class="bi bi-calendar3 me-1"></i> {{ $popularPost->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="sidebar-widget">
                    <h4 class="sidebar-title">Tags</h4>
                    <div class="tags-cloud">
                        @foreach($popularTags as $tag)
                        <a href="{{ route('posts.byTag', $tag->name) }}" class="tag-cloud-item">
                            #{{ $tag->name }} ({{ $tag->count }})
                        </a>
                        @endforeach
                    </div>
                </div>

                @if($relatedPlace)
                <div class="sidebar-widget">
                    <h4 class="sidebar-title">Địa điểm liên quan</h4>
                    <div class="related-place">
                        @if($relatedPlace->images->isNotEmpty())
                        <img src="{{ asset('storage/' . $relatedPlace->images->first()->url) }}"
                            class="related-place-image" alt="{{ $relatedPlace->name }}">
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="width: 70px; height: 70px;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                        @endif
                        <div>
                            <h6 class="related-place-title">
                                <a href="{{ route('attractions.show', $relatedPlace->attraction_id) }}" class="text-decoration-none">
                                    {{ $relatedPlace->name }}
                                </a>
                            </h6>
                            <div class="related-place-address">{{ Str::limit($relatedPlace->address, 50) }}</div>
                            @if($relatedPlace->reviews->count() > 0)
                            <div class="related-place-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=round($relatedPlace->reviews->avg('rating')))
                                    <i class="bi bi-star-fill star-filled"></i>
                                    @else
                                    <i class="bi bi-star star-empty"></i>
                                    @endif
                                    @endfor
                                    <span class="text-muted ms-1">({{ $relatedPlace->reviews->count() }})</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('attractions.show', $relatedPlace->attraction_id) }}" class="btn btn-outline-primary btn-sm">
                            Xem chi tiết địa điểm
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    // Hiển thị form trả lời
    function showReplyForm(formId, commentId, userName) {
        // Ẩn tất cả các form trả lời khác
        document.querySelectorAll('.reply-form').forEach(form => {
            form.style.display = 'none';
        });

        // Hiển thị form trả lời được chọn
        const replyForm = document.getElementById(formId);
        if (replyForm) {
            replyForm.style.display = 'block';

            // Focus vào textarea
            const textarea = replyForm.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }

            // Cuộn đến form trả lời
            replyForm.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    }

    // Ẩn form trả lời
    function hideReplyForm(formId) {
        const replyForm = document.getElementById(formId);
        if (replyForm) {
            replyForm.style.display = 'none';
        }
    }

    // Hiển thị thông báo thành công hoặc lỗi
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
        alert("{{ session('success') }}");
        @endif

        @if(session('error'))
        alert("{{ session('error') }}");
        @endif
    });
</script>
@endsection