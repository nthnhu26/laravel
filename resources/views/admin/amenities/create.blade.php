@extends('admin.layouts.app')

@section('title', 'Thêm Tiện ích mới - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.amenities.index') }}">Quản lý Tiện ích</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Tiện ích mới</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Thêm Tiện ích mới</h2>
            <div>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('amenity-form').submit();">
                    <i class="fas fa-save me-1"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('amenity-form').reset();">
                    <i class="fas fa-redo me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <div class="content">
            <div class="language-alert" id="language-alert">
                Bạn đang chỉnh sửa phiên bản "Tiếng Việt (Vietnamese)"
            </div>

            <form id="amenity-form" action="{{ route('admin.amenities.store') }}" method="POST">
                @csrf
                <div class="form-container">
                    <div class="form-main">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thông tin Tiện ích</h5>
                            </div>
                            <div class="card-body">
                                <div class="language-content active" id="lang-vi-content">
                                    <div class="form-group">
                                        <label for="name_vi">Tên Tiện ích <span class="text-danger">*</span></label>
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
                                        <label for="name_en">Tên Tiện ích (Tiếng Anh)</label>
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
                                <div class="form-group">
                                    <label for="icon">Icon</label>
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" value="{{ old('icon') }}" placeholder="Ví dụ: wifi, swimming-pool">
                                    <div class="form-text">Nhập tên icon từ Font Awesome (ví dụ: wifi, swimming-pool).</div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @include('admin.partials.form-actions', ['formId' => 'amenity-form', 'viewPrefix' => 'amenities'])
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
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'imageUpload', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
@endsection