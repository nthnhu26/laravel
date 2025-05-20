@extends('layouts.app')

@section('title', 'Bài viết du lịch')

@section('styles')
<style>
    .post-card {
        transition: all 0.3s ease;
        border-radius: 1rem;
        overflow: hidden;
        height: 100%;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: none;
    }
    
    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .post-card .card-img-top {
        height: 220px;
        object-fit: cover;
    }
    
    .post-card .card-body {
        padding: 1.5rem;
    }
    
    .post-card .card-title {
        font-weight: 700;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }
    
    .post-card .card-text {
        color: #6c757d;
        margin-bottom: 1rem;
    }
    
    .post-card .card-footer {
        background-color: transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem 1.5rem;
    }
    
    .topic-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 10;
        padding: 0.35rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: linear-gradient(45deg, #0078D4, #00C4B4);
        color: white;
    }
    
    .sidebar {
        position: sticky;
        top: 2rem;
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
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
    
    .search-form .form-control {
        border-radius: 2rem;
        padding: 0.75rem 1.25rem;
        border: 1px solid #e9ecef;
    }
    
    .search-form .btn {
        border-radius: 2rem;
        padding: 0.75rem 1.5rem;
    }
    
    .topic-list .list-group-item {
        border: none;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
        background: transparent;
        transition: all 0.2s ease;
    }
    
    .topic-list .list-group-item:last-child {
        border-bottom: none;
    }
    
    .topic-list .list-group-item:hover {
        color: #0078D4;
        padding-left: 0.5rem;
    }
    
    .topic-list .badge {
        background: linear-gradient(45deg, #0078D4, #00C4B4);
        color: white;
        font-weight: 500;
    }
    
    .featured-section {
        margin-bottom: 3rem;
    }
    
    .section-title {
        position: relative;
        font-weight: 700;
        margin-bottom: 2rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 80px;
        height: 2px;
        background: linear-gradient(45deg, #0078D4, #00C4B4);
    }
    
    .pagination {
        margin-top: 3rem;
    }
    
    .pagination .page-link {
        border-radius: 50%;
        margin: 0 0.25rem;
        border: none;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #495057;
        font-weight: 500;
    }
    
    .pagination .page-item.active .page-link {
        background: linear-gradient(45deg, #0078D4, #00C4B4);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }
    
    .empty-state p {
        color: #6c757d;
        font-size: 1.1rem;
    }
    
    @media (max-width: 991.98px) {
        .sidebar {
            position: static;
            margin-bottom: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="sidebar">
                <h4 class="sidebar-title">Tìm kiếm</h4>
                <form action="{{ route('posts.index') }}" method="GET" class="search-form mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm bài viết...">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>

                <h4 class="sidebar-title">Chủ đề</h4>
                <div class="list-group topic-list mb-4">
                    <a href="{{ route('posts.index') }}" class="list-group-item d-flex justify-content-between align-items-center {{ !request('topic') ? 'fw-bold text-primary' : '' }}">
                        Tất cả
                        <span class="badge rounded-pill">{{ $totalPosts }}</span>
                    </a>
                    @foreach($popularTypes as $type)
                    <a href="{{ route('posts.byType', $type->topic) }}" class="list-group-item d-flex justify-content-between align-items-center {{ request('topic') == $type->topic ? 'fw-bold text-primary' : '' }}">
                        {{ (new App\Models\Post)->fill(['topic' => $type->topic])->topicLabel }}
                        <span class="badge rounded-pill">{{ $type->post_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            @if(request()->has('search') && !empty(request('search')))
                <div class="alert alert-info mb-4">
                    <h5 class="mb-0">Kết quả tìm kiếm: "{{ request('search') }}"</h5>
                    <p class="mb-0">Tìm thấy {{ $posts->total() }} bài viết</p>
                </div>
            @elseif(request()->has('topic') && !empty(request('topic')))
                <div class="alert alert-info mb-4">
                    <h5 class="mb-0">Chủ đề: {{ (new App\Models\Post)->fill(['topic' => request('topic')])->topicLabel }}</h5>
                    <p class="mb-0">Tìm thấy {{ $posts->total() }} bài viết</p>
                </div>
            @endif

            @if(isset($featuredPosts) && $featuredPosts->isNotEmpty() && !request()->has('search') && !request()->has('topic'))
                <div class="featured-section">
                    <h3 class="section-title">Bài viết nổi bật</h3>
                    <div class="row">
                        @foreach($featuredPosts as $post)
                            <div class="col-md-4 mb-4">
                                <div class="card post-card">
                                    <div class="position-relative">
                                        @if($post->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $post->images->where('is_featured', true)->first()?->url ?? $post->images->first()->url) }}" 
                                                class="card-img-top" alt="{{ $post->title }}">
                                        @else
                                            <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="Placeholder">
                                        @endif
                                        <span class="topic-badge">{{ $post->topicLabel }}</span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ Str::limit($post->title, 60) }}</h5>
                                        <p class="card-text">{{ $post->short_description ?? Str::limit(strip_tags($post->content), 100) }}</p>
                                    </div>
                                    <div class="card-footer">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $post->author->avatar ?? asset('images/avatar-placeholder.jpg') }}" 
                                                    class="rounded-circle me-2" width="30" height="30" alt="{{ $post->author->full_name }}">
                                                <small class="text-muted">{{ $post->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-sm btn-outline-primary">Đọc tiếp</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <h3 class="section-title">{{ request()->has('search') || request()->has('topic') ? 'Kết quả' : 'Bài viết mới nhất' }}</h3>
            
            @if($posts->isNotEmpty())
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-md-4 mb-4">
                            <div class="card post-card">
                                <div class="position-relative">
                                    @if($post->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $post->images->where('is_featured', true)->first()?->url ?? $post->images->first()->url) }}" 
                                            class="card-img-top" alt="{{ $post->title }}">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="Placeholder">
                                    @endif
                                    <span class="topic-badge">{{ $post->topicLabel }}</span>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($post->title, 60) }}</h5>
                                    <p class="card-text">{{ $post->short_description ?? Str::limit(strip_tags($post->content), 100) }}</p>
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $post->author->avatar ?? asset('images/avatar-placeholder.jpg') }}" 
                                                class="rounded-circle me-2" width="30" height="30" alt="{{ $post->author->full_name }}">
                                            <small class="text-muted">{{ $post->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-sm btn-outline-primary">Đọc tiếp</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center">
                    {{ $posts->withQueryString()->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-journal-text"></i>
                    <p>Không tìm thấy bài viết nào. Vui lòng thử lại với tìm kiếm khác.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection