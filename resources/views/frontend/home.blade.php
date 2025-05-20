<!-- resources/views/frontend/nhà/home.blade.php -->
@extends('layouts.app')

@section('title', 'Khám phá Ba Đông')

@section('styles')
    <style>
        /* General */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f6f9fc;
        }
        .hero-section {
            position: relative;
            height: 600px;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        .hero-section p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
        }
        .section-title {
            position: relative;
            padding-left: 15px;
            border-left: 5px solid #0078D4;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 700;
        }
        .section-title::before {
            content: '';
            position: absolute;
            top: 0;
            left: -5px;
            height: 100%;
            width: 5px;
            background: linear-gradient(to bottom, #0078D4, #00C4B4);
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

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-img-top {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .card-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Carousel */
        .carousel-inner img {
            object-fit: cover;
            height: 600px;
            width: 100%;
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 8px;
        }

        /* Sections */
        .section-padding {
            padding: 60px 0;
        }
        .personalized-section {
            background: #ffffff;
        }
        .event-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .event-item {
            flex: 1 1 100%;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .event-item:hover {
            transform: translateY(-5px);
        }

        /* Footer */
        .footer {
            background: linear-gradient(45deg, #0078D4, #00C4B4);
            color: white;
            padding: 40px 0;
        }
        .footer a {
            color: #ffffff;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                height: 400px;
            }
            .hero-section h1 {
                font-size: 2rem;
            }
            .hero-section p {
                font-size: 1rem;
            }
            .carousel-inner img {
                height: 400px;
            }
            .section-padding {
                padding: 40px 0;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .card {
                margin-bottom: 20px;
            }
            .card-title {
                font-size: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1>Khám phá Ba Đông - Thiên đường biển đảo</h1>
            <p>Trải nghiệm những chuyến đi tuyệt vời với các địa điểm, tour du lịch và sự kiện độc đáo!</p>
            <a href="{{ route('tours.index') }}" class="btn btn-primary">Đặt tour ngay</a>
        </div>
    </section>

    <!-- Personalized Suggestions (nếu đăng nhập) -->
    @if (Auth::check() && !empty($personalizedItems))
        <section class="personalized-section section-padding">
            <div class="container">
                <h2 class="section-title">Gợi ý dành riêng cho bạn</h2>
                <div class="row g-4">
                    @foreach ($personalizedItems as $item)
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                @if (isset($item['images']) && is_array($item['images']) && !empty($item['images']))
                                    <img src="{{ asset('storage/' . $item['images'][0]['url']) }}" alt="{{ $item['name'] }}" class="card-img-top">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item['name'] }}</h5>
                                    <p class="card-text">{{ Str::limit($item['description'], 100) }}</p>
                                    <a href="{{ route('attractions.show', $item['attraction_id']) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Địa điểm nổi bật -->
    <section class="section-padding">
        <div class="container">
            <h2 class="section-title">Địa điểm nổi bật</h2>
            <div class="row g-4">
                @foreach ($popularAttractions as $attraction)
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            @if ($attraction->images->first())
                                <img src="{{ asset('storage/' . $attraction->images->first()->url) }}" alt="{{ $attraction->name }}" class="card-img-top">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $attraction->name }}</h5>
                                <p class="card-text">{{ Str::limit($attraction->description, 100) }}</p>
                                <a href="{{ route('attractions.show', $attraction->attraction_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tour nổi bật -->
    <section class="section-padding bg-light">
        <div class="container">
            <h2 class="section-title">Tour nổi bật</h2>
            <div class="row g-4">
                @foreach ($popularTours as $tour)
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            @if ($tour->images->first())
                                <img src="{{ asset('storage/' . $tour->images->first()->url) }}" alt="{{ $tour->name }}" class="card-img-top">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $tour->name }}</h5>
                                <p class="card-text">{{ Str::limit($tour->description, 100) }}</p>
                                <p class="card-text small text-muted"><i class="bi bi-clock-fill me-2"></i> {{ $tour->duration_days }} ngày</p>
                                <a href="{{ route('tours.show', $tour->tour_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Nhà hàng được yêu thích -->
    <section class="section-padding">
        <div class="container">
            <h2 class="section-title">Nhà hàng được yêu thích</h2>
            <div class="row g-4">
                @foreach ($popularRestaurants as $restaurant)
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            @if ($restaurant->images->first())
                                <img src="{{ asset('storage/' . $restaurant->images->first()->url) }}" alt="{{ $restaurant->name }}" class="card-img-top">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $restaurant->name }}</h5>
                                <p class="card-text">{{ Str::limit($restaurant->description, 100) }}</p>
                                <a href="{{ route('restaurants.show', $restaurant->restaurant_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Phương tiện di chuyển -->
    <section class="section-padding bg-light">
        <div class="container">
            <h2 class="section-title">Phương tiện di chuyển</h2>
            <div class="row g-4">
                @foreach ($availableTransports as $transport)
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            @if ($transport->images->first())
                                <img src="{{ asset('storage/' . $transport->images->first()->url) }}" alt="{{ $transport->name }}" class="card-img-top">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $transport->name }}</h5>
                                <p class="card-text small text-muted"><i class="bi bi-car-front-fill me-2"></i> {{ $transport->type }}</p>
                                <p class="card-text small text-muted"><i class="bi bi-currency-dollar me-2"></i> {{ number_format($transport->price_per_day, 2) }} VNĐ/ngày</p>
                                <a href="{{ route('transports.show', $transport->transport_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Sự kiện sắp tới -->
    <section class="section-padding">
        <div class="container">
            <h2 class="section-title">Sự kiện sắp tới</h2>
            <div class="event-list">
                @foreach ($upcomingEvents as $event)
                    <div class="event-item">
                        <h5 class="fw-bold">{{ $event->title }}</h5>
                        <p class="text-muted">{{ Str::limit($event->description, 150) }}</p>
                        <p class="small text-muted"><i class="bi bi-calendar-fill me-2"></i> {{ $event->start_date->format('d/m/Y H:i') }}</p>
                        <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-primary">Xem chi tiết</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Bài viết cộng đồng -->
    <section class="section-padding bg-light">
        <div class="container">
            <h2 class="section-title">Bài viết từ cộng đồng</h2>
            <div class="row g-4">
                @foreach ($communityPosts as $post)
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                                <p class="small text-muted"><i class="bi bi-person-fill me-2"></i> {{ $post->author->full_name ?? 'Ẩn danh' }}</p>
                                <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-primary w-100">Đọc thêm</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

   
@endsection