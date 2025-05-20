@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mb-3">Quản lý nhà cung cấp</h1>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-table me-1"></i>
                Danh sách nhà cung cấp
            </div>
            <div>
                <a href="{{ route('admin.service-providers.approval') }}" class="btn btn-primary">
                    <i class="fas fa-check-circle me-1"></i>Phê duyệt 
                    <span class="badge bg-danger">{{ App\Models\ServiceProvider::pending()->count() }}</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <table id="serviceProvidersTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="5%">Logo</th>
                        <th width="15%">Tên nhà cung cấp</th>
                        <th width="15%">Liên hệ</th>
                        <th width="15%">Địa chỉ</th>
                        <th width="10%">Trạng thái</th>
                        <th width="10%">Phê duyệt</th>
                        <th width="10%">Ngày tạo</th>
                        <th width="15%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceProviders as $index => $provider)
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
                        <td>{{ $provider->address }}</td>
                        <td>
                            @if($provider->status == 'active')
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Không hoạt động</span>
                            @endif
                        </td>
                        <td>
                            @if($provider->approval_status == 'approved')
                                <span class="badge bg-success">Đã duyệt</span>
                            @elseif($provider->approval_status == 'pending')
                                <span class="badge bg-warning text-dark">Chờ duyệt</span>
                            @else
                                <span class="badge bg-danger">Từ chối</span>
                            @endif
                        </td>
                        <td>{{ $provider->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.service-providers.show', $provider->provider_id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="{{ route('admin.service-providers.toggle-status', $provider->provider_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm {{ $provider->status == 'active' ? 'btn-warning' : 'btn-success' }}" 
                                        title="{{ $provider->status == 'active' ? 'Vô hiệu hóa' : 'Kích hoạt' }}">
                                    <i class="fas {{ $provider->status == 'active' ? 'fa-ban' : 'fa-check' }}"></i>
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
    $(document).ready(function() {
        initDataTable('serviceProvidersTable');
    });
</script>
@endsection
