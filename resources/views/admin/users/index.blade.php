@extends('admin.layouts.app')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mb-3">Quản lý người dùng</h1>
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Danh sách người dùng
        </div>
        <div class="card-body">
            <table id="usersTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($user->avatar)
                                <img src="{{ $user->avatar ?? 'https://placehold.co/40' }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $user->full_name }}">
                                @else
                                <div class="bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <span class="text-white">{{ strtoupper(substr($user->full_name ?? $user->email, 0, 1)) }}</span>
                                </div>
                                @endif
                                {{ $user->full_name }}
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                            <span class="badge bg-primary">Admin</span>
                            @elseif($user->role == 'provider')
                            <span class="badge bg-info">Provider</span>
                            @else
                            <span class="badge bg-secondary">User</span>
                            @endif
                        </td>
                        <td>{!! $user->getStatusBadge() !!}</td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.users.show', $user->user_id) }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($user->status != 'active')
                                <a href="{{ route('admin.users.activate', $user->user_id) }}" class="btn btn-sm btn-success" 
                                   onclick="return confirm('Bạn có chắc muốn kích hoạt tài khoản này?')">
                                    <i class="fas fa-check"></i>
                                </a>
                                @endif
                                @if($user->status != 'inactive' && $user->role != 'admin')
                                <a href="{{ route('admin.users.deactivate', $user->user_id) }}" class="btn btn-sm btn-warning" 
                                   onclick="return confirm('Bạn có chắc muốn vô hiệu hóa tài khoản này?')">
                                    <i class="fas fa-pause"></i>
                                </a>
                                @endif
                                @if($user->status != 'banned' && $user->role != 'admin')
                                <a href="{{ route('admin.users.ban.form', $user->user_id) }}" class="btn btn-sm btn-danger">
                                    <i class="fas fa-ban"></i>
                                </a>
                                @endif
                            </div>
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
        initDataTable('usersTable');
    });
</script>
@endsection