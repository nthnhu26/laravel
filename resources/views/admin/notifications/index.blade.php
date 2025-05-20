@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mb-3">Quản lý thông báo</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-bell me-1"></i>
            Danh sách thông báo
            <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> Tạo thông báo mới
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <table id="notificationsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="15%">Người dùng</th>
                        <th>Nội dung</th>
                        <th width="10%">Trạng thái</th>
                        <th width="15%">Ngày tạo</th>
                        <th width="15%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $index => $notification)
                    <tr class="{{ $notification->is_read ? '' : 'table-light' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $notification->user->full_name ?? 'N/A' }}</td>
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
                            <button class="btn btn-sm btn-info mark-read" data-id="{{ $notification->notification_id }}">
                                <i class="fas fa-check"></i> Đánh dấu đã đọc
                            </button>
                            @endif
                            <button class="btn btn-sm btn-danger delete-notification" data-id="{{ $notification->notification_id }}">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
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
    $(document).ready(function() {
        // Khởi tạo DataTable
        initDataTable('notificationsTable');
        
        // Đánh dấu đã đọc
        $('.mark-read').on('click', function() {
            const id = $(this).data('id');
            const row = $(this).closest('tr');
            
            $.ajax({
                url: `/admin/notifications/${id}/mark-read`,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        row.removeClass('table-light');
                        row.find('.badge').removeClass('bg-warning text-dark').addClass('bg-success').text('Đã đọc');
                        row.find('.mark-read').remove();
                        toastr.success('Đã đánh dấu thông báo là đã đọc');
                    }
                },
                error: function() {
                    toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
                }
            });
        });
        
        // Xóa thông báo
        $('.delete-notification').on('click', function() {
            const id = $(this).data('id');
            const row = $(this).closest('tr');
            
            if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
                $.ajax({
                    url: `/admin/notifications/${id}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            row.remove();
                            toastr.success('Đã xóa thông báo thành công');
                            
                            // Cập nhật lại số thứ tự
                            $('#notificationsTable tbody tr').each(function(index) {
                                $(this).find('td:first-child').text(index + 1);
                            });
                        }
                    },
                    error: function() {
                        toastr.error('Có lỗi xảy ra. Vui lòng thử lại sau.');
                    }
                });
            }
        });
    });
</script>
@endsection