@extends('admin.layouts.app')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Chi tiết người dùng</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>
    
    <div class="row">
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-circle me-1"></i>
                    Thông tin cơ bản
                </div>
                <div class="card-body text-center">
                    @if($user->avatar)
                    <img src="{{ $user->avatar }}" class="rounded-circle img-thumbnail mb-3" width="150" height="150" alt="{{ $user->full_name }}">
                    @else
                    <div class="bg-secondary rounded-circle mb-3 d-flex align-items-center justify-content-center mx-auto" style="width: 150px; height: 150px;">
                        <span class="text-white fs-1">{{ strtoupper(substr($user->full_name ?? $user->email, 0, 1)) }}</span>
                    </div>
                    @endif
                    <h5 class="card-title">{{ $user->full_name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <div class="mt-2">
                        @if($user->role == 'admin')
                        <span class="badge bg-primary">Admin</span>
                        @elseif($user->role == 'provider')
                        <span class="badge bg-info">Provider</span>
                        @else
                        <span class="badge bg-secondary">User</span>
                        @endif
                        {!! $user->getStatusBadge() !!}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-center gap-2">
                        @if($user->status != 'active')
                        <a href="{{ route('admin.users.activate', $user->user_id) }}" class="btn btn-success" 
                           onclick="return confirm('Bạn có chắc muốn kích hoạt tài khoản này?')">
                            <i class="fas fa-check me-1"></i> Kích hoạt
                        </a>
                        @endif
                        @if($user->status != 'inactive' && $user->role != 'admin')
                        <a href="{{ route('admin.users.deactivate', $user->user_id) }}" class="btn btn-warning" 
                           onclick="return confirm('Bạn có chắc muốn vô hiệu hóa tài khoản này?')">
                            <i class="fas fa-pause me-1"></i> Vô hiệu hóa
                        </a>
                        @endif
                        @if($user->status != 'banned' && $user->role != 'admin')
                        <a href="{{ route('admin.users.ban.form', $user->user_id) }}" class="btn btn-danger">
                            <i class="fas fa-ban me-1"></i> Khóa tài khoản
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-1"></i>
                    Thông tin chi tiết
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th width="200">ID</th>
                                <td>{{ $user->user_id }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Xác thực email</th>
                                <td>
                                    @if($user->email_verified_at)
                                    <span class="badge bg-success">Đã xác thực</span> vào lúc {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                    @else
                                    <span class="badge bg-warning text-dark">Chưa xác thực</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Đăng nhập bằng Google</th>
                                <td>{{ $user->google_id ? 'Có' : 'Không' }}</td>
                            </tr>
                            <tr>
                                <th>Vai trò</th>
                                <td>
                                    @if($user->role == 'admin')
                                    <span class="badge bg-primary">Admin</span>
                                    @elseif($user->role == 'provider')
                                    <span class="badge bg-info">Provider</span>
                                    @else
                                    <span class="badge bg-secondary">User</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>{!! $user->getStatusBadge() !!}</td>
                            </tr>
                            @if($user->status == 'banned')
                            <tr>
                                <th>Lý do khóa</th>
                                <td>{{ $user->ban_reason }}</td>
                            </tr>
                            <tr>
                                <th>Thời hạn khóa</th>
                                <td>
                                    @if($user->banned_until)
                                    Đến {{ $user->banned_until->format('d/m/Y H:i') }}
                                    @else
                                    <span class="badge bg-danger">Vĩnh viễn</span>
                                    @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>Ngày tạo tài khoản</th>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật lần cuối</th>
                                <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection