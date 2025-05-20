@extends('admin.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mb-3">Tạo thông báo mới</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-bell me-1"></i>
            Thêm thông báo mới
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
            
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="user_id" class="form-label">Người nhận</label>
                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                        <option value="">-- Chọn người nhận --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="message" class="form-label">Nội dung thông báo</label>
                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary me-2">Hủy</a>
                    <button type="submit" class="btn btn-primary">Gửi thông báo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo select2 cho dropdown người dùng
        $('#user_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Chọn người nhận thông báo',
            allowClear: true
        });
    });
</script>
@endsection