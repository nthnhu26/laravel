@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Phê duyệt nhà cung cấp</h1>
        <a href="{{ route('admin.service-providers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-tasks me-1"></i>
            Danh sách chờ phê duyệt ({{ $pendingProviders->count() }})
        </div>
        <div class="card-body">
            @if($pendingProviders->count() > 0)
            <table id="pendingProvidersTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="5%">Logo</th>
                        <th width="15%">Tên nhà cung cấp</th>
                        <th width="15%">Liên hệ</th>
                        <th width="15%">Giấy phép</th>
                        <th width="10%">Ngày đăng ký</th>
                        <th width="20%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingProviders as $index => $provider)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($provider->logo)
                                <img src="{{ asset($provider->logo) }}" alt="{{ $provider->name }}" width="40" height="40" class="rounded-circle">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ substr($provider->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td>
                            {{ $provider->name }}
                            <div class="small text-muted">{{ $provider->website }}</div>
                        </td>
                        <td>
                            {{ $provider->phone }}<br>
                            <span class="small">{{ $provider->email }}</span>
                        </td>
                        <td>
                            <strong>Số:</strong> {{ $provider->license_number }}<br>
                            @if($provider->license_file)
                                <a href="{{ asset($provider->license_file) }}" target="_blank" class="small">
                                    <i class="fas fa-file-pdf"></i> Xem file
                                </a>
                            @else
                                <span class="text-muted small">Không có file đính kèm</span>
                            @endif
                        </td>
                        <td>{{ $provider->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.service-providers.show', $provider->provider_id) }}" class="btn btn-sm btn-info mb-1">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                            
                            <button type="button" class="btn btn-sm btn-success mb-1" data-bs-toggle="modal" data-bs-target="#approveModal{{ $provider->provider_id }}">
                                <i class="fas fa-check"></i> Phê duyệt
                            </button>
                            
                            <button type="button" class="btn btn-sm btn-danger mb-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $provider->provider_id }}">
                                <i class="fas fa-times"></i> Từ chối
                            </button>
                            
                            <!-- Modal Phê duyệt -->
                            <div class="modal fade" id="approveModal{{ $provider->provider_id }}" tabindex="-1" aria-hidden="true">
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
                            <div class="modal fade" id="rejectModal{{ $provider->provider_id }}" tabindex="-1" aria-hidden="true">
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="alert alert-info">
                    Không có nhà cung cấp nào đang chờ phê duyệt.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        initDataTable('pendingProvidersTable');
    });
</script>
@endsection