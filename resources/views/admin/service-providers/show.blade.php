@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Chi tiết nhà cung cấp</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.service-providers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>

            @if($provider->approval_status == 'approved')
            <form action="{{ route('admin.service-providers.toggle-status', $provider->provider_id) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit" class="btn {{ $provider->status == 'active' ? 'btn-warning' : 'btn-success' }}">
                <i class="fas {{ $provider->status == 'active' ? 'fa-ban me-1' : 'fa-check me-1' }}"></i>
                {{ $provider->status == 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' }}
            </button>
            </form>
            @endif
        </div>

    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-id-card me-1"></i>
                    Thông tin cơ bản
                </div>
                <div class="card-body text-center">
                    @if($provider->logo)
                    <img src="{{ asset($provider->logo) }}" alt="{{ $provider->name }}" class="rounded-circle img-thumbnail mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; font-size: 60px;">
                        {{ substr($provider->name, 0, 1) }}
                    </div>
                    @endif

                    <h4>{{ $provider->name }}</h4>

                    <div class="d-flex justify-content-center mb-3">
                        @if($provider->approval_status == 'approved')
                        <span class="badge bg-success me-2">Đã duyệt</span>
                        @elseif($provider->approval_status == 'pending')
                        <span class="badge bg-warning text-dark me-2">Chờ duyệt</span>
                        @else
                        <span class="badge bg-danger me-2">Từ chối</span>
                        @endif

                        @if($provider->status == 'active')
                        <span class="badge bg-success">Hoạt động</span>
                        @else
                        <span class="badge bg-danger">Không hoạt động</span>
                        @endif
                    </div>

                    @if($provider->website)
                    <a href="{{ $provider->website }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-globe me-1"></i> Truy cập website
                    </a>
                    @endif
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-phone me-2"></i> {{ $provider->phone }}
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-envelope me-2"></i> {{ $provider->email }}
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-map-marker-alt me-2"></i> {{ $provider->address }}
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-calendar me-2"></i> Ngày đăng ký: {{ $provider->created_at->format('d/m/Y') }}
                    </li>
                </ul>
            </div>

            @if($provider->approval_status == 'pending')
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-tasks me-1"></i>
                    Thao tác phê duyệt
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-check me-1"></i> Phê duyệt nhà cung cấp
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-1"></i> Từ chối nhà cung cấp
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal Phê duyệt -->
            <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xác nhận phê duyệt</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn phê duyệt nhà cung cấp <strong>{{ $provider->name }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            <form action="{{ route('admin.service-providers.approve', $provider->provider_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">Xác nhận phê duyệt</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Từ chối -->
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.service-providers.reject', $provider->provider_id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Từ chối nhà cung cấp</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="reject_reason" class="form-label">Lý do từ chối</label>
                                    <textarea class="form-control" id="reject_reason" name="reject_reason" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-file-alt me-1"></i>
                    Thông tin chi tiết
                </div>
                <div class="card-body">
                    <h5>Mô tả</h5>
                    <div class="mb-4">
                        @if($provider->description)
                        <p>{{ $provider->description }}</p>
                        @else
                        <p class="text-muted">Không có mô tả</p>
                        @endif
                    </div>

                    <h5>Thông tin giấy phép</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số giấy phép:</label>
                                <p>{{ $provider->license_number ?? 'Chưa cung cấp' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">File giấy phép:</label>
                                @if($provider->license_file)
                                <div>
                                    <a href="{{ asset($provider->license_file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-file-pdf me-1"></i> Xem file
                                    </a>
                                    <a href="{{ asset($provider->license_file) }}" download class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-download me-1"></i> Tải xuống
                                    </a>
                                </div>
                                @else
                                <p class="text-muted">Chưa cung cấp file</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <h5>Thông tin tài khoản</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên người dùng:</label>
                                <p>{{ $provider->user->full_name ?? 'Không tìm thấy' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Email người dùng:</label>
                                <p>{{ $provider->user->email ?? 'Không tìm thấy' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection