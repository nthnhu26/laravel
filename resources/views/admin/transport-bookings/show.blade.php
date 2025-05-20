@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn đặt phương tiện</h1>
        <div>
            <a href="{{ route('admin.transport-bookings.edit', $booking->booking_id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.transport-bookings.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại danh sách
            </a>
        </div>
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

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đơn đặt</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Thông tin phương tiện</h5>
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->transportation && $booking->transportation->image_url)
                                    <img src="{{ asset($booking->transportation->image_url) }}" alt="{{ $booking->transportation->name }}" class="mr-3" width="80">
                                @else
                                    <div class="bg-light text-center mr-3" style="width: 80px; height: 80px; line-height: 80px;">
                                        <i class="fas fa-car fa-2x text-muted"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-1">{{ $booking->transportation->name ?? 'N/A' }}</h6>
                                    <p class="mb-1"><span class="font-weight-bold">Loại:</span> {{ ucfirst($booking->transportation->type ?? 'N/A') }}</p>
                                    <p class="mb-0"><span class="font-weight-bold">Sức chứa:</span> {{ $booking->transportation->capacity ?? 'N/A' }} người</p>
                                </div>
                            </div>
                            <p><span class="font-weight-bold">Nhà cung cấp:</span> {{ $booking->transportation->provider->name ?? 'N/A' }}</p>
                            <p><span class="font-weight-bold">Giá thuê:</span> {{ number_format($booking->transportation->price_per_day ?? 0, 0, ',', '.') }}đ/ngày</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Thông tin khách hàng</h5>
                            <p><span class="font-weight-bold">Họ tên:</span> {{ $booking->user->full_name ?? 'N/A' }}</p>
                            <p><span class="font-weight-bold">Email:</span> {{ $booking->user->email ?? 'N/A' }}</p>
                            <p><span class="font-weight-bold">Ngày đặt:</span> {{ date('d/m/Y H:i', strtotime($booking->created_at)) }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Thông tin thuê</h5>
                            <p><span class="font-weight-bold">Ngày bắt đầu:</span> {{ date('d/m/Y', strtotime($booking->start_date)) }}</p>
                            <p><span class="font-weight-bold">Ngày kết thúc:</span> {{ date('d/m/Y', strtotime($booking->end_date)) }}</p>
                            <p><span class="font-weight-bold">Số ngày thuê:</span> {{ $booking->getDaysCount() }} ngày</p>
                            <p><span class="font-weight-bold">Địa điểm nhận xe:</span> {{ $booking->pickup_location }}</p>
                            <p><span class="font-weight-bold">Địa điểm trả xe:</span> {{ $booking->dropoff_location }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Thông tin thanh toán</h5>
                            <p><span class="font-weight-bold">Tổng tiền:</span> {{ number_format($booking->total_price, 0, ',', '.') }}đ</p>
                            <p>
                                <span class="font-weight-bold">Trạng thái thanh toán:</span> 
                                <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ $booking->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Trạng thái đơn đặt</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }} p-2" style="font-size: 1rem;">
                                {{ $booking->status === 'pending' ? 'Chờ xác nhận' : ($booking->status === 'confirmed' ? 'Đã xác nhận' : ($booking->status === 'cancelled' ? 'Đã hủy' : 'Đã hoàn thành')) }}
                            </span>
                        </div>
                        <p class="text-muted">Cập nhật lần cuối: {{ date('d/m/Y H:i', strtotime($booking->updated_at ?? $booking->created_at)) }}</p>
                    </div>

                    <form action="{{ route('admin.transport-bookings.update-status', $booking->booking_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="status"><strong>Cập nhật trạng thái</strong></label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>Xác nhận</option>
                                <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Cập nhật trạng thái</button>
                    </form>

                    <hr>

                    <form action="{{ route('admin.transport-bookings.update-payment', $booking->booking_id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="payment_status"><strong>Cập nhật thanh toán</strong></label>
                            <select class="form-control" id="payment_status" name="payment_status">
                                <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                                <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Cập nhật thanh toán</button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thao tác</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.transport-bookings.edit', $booking->booking_id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit fa-sm"></i> Chỉnh sửa đơn đặt
                    </a>
                    
                    @if(in_array($booking->status, ['pending', 'cancelled']))
                        <form action="{{ route('admin.transport-bookings.destroy', $booking->booking_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn đặt này?')">
                                <i class="fas fa-trash fa-sm"></i> Xóa đơn đặt
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
