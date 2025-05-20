@extends('admin.layouts.app')

@section('title', 'Thêm Hình ảnh mới - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.images.index') }}">Quản lý Hình ảnh</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Hình ảnh mới</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Thêm Hình ảnh mới</h2>
            <div>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('image-form').submit();">
                    <i class="fas fa-save me-1"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('image-form').reset();">
                    <i class="fas fa-redo me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <div class="content">
            <form id="image-form" action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-container">
                    <div class="form-main">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thông tin Hình ảnh</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="entity_type">Loại Thực thể <span class="text-danger">*</span></label>
                                    <select class="form-control @error('entity_type') is-invalid @enderror" id="entity_type" name="entity_type" required>
                                        <option value="">-- Chọn loại thực thể --</option>
                                        @foreach(['hotel', 'room', 'restaurant', 'attraction', 'dish', 'tour', 'transport', 'review', 'post', 'event'] as $type)
                                            <option value="{{ $type }}" {{ old('entity_type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                        @endforeach
                                    </select>
                                    @error('entity_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="entity_id">ID Thực thể <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('entity_id') is-invalid @enderror" id="entity_id" name="entity_id" value="{{ old('entity_id') }}" required>
                                    <div class="form-text">Nhập ID của thực thể (ví dụ: ID khách sạn, tour).</div>
                                    @error('entity_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="url">Hình ảnh <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('url') is-invalid @enderror" id="url" name="url" accept="image/*" required>
                                    @error('url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="image-gallery" id="image-preview"></div>
                                </div>
                                <div class="form-group">
                                    <label for="caption">Chú thích</label>
                                    <input type="text" class="form-control @error('caption') is-invalid @enderror" id="caption" name="caption" value="{{ old('caption') }}">
                                    @error('caption')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Hình ảnh nổi bật
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('admin.partials.form-actions', ['formId' => 'image-form', 'viewPrefix' => 'images'])
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        @include('admin.partials.styles')
    </style>
@endsection

@section('scripts')
    <script>
        document.getElementById('url').addEventListener('change', function(event) {
            const previewContainer = document.getElementById('image-preview');
            previewContainer.innerHTML = '';

            if (this.files) {
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.classList.add('image-gallery-item');
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        div.appendChild(img);
                        previewContainer.appendChild(div);
                    }

                    reader.readAsDataURL(file);
                }
            }
        });
    </script>
@endsection