<!-- resources/views/frontend/nhà/attractions/index.blade.php -->
@extends('layouts.app')

@section('title', 'Danh sách địa điểm tham quan')

@section('styles')
<style>
    .section-padding {
        padding: 60px 0;
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

    .filter-sidebar {
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        height: fit-content;
    }

    .attraction-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #ffffff;
        overflow: hidden;
    }

    .attraction-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .attraction-card img {
        object-fit: cover;
        height: 200px;
        width: 100%;
        border-radius: 12px 12px 0 0;
    }

    .attraction-card .card-body {
        padding: 20px;
    }

    .attraction-card .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .attraction-card .card-text {
        font-size: 0.9rem;
        color: #6c757d;
        line-height: 1.5;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0078D4;
        box-shadow: 0 0 8px rgba(0, 120, 212, 0.2);
    }

    .btn-primary {
        background: linear-gradient(45deg, #0078D4, #00C4B4);
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: background 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #005a9e, #00897b);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 120, 212, 0.3);
    }

    .badge-primary {
        background: linear-gradient(45deg, #0078D4, #00C4B4);
        border-radius: 4px;
        font-size: 0.9rem;
        padding: 6px 10px;
    }

    .pagination .page-link {
        border-radius: 8px;
        margin: 0 5px;
        padding: 12px 18px;
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(45deg, #0078D4, #00C4B4);
        border-color: #0078D4;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 1.1rem;
    }

    .card-favorite {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #ffffff;
        color: #6c757d;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        z-index: 10;
    }

    .card-favorite:hover,
    .card-favorite.active {
        color: #dc3545;
        background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
        .section-padding {
            padding: 40px 0;
        }

        .section-title {
            font-size: 1.5rem;
        }

        .attraction-card img {
            height: 200px;
        }

        .filter-sidebar {
            margin-bottom: 20px;
        }

        .form-control,
        .form-select,
        .btn-primary {
            padding: 10px;
        }

        .attraction-card .card-body {
            padding: 15px;
        }

        .attraction-card .card-title {
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <!-- Bộ lọc bên trái -->
            <div class="col-lg-3 col-md-4">
                <div class="filter-sidebar">
                    <h3 class="section-title">Bộ lọc</h3>
                    <form method="GET" action="{{ route('attractions.index') }}">
                        <!-- Lọc theo loại -->
                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold">Loại địa điểm</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="historical" {{ request('type') == 'historical' ? 'selected' : '' }}>Lịch sử</option>
                                <option value="natural" {{ request('type') == 'natural' ? 'selected' : '' }}>Thiên nhiên</option>
                                <option value="cultural" {{ request('type') == 'cultural' ? 'selected' : '' }}>Văn hóa</option>
                            </select>
                        </div>
                        <!-- Lọc theo trạng thái -->
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Trạng thái</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Đang mở</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Đóng cửa</option>
                            </select>
                        </div>
                        <!-- Lọc theo tiện ích -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Tiện ích</label>
                            @if($amenities->isEmpty())
                            <p class="text-muted">Không có tiện ích nào.</p>
                            @else
                            @foreach ($amenities as $amenity)
                            <div class="form-check mb-2">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity->amenity_id }}"
                                    {{ in_array($amenity->amenity_id, request('amenities', [])) ? 'checked' : '' }}
                                    class="form-check-input">
                                <label class="form-check-label">{{ $amenity->name }}</label>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                    </form>
                </div>
            </div>

            <!-- Danh sách địa điểm -->
            <div class="col-lg-9 col-md-8">
                <h2 class="section-title">Danh sách địa điểm tham quan</h2>
                @if ($attractions->isEmpty())
                <div class="empty-state">
                    <i class="bi bi-journal-text"></i>
                    <p class="mt-3 fs-5 text-muted">Không tìm thấy địa điểm nào. Hãy thử thay đổi bộ lọc!</p>
                </div>

            </div>
            @else
            <div class="row g-4">
                @foreach ($attractions as $attraction)
                <div class="col-lg-4 col-md-6">
                    <div class="attraction-card">
                        @if ($attraction->images->first())
                        <img src="{{ asset('storage/' . $attraction->images->first()->url) }}" alt="{{ $attraction->name }}" class="card-img-top">
                        @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Placeholder" class="card-img-top">
                        @endif
                        @auth
                        <div class="card-favorite {{ auth()->user()->favorites()->where('entity_type', 'attraction')->where('entity_id', $attraction->attraction_id)->exists() ? 'active' : '' }}"
                            data-entity-type="attraction" data-entity-id="{{ $attraction->attraction_id }}">
                            <i class="bi bi-heart"></i>
                        </div>
                        @endauth
                        <div class="card-body">
                            <h5 class="card-title">{{ $attraction->name }}</h5>
                            <p class="card-text">{{ Str::limit($attraction->description, 100) }}</p>
                            <p class="card-text small text-muted"><i class="bi bi-geo-alt-fill me-2"></i> {{ $attraction->address }}</p>
                            <p class="card-text small text-muted"><i class="bi bi-clock me-2"></i> {{ $attraction->opening_hours }}</p>
                            <a href="{{ route('attractions.show', $attraction->attraction_id) }}" class="btn btn-primary w-100">Xem thêm</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-5">
                {{ $attractions->links() }}
            </div>
            @endif
        </div>
    </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.card-favorite').forEach(button => {
        button.addEventListener('click', function() {
            
            const entityType = this.dataset.entityType;
            const entityId = this.dataset.entityId;
            const isActive = this.classList.contains('active');

            fetch("{{ route('favorites.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            entity_type: entityType,
                            entity_id: entityId
                        })
                    })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'added') {
                        this.classList.add('active');
                        this.querySelector('i').classList.remove('bi-heart');
                        this.querySelector('i').classList.add('bi-heart-fill');
                    } else {
                        this.classList.remove('active');
                        this.querySelector('i').classList.remove('bi-heart-fill');
                        this.querySelector('i').classList.add('bi-heart');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã có lỗi xảy ra khi cập nhật yêu thích.');
                });
           
        });
    });
</script>
@endsection