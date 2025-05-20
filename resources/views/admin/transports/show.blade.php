@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết phương tiện</h1>
        <div>
            <a href="{{ route('admin.transportation.edit', $transportation->transport_id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.transportation.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hình ảnh phương tiện</h6>
                </div>
                <div class="card-body text-center">
                    @if($transportation->image_url)
                        <img src="{{ asset($transportation->image_url) }}" alt="{{ $transportation->name }}" class="img-fluid">
                    @else
                        <div class="text-center p-4 bg-light">
                            <i class="fas fa-image fa-3x text-gray-400"></i>
                            <p class="mt-2 text-gray-500">Không có hình ảnh</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="width: 30%">Mã phương tiện</th>
                            <td>{{ $transportation->transport_id }}</td>
                        </tr>
                        <tr>
                            <th>Tên phương tiện</th>
                            <td>{{ $transportation->name }}</td>
                        </tr>
                        <tr>
                            <th>Loại</th>
                            <td>
                                @if($transportation->type === 'car')
                                    Ô tô
                                @elseif($transportation->type === 'motorbike')
                                    Xe máy
                                @elseif($transportation->type === 'bicycle')
                                    Xe đạp
                                @elseif($transportation->type === 'boat')
                                    Thuyền
                                @else
                                    {{ ucfirst($transportation->type) }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Sức chứa</th>
                            <td>{{ $transportation->capacity }} người</td>
                        </tr>
                        <tr>
                            <th>Giá thuê/ngày</th>
                            <td>{{ number_format($transportation->price_per_day, 0, ',', '.') }}đ</td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <span class="badge bg-{{ $transportation->status === 'available' ? 'success' : 'warning' }}">
                                    {{ $transportation->status === 'available' ? 'Có sẵn' : 'Đã đặt' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Nhà cung cấp</th>
                            <td>{{ $transportation->provider->name ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lịch sử đặt phương tiện</h6>
        </div>
        <div class="card-body">
            @if($transportation->bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Thời gian thuê</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transportation->bookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_id }}</td>
                                <td>{{ $booking->user->full_name ?? 'N/A' }}</td>
                                <td>
                                    <div>Từ: {{ date('d/m/Y', strtotime($booking->start_date)) }}</div>
                                    <div>Đến: {{ date('d/m/Y', strtotime($booking->end_date)) }}</div>
                                    <small class="text-muted">{{ $booking->getDaysCount() }} ngày</small>
                                </td>
                                <td>{{ number_format($booking->total_price, 0, ',', '.') }}đ</td>
                                <td>
                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">
                                        {{ $booking->status === 'pending' ? 'Chờ xác nhận' : ($booking->status === 'confirmed' ? 'Đã xác nhận' : ($booking->status === 'cancelled' ? 'Đã hủy' : 'Đã hoàn thành')) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                        {{ $booking->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.transport-bookings.show', $booking->booking_id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Chưa có lịch sử đặt phương tiện này.</p>
            @endif
        </div>
    </div>
</div>
@endsection
