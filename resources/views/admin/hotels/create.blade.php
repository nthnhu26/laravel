@extends('admin.layouts.app')

@section('title', 'Thêm Khách sạn mới - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.hotels.index') }}">Quản lý Khách sạn</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Khách sạn mới</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Thêm Khách sạn mới</h2>
            <div>
                <button type="submit" form="hotel-form" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('hotel-form').reset();">
                    <i class="fas fa-redo me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <div class="content">
            <div class="language-alert" id="language-alert">
                Bạn đang chỉnh sửa phiên bản "Tiếng Việt (Vietnamese)"
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="hotel-form" action="{{ route('admin.hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-container">
                    <div class="form-main">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Thông tin cơ bản</h5>
                            </div>
                            <div class="card-body">
                                <div class="language-content active" id="lang-vi-content">
                                    <div class="form-group mb-3">
                                        <label for="name_vi">Tên Khách sạn <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name.vi') is-invalid @enderror" id="name_vi" name="name[vi]" value="{{ old('name.vi') }}" required>
                                        @error('name.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="address_vi">Địa chỉ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('address.vi') is-invalid @enderror" id="address_vi" name="address[vi]" value="{{ old('address.vi') }}" required>
                                        @error('address.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description_vi">Mô tả</label>
                                        <textarea class="form-control summernote @error('description.vi') is-invalid @enderror" id="description_vi" name="description[vi]">{{ old('description.vi') }}</textarea>
                                        @error('description.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="cancellation_policy_vi">Chính sách hủy</label>
                                        <textarea class="form-control @error('cancellation_policy.vi') is-invalid @enderror" id="cancellation_policy_vi" name="cancellation_policy[vi]">{{ old('cancellation_policy.vi') }}</textarea>
                                        @error('cancellation_policy.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="language-content" id="lang-en-content">
                                    <div class="form-group mb-3">
                                        <label for="name_en">Tên Khách sạn (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('name.en') is-invalid @enderror" id="name_en" name="name[en]" value="{{ old('name.en') }}">
                                        @error('name.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="address_en">Địa chỉ (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('address.en') is-invalid @enderror" id="address_en" name="address[en]" value="{{ old('address.en') }}">
                                        @error('address.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description_en">Mô tả (Tiếng Anh)</label>
                                        <textarea class="form-control summernote @error('description.en') is-invalid @enderror" id="description_en" name="description[en]">{{ old('description.en') }}</textarea>
                                        @error('description.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="cancellation_policy_en">Chính sách hủy (Tiếng Anh)</label>
                                        <textarea class="form-control @error('cancellation_policy.en') is-invalid @enderror" id="cancellation_policy_en" name="cancellation_policy[en]">{{ old('cancellation_policy.en') }}</textarea>
                                        @error('cancellation_policy.en')
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
                                        <div class="form-group mb-3">
                                            <label for="type">Loại Khách sạn <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="">-- Chọn loại --</option>
                                                @foreach(['luxury' => 'Sang trọng', 'budget' => 'Giá rẻ', 'resort' => 'Khu nghỉ dưỡng', 'homestay' => 'Homestay', 'beach_view' => 'View biển', 'family' => 'Gia đình', 'business' => 'Doanh nhân', 'villa' => 'Biệt thự', 'apartment' => 'Căn hộ', 'other' => 'Khác'] as $value => $label)
                                                    <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3" id="provider-id" style="{{ old('is_admin_managed') ? 'display: none;' : '' }}">
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
                                        <div class="form-group mb-3">
                                            <label for="price_range">Khoảng giá</label>
                                            <input type="text" class="form-control @error('price_range') is-invalid @enderror" id="price_range" name="price_range" value="{{ old('price_range') }}" placeholder="VD: 500.000 - 1.500.000 VNĐ">
                                            @error('price_range')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="check_in_time">Giờ check-in</label>
                                            <input type="time" class="form-control @error('check_in_time') is-invalid @enderror" id="check_in_time" name="check_in_time" value="{{ old('check_in_time') }}">
                                            @error('check_in_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="check_out_time">Giờ check-out</label>
                                            <input type="time" class="form-control @error('check_out_time') is-invalid @enderror" id="check_out_time" name="check_out_time" value="{{ old('check_out_time') }}">
                                            @error('check_out_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tọa độ và hình ảnh -->
                                @include('admin.partials.location-image', [
                                    'resourceName' => 'Khách sạn',
                                    'entity' => null
                                ])

                                <div class="form-group mb-3" id="contact-info" style="{{ old('provider_id') ? 'display: none;' : '' }}">
                                    <label>Thông tin liên hệ <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="contact_email">Email</label>
                                            <input type="email" class="form-control @error('contact_info.email') is-invalid @enderror" id="contact_email" name="contact_info[email]" value="{{ old('contact_info.email') }}" {{ old('provider_id') ? 'disabled' : '' }}>
                                            @error('contact_info.email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contact_phone">Số điện thoại</label>
                                            <input type="text" class="form-control @error('contact_info.phone') is-invalid @enderror" id="contact_phone" name="contact_info[phone]" value="{{ old('contact_info.phone') }}" {{ old('provider_id') ? 'disabled' : '' }}>
                                            @error('contact_info.phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contact_website">Website</label>
                                            <input type="url" class="form-control @error('contact_info.website') is-invalid @enderror" id="contact_website" name="contact_info[website]" value="{{ old('contact_info.website') }}" {{ old('provider_id') ? 'disabled' : '' }}>
                                            @error('contact_info.website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tiện ích -->
                        @include('admin.partials.amenities-selector', [
                            'amenities' => $amenities,
                            'entity' => null
                        ])

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Quản lý Phòng</h5>
                            </div>
                            <div class="card-body">
                                <p>Lưu khách sạn trước để thêm phòng.</p>
                            </div>
                        </div>

                        @include('admin.partials.form-actions', ['formId' => 'hotel-form', 'viewPrefix' => 'hotels'])
                    </div>

                    <div class="form-sidebar">
                        @include('admin.partials.language-selector')
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="status">Trạng thái</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Công khai</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_admin_managed" id="is_admin_managed" value="1" {{ old('is_admin_managed') ? 'checked' : '' }} onchange="toggleProviderField()">
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
        .note-editor.note-frame {
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
        }
        .note-editor.note-frame .note-editing-area {
            min-height: 200px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-container {
            display: flex;
            gap: 1.5rem;
        }
        .form-main {
            flex: 3;
        }
        .form-sidebar {
            flex: 1;
            min-width: 250px;
        }
        .image-gallery-item img {
            max-width: 100%;
            height: 100px;
            object-fit: cover;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin: 5px;
        }
        #contact-info {
            border-top: 1px solid #e9ecef;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        #place_suggestions .list-group-item {
            cursor: pointer;
        }
        #place_suggestions .list-group-item:hover {
            background-color: #f8f9fa;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
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

        function toggleProviderField() {
            const isAdminManaged = document.getElementById('is_admin_managed').checked;
            const providerId = document.getElementById('provider-id');
            const contactInfo = document.getElementById('contact-info');

            providerId.style.display = isAdminManaged ? 'none' : 'block';
            contactInfo.style.display = isAdminManaged ? 'block' : 'none';
            contactInfo.querySelectorAll('input').forEach(input => {
                input.disabled = !isAdminManaged;
            });

            if (!isAdminManaged) {
                document.getElementById('provider_id').value = '';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        let formData = new FormData();
                        formData.append('file', files[0]);

                        fetch('{{ route('admin.upload.image') }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.url) {
                                $(this).summernote('insertImage', data.url);
                            } else {
                                console.error('Lỗi tải ảnh:', data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi tải ảnh:', error);
                        });
                    }
                }
            });

            toggleProviderField();
            switchLanguage('vi');

            document.getElementById('provider_id').addEventListener('change', toggleProviderField);
            document.getElementById('is_admin_managed').addEventListener('change', toggleProviderField);
        });
    </script>
@endsection