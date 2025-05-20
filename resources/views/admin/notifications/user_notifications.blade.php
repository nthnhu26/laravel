@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Thông báo của người dùng</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.notifications.index') }}">Quản lý thông báo</a></li>
        <li class="breadcrumb-item active">Thông báo người dùng</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-bell me-1"></i>
                Danh sách thông báo
            </div>
            <div>
                <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" value="{{ request()->route('userId') }}">
                    <button type="submit" class="btn btn-success btn-sm" {{ $notifications->where('is_read', false)->count() ? '' : 'disabled' }}>
                        <i class="fas fa-check-double"></i> Đánh dấu tất cả đã đọc
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <table id="userNotificationsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Nội dung</th>
                        <th width="10%">Trạng thái</th>
                        <th width="15%">Thời gian</th>
                        <th width="15%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $index => $notification)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $notification->message }}</td>
                        <td>
                            @if($notification->is_read)
                                <span class="badge bg-success">Đã đọc</span>
                            @else
                                <span class="badge bg-warning text-dark">Chưa đọc</span>
                            @endif
                        </td>
                        <td>{{ $notification->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if(!$notification->is_read)
                                <form action="{{ route('admin.notifications.mark-read', $notification->notification_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-info btn-sm" title="Đánh dấu đã đọc">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.notifications.destroy', $notification->notification_id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Sử dụng hàm chung cho DataTable
    initDataTable('userNotificationsTable');
    
    // Xác nhận xóa
    $(document).ready(function() {
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
                this.submit();
            }
        });
    });
</script>
@endsection