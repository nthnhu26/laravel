@extends('admin.layouts.app')

@section('title', 'Khóa tài khoản người dùng')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mb-3">Khóa tài khoản người dùng</h1>
  
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-ban me-1"></i>
                    Khóa tài khoản: {{ $user->full_name }} ({{ $user->email }})
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <form action="{{ route('admin.users.ban', $user->user_id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ban_reason" class="form-label">Lý do khóa <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="ban_reason" name="ban_reason" rows="4" required>{{ old('ban_reason') }}</textarea>
                            <div class="form-text">Nhập lý do khóa tài khoản. Thông tin này sẽ hiển thị cho người dùng.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="banned_until" class="form-label">Thời hạn khóa</label>
                            <input type="datetime-local" class="form-control" id="banned_until" name="banned_until" value="{{ old('banned_until') }}">
                            <div class="form-text">Để trống nếu muốn khóa vĩnh viễn. Thời gian phải lớn hơn thời điểm hiện tại.</div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-ban me-1"></i> Khóa tài khoản
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Thông tin người dùng
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($user->avatar)
                        <img src="{{ $user->avatar }}" class="rounded-circle img-thumbnail mb-2" width="100" height="100" alt="{{ $user->full_name }}">
                        @else
                        <div class="bg-secondary rounded-circle mb-2 d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px;">
                            <span class="text-white fs-2">{{ strtoupper(substr($user->full_name ?? $user->email, 0, 1)) }}</span>
                        </div>
                        @endif
                        <h5>{{ $user->full_name }}</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>ID:</span>
                            <span>{{ $user->user_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Vai trò:</span>
                            <span>
                                @if($user->role == 'admin')
                                <span class="badge bg-primary">Admin</span>
                                @elseif($user->role == 'provider')
                                <span class="badge bg-info">Provider</span>
                                @else
                                <span class="badge bg-secondary">User</span>
                                @endif
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Trạng thái:</span>
                            <span>{!! $user->getStatusBadge() !!}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Ngày tạo:</span>
                            <span>{{ $user->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection