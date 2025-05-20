@extends('layouts.app')

@section('title', __('Danh sách khách sạn và homestay'))

@section('styles')
    <style>
        .filter-sidebar {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            height: fit-content;
            position: sticky;
            top: 100px;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .card {
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .price-range {
            font-weight: bold;
            color: #007bff;
        }
        .hotel-list {
            padding-left: 15px;
        }
        @media (max-width: 991px) {
            .filter-sidebar {
                position: static;
                margin-bottom: 20px;
            }
            .hotel-list {
                padding-left: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">@lang('Danh sách khách sạn và homestay')</h1>

        <div class="row g-4">
            <!-- Filter Sidebar (Left) -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h4 class="mb-3">@lang('Bộ lọc')</h4>
                    <form action="{{ route('hotels.index') }}" method="GET">
                        <div class="mb-3">
                            <label for="type" class="form-label">@lang('Loại hình')</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">@lang('Tất cả')</option>
                                <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>@lang('Khách sạn')</option>
                                <option value="homestay" {{ request('type') == 'homestay' ? 'selected' : '' }}>@lang('Homestay')</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price_range" class="form-label">@lang('Mức giá')</label>
                            <select name="price_range" id="price_range" class="form-select">
                                <option value="">@lang('Tất cả')</option>
                                <option value="$" {{ request('price_range') == '$' ? 'selected' : '' }}>@lang('Giá rẻ')</option>
                                <option value="$$" {{ request('price_range') == '$$' ? 'selected' : '' }}>@lang('Trung bình')</option>
                                <option value="$$$" {{ request('price_range') == '$$$' ? 'selected' : '' }}>@lang('Cao cấp')</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amenities" class="form-label">@lang('Tiện ích')</label>
                            <select name="amenities[]" id="amenities" class="form-select" multiple>
                                @foreach (\App\Models\Amenity::all() as $amenity)
                                    <option value="{{ $amenity->amenity_id }}" {{ in_array($amenity->amenity_id, request('amenities', [])) ? 'selected' : '' }}>{{ $amenity->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">@lang('Vị trí')</label>
                            <input type="text" name="location" id="location" class="form-control" value="{{ request('location') }}" placeholder="@lang('Nhập vị trí')">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">@lang('Lọc')</button>
                            <a href="{{ route('hotels.index') }}" class="btn btn-secondary">@lang('Xóa bộ lọc')</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hotel List (Right) -->
            <div class="col-lg-9 hotel-list">
                <div class="row g-4">
                    @forelse ($hotels as $hotel)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="{{ $hotel->images->first()->url ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $hotel->name }}">
                                <div class="card-body">
                                    <h3 class="h5 fw-bold">{{ $hotel->name }}</h3>
                                    <span class="badge bg-primary">{{ $hotel->type == 'hotel' ? __('Khách sạn') : __('Homestay') }}</span>
                                    <span class="badge bg-success">{{ $hotel->price_range }}</span>
                                    <p class="text-muted mt-2">{{ Str::limit($hotel->description, 100) }}</p>
                                    <p><i class="fas fa-map-marker-alt"></i> {{ $hotel->address }}</p>
                                    <a href="{{ route('hotels.show', $hotel->hotel_id) }}" class="btn btn-primary">@lang('Xem chi tiết')</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">@lang('Không tìm thấy khách sạn phù hợp.')</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $hotels->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Optional: Add JavaScript for dynamic filter interactions
        document.getElementById('amenities').addEventListener('click', function(e) {
            // Ensure multiple selection works smoothly
        });
    </script>
@endsection