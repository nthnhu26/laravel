@extends('layouts.app')

@section('title', $hotel->name)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    :root {
        --primary-color: #0078d4;
        --secondary-color: #00aaff;
        --bg-light: #f6f9fc;
        --border-light: #e9ecef;
        --accent-dark: #2c3e50;
        --border-dark: #4a5e77;
        --transition-speed: 0.3s;
    }

    .hotel-hero img {
        object-fit: cover;
        height: 450px;
        border-radius: 16px;
        transition: transform var(--transition-speed) ease;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .hotel-hero img:hover {
        transform: scale(1.03);
    }

    .gallery img {
        object-fit: cover;
        height: 200px;
        border-radius: 12px;
        transition: transform var(--transition-speed) ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .gallery img:hover {
        transform: scale(1.05);
    }

    .info-card,
    .amenity-card,
    .review-summary,
    .booking-form,
    .provider-info {
        border-radius: 12px;
        padding: 25px;
        background: linear-gradient(145deg, #ffffff, #f6f9fc);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .dark-mode .info-card,
    .dark-mode .amenity-card,
    .dark-mode .review-summary,
    .dark-mode .booking-form,
    .dark-mode .provider-info {
        background: linear-gradient(145deg, #1e2a3a, #2c3e50);
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

    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transition: transform 0.2s ease;
    }

    .btn-outline-primary:hover {
        transform: translateY(-2px);
        background: var(--primary-color);
        color: white;
    }

    .room-card {
        transition: transform var(--transition-speed) ease;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .room-card:hover {
        transform: translateY(-5px);
    }

    .room-img {
        object-fit: cover;
        height: 200px;
        border-radius: 12px 0 0 12px;
    }

    .related-sidebar {
        background-color: var(--bg-light);
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        height: fit-content;
        position: sticky;
        top: 80px;
    }

    .dark-mode .related-sidebar {
        background-color: var(--accent-dark);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
    }

    .related-card {
        transition: transform var(--transition-speed) ease;
        padding: 10px 0;
        border-bottom: 1px solid var(--border-light);
    }

    .dark-mode .related-card {
        border-bottom: 1px solid var(--border-dark);
    }

    .related-card:last-child {
        border-bottom: none;
    }

    .related-card:hover {
        transform: translateY(-3px);
    }

    .related-card img {
        object-fit: cover;
        height: 60px;
        width: 60px;
        border-radius: 8px;
    }

    .review-images img {
        cursor: pointer;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.2s ease;
    }

    .review-images img:hover {
        transform: scale(1.1);
    }

    .map-container {
        height: 300px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8 col-12">
            <!-- Hero Section -->
            <div class="hotel-hero mb-5">
                @if ($hotel->images->first())
                <img src="{{ asset('storage/' . $hotel->images->first()->url) }}" alt="{{ $hotel->name }}" class="w-100">
                @else
                <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="w-100">
                @endif
                <h1 class="fs-2 fw-bold mt-4 mb-2">{{ $hotel->name }}</h1>
                <p class="text-muted fs-5"><i class="bi bi-geo-alt-fill me-2"></i> {{ $hotel->address }}</p>
            </div>

            <!-- Hotel Info -->
            <div class="mb-5">
                <div class="info-card card border-0 shadow-sm">
                    <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Thông tin chi tiết')</h3>
                    <p class="text-muted">{{ $hotel->description }}</p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><strong>@lang('Giờ check-in'):</strong> {{ $hotel->check_in_time }}</li>
                        <li class="mb-2"><strong>@lang('Giờ check-out'):</strong> {{ $hotel->check_out_time }}</li>
                        <li><strong>@lang('Chính sách hủy'):</strong> {{ $hotel->cancellation_policy }}</li>
                    </ul>
                </div>
            </div>

            <!-- Amenities -->
            <div class="mb-5">
                <div class="amenity-card card border-0 shadow-sm">
                    <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Tiện ích')</h3>
                    @if ($hotel->amenities->isEmpty())
                    <p class="text-muted">@lang('Không có tiện ích nào.')</p>
                    @else
                    <ul class="list-unstyled">
                        @foreach ($hotel->amenities as $amenity)
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> {{ $amenity->name }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>

            <!-- Gallery -->
            <div class="mb-5">
                <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Hình ảnh')</h3>
                @if ($hotel->images->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-image text-muted display-3"></i>
                    <p class="mt-3 text-muted fs-5">@lang('Chưa có hình ảnh nào.')</p>
                </div>
                @else
                <div class="gallery row g-3">
                    @foreach ($hotel->images as $image)
                    <div class="col-6 col-md-3">
                        <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $hotel->name }}" class="w-100">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Rooms List -->
            <div class="mb-5">
                <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Các loại phòng')</h3>
                <div class="row g-4">
                    @foreach ($hotel->rooms as $room)
                    <div class="col-12">
                        <div class="room-card card border-0 shadow-sm">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ $room->images->first()->url ?? asset('images/placeholder.jpg') }}" class="room-img w-100" alt="{{ $room->name }}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h3 class="fs-5 fw-bold">{{ $room->name }}</h3>
                                        <p class="text-muted">{{ $room->description }}</p>
                                        <ul class="list-unstyled">
                                            <li><strong>@lang('Diện tích'):</strong> {{ $room->area }} m²</li>
                                            <li><strong>@lang('Sức chứa'):</strong> {{ $room->capacity }} @lang('người')</li>
                                            <li><strong>@lang('Loại giường'):</strong> {{ $room->bed_type }}</li>
                                        </ul>
                                        <p><strong>@lang('Tiện ích'):</strong>
                                            @foreach ($room->amenities as $amenity)
                                            <span class="badge bg-light text-dark me-1">{{ $amenity->name }}</span>
                                            @endforeach
                                        </p>
                                        <p class="fw-bold">{{ number_format($room->price_per_night) }}đ/@lang('đêm')</p>
                                        @auth
                                        <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $room->room_id }}">@lang('Đặt ngay')</button>
                                        @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 py-2">@lang('Đăng nhập để đặt')</a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Modal -->
                    <div class="modal fade" id="bookingModal{{ $room->room_id }}" tabindex="-1" aria-labelledby="bookingModalLabel{{ $room->room_id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookingModalLabel{{ $room->room_id }}">@lang('Đặt phòng'): {{ $room->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('bookings.store') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="room_id" value="{{ $room->room_id }}">
                                        <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                                        <div class="mb-3">
                                            <label for="start_date_{{ $room->room_id }}" class="form-label">@lang('Ngày nhận phòng')</label>
                                            <input type="date" class="form-control" id="start_date_{{ $room->room_id }}" name="start_date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="end_date_{{ $room->room_id }}" class="form-label">@lang('Ngày trả phòng')</label>
                                            <input type="date" class="form-control" id="end_date_{{ $room->room_id }}" name="end_date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="number_of_people_{{ $room->room_id }}" class="form-label">@lang('Số người')</label>
                                            <input type="number" class="form-control" id="number_of_people_{{ $room->room_id }}" name="number_of_people" min="1" max="{{ $room->capacity }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="special_requests_{{ $room->room_id }}" class="form-label">@lang('Yêu cầu đặc biệt')</label>
                                            <textarea class="form-control" id="special_requests_{{ $room->room_id }}" name="special_requests" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">@lang('Hủy')</button>
                                        <button type="submit" class="btn btn-primary px-4 py-2">@lang('Tiếp tục thanh toán')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Reviews -->
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h3 class="fs-5 fw-bold mb-0 section-title">@lang('Đánh giá') ({{ $hotel->reviews->count() }})</h3>
                    @auth
                    <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class="bi bi-pencil-square me-2"></i> @lang('Viết đánh giá')
                    </button>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 py-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i> @lang('Đăng nhập để đánh giá')
                    </a>
                    @endauth
                </div>

                @if ($hotel->reviews->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text text-muted display-1"></i>
                    <p class="mt-3">@lang('Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!')</p>
                </div>
                @else
                <div class="review-summary bg-light p-4 rounded mb-4 shadow-sm">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div class="display-3 fw-bold text-primary">{{ number_format($hotel->reviews->avg('rating'), 1) }}</div>
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($hotel->reviews->avg('rating')))
                                <i class="bi bi-star-fill text-warning"></i>
                                @else
                                <i class="bi bi-star text-warning"></i>
                                @endif
                                @endfor
                            </div>
                            <div class="text-muted fs-5">{{ $hotel->reviews->count() }} @lang('đánh giá')</div>
                        </div>
                        <div class="col-md-8">
                            @foreach ([5, 4, 3, 2, 1] as $rating)
                            @php
                            $count = $hotel->reviews->where('rating', $rating)->count();
                            $percentage = $hotel->reviews->count() > 0 ? ($count / $hotel->reviews->count()) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-2" style="width: 60px;">{{ $rating }} <i class="bi bi-star-fill text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="ms-2" style="width: 40px;">{{ $count }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @foreach ($hotel->reviews->take(5) as $review)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                @if ($review->user && $review->user->avatar)
                                <img src="{{ asset('storage/' . $review->user->avatar) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $review->user->name }}">
                                @else
                                <div class="bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($review->user->name ?? 'User', 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $review->user->name ?? 'Người dùng ẩn danh' }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                            <div>
                                @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                <i class="bi bi-star-fill text-warning"></i>
                                @else
                                <i class="bi bi-star text-warning"></i>
                                @endif
                                @endfor
                            </div>
                        </div>
                        <p class="mb-0 text-muted">{{ $review->content }}</p>
                        @if ($review->images)
                        <div class="review-images mt-3">
                            <div class="row g-2">
                                @foreach (json_decode($review->images, true) as $image)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded cursor-pointer" alt="Review image" style="height: 80px; object-fit: cover;" onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                @if ($hotel->reviews->count() > 5)
                <div class="text-center">
                    <a href="{{ route('hotels.reviews', $hotel->hotel_id) }}" class="btn btn-outline-primary px-4 py-2">@lang('Xem tất cả đánh giá')</a>
                </div>
                @endif
                @endif

                <!-- Review Modal -->
                <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel">@lang('Đánh giá') {{ $hotel->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @auth
                                <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                                    <div class="mb-4 text-center">
                                        <label class="form-label fw-bold">@lang('Đánh giá của bạn')</label>
                                        <div class="rating-stars fs-2">
                                            @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="d-none">
                                            <label for="star{{ $i }}" class="bi bi-star me-1 text-warning rating-label"></label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="reviewContent" class="form-label fw-bold">@lang('Nội dung đánh giá')</label>
                                        <textarea class="form-control" id="reviewContent" name="content" rows="5" required></textarea>
                                    </div>
                                    <div class="mb-4">
                                        <label for="reviewImages" class="form-label fw-bold">@lang('Hình ảnh (tối đa 5 ảnh)')</label>
                                        <input class="form-control" type="file" id="reviewImages" name="images[]" multiple accept="image/*">
                                        <div class="form-text">@lang('Chọn tối đa 5 hình ảnh, mỗi ảnh không quá 2MB')</div>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">@lang('Hủy')</button>
                                        <button type="submit" class="btn btn-primary px-4 py-2">@lang('Gửi đánh giá')</button>
                                    </div>
                                </form>
                                @else
                                <div class="alert alert-info">
                                    <a href="{{ route('login') }}" class="alert-link">@lang('Đăng nhập')</a> @lang('để viết đánh giá của bạn.')
                                </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map -->
            <div class="mb-5">
                <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Vị trí')</h3>
                <div class="map-container">
                    <div id="map" style="height: 100%;"></div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center">
                <a href="{{ route('hotels.index') }}" class="btn btn-outline-primary px-4 py-2">@lang('Quay lại danh sách')</a>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 col-12">
            <div class="related-sidebar">
                <!-- Booking Form -->
                <div class="booking-form mb-4">
                    <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Đặt phòng ngay')</h3>
                    @auth
                    <form action="{{ route('bookings.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="room_id" class="form-label">@lang('Chọn phòng')</label>
                            <select name="room_id" id="room_id" class="form-select" required>
                                <option value="">@lang('Chọn phòng')</option>
                                @foreach ($hotel->rooms as $room)
                                <option value="{{ $room->room_id }}">{{ $room->name }} ({{ number_format($room->price_per_night) }}đ/@lang('đêm'))</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">@lang('Ngày nhận phòng')</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">@lang('Ngày trả phòng')</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="number_of_people" class="form-label">@lang('Số người')</label>
                            <input type="number" class="form-control" id="number_of_people" name="number_of_people" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="special_requests" class="form-label">@lang('Yêu cầu đặc biệt')</label>
                            <textarea class="form-control" id="special_requests" name="special_requests" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 px-4 py-2">@lang('Tiếp tục thanh toán')</button>
                    </form>
                    @else
                    <p class="text-muted">@lang('Vui lòng đăng nhập để đặt phòng.')</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary w-100 px-4 py-2">@lang('Đăng nhập')</a>
                    @endauth
                </div>

                <!-- Provider Info -->
                <div class="provider-info mb-4">
                    <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Thông tin nhà cung cấp')</h3>
                    <div class="text-center mb-3">
                        @if ($hotel->provider->logo)
                        <img src="{{ asset('storage/' . $hotel->provider->logo) }}" alt="{{ $hotel->provider->name }}" class="rounded-circle mb-2" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 80px; height: 80px;">
                            <i class="bi bi-building fs-1 text-muted"></i>
                        </div>
                        @endif
                        <h5>{{ $hotel->provider->name }}</h5>
                    </div>
                    @if ($hotel->provider->description)
                    <p class="text-muted">{{ Str::limit($hotel->provider->description, 150) }}</p>
                    @endif
                    <div class="d-flex mb-2">
                        <i class="bi bi-geo-alt text-primary me-3"></i>
                        <div>{{ $hotel->provider->address }}</div>
                    </div>
                    @if ($hotel->provider->phone)
                    <div class="d-flex mb-2">
                        <i class="bi bi-telephone text-primary me-3"></i>
                        <div>{{ $hotel->provider->phone }}</div>
                    </div>
                    @endif
                    @if ($hotel->provider->email)
                    <div class="d-flex mb-2">
                        <i class="bi bi-envelope text-primary me-3"></i>
                        <div>{{ $hotel->provider->email }}</div>
                    </div>
                    @endif
                    @if ($hotel->provider->website)
                    <div class="d-flex mb-2">
                        <i class="bi bi-globe text-primary me-3"></i>
                        <div><a href="{{ $hotel->provider->website }}" target="_blank">{{ $hotel->provider->website }}</a></div>
                    </div>
                    @endif
                </div>

                <!-- Nearby Hotels -->
                <h3 class="fs-5 fw-bold mb-4 section-title">@lang('Khách sạn liên quan')</h3>
                @if ($nearbyHotels->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-search text-muted fs-2"></i>
                    <p class="mt-3 text-muted fs-6">@lang('Không có khách sạn liên quan.')</p>
                </div>
                @else
                @foreach ($nearbyHotels as $nearbyHotel)
                <div class="related-card d-flex align-items-center">
                    <a href="{{ route('hotels.show', $nearbyHotel->hotel_id) }}" class="text-decoration-none d-flex align-items-center w-100">
                        @if ($nearbyHotel->images->first())
                        <img src="{{ asset('storage/' . $nearbyHotel->images->first()->url) }}" alt="{{ $nearbyHotel->name }}" class="me-3">
                        @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="me-3">
                        @endif
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $nearbyHotel->name }}</h6>
                            <p class="text-muted small mb-0">{{ Str::limit($nearbyHotel->description, 60) }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://maps.goong.io/goong-js/1.0.9/goong-js.js"></script>
<script>
    // Initialize Goong Map
    goongjs.accessToken = '{{ env("GOONG_API_KEY") }}';
    const map = new goongjs.Map({
        container: 'map',
        style: 'https://tiles.goong.io/assets/goong_map_web.json?api_key={{ env("GOONG_MAPTILES_API_KEY") }}',
        center: [{{ $hotel->longitude }}, {{ $hotel->latitude }}],
        zoom: 15
    });
    new goongjs.Marker()
        .setLngLat([{{ $hotel->longitude }}, {{ $hotel->latitude }}])
        .setPopup(new goongjs.Popup().setHTML('<h5>{{ $hotel->name }}</h5>'))
        .addTo(map);

    // Room capacity validation
    document.getElementById('room_id').addEventListener('change', function() {
        const capacities = @json($hotel->rooms->pluck('capacity', 'room_id'));
        const numberInput = document.getElementById('number_of_people');
        const selectedRoom = this.value;
        if (selectedRoom && capacities[selectedRoom]) {
            numberInput.max = capacities[selectedRoom];
        }
    });

    // Rating stars hover
    document.querySelectorAll('.rating-label').forEach(label => {
        label.addEventListener('mouseover', function() {
            const starValue = parseInt(this.getAttribute('for').replace('star', ''));
            document.querySelectorAll('.rating-label').forEach(l => {
                const lValue = parseInt(l.getAttribute('for').replace('star', ''));
                if (lValue <= starValue) {
                    l.classList.remove('bi-star');
                    l.classList.add('bi-star-fill');
                } else {
                    l.classList.remove('bi-star-fill');
                    l.classList.add('bi-star');
                }
            });
        });
        label.addEventListener('mouseout', function() {
            const selected = document.querySelector('input[name="rating"]:checked');
            const selectedValue = selected ? parseInt(selected.value) : 0;
            document.querySelectorAll('.rating-label').forEach(l => {
                const lValue = parseInt(l.getAttribute('for').replace('star', ''));
                if (lValue <= selectedValue) {
                    l.classList.remove('bi-star');
                    l.classList.add('bi-star-fill');
                } else {
                    l.classList.remove('bi-star-fill');
                    l.classList.add('bi-star');
                }
            });
        });
        label.addEventListener('click', function() {
            const starValue = parseInt(this.getAttribute('for').replace('star', ''));
            document.getElementById(`star${starValue}`).checked = true;
        });
    });

    // Image modal
    function openImageModal(src) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <img src="${src}" class="img-fluid" alt="Review image">
                </div>
            </div>`;
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        modal.addEventListener('hidden.bs.modal', () => modal.remove());
    }
</script>
@endsection