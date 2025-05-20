@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Khách sạn - Ba Động Tourism')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.hotels.index') }}">Quản lý Khách sạn</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa Khách sạn</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="main-content">
        <div class="header">
            <h2>Chỉnh sửa Khách sạn</h2>
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

            <form id="hotel-form" action="{{ route('admin.hotels.update', $entity) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                        <input type="text" class="form-control @error('name.vi') is-invalid @enderror" id="name_vi" name="name[vi]" value="{{ old('name.vi', $entity->getTranslation('name', 'vi')) }}" required>
                                        @error('name.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="address_vi">Địa chỉ <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('address.vi') is-invalid @enderror" id="address_vi" name="address[vi]" value="{{ old('address.vi', $entity->getTranslation('address', 'vi')) }}" required>
                                        @error('address.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description_vi">Mô tả</label>
                                        <textarea class="form-control summernote @error('description.vi') is-invalid @enderror" id="description_vi" name="description[vi]">{{ old('description.vi', $entity->getTranslation('description', 'vi')) }}</textarea>
                                        @error('description.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="cancellation_policy_vi">Chính sách hủy</label>
                                        <textarea class="form-control @error('cancellation_policy.vi') is-invalid @enderror" id="cancellation_policy_vi" name="cancellation_policy[vi]">{{ old('cancellation_policy.vi', $entity->getTranslation('cancellation_policy', 'vi')) }}</textarea>
                                        @error('cancellation_policy.vi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="language-content" id="lang-en-content">
                                    <div class="form-group mb-3">
                                        <label for="name_en">Tên Khách sạn (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('name.en') is-invalid @enderror" id="name_en" name="name[en]" value="{{ old('name.en', $entity->getTranslation('name', 'en')) }}">
                                        @error('name.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="address_en">Địa chỉ (Tiếng Anh)</label>
                                        <input type="text" class="form-control @error('address.en') is-invalid @enderror" id="address_en" name="address[en]" value="{{ old('address.en', $entity->getTranslation('address', 'en')) }}">
                                        @error('address.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="description_en">Mô tả (Tiếng Anh)</label>
                                        <textarea class="form-control summernote @error('description.en') is-invalid @enderror" id="description_en" name="description[en]">{{ old('description.en', $entity->getTranslation('description', 'en')) }}</textarea>
                                        @error('description.en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="cancellation_policy_en">Chính sách hủy (Tiếng Anh)</label>
                                        <textarea class="form-control @error('cancellation_policy.en') is-invalid @enderror" id="cancellation_policy_en" name="cancellation_policy[en]">{{ old('cancellation_policy.en', $entity->getTranslation('cancellation_policy', 'en')) }}</textarea>
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
                                                    <option value="{{ $value }}" {{ old('type', $entity->type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3" id="provider-id" style="{{ $entity->is_admin_managed ? 'display: none;' : '' }}">
                                            <label for="provider_id">Nhà cung cấp</label>
                                            <select class="form-control @error('provider_id') is-invalid @enderror" id="provider_id" name="provider_id">
                                                <option value="">-- Chọn nhà cung cấp --</option>
                                                @foreach($providers as $provider)
                                                    <option value="{{ $provider->provider_id }}" {{ old('provider_id', $entity->provider_id) == $provider->provider_id ? 'selected' : '' }}>
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
                                            <input type="text" class="form-control @error('price_range') is-invalid @enderror" id="price_range" name="price_range" value="{{ old('price_range', $entity->price_range) }}" placeholder="VD: 500.000 - 1.500.000 VNĐ">
                                            @error('price_range')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="check_in_time">Giờ check-in</label>
                                            <input type="time" class="form-control @error('check_in_time') is-invalid @enderror" id="check_in_time" name="check_in_time" value="{{ old('check_in_time', $entity->check_in_time) }}">
                                            @error('check_in_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="check_out_time">Giờ check-out</label>
                                            <input type="time" class="form-control @error('check_out_time') is-invalid @enderror" id="check_out_time" name="check_out_time" value="{{ old('check_out_time', $entity->check_out_time) }}">
                                            @error('check_out_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude">Vĩ độ (Latitude)</label>
                                            <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $entity->latitude) }}">
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude">Kinh độ (Longitude)</label>
                                            <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $entity->longitude) }}">
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="place_search">Tìm kiếm địa điểm</label>
                                    <input type="text" class="form-control" id="place_search" placeholder="Nhập địa chỉ hoặc tên địa điểm" value="{{ old('address.vi', $entity->getTranslation('address', 'vi')) }}">
                                    <div id="place_suggestions" class="list-group" style="position: absolute; z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto; display: none;"></div>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Bản đồ</label>
                                    <div id="map" style="height: 300px; width: 100%;" class="border rounded"></div>
                                    <div class="form-text">Nhấp vào bản đồ để chọn vị trí hoặc nhập tọa độ thủ công</div>
                                    <div id="map-error" class="text-danger" style="display: none;">Lỗi tải bản đồ. Vui lòng kiểm tra API key hoặc cấu hình Goong Dashboard.</div>
                                </div>
                                <div class="form-group mb-3" id="contact-info" style="{{ $entity->provider_id ? 'display: none;' : '' }}">
                                    <label>Thông tin liên hệ <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="contact_email">Email</label>
                                            <input type="email" class="form-control @error('contact_info.email') is-invalid @enderror" id="contact_email" name="contact_info[email]" value="{{ old('contact_info.email', $entity->contact_info['email'] ?? '') }}" {{ $entity->provider_id ? 'disabled' : '' }}>
                                            @error('contact_info.email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contact_phone">Số điện thoại</label>
                                            <input type="text" class="form-control @error('contact_info.phone') is-invalid @enderror" id="contact_phone" name="contact_info[phone]" value="{{ old('contact_info.phone', $entity->contact_info['phone'] ?? '') }}" {{ $entity->provider_id ? 'disabled' : '' }}>
                                            @error('contact_info.phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label for="contact_website">Website</label>
                                            <input type="url" class="form-control @error('contact_info.website') is-invalid @enderror" id="contact_website" name="contact_info[website]" value="{{ old('contact_info.website', $entity->contact_info['website'] ?? '') }}" {{ $entity->provider_id ? 'disabled' : '' }}>
                                            @error('contact_info.website')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Quản lý Phòng</h5>
                            </div>
                            <div class="card-body">
                                <a href="{{ route('admin.rooms.create', ['hotel_id' => $entity->id]) }}" class="btn btn-primary mb-3">
                                    <i class="fas fa-plus me-1"></i> Thêm Phòng
                                </a>
                                @if($entity->rooms->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tên</th>
                                                    <th>Giá/Ngày (VNĐ)</th>
                                                    <th>Sức chứa</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($entity->rooms as $room)
                                                    <tr>
                                                        <td>{{ $room->id }}</td>
                                                        <td>{{ $room->getTranslation('name', 'vi') }}</td>
                                                        <td>{{ number_format($room->price_per_night) }}</td>
                                                        <td>{{ $room->capacity }}</td>
                                                        <td><span class="status-badge status-{{ $room->status }}">{{ ucfirst($room->status) }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody typu
                                        </table>
                                    </div>
                                @else
                                    <p>Chưa có phòng nào được thêm.</p>
                                @endif
                            </div>
                        </div>

                        @include('admin.partials.image-upload', ['resourceName' => 'Khách sạn', 'entity' => $entity])
                        @include('admin.partials.amenities-selector', ['entity' => $entity])
                        @include('admin.partials.form-actions', ['formId' => 'hotel-form', 'viewPrefix' => 'hotels'])
                    </div>

                    <div class="form-sidebar">
                        @include('admin.partials.language-selector')
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="status">Trạng thái</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', $entity->status) == 'active' ? 'selected' : '' }}>Công khai</option>
                                        <option value="inactive" {{ old('status', $entity->status) == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_admin_managed" id="is_admin_managed" value="1" {{ old('is_admin_managed', $entity->is_admin_managed) ? 'checked' : '' }} onchange="toggleProviderField()">
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
    <link href="https://cdn.jsdelivr.net/npm/@goongmaps/goong-js@1.0.9/dist/goong-js.css" rel="stylesheet">
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@goongmaps/goong-js@1.0.9/dist/goong-js.js" defer></script>
    <script>
        let map, marker;
        const goongApiKey = '{{ $goong_api_key ?? '' }}';
        const goongMaptilesKey = '{{ $goong_maptiles_key ?? '' }}';

        // Debug API keys
        console.log('Goong API Key:', goongApiKey);
        console.log('Goong Maptiles Key:', goongMaptilesKey);

        function initMap() {
            if (typeof goongjs === 'undefined') {
                document.getElementById('map').style.display = 'none';
                document.getElementById('map-error').style.display = 'block';
                console.error('Thư viện Goong JS không được tải. Kiểm tra CDN.');
                return;
            }

            if (!goongMaptilesKey) {
                document.getElementById('map').style.display = 'none';
                document.getElementById('map-error').style.display = 'block';
                console.error('Khóa Maptiles Goong bị thiếu.');
                return;
            }

            try {
                goongjs.accessToken = goongMaptilesKey;
                const defaultLocation = { lng: {{ $entity->longitude ?? 106.5662 }}, lat: {{ $entity->latitude ?? 9.8088 }} };
                map = new goongjs.Map({
                    container: 'map',
                    style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${goongMaptilesKey}`,
                    center: [defaultLocation.lng, defaultLocation.lat],
                    zoom: 12
                });

                @if($entity->latitude && $entity->longitude)
                    placeMarker({ lng: {{ $entity->longitude }}, lat: {{ $entity->latitude }} });
                @endif

                map.on('click', function(e) {
                    placeMarker(e.lngLat);
                });

                map.on('error', function(e) {
                    document.getElementById('map').style.display = 'none';
                    document.getElementById('map-error').style.display = 'block';
                    console.error('Lỗi tải bản đồ Goong:', e);
                });
            } catch (error) {
                document.getElementById('map').style.display = 'none';
                document.getElementById('map-error').style.display = 'block';
                console.error('Lỗi khởi tạo bản đồ Goong:', error);
            }
        }

        function placeMarker(lngLat) {
            if (marker) {
                marker.setLngLat(lngLat);
            } else {
                marker = new goongjs.Marker({
                    draggable: true
                })
                    .setLngLat(lngLat)
                    .addTo(map);

                marker.on('dragend', function() {
                    updateCoordinates(marker.getLngLat());
                });
            }

            updateCoordinates(lngLat);
            map.panTo(lngLat);
        }

        function updateCoordinates(lngLat) {
            document.getElementById('latitude').value = lngLat.lat.toFixed(6);
            document.getElementById('longitude').value = lngLat.lng.toFixed(6);
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

        // Place Search Functionality
        let typingTimer;
        const typingDelay = 500;

        document.getElementById('place_search').addEventListener('input', function() {
            clearTimeout(typingTimer);
            const query = this.value.trim();
            if (query.length < 3) {
                document.getElementById('place_suggestions').style.display = 'none';
                return;
            }

            typingTimer = setTimeout(() => {
                fetchPlaces(query);
            }, typingDelay);
        });

        document.getElementById('place_search').addEventListener('blur', function() {
            setTimeout(() => {
                document.getElementById('place_suggestions').style.display = 'none';
            }, 200);
        });

        function fetchPlaces(query) {
            if (!goongApiKey) {
                console.error('Khóa API Goong bị thiếu cho tìm kiếm địa điểm.');
                return;
            }

            const url = `https://rsapi.goong.io/Place/AutoComplete?api_key=${goongApiKey}&input=${encodeURIComponent(query)}&limit=5&location=9.8088,106.5662&radius=2000`;
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    displaySuggestions(data);
                })
                .catch(error => {
                    console.error('Lỗi tìm kiếm địa điểm:', error);
                    document.getElementById('place_suggestions').style.display = 'none';
                });
        }

        function displaySuggestions(data) {
            const suggestionsDiv = document.getElementById('place_suggestions');
            suggestionsDiv.innerHTML = '';
            if (data.predictions && data.predictions.length > 0) {
                data.predictions.forEach(place => {
                    const item = document.createElement('div');
                    item.className = 'list-group-item';
                    item.textContent = place.description;
                    item.dataset.placeId = place.place_id;
                    item.addEventListener('click', () => selectPlace(place.place_id));
                    suggestionsDiv.appendChild(item);
                });
                suggestionsDiv.style.display = 'block';
            } else {
                suggestionsDiv.innerHTML = '<div class="list-group-item">Không tìm thấy địa điểm.</div>';
                suggestionsDiv.style.display = 'block';
            }
        }

        function selectPlace(placeId) {
            if (!goongApiKey) {
                console.error('Khóa API Goong bị thiếu cho chi tiết địa điểm.');
                return;
            }

            const url = `https://rsapi.goong.io/Place/Detail?api_key=${goongApiKey}&place_id=${placeId}`;
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.result && data.result.geometry && data.result.geometry.location) {
                        const lngLat = {
                            lng: data.result.geometry.location.lng,
                            lat: data.result.geometry.location.lat
                        };
                        placeMarker(lngLat);
                        document.getElementById('place_suggestions').style.display = 'none';
                        document.getElementById('place_search').value = data.result.formatted_address;
                        document.getElementById('address_vi').value = data.result.formatted_address;
                        document.getElementById('address_en').value = data.result.formatted_address;
                    }
                })
                .catch(error => {
                    console.error('Lỗi lấy chi tiết địa điểm:', error);
                });
        }

        document.getElementById('images')?.addEventListener('change', function(event) {
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
                    };

                    reader.readAsDataURL(file);
                }
            }
        });

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
            initMap();

            document.getElementById('provider_id').addEventListener('change', toggleProviderField);
            document.getElementById('is_admin_managed').addEventListener('change', toggleProviderField);
        });
    </script>
@endsection