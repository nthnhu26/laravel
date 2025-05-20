<!-- Location (Tọa độ và tìm kiếm địa điểm) -->
<div class="form-group mb-3">
    <label for="place_search">Tìm kiếm địa điểm</label>
    <input type="text" class="form-control" id="place_search" placeholder="Nhập địa chỉ hoặc tên địa điểm" value="{{ old('address.vi', isset($entity) && $entity ? $entity->getTranslation('address', 'vi') : '') }}">
    <div id="place_suggestions" class="list-group" style="position: absolute; z-index: 1000; width: 100%; max-height: 200px; overflow-y: auto; display: none;"></div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="latitude">Vĩ độ (Latitude)</label>
            <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', isset($entity) && $entity ? $entity->latitude : '') }}">
            @error('latitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="longitude">Kinh độ (Longitude)</label>
            <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', isset($entity) && $entity ? $entity->longitude : '') }}">
            @error('longitude')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="form-group mb-3">
    <label>Bản đồ</label>
    <div id="map" style="height: 300px; width: 100%;" class="border rounded"></div>
    <div class="form-text">Nhấp vào bản đồ để chọn vị trí hoặc nhập tọa độ thủ công</div>
    <div id="map-error" class="text-danger" style="display: none;">Lỗi tải bản đồ. Vui lòng kiểm tra API key hoặc cấu hình Goong Dashboard.</div>
</div>

<!-- Images (Hình ảnh) -->
<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Hình ảnh {{ $resourceName }}</h5>
    </div>
    <div class="card-body">
        @if(isset($entity) && $entity && $entity->images->isNotEmpty())
            <div class="image-gallery mb-3 d-flex flex-wrap">
                @foreach($entity->images as $image)
                    <div class="image-gallery-item position-relative">
                        <img src="{{ Storage::url($image->url) }}" alt="{{ $image->caption ?? 'Hình ảnh' }}">
                        <span class="badge {{ $image->is_featured ? 'bg-primary' : 'bg-secondary' }} position-absolute top-0 start-0 m-2">
                            {{ $image->is_featured ? 'Nổi bật' : 'Bình thường' }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="form-group mb-3">
            <label for="images">Tải lên hình ảnh mới</label>
            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
            @error('images')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div id="image-preview" class="image-gallery d-flex flex-wrap"></div>
    </div>
</div>

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@goongmaps/goong-js@1.0.9/dist/goong-js.css" rel="stylesheet">
@endpush