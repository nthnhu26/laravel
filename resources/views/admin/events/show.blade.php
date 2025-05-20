@extends('admin.layouts.app')

@section('title', 'Chi tiết sự kiện')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết sự kiện</h1>
        <div>
            <a href="{{ route('admin.events.edit', $event->event_id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left me-2"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin sự kiện</h6>
                </div>
                <div class="card-body">
                    <h2 class="h4 mb-3">{{ $event->title }}</h2>
                    
                    <div class="mb-4">
                        <span class="badge 
                            @if($event->status == 'ongoing') bg-success 
                            @elseif($event->status == 'upcoming') bg-primary 
                            @else bg-secondary @endif">
                            @if($event->status == 'ongoing') Đang diễn ra
                            @elseif($event->status == 'upcoming') Sắp diễn ra
                            @else Đã kết thúc @endif
                        </span>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Thời gian bắt đầu:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Thời gian kết thúc:</strong></p>
                            <p>{{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Địa điểm:</strong></p>
                        <p>{{ $event->location ?: 'Chưa cập nhật' }}</p>
                    </div>
                    
                    @if($event->place)
                    <div class="mb-3">
                        <p class="mb-1"><strong>Địa điểm liên quan:</strong></p>
                        <p>
                            <a href="{{ route('admin.places.edit', $event->place->place_id) }}">
                                {{ $event->place->name }}
                            </a>
                        </p>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Mô tả:</strong></p>
                        <div class="border rounded p-3 bg-light">
                            {!! nl2br(e($event->description)) ?: 'Chưa có mô tả' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thao tác nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-info" target="_blank">
                            <i class="fas fa-eye me-2"></i> Xem trên trang chủ
                        </a>
                        
                        <button type="button" class="btn btn-warning" id="updateStatusBtn">
                            <i class="fas fa-sync me-2"></i> Cập nhật trạng thái
                        </button>
                        
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i> Xóa sự kiện
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khác</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1"><strong>ID:</strong></p>
                        <p>{{ $event->event_id }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Ngày tạo:</strong></p>
                        <p>{{ $event->created_at ? \Carbon\Carbon::parse($event->created_at)->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <p class="mb-1"><strong>Cập nhật lần cuối:</strong></p>
                        <p>{{ $event->updated_at ? \Carbon\Carbon::parse($event->updated_at)->format('d/m/Y H:i') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa sự kiện <strong>{{ $event->title }}</strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form action="{{ route('admin.events.destroy', $event->event_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cập nhật trạng thái
        document.getElementById('updateStatusBtn').addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang cập nhật...';
            
            fetch('{{ route("admin.events.update-status", $event->event_id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã cập nhật trạng thái thành công!');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-sync me-2"></i> Cập nhật trạng thái';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái.');
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-sync me-2"></i> Cập nhật trạng thái';
            });
        });
    });
</script>
@endpush
