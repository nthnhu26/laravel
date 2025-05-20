<!-- admin/partials/amenities-selector.blade.php -->
<div class="form-group mb-3">
    <label>Tiện ích</label>
    <div class="row">
        @foreach($amenities as $amenity)
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="amenities[]"
                           id="amenity_{{ $amenity->amenity_id }}" value="{{ $amenity->amenity_id }}"
                           {{ in_array($amenity->amenity_id, old('amenities', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="amenity_{{ $amenity->amenity_id }}">
                        {{ $amenity->getTranslation('name', 'vi') }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    @error('amenities')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>