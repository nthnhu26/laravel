<!-- resources/views/frontend/nhà/transport/index.blade.php -->
@extends('layouts.app')

@section('title', 'Danh sách phương tiện')

@section('styles')
    <style>
        .transport-card {
            transition: transform var(--transition-speed) ease;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .transport-card:hover {
            transform: translateY(-5px);
        }
        .transport-card img {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
        .transport-card .card-body {
            padding: 20px;
        }
        .transport-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .transport-card .card-text {
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
                    <form method="GET" action="{{ route('transports.index') }}">
                        <!-- Lọc theo loại phương tiện -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Loại phương tiện</h5>
                            <select name="type" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="car" {{ request('type') == 'car' ? 'selected' : '' }}>Xe hơi</option>
                                <option value="motorbike" {{ request('type') == 'motorbike' ? 'selected' : '' }}>Xe máy</option>
                                <option value="bicycle" {{ request('type') == 'bicycle' ? 'selected' : '' }}>Xe đạp</option>
                                <option value="boat" {{ request('type') == 'boat' ? 'selected' : '' }}>Thuyền</option>
                            </select>
                        </div>

                        <!-- Lọc theo trạng thái -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Trạng thái</h5>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Có sẵn</option>
                                <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>Đã đặt</option>
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

            <!-- Cột chính: Danh sách phương tiện -->
            <div class="col-lg-9 col-md-8">
                <h2 class="fs-3 fw-bold mb-4 section-title">Danh sách phương tiện</h2>
                @if ($transports->isEmpty())
                    <div class="alert alert-info">
                        Không tìm thấy phương tiện nào phù hợp với bộ lọc.
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($transports as $transport)
                            <div class="col-lg-4 col-md-6">
                                <div class="transport-card card border-0">
                                    @if ($transport->images->first())
                                        <img src="{{ asset('storage/' . $transport->images->first()->url) }}" alt="{{ $transport->name }}" class="card-img-top">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $transport->name }}</h5>
                                        <p class="card-text">{{ Str::limit($transport->description, 100) }}</p>
                                        <p class="card-text small text-muted"><i class="bi bi-car-front-fill me-2"></i> {{ $transport->type }}</p>
                                        <p class="card-text small text-muted"><i class="bi bi-currency-dollar me-2"></i> {{ number_format($transport->price_per_day, 2) }} VNĐ/ngày</p>
                                        <a href="{{ route('transports.show', $transport->transport_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Phân trang -->
                    <div class="mt-5">
                        {{ $transports->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection