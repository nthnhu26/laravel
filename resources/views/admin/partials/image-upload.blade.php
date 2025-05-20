<div class="card mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">Hình ảnh</h5>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="images">Hình ảnh {{ $resourceName }}</label>
            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
            <div class="form-text">Có thể chọn nhiều hình ảnh. Hình đầu tiên sẽ là hình đại diện.</div>
            @error('images')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="image-gallery" id="image-preview">
                @if(isset($entity) && $entity->images)
                    @foreach($entity->images as $image)
                        <div class="image-gallery-item">
                            <img src="{{ Storage::url($image->url) }}" alt="{{ $image->caption ?? 'Image' }}">
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>