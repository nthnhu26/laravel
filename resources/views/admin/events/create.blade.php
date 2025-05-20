@extends('admin.layouts.app')

@section('title', 'Thêm sự kiện mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm sự kiện mới</h1>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Quay lại
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Thông tin sự kiện</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.events.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề sự kiện <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả sự kiện</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="location" class="form-label">Địa điểm</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="place_id" class="form-label">Địa điểm liên quan</label>
                            <select class="form-select @error('place_id') is-invalid @enderror" id="place_id" name="place_id">
                                <option value="">-- Chọn địa điểm --</option>
                                @foreach($places as $place)
                                    <option value="{{ $place->place_id }}" {{ old('place_id') == $place->place_id ? 'selected' : '' }}>
                                        {{ $place->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('place_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                                <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Lưu sự kiện
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Tự động cập nhật trạng thái dựa trên thời gian
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const statusSelect = document.getElementById('status');
        
        function updateStatus() {
            if (!startDateInput.value || !endDateInput.value) return;
            
            const now = new Date();
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);
            
            if (now < startDate) {
                statusSelect.value = 'upcoming';
            } else if (now >= startDate && now <= endDate) {
                statusSelect.value = 'ongoing';
            } else {
                statusSelect.value = 'completed';
            }
        }
        
        startDateInput.addEventListener('change', updateStatus);
        endDateInput.addEventListener('change', updateStatus);
    });
</script>
@endpush
