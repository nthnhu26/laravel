<form action="{{ route('frontend.attractions.index') }}" method="GET" class="search-bar">
    <div class="row g-3 align-items-center">
        <div class="col-md-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm địa điểm..." class="form-control rounded-pill">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select rounded-pill">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}" {{ request('category_id') == $category->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="amenity_id" class="form-select rounded-pill">
                <option value="">Tất cả tiện ích</option>
                @foreach(\App\Models\Amenity::all() as $amenity)
                    <option value="{{ $amenity->amenity_id }}" {{ request('amenity_id') == $amenity->amenity_id ? 'selected' : '' }}>{{ $amenity->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100 rounded-pill"><i class="fas fa-search me-2"></i>Tìm kiếm</button>
        </div>
    </div>
</form>