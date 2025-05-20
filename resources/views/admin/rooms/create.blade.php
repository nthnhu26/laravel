@extends('admin.layouts.app')

@section('title', 'Thêm Phòng mới - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.hotels.index') }}">Quản lý Khách sạn</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Phòng mới</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Thêm Phòng mới</h2>
            <div>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('room-form').submit();">
                    <i class="fas fa-save me-1"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('room-form').reset();">
                    <i class="fas fa-redo me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <div class="content">
            <div class="language-alert" id="language-alert">
                Bạn đang chỉnh sửa phiên bản "Tiếng Việt (Vietnamese)"
            </div>

            <form id="room-form" action="{{ route('admin.rooms.store') }}" method="POST">
                @csrf
                <div class="form-container">
                    <div class="form-main">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thông tin Phòng</h5>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="hotel_id" value="{{ $hotel_id }}">
                                <div class="language-content active" id="lang-vi-content">
                                    <div class="form-group">
                                        <label for="name_vi">Tên Phòng <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name.vi') is-invalid @enderror" id="name_vi" name="name[vi]" value="{{ old('name.vi') }}" required>
                                        @error('name.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description_vi">Mô tả</label>
                                        <textarea class="form-control ckeditor @error('description.vi') is-invalid @enderror" id="description_vi" name="description[vi]">{{ old('description.vi') }}</textarea>
                                        @error('description.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="language-content" id="lang-en-content">
                                    <div class="form-group">
                                        <label for="name_en">Tên Phòng (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('name.en') is-invalid @enderror" id="name_en" name="name[en]" value="{{ old('name.en') }}">
                                        @error('name.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description_en">Mô tả (Tiếng Anh)</label>
                                        <textarea class="form-control ckeditor @error('description.en') is-invalid @enderror" id="description_en" name="description[en]">{{ old('description.en') }}</textarea>
                                        @error('description.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price_per_night">Giá mỗi đêm (VNĐ) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('price_per_night') is-invalid @enderror" id="price_per_night" name="price_per_night" value="{{ old('price_per_night') }}" required>
                                            @error('price_per_night')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="area">Diện tích (m²)</label>
                                            <input type="number" class="form-control @error('area') is-invalid @enderror" id="area" name="area" value="{{ old('area') }}">
                                            @error('area')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="capacity">Sức chứa <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity') }}" required>
                                            @error('capacity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="bed_type">Loại giường</label>
                                            <input type="text" class="form-control @error('bed_type') is-invalid @enderror" id="bed_type" name="bed_type" value="{{ old('bed_type') }}" placeholder="Ví dụ: 1 giường đôi">
                                            @error('bed_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Công khai</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @include('admin.partials.form-actions', ['formId' => 'room-form', 'viewPrefix' => 'hotels'])
                    </div>

                    <div class="form-sidebar">
                        @include('admin.partials.language-selector')
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
        function switchLanguage(lang) {
            document.querySelectorAll('.language-item').forEach(item => {
                if (item.dataset.lang === lang) {
                    item.classList.add('active');
                    const langLabel = item.querySelector('.lang-label');
                    const langName = lang === 'en' ? 'English' : 'Tiếng Việt';
                    langLabel.textContent = `${langName} (Đang chỉnh sửa)`;
                } else {
                    item.classList.remove('active');
                    const langLabel = item.querySelector('.lang-label');
                    const langName = item.dataset.lang === 'en' ? 'English' : 'Tiếng Việt';
                    langLabel.textContent = langName;
                }
            });

            document.getElementById('language-alert').textContent = `Bạn đang chỉnh sửa phiên bản "${lang === 'en' ? 'English' : 'Tiếng Việt'} (${lang === 'en' ? 'English' : 'Vietnamese'})"`;

            document.querySelectorAll('.language-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(`lang-${lang}-content`).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const editors = document.querySelectorAll('.ckeditor');
            editors.forEach(function(editor) {
                ClassicEditor
                    .create(editor, {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo'],
                        simpleUpload: {
                            uploadUrl: '{{ route('admin.upload.image') }}',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection