@extends('admin.layouts.app')

@section('title', 'Thêm Nhà hàng mới - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.restaurants.index') }}">Quản lý Nhà hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thêm Nhà hàng mới</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Thêm Nhà hàng mới</h2>
            <div>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('restaurant-form').submit();">
                    <i class="fas fa-save me-1"></i> Lưu
                </button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('restaurant-form').reset();">
                    <i class="fas fa-redo me-1"></i> Làm mới
                </button>
            </div>
        </div>

        <div class="content">
            <div class="language-alert" id="language-alert">
                Bạn đang chỉnh sửa phiên bản "Tiếng Việt (Vietnamese)"
            </div>

            <form id="restaurant-form" action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="name_vi">Tên Nhà hàng <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name.vi') is-invalid @enderror" id="name_vi" name="name[vi]" value="{{ old('name.vi') }}" required>
                                        @error('name.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="address_vi">Địa chỉ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('address.vi') is-invalid @enderror" id="address_vi" name="address[vi]" value="{{ old('address.vi') }}" required>
                                        @error('address.vi')
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
                                        @error('contact_info.v
                                        i')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="cancellation_policy_vi">Chính sách hủy</label>
                                        <textarea class="form-control @error('cancellation_policy.vi') is-invalid @enderror" id="cancellation_policy_vi" name="cancellation_policy[vi]">{{ old('cancellation_policy.vi') }}</textarea>
                                        @error('cancellation_policy.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="language-content" id="lang-en-content">
                                    <div class="form-group">
                                        <label for="name_en">Tên Nhà hàng (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('name.en') is-invalid @enderror" id="name_en" name="name[en]" value="{{ old('name.en') }}">
                                        @error('name.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="address_en">Địa chỉ (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('address.en') is-invalid @enderror" id="address_en" name="address[en]" value="{{ old('address.en') }}">
                                        @error('address.en')
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
                                    <div class="form-group">
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
                                        <div class="form-group">
                                            <label for="type">Loại Nhà hàng <span class="text-danger">*</span></label>
                                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                                <option value="">-- Chọn loại --</option>
                                                @foreach(['vietnamese', 'seafood', 'asian', 'western', 'fusion', 'vegetarian', 'buffet', 'street_food', 'other'] as $type)
                                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="price_category">Hạng giá <span class="text-danger">*</span></label>
                                            <select class="form-control @error('price_category') is-invalid @enderror" id="price_category" name="price_category" required>
                                                <option value="">-- Chọn hạng giá --</option>
                                                @foreach(['budget', 'mid_range', 'fine_dining', 'luxury'] as $category)
                                                    <option value="{{ $category }}" {{ old('price_category') == $category ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $category)) }}</option>
                                                @endforeach
                                            </select>
                                            @error('price_category')
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
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="opening_hours">Giờ mở cửa</label>
                                            <input type="text" class="form-control @error('opening_hours') is-invalid @enderror" id="opening_hours" name="opening_hours" value="{{ old('opening_hours') }}" placeholder="Ví dụ: 8:00 - 22:00">
                                            @error('opening_hours')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="price_range">Khoảng giá</label>
                                            <input type="text" class="form-control @error('price_range') is-invalid @enderror" id="price_range" name="price_range" value="{{ old('price_range') }}">
                                            @error('price_range')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitude">Vĩ độ (Latitude)</label>
                                            <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude') }}">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitude">Kinh độ (Longitude)</label>
                                            <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude') }}">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <label>Bản đồ</label>
                                    <div id="map" style="height: 300px; width: 100%;" class="border rounded"></div>
                                    <div class="form-text">Nhấp vào bản đồ để chọn vị trí hoặc nhập tọa độ thủ công</div>
                                </div>
                            </div>
                        </div>

                        @include('admin.partials.image-upload', ['resourceName' => 'Nhà hàng'])
                        @include('admin.partials.amenities-selector')
                        @include('admin.partials.form-actions', ['formId' => 'restaurant-form', 'viewPrefix' => 'restaurants'])
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
        let map, marker;

        function initMap() {
            const defaultLocation = { lat: 9.8088, lng: 106.5662 };
            map = new google.maps.Map(document.getElementById('map'), {
                center: defaultLocation,
                zoom: 12
            });

            map.addListener('click', function(event) {
                placeMarker(event.latLng);
            });
        }

        function placeMarker(location) {
            if (marker) {
                marker.setPosition(location);
            } else {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    draggable: true
                });

                google.maps.event.addListener(marker, 'dragend', function() {
                    updateCoordinates(marker.getPosition());
                });
            }

            updateCoordinates(location);
            map.panTo(location);
        }

        function updateCoordinates(location) {
            document.getElementById('latitude').value = location.lat().toFixed(6);
            document.getElementById('longitude').value = location.lng().toFixed(6);
        }

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