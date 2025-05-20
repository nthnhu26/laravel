@extends('admin.layouts.app')

@section('title', 'Hồ Sơ')

@section('content')
    <div class="container py-4">
        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-md-12"> <!-- Thay col-md-9 thành col-md-12 vì đã xóa sidebar -->
                <h2 class="mb-4">Cài Đặt Tài Khoản</h2>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#profile">Hồ Sơ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#account">Tài Khoản</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Hồ Sơ -->
                            <div class="tab-pane fade show active" id="profile">
                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Họ và Tên</label>
                                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', auth()->user()->full_name ?? auth()->user()->name) }}" required>
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar_file" class="form-label">Ảnh Đại Diện</label>
                                        <input type="file" class="form-control @error('avatar_file') is-invalid @enderror" id="avatar_file" name="avatar_file" accept="image/*">
                                        @error('avatar_file')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="avatar_url" class="form-label">Hoặc nhập URL ảnh đại diện</label>
                                        <input type="url" class="form-control @error('avatar_url') is-invalid @enderror" id="avatar_url" name="avatar_url" value="{{ old('avatar_url') }}" placeholder="Nhập URL ảnh từ Google">
                                        @error('avatar_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </form>
                            </div>
                            <!-- Tài Khoản -->
                            <div class="tab-pane fade" id="account">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" value="{{ auth()->user()->email }}" readonly>
                                </div>
                                @if (is_null(auth()->user()->password))
                                    <!-- Đăng nhập bằng Google: Form thêm mật khẩu -->
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">Mật Khẩu Mới</label>
                                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password_confirmation" class="form-label">Xác Nhận Mật Khẩu</label>
                                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Thêm Mật Khẩu</button>
                                    </form>
                                @else
                                    <!-- Đã có mật khẩu: Form đổi mật khẩu -->
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Mật Khẩu Hiện Tại</label>
                                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">Mật Khẩu Mới</label>
                                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="new_password_confirmation" class="form-label">Xác Nhận Mật Khẩu</label>
                                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Đổi Mật Khẩu</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Xóa Tài Khoản</h5>
                        <form method="POST" action="{{ route('account.delete') }}">
                            @csrf
                            @method('DELETE')
                            <p class="text-muted">Hành động này sẽ xóa tài khoản vĩnh viễn và không thể khôi phục.</p>
                            <div class="mb-3">
                                <label for="confirmation" class="form-label">Nhập "XÓA TÀI KHOẢN" để xác nhận</label>
                                <input type="text" class="form-control @error('confirmation') is-invalid @enderror" id="confirmation" name="confirmation">
                                @error('confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-danger">Xóa Tài Khoản</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

