<!-- resources/views/frontend/nhà/event/show.blade.php -->
@extends('layouts.app')

@section('title', $event->title)

@section('styles')
    <style>
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
        .event-hero img {
            object-fit: cover;
            height: 450px;
            border-radius: 16px;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        .event-hero img:hover {
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
        .info-card, .review-summary {
            border-radius: 12px;
            padding: 25px;
            background: linear-gradient(145deg, #ffffff, #f6f9fc);
        }
        .dark-mode .info-card, .dark-mode .review-summary {
            background: linear-gradient(145deg, #1e2a3a, #2c3e50);
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
    </style>
@endsection

@section('content')
    <div class="container py-5">
        <div class="row g-4">
            <!-- Cột chính: Nội dung chính -->
            <div class="col-lg-8 col-12">
                <!-- Hero Section -->
                <div class="event-hero mb-5">
                    @if ($event->images->where('is_featured', true)->first())
                        <img src="{{ asset('storage/' . $event->images->where('is_featured', true)->first()->url) }}" alt="{{ $event->title }}" class="w-100">
                    @elseif ($event->images->first())
                        <img src="{{ asset('storage/' . $event->images->first()->url) }}" alt="{{ $event->title }}" class="w-100">
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="w-100">
                    @endif
                    <h1 class="fs-2 fw-bold mt-4 mb-2">{{ $event->title }}</h1>
                    <p class="text-muted fs-5"><i class="bi bi-calendar-fill me-2"></i> {{ $event->start_date->format('d/m/Y H:i') }} - {{ $event->end_date->format('d/m/Y H:i') }}</p>
                </div>

                <!-- Thông tin chi tiết -->
                <div class="mb-5">
                    <div class="info-card card border-0 shadow-sm">
                        <h3 class="fs-5 fw-bold mb-4 section-title">Thông tin chi tiết</h3>
                        <p class="text-muted">{{ $event->description ?? 'Không có mô tả.' }}</p>
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2"><strong>Thời gian:</strong> {{ $event->start_date->format('d/m/Y H:i') }} - {{ $event->end_date->format('d/m/Y H:i') }}</li>
                            <li class="mb-2"><strong>Địa điểm:</strong> {{ $event->attraction->name ?? $event->location }}</li>
                            <li><strong>Trạng thái:</strong> {{ $event->status }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Hình ảnh -->
                <div class="mb-5">
                    <h3 class="fs-5 fw-bold mb-4 section-title">Hình ảnh</h3>
                    @if ($event->images->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-image text-muted display-3"></i>
                            <p class="mt-3 text-muted fs-5">Chưa có hình ảnh nào.</p>
                        </div>
                    @else
                        <div class="gallery row g-3">
                            @foreach ($event->images as $image)
                                <div class="col-6 col-md-3">
                                    <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $event->title }}" class="w-100">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Đánh giá -->
                <div class="place-reviews mb-5">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <h3 class="fs-5 fw-bold mb-0 section-title">Đánh giá ({{ $event->reviews->count() }})</h3>
                        @auth
                            <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                <i class="bi bi-pencil-square me-2"></i> Viết đánh giá
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Đăng nhập để đánh giá
                            </a>
                        @endauth
                    </div>

                    <!-- Debug số lượng đánh giá -->
                    @php
                        \Illuminate\Support\Facades\Log::info('View - Reviews count: ' . $event->reviews->count());
                    @endphp

                    @if($event->reviews->isEmpty())
                        <div class="alert alert-info">
                            Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá!
                        </div>
                    @else
                        <!-- Tổng quan đánh giá -->
                        <div class="review-summary bg-light p-4 rounded mb-4 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center">
                                    <div class="display-3 fw-bold text-primary">{{ number_format($event->reviews->avg('rating'), 1) }}</div>
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($event->reviews->avg('rating')))
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div class="text-muted fs-5">{{ $event->reviews->count() }} đánh giá</div>
                                </div>
                                <div class="col-md-8">
                                    @foreach([5, 4, 3, 2, 1] as $rating)
                                        @php
                                            $count = $event->reviews->where('rating', $rating)->count();
                                            $percentage = $event->reviews->count() > 0 ? ($count / $event->reviews->count()) * 100 : 0;
                                        @endphp
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="me-2" style="width: 60px;">{{ $rating }} <i class="bi bi-star-fill text-warning"></i></div>
                                            <div class="progress flex-grow-1" style="height: 10px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percentage }}%"
                                                    aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="ms-2" style="width: 40px;">{{ $count }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Danh sách đánh giá -->
                        <div class="review-list">
                            @foreach($event->reviews->take(5) as $review)
                                <div class="card mb-3 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                @if($review->user && $review->user->avatar)
                                                    <img src="{{ asset('storage/' . $review->user->avatar) }}"
                                                        class="rounded-circle me-2" width="40" height="40"
                                                        alt="{{ $review->user->full_name }}">
                                                @else
                                                    <div class="bg-secondary text-white rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($review->user->full_name ?? 'User', 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0 fw-bold">{{ $review->user->full_name ?? 'Người dùng ẩn danh' }}</h6>
                                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                                </div>
                                            </div>
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mb-0 text-muted">{{ $review->comment }}</p>
                                        @if($review->images)
                                            <div class="review-images mt-3">
                                                <div class="row g-2">
                                                    @foreach($review->images as $image)
                                                        <div class="col-3">
                                                            <img src="{{ asset('storage/' . $image->url) }}"
                                                                class="img-fluid rounded cursor-pointer"
                                                                alt="Review image"
                                                                style="height: 80px; object-fit: cover;"
                                                                onclick="openImageModal('{{ asset('storage/' . $image->url) }}')">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($event->reviews->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('events.reviews', $event->event_id) }}" class="btn btn-outline-primary px-4 py-2">
                                        Xem tất cả đánh giá
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Modal đánh giá -->
                    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="reviewModalLabel">Đánh giá {{ $event->title }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @auth
                                        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="entity_id" value="{{ $event->event_id }}">
                                            <input type="hidden" name="entity_type" value="event">

                                            <div class="mb-4 text-center">
                                                <label class="form-label fw-bold">Đánh giá của bạn</label>
                                                <div class="rating-stars fs-2">
                                                    @for($i = 5; $i >= 1; $i--)
                                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="d-none">
                                                        <label for="star{{ $i }}" class="bi bi-star me-1 text-warning rating-label"></label>
                                                    @endfor
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <label for="reviewContent" class="form-label fw-bold">Nội dung đánh giá</label>
                                                <textarea class="form-control" id="reviewContent" name="comment" rows="5" required></textarea>
                                            </div>

                                            <div class="mb-4">
                                                <label for="reviewImages" class="form-label fw-bold">Hình ảnh (tối đa 5 ảnh)</label>
                                                <input class="form-control" type="file" id="reviewImages" name="images[]" multiple accept="image/*">
                                                <div class="form-text">Chọn tối đa 5 hình ảnh, mỗi ảnh không quá 2MB</div>
                                            </div>

                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-primary px-4 py-2">Gửi đánh giá</button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="alert alert-info">
                                            <a href="{{ route('login') }}" class="alert-link">Đăng nhập</a> để viết đánh giá của bạn.
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nút quay lại -->
                <div class="text-center">
                    <a href="{{ route('events.index') }}" class="btn btn-outline-primary px-4 py-2">Quay lại danh sách</a>
                </div>
            </div>

            <!-- Cột phụ: Sự kiện liên quan -->
            <div class="col-lg-4 col-12">
                <div class="related-sidebar">
                    <h3 class="fs-5 fw-bold mb-4 section-title">Sự kiện liên quan</h3>
                    @if ($relatedEvents->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-search text-muted fs-2"></i>
                            <p class="mt-3 text-muted fs-6">Không có sự kiện liên quan.</p>
                        </div>
                    @else
                        @foreach ($relatedEvents as $related)
                            <div class="related-card d-flex align-items-center">
                                <a href="{{ route('events.show', $related->event_id) }}" class="text-decoration-none d-flex align-items-center w-100">
                                    @if ($related->images->first())
                                        <img src="{{ asset('storage/' . $related->images->first()->url) }}" alt="{{ $related->title }}" class="me-3">
                                    @else
                                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="me-3">
                                    @endif
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $related->title }}</h6>
                                        <p class="text-muted small mb-0">{{ Str::limit($related->description, 60) }}</p>
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
    <script>
        // Xử lý hover sao đánh giá
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

        // Mở modal hình ảnh
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