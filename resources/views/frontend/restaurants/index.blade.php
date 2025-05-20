<!-- resources/views/frontend/nhà/index.blade.php -->
@extends('layouts.app')

@section('title', 'Danh sách nhà hàng')

@section('styles')
    <style>
        .restaurant-card {
            transition: transform var(--transition-speed) ease;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .restaurant-card:hover {
            transform: translateY(-5px);
        }
        .restaurant-card img {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
        .restaurant-card .card-body {
            padding: 20px;
        }
        .restaurant-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .restaurant-card .card-text {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .filter-sidebar {
            background-color: var(--bg-light);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .dark-mode .filter-sidebar {
            background-color: var(--accent-dark);
            box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
        }
        .section-title {
            position: relative;
            padding-left: 15px;
            border-left: 5px solid var(--primary-color);
        }
        .section-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: -5px;
            height: 100%;
            width: 5px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
        }
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 120, 212, 0.3);
        }
        .pagination .page-link {
            border-radius: 8px;
            margin: 0 5px;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
        }
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row g-4">
            <!-- Cột trái: Bộ lọc -->
            <div class="col-lg-3 col-md-4">
                <div class="filter-sidebar">
                    <h3 class="fs-5 fw-bold mb-4 section-title">Bộ lọc</h3>
                    <form method="GET" action="{{ route('restaurants.index') }}">
                        <!-- Lọc theo trạng thái -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Trạng thái</h5>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>

                        <!-- Lọc theo tiện ích -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Tiện ích</h5>
                            @foreach ($amenities as $amenity)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->amenity_id }}"
                                        id="amenity-{{ $amenity->amenity_id }}"
                                        {{ in_array($amenity->amenity_id, request('amenities', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity-{{ $amenity->amenity_id }}">
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Áp dụng bộ lọc</button>
                    </form>
                </div>
            </div>

            <!-- Cột chính: Danh sách nhà hàng -->
            <div class="col-lg-9 col-md-8">
                <h2 class="fs-3 fw-bold mb-4 section-title">Danh sách nhà hàng</h2>
                @if ($restaurants->isEmpty())
                    <div class="alert alert-info">
                        Không tìm thấy nhà hàng nào phù hợp với bộ lọc.
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($restaurants as $restaurant)
                            <div class="col-lg-4 col-md-6">
                                <div class="restaurant-card card border-0">
                                    @if ($restaurant->images->first())
                                        <img src="{{ asset('storage/' . $restaurant->images->first()->url) }}" alt="{{ $restaurant->name }}" class="card-img-top">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                                        <p class="card-text">{{ Str::limit($restaurant->description, 100) }}</p>
                                        <p class="card-text small text-muted"><i class="bi bi-geo-alt-fill me-2"></i> {{ $restaurant->address }}</p>
                                        <a href="{{ route('restaurants.show', $restaurant->restaurant_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Phân trang -->
                    <div class="mt-5">
                        {{ $restaurants->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection