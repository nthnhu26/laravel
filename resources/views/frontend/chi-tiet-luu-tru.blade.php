@extends('layouts.app')

@section('title', $hotel->name)

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .hotel-img {
        height: 400px;
        object-fit: cover;
        border-radius: 8px;
    }

    .room-img {
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }

    .booking-form,
    .provider-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .booking-form {
        position: sticky;
        top: 100px;
    }

    .room-card {
        transition: transform 0.3s;
    }

    .room-card:hover {
        transform: translateY(-5px);
    }

    .carousel-control-prev,
    .carousel-control-next {
        background: rgba(0, 0, 0, 0.5);
        width: 5%;
    }

    .amenity-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .review-card {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 15px;
        margin-bottom: 15px;
    }

    .map-container {
        height: 300px;
        border-radius: 8px;
        overflow: hidden;
    }

    .nearby-hotel-card {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row g-4">
        <!-- Hotel Details -->
        <div class="col-lg-8">
            <!-- Image Carousel -->
            <div id="hotelCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($hotel->images as $index => $image)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ $image->url }}" class="d-block w-100 hotel-img" alt="{{ $hotel->name }}">
                    </div>
                    @endforeach
                    @if ($hotel->images->isEmpty())
                    <div class="carousel-item active">
                        <img src="https://via.placeholder.com/800x400" class="d-block w-100 hotel-img" alt="{{ $hotel->name }}">
                    </div>
                    @endif
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#hotelCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#hotelCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Hotel Info -->
            <h1 class="mb-3">{{ $hotel->name }}</h1>
            <p><i class="fas fa-map-marker-alt"></i> {{ $hotel->address }}</p>
            <p class="text-muted">{{ $hotel->description }}</p>

            <!-- Amenities -->
            <h3 class="mt-4 mb-3">@lang('Tiện ích')</h3>
            <div class="row g-3 mb-4">
                @foreach ($hotel->amenities as $amenity)
                <div class="col-md-6 amenity-item">
                    <i class="fas fa-check-circle text-primary"></i>
                    <span>{{ $amenity->name }}</span>
                </div>
                @endforeach
            </div>

            <!-- Hotel Policies -->
            <h3 class="mt-4 mb-3">@lang('Chính sách')</h3>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <p><strong>@lang('Giờ check-in'):</strong> {{ $hotel->check_in_time }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>@lang('Giờ check-out'):</strong> {{ $hotel->check_out_time }}</p>
                </div>
                <div class="col-md-12">
                    <p><strong>@lang('Chính sách hủy'):</strong> {{ $hotel->cancellation_policy }}</p>
                </div>
            </div>

            <!-- Rooms List -->
            <h3 class="mt-4 mb-3">@lang('Các loại phòng')</h3>
            <div class="row g-4">
                @foreach ($hotel->rooms as $room)
                <div class="col-12">
                    <div class="card room-card border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ $room->images->first()->url ?? 'https://via.placeholder.com/150' }}" class="room-img w-100" alt="{{ $room->name }}">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h3 class="h5 fw-bold">{{ $room->name }}</h3>
                                    <p class="text-muted">{{ $room->description }}</p>
                                    <p><strong>@lang('Diện tích'):</strong> {{ $room->area }} m²</p>
                                    <p><strong>@lang('Sức chứa'):</strong> {{ $room->capacity }} @lang('người')</p>
                                    <p><strong>@lang('Loại giường'):</strong> {{ $room->bed_type }}</p>
                                    <p><strong>@lang('Tiện ích'):</strong>
                                        @foreach ($room->amenities as $amenity)
                                        <span class="badge bg-light text-dark me-1">{{ $amenity->name }}</span>
                                        @endforeach
                                    </p>
                                    <span class="price-range">{{ number_format($room->price_per_night) }}đ/@lang('đêm')</span>
                                    @auth
                                    <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#bookingModal{{ $room->room_id }}">@lang('Đặt ngay')</button>
                                    @else
                                    <a href="{{ route('login') }}" class="btn btn-warning mt-2">@lang('Đăng nhập để đặt')</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Modal for Each Room -->
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
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Đóng')</button>
                                    <button type="submit" class="btn btn-primary">@lang('Tiếp tục thanh toán')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Reviews -->
            <!-- Reviews -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Đánh giá ({{ $hotel->reviews->count() }})</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal">
                        <i class="bi bi-pencil-square me-1"></i> Viết đánh giá
                    </button>
                </div>

                @if($hotel->reviews->isEmpty())
                <div class="alert alert-info">
                    Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!
                </div>
                @else
                <div class="review-summary bg-light p-4 rounded mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 text-center">
                            <div class="display-4 fw-bold text-primary">
                                {{ number_format($hotel->reviews->avg('rating'), 1) }}
                            </div>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=round($hotel->reviews->avg('rating')))
                                    <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                    <i class="bi bi-star text-warning"></i>
                                    @endif
                                    @endfor
                            </div>
                            <div class="text-muted">{{ $hotel->reviews->count() }} đánh giá</div>
                        </div>
                        <div class="col-md-9">
                            @foreach([5, 4, 3, 2, 1] as $rating)
                            @php
                            $count = $hotel->reviews->where('rating', $rating)->count();
                            $percentage = $hotel->reviews->count() > 0 ? ($count / $hotel->reviews->count()) * 100 : 0;
                            @endphp
                            <div class="d-flex align-items-center mb-1">
                                <div class="me-2" style="width: 60px;">{{ $rating }} <i
                                        class="bi bi-star-fill text-warning"></i></div>
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar"
                                        style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="ms-2" style="width: 40px;">{{ $count }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                @foreach($hotel->reviews->take(5) as $review)
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                @if($review->user && $review->user->avatar)
                                <img src="{{ asset('storage/' . $review->user->avatar) }}"
                                    class="rounded-circle me-2" width="40" height="40"
                                    alt="{{ $review->user->name }}">
                                @else
                                <div class="bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px;">
                                    {{ strtoupper(substr($review->user->name ?? 'User', 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $review->user->name ?? 'Người dùng ẩn danh' }}</h6>
                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$review->rating)
                                    <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                    <i class="bi bi-star text-warning"></i>
                                    @endif
                                    @endfor
                            </div>
                        </div>
                        <p class="mb-0">{{ $review->content }}</p>
                        @if($review->images)
                        <div class="mt-3">
                            <div class="row g-2">
                                @foreach(json_decode($review->images, true) as $image)
                                <div class="col-3">
                                    <img src="{{ asset('storage/' . $image) }}"
                                        class="img-fluid rounded cursor-pointer"
                                        alt="Review image"
                                        style="height: 80px; object-fit: cover;"
                                        onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                @if($hotel->reviews->count() > 5)
                <div class="text-center">
                    <a href="{{ route('hotels.reviews', $hotel->hotel_id) }}"
                        class="btn btn-outline-primary">Xem tất cả đánh giá</a>
                </div>
                @endif
                @endif

                <!-- Review Modal -->
                <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewModalLabel">Đánh giá {{ $hotel->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @auth
                                <form action="{{ route('reviews.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="hotel_id" value="{{ $hotel->hotel_id }}">

                                    <div class="mb-3 text-center">
                                        <label class="form-label">Đánh giá của bạn</label>
                                        <div class="rating-stars fs-3">
                                            @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star{{ $i }}" name="rating"
                                                value="{{ $i }}" class="d-none">
                                            <label for="star{{ $i }}"
                                                class="bi bi-star me-1 text-warning rating-label"></label>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="reviewContent" class="form-label">Nội dung đánh giá</label>
                                        <textarea class="form-control" id="reviewContent" name="content" rows="4"
                                            required></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="reviewImages" class="form-label">Hình ảnh (tối đa 5 ảnh)</label>
                                        <input class="form-control" type="file" id="reviewImages" name="images[]"
                                            multiple accept="image/*">
                                        <div class="form-text">Chọn tối đa 5 hình ảnh, mỗi ảnh không quá 2MB</div>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                    </div>
                                </form>
                                @else
                                <div class="alert alert-info">
                                    <a href="{{ route('login') }}" class="alert-link">Đăng nhập</a> để viết đánh giá
                                    của bạn.
                                </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nearby Hotels -->
            <h3 class="mt-4 mb-3">@lang('Khách sạn gần đây')</h3>
            @if ($nearbyHotels->isNotEmpty())
            @foreach ($nearbyHotels as $nearbyHotel)
            <div class="nearby-hotel-card">
                <div class="row g-3">
                    <div class="col-md-4">
                        <img src="{{ $nearbyHotel->images->first()->url ?? 'https://via.placeholder.com/150' }}" class="room-img w-100" alt="{{ $nearbyHotel->name }}">
                    </div>
                    <div class="col-md-8">
                        <h5 class="fw-bold">{{ $nearbyHotel->name }}</h5>
                        <p class="text-muted">{{ Str::limit($nearbyHotel->description, 100) }}</p>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $nearbyHotel->address }}</p>
                        <a href="{{ route('hotels.show', $nearbyHotel->hotel_id) }}" class="btn btn-outline-primary btn-sm">@lang('Xem chi tiết')</a>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <p class="text-muted">@lang('Không có khách sạn gần đây.')</p>
            @endif

            <!-- Map -->
            <h3 class="mt-4 mb-3">@lang('Vị trí')</h3>
            <div class="map-container">
                <div id="map" style="height: 100%;"></div>
            </div>
        </div>

        <!-- Booking Form and Provider Info -->
        <div class="col-lg-4">
            <!-- Booking Form -->
            <div class="booking-form">
                <h3 class="mb-3">@lang('Đặt phòng ngay')</h3>
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
                    <button type="submit" class="btn btn-primary w-100">@lang('Tiếp tục thanh toán')</button>
                </form>
                @else
                <p class="text-muted">@lang('Vui lòng đăng nhập để đặt phòng.')</p>
                <a href="{{ route('login') }}" class="btn btn-warning w-100">@lang('Đăng nhập')</a>
                @endauth
            </div>

            <!-- Provider Info -->
            <div class="provider-info">
                <h3 class="mb-3">@lang('Thông tin nhà cung cấp')</h3>
                <p><strong>@lang('Tên'):</strong> {{ $hotel->provider->name ?? 'N/A' }}</p>
                <p><strong>@lang('Điện thoại'):</strong> {{ $hotel->provider->phone ?? 'N/A' }}</p>
                <p><strong>@lang('Email'):</strong> {{ $hotel->provider->email ?? 'N/A' }}</p>
                <p><strong>@lang('Website'):</strong> <a href="{{ $hotel->provider->website ?? '#' }}" target="_blank">{{ $hotel->provider->website ?? 'N/A' }}</a></p>
            </div>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2 mb-3">Nhà cung cấp</h5>
                    <div class="text-center mb-3">
                        @if($hotel->provider->logo)
                        <img src="{{ asset('storage/' . $hotel->provider->logo) }}"
                            alt="{{ $hotel->provider->name }}" class="rounded-circle mb-2"
                            style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-building fs-1 text-muted"></i>
                        </div>
                        @endif
                        <h5>{{ $hotel->provider->name }}</h5>
                    </div>

                    <div class="provider-info">
                        @if($hotel->provider->description)
                        <p>{{ Str::limit($hotel->provider->description, 150) }}</p>
                        @endif
                        <div class="d-flex mb-2">
                            <i class="bi bi-geo-alt text-primary me-3"></i>
                            <div>{{ $hotel->provider->address }}</div>
                        </div>
                        @if($hotel->provider->phone)
                        <div class="d-flex mb-2">
                            <i class="bi bi-telephone text-primary me-3"></i>
                            <div>{{ $hotel->provider->phone }}</div>
                        </div>
                        @endif
                        @if($hotel->provider->email)
                        <div class="d-flex mb-2">
                            <i class="bi bi-envelope text-primary me-3"></i>
                            <div>{{ $hotel->provider->email }}</div>
                        </div>
                        @endif
                        @if($hotel->provider->website)
                        <div class="d-flex mb-2">
                            <i class="bi bi-globe text-primary me-3"></i>
                            <div><a href="{{ $hotel->provider->website }}"
                                    target="_blank">{{ $hotel->provider->website }}</a></div>
                        </div>
                        @endif
                    </div>
                </div>
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
        center: [{
            {
                $hotel - > longitude
            }
        }, {
            {
                $hotel - > latitude
            }
        }],
        zoom: 15
    });
    new goongjs.Marker()
        .setLngLat([{
            {
                $hotel - > longitude
            }
        }, {
            {
                $hotel - > latitude
            }
        }])
        .setPopup(new goongjs.Popup().setHTML('<h5>{{ $hotel->name }}</h5>'))
        .addTo(map);

    // Ensure number_of_people respects room capacity
    document.getElementById('room_id').addEventListener('change', function() {
        const capacities = @json($hotel->rooms->pluck('capacity', 'room_id'));
        const numberInput = document.getElementById('number_of_people');
        const selectedRoom = this.value;
        if (selectedRoom && capacities[selectedRoom]) {
            numberInput.max = capacities[selectedRoom];
        }
    });
</script>
@endsection