@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý đặt phương tiện</h1>
        <a href="{{ route('admin.transport-bookings.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tạo đơn đặt mới
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đơn đặt phương tiện</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Lọc theo:</div>
                    <a class="dropdown-item" href="{{ route('admin.transport-bookings.index') }}">Tất cả đơn đặt</a>
                    <a class="dropdown-item" href="{{ route('admin.transport-bookings.index', ['status' => 'pending']) }}">Chờ xác nhận</a>
                    <a class="dropdown-item" href="{{ route('admin.transport-bookings.index', ['status' => 'confirmed']) }}">Đã xác nhận</a>
                    <a class="dropdown-item" href="{{ route('admin.transport-bookings.index', ['status' => 'completed']) }}">Đã hoàn thành</a>
                    <a class="dropdown-item" href="{{ route('admin.transport-bookings.index', ['status' => 'cancelled']) }}">Đã hủy</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Phương tiện</th>
                            <th>Khách hàng</th>
                            <th>Thời gian</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_id }}</td>
                            <td>
                                @if($booking->transportation)
                                <div class="d-flex align-items-center">
                                    @if($booking->transportation->image_url)
                                    <img src="{{ asset($booking->transportation->image_url) }}" alt="{{ $booking->transportation->name }}" class="mr-2" width="40">
                                    @endif
                                    <div>
                                        <div>{{ $booking->transportation->name }}</div>
                                        <small class="text-muted">
                                            @if($booking->transportation->type === 'car')
                                            Ô tô
                                            @elseif($booking->transportation->type === 'motorbike')
                                            Xe máy
                                            @elseif($booking->transportation->type === 'bicycle')
                                            Xe đạp
                                            @elseif($booking->transportation->type === 'boat')
                                            Thuyền
                                            @else
                                            {{ ucfirst($booking->transportation->type) }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>{{ $booking->user->full_name ?? 'Không có' }}</td>
                            <td>
                                <div>Từ: {{ date('d/m/Y', strtotime($booking->start_date)) }}</div>
                                <div>Đến: {{ date('d/m/Y', strtotime($booking->end_date)) }}</div>
                                <small class="text-muted">{{ $booking->getDaysCount() }} ngày</small>
                            </td>
                            <td>{{ number_format($booking->total_price, 0, ',', '.') }}đ</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $booking->status === 'pending' ? 'Chờ xác nhận' : ($booking->status === 'confirmed' ? 'Đã xác nhận' : ($booking->status === 'cancelled' ? 'Đã hủy' : 'Đã hoàn thành')) }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('admin.transport-bookings.update-status', $booking->booking_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" name="status" value="pending" class="dropdown-item">Chờ xác nhận</button>
                                            <button type="submit" name="status" value="confirmed" class="dropdown-item">Xác nhận</button>
                                            <button type="submit" name="status" value="completed" class="dropdown-item">Hoàn thành</button>
                                            <button type="submit" name="status" value="cancelled" class="dropdown-item">Hủy đơn</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ $booking->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('admin.transport-bookings.update-payment', $booking->booking_id) }}" method="POST">
                                            @csrf
                                            <button type="submit" name="payment_status" value="pending" class="dropdown-item">Chưa thanh toán</button>
                                            <button type="submit" name="payment_status" value="paid" class="dropdown-item">Đã thanh toán</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.transport-bookings.show', $booking->booking_id) }}" class="btn btn-info btn-sm" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.transport-bookings.edit', $booking->booking_id) }}" class="btn btn-primary btn-sm" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.transport-bookings.destroy', $booking->booking_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn đặt này?')" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection