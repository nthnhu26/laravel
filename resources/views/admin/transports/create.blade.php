@extends('admin.layouts.app')

@section('title', 'Thêm Phương tiện mới - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.transports.index') }}">Quản lý Phương tiện</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Phương tiện mới</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Thêm Phương tiện mới</h2>
            <div>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('transport-form').submit();">
                    <i class="fas fa-save me-1"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('transport-form').reset();">
                    <i class="fas fa-redo me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <div class="content">
            <div class="language-alert" id="language-alert">
                Bạn đang chỉnh sửa phiên bản "Tiếng Việt (Vietnamese)"
            </div>

            <form id="transport-form" action="{{ route('admin.transports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-container">
                    <div class="form-main">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thông tin cơ bản</h5>
                            </div>
                            <div class="card-body">
                                <div class="language-content active" id="lang-vi-content">
                                    <div class="form-group">
                                        <label for="name_vi">Tên Phương tiện <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name.vi') is-invalid @enderror" id="name_vi" name="name[vi]" value="{{ old('name.vi') }}" required>
                                        @error('name.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_info_vi">Thông tin liên hệ <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('contact_info.vi') is-invalid @enderror" id="contact_info_vi" name="contact_info[vi]">{{ old('contact_info.vi') }}</textarea>
                                        @error('contact_info.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="language-content" id="lang-en-content">
                                    <div class="form-group">
                                        <label for="name_en">Tên Phương tiện (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('name.en') is-invalid @enderror" id="name_en" name="name[en]" value="{{ old('name.en') }}">
                                        @error('name.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="contact_info_en">Thông tin liên hệ (Tiếng Anh)</label>
                                        <textarea class="form-control @error('contact_info.en') is-invalid @enderror" id="contact_info_en" name="contact_info[en]">{{ old('contact_info.en') }}</textarea>
                                        @error('contact_info.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">Loại Phương tiện <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="">-- Chọn loại --</option>
                                                @foreach(['car', 'motorbike', 'bicycle', 'boat'] as $type)
                                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="provider_id">Nhà cung cấp</label>
                                            <select class="form-control @error('provider_id') is-invalid @enderror" id="provider_id" name="provider_id">
                                                <option value="">-- Chọn nhà cung cấp --</option>
                                                @foreach($providers as $provider)
                                                    <option value="{{ $provider->provider_id }}" {{ old('provider_id') == $provider->provider_id ? 'selected' : '' }}>
                                                        {{ $provider->getTranslation('name', 'vi') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('provider_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="capacity">Sức chứa <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" id="capacity" name="capacity" value="{{ old('capacity') }}" required>
                                            @error('capacity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price_per_day">Giá mỗi ngày (VNĐ) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" id="price_per_day" name="price_per_day" value="{{ old('price_per_day') }}" required>
                                            @error('price_per_day')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('admin.partials.image-upload', ['resourceName' => 'Phương tiện'])
                        @include('admin.partials.amenities-selector')
                        @include('admin.partials.form-actions', ['formId' => 'transport-form', 'viewPrefix' => 'transports'])
                    </div>

                    <div class="form-sidebar">
                        @include('admin.partials.language-selector')
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="status">Trạng thái</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                        <option value="booked" {{ old('status') == 'booked' ? 'selected' : '' }}>Đã đặt</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_admin_managed" id="is_admin_managed" value="1" {{ old('is_admin_managed') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_admin_managed">
                                            Quản lý bởi Admin
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
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

        document.getElementById('images').addEventListener('change', function(event) {
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