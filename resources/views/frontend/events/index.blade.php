<!-- resources/views/frontend/nhà/event/index.blade.php -->
@extends('layouts.app')

@section('title', 'Danh sách sự kiện')

@section('styles')
    <style>
        .event-card {
            transition: transform var(--transition-speed) ease;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .event-card:hover {
            transform: translateY(-5px);
        }
        .event-card img {
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
        .event-card .card-body {
            padding: 20px;
        }
        .event-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .event-card .card-text {
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
                    <form method="GET" action="{{ route('events.index') }}">
                        <!-- Lọc theo trạng thái -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Trạng thái</h5>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            </select>
                        </div>

                        <!-- Lọc theo địa điểm -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">Địa điểm</h5>
                            <select name="attraction_id" class="form-select">
                                <option value="">Tất cả</option>
                                @foreach ($attractions as $attraction)
                                    <option value="{{ $attraction->attraction_id }}" {{ request('attraction_id') == $attraction->attraction_id ? 'selected' : '' }}>
                                        {{ $attraction->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Áp dụng bộ lọc</button>
                    </form>
                </div>
            </div>

            <!-- Cột chính: Danh sách sự kiện -->
            <div class="col-lg-9 col-md-8">
                <h2 class="fs-3 fw-bold mb-4 section-title">Danh sách sự kiện</h2>
                @if ($events->isEmpty())
                    <div class="alert alert-info">
                        Không tìm thấy sự kiện nào phù hợp với bộ lọc.
                    </div>
                @else
                    <div class="row g-4">
                        @foreach ($events as $event)
                            <div class="col-lg-4 col-md-6">
                                <div class="event-card card border-0">
                                    @if ($event->images->first())
                                        <img src="{{ asset('storage/' . $event->images->first()->url) }}" alt="{{ $event->title }}" class="card-img-top">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $event->title }}</h5>
                                        <p class="card-text">{{ Str::limit($event->description, 100) }}</p>
                                        <p class="card-text small text-muted"><i class="bi bi-calendar-fill me-2"></i> {{ $event->start_date->format('d/m/Y H:i') }}</p>
                                        <p class="card-text small text-muted"><i class="bi bi-geo-alt-fill me-2"></i> {{ $event->attraction->name ?? 'Không có địa điểm' }}</p>
                                        <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-primary w-100">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Phân trang -->
                    <div class="mt-5">
                        {{ $events->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection