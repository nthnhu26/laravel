@extends('admin.layouts.app')

@section('title', 'Quản lý bài viết')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý bài viết</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Thêm bài viết mới
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm bài viết..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="topic">
                        <option value="">Tất cả chủ đề</option>
                        <option value="travel_tips" {{ request('topic') == 'travel_tips' ? 'selected' : '' }}>Mẹo du lịch</option>
                        <option value="local_experience" {{ request('topic') == 'local_experience' ? 'selected' : '' }}>Trải nghiệm địa phương</option>
                        <option value="food" {{ request('topic') == 'food' ? 'selected' : '' }}>Ẩm thực</option>
                        <option value="accommodation" {{ request('topic') == 'accommodation' ? 'selected' : '' }}>Chỗ ở</option>
                        <option value="transportation" {{ request('topic') == 'transportation' ? 'selected' : '' }}>Phương tiện</option>
                        <option value="event" {{ request('topic') == 'event' ? 'selected' : '' }}>Sự kiện</option>
                        <option value="culture" {{ request('topic') == 'culture' ? 'selected' : '' }}>Văn hóa</option>
                        <option value="history" {{ request('topic') == 'history' ? 'selected' : '' }}>Lịch sử</option>
                        <option value="activity" {{ request('topic') == 'activity' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="other" {{ request('topic') == 'other' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">Tất cả trạng thái</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Đã từ chối</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Đặt lại
                    </a>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" style="width: 60px;">ID</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Chủ đề</th>
                            <th scope="col">Tác giả</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Lượt xem</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col" style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>{{ $post->post_id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($post->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $post->images->first()->url) }}" 
                                                class="rounded me-2" alt="{{ $post->title }}" width="50" height="50" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ Str::limit($post->title, 50) }}</div>
                                            <div class="small text-muted">{{ Str::limit($post->short_description ?? strip_tags($post->content), 60) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $post->topicLabel }}</td>
                                <td>{{ $post->author->full_name ?? 'Ẩn danh' }}</td>
                                <td>
                                    @if($post->status == 'published')
                                        <span class="badge bg-success">Đã xuất bản</span>
                                    @elseif($post->status == 'draft')
                                        <span class="badge bg-warning text-dark">Bản nháp</span>
                                    @elseif($post->status == 'rejected')
                                        <span class="badge bg-danger">Đã từ chối</span>
                                    @endif
                                </td>
                                <td>{{ number_format($post->views) }}</td>
                                <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.posts.edit', $post->post_id) }}" class="btn btn-sm btn-outline-secondary" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" data-bs-target="#deleteModal{{ $post->post_id }}" title="Xóa">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal xóa -->
                                    <div class="modal fade" id="deleteModal{{ $post->post_id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Xác nhận xóa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Bạn có chắc chắn muốn xóa bài viết <strong>{{ $post->title }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <form action="{{ route('admin.posts.destroy', $post->post_id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-journal-x text-muted display-4"></i>
                                    <p class="mt-3">Không tìm thấy bài viết nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>Hiển thị {{ $posts->firstItem() ?? 0 }}-{{ $posts->lastItem() ?? 0 }} / {{ $posts->total() }} bài viết</div>
                {{ $posts->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection