@extends('admin.layouts.app')

@section('title', 'Thêm Tour mới - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tours.index') }}">Quản lý Tour</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Tour mới</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Thêm Tour mới</h2>
            <div>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('tour-form').submit();">
                    <i class="fas fa-save me-1"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('tour-form').reset();">
                    <i class="fas fa-redo me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <div class="content">
            <div class="language-alert" id="language-alert">
                Bạn đang chỉnh sửa phiên bản "Tiếng Việt (Vietnamese)"
            </div>

            <form id="tour-form" action="{{ route('admin.tours.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="name_vi">Tên Tour <span class="text-danger">*</span></label>
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
                                        <label for="name_en">Tên Tour (Tiếng Anh)</label>
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
                                    <div class="form-group">
                                        <label for="contact_info_en">Thông tin liên hệ (Tiếng Anh)</label>
                                        <textarea class="form-control @error('contact_info.en') is-invalid @enderror" id="contact_info_en" name="contact_info[en]">{{ old('contact_info.en') }}</textarea>
                                        @error('contact_info.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </上市

System: It seems the response was cut off again. I'll continue from the `tours/create.blade.php` file, complete it, and then provide the `tours/edit.blade.php`, followed by the views for `Transport`, `Amenity`, and `Image`. I'll also include the JavaScript, routes, middleware, and Flasher configuration to finalize the solution. The templates will maintain simplicity, use client-side DataTables, support multilingual fields, and reuse partials for consistency.

---

### Continuing Step 5: Blade Templates (Tour Create View)

**`resources/views/admin/tours/create.blade.php`** (continued)

```blade
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
                                            <label for="type">Loại Tour <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="">-- Chọn loại --</option>
                                                @foreach(['eco', 'beach', 'cultural', 'adventure', 'family', 'romantic', 'photography', 'fishing', 'luxury', 'other'] as $type)
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
                                            <label for="duration_days">Số ngày <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('duration_days') is-invalid @enderror" id="duration_days" name="duration_days" value="{{ old('duration_days') }}" required>
                                            @error('duration_days')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Giá (VNĐ) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="max_people">Số người tối đa</label>
                                            <input type="number" class="form-control @error('max_people') is-invalid @enderror" id="max_people" name="max_people" value="{{ old('max_people') }}">
                                            @error('max_people')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="created_by">Người tạo <span class="text-danger">*</span></label>
                                            <select class="form-control @error('created_by') is-invalid @enderror" id="created_by" name="created_by" required>
                                                <option value="">-- Chọn người tạo --</option>
                                                @foreach(\App\Models\User::where('role', 'admin')->get() as $user)
                                                    <option value="{{ $user->user_id }}" {{ old('created_by') == $user->user_id ? 'selected' : '' }}>
                                                        {{ $user->full_name['vi'] ?? $user->email }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('created_by')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @include('admin.partials.image-upload', ['resourceName' => 'Tour'])
                        @include('admin.partials.amenities-selector')
                        @include('admin.partials.form-actions', ['formId' => 'tour-form', 'viewPrefix' => 'tours'])
                    </div>

                    <div class="form-sidebar">
                        @include('admin.partials.language-selector')
                        <div class="card mb-3">
                            <div class="card-body">
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