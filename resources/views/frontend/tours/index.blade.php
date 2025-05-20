<!-- resources/views/frontend/nhà/tour/index.blade.php -->
@extends('layouts.app')

@section('title', 'Danh sách tour')

@section('styles')
    <style>
        .tour-card {
            transition: transform var(--transition-speed) ease;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .tour-card:hover {
            transform: translateY(-5px);
        }
        .tour-card img {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
        .tour-card .card-body {
            padding: 20px;
        }
        .tour-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .tour-card .card-text {
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
                    <form method="GET" action="{{ route('tours.index') }}">
                        <!-- Lọc theo trạng thái -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Trạng thái</h5>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>

                        <!-- Lọc theo số ngày -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Thời gian tour</h5>
                            <select name="duration_days" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="1" {{ request('duration_days') == '1' ? 'selected' : '' }}>1 ngày</option>
                                <option value="2" {{ request('duration_days') == '2' ? 'selected' : '' }}>2 ngày</option>
                                <option value="3" {{ request('duration_days') == '3' ? 'selected' : '' }}>3 ngày</option>
                                <option value="4" {{ request('duration_days') == '4' ? 'selected' : '' }}>4 ngày</option>
                                <option value="5" {{ request('duration_days') == '5' ? 'selected' : '' }}>5+ ngày</option>
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

            <!-- Cột chính: Danh sách tour -->
            <div class="col-lg-9 col-md-8">
                <h2 class="fs-3 fw-bold mb-4 section-title">Danh sách tour</h2>
                @if ($tours->isEmpty())
                    <div class="alert alert-info">
                        Không tìm thấy tour nào phù hợp với bộ lọc.
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($tours as $tour)
                            <div class="col-lg-4 col-md-6">
                                <div class="tour-card card border-0">
                                    @if ($tour->images->first())
                                        <img src="{{ asset('storage/' . $tour->images->first()->url) }}" alt="{{ $tour->name }}" class="card-img-top">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $tour->name }}</h5>
                                        <p class="card-text">{{ Str::limit($tour->description, 100) }}</p>
                                        <p class="card-text small text-muted"><i class="bi bi-clock-fill me-2"></i> {{ $tour->duration_days }} ngày</p>
                                        <p class="card-text small text-muted"><i class="bi bi-currency-dollar me-2"></i> {{ number_format($tour->price, 2) }} VNĐ</p>
                                        <a href="{{ route('tours.show', $tour->tour_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Phân trang -->
                    <div class="mt-5">
                        {{ $tours->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection