<!-- resources/views/frontend/nhà/hotels/index.blade.php -->
@extends('layouts.app')

@section('title', __('Danh sách khách sạn và homestay'))

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
            position: sticky;
            top: 20px;
        }
        
        .hotel-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }
        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .hotel-card img {
            object-fit: cover;
            height: 200px;
            width: 100%;
            border-radius: 12px 12px 0 0;
        }
       
        .hotel-card .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .hotel-card .card-text {
            font-size: 0.9rem;
            color: #6c757d;
            line-height: 1.5;
        }
         .hotel-card .card-body {
            padding: 20px;
            flex-grow: 1;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
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
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .badge-primary {
            background: linear-gradient(45deg, #0078D4, #00C4B4);
            border-radius: 4px;
            font-size: 0.9rem;
            padding: 6px 10px;
        }
        .badge-success {
            background: #28a745;
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
            background: #ffffff;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
       
        @media (max-width: 991px) {
            .filter-sidebar {
                position: static;
                margin-bottom: 20px;
            }
            .section-padding {
                padding: 40px 0;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .hotel-card img {
                height: 200px;
            }
            .hotel-card .card-body {
                padding: 15px;
            }
            .hotel-card .card-title {
                font-size: 1rem;
            }
            .form-control, .form-select, .btn-primary, .btn-secondary {
                padding: 10px;
            }
        }
    </style>
@endsection

@section('content')
<section class="section-padding">
    <div class="container">
        <h1 class="section-title">@lang('Danh sách khách sạn và homestay')</h1>

        <div class="row g-4">
            <!-- Filter Sidebar (Left) -->
            <div class="col-lg-3">
                <div class="filter-sidebar">
                    <h4 class="section-title">@lang('Bộ lọc')</h4>
                    <form action="{{ route('hotels.index') }}" method="GET">
                        <div class="mb-3">
                            <label for="type" class="form-label fw-semibold">@lang('Loại hình')</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">@lang('Tất cả')</option>
                                <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>@lang('Khách sạn')</option>
                                <option value="homestay" {{ request('type') == 'homestay' ? 'selected' : '' }}>@lang('Homestay')</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price_range" class="form-label fw-semibold">@lang('Mức giá')</label>
                            <select name="price_range" id="price_range" class="form-select">
                                <option value="">@lang('Tất cả')</option>
                                <option value="$" {{ request('price_range') == '$' ? 'selected' : '' }}>@lang('Giá rẻ')</option>
                                <option value="$$" {{ request('price_range') == '$$' ? 'selected' : '' }}>@lang('Trung bình')</option>
                                <option value="$$$" {{ request('price_range') == '$$$' ? 'selected' : '' }}>@lang('Cao cấp')</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">@lang('Tiện ích')</label>
                            @if(\App\Models\Amenity::all()->isEmpty())
                                <p class="text-muted">@lang('Không có tiện ích nào.')</p>
                            @else
                                @foreach (\App\Models\Amenity::all() as $amenity)
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="amenities[]" id="amenity-{{ $amenity->amenity_id }}"
                                            value="{{ $amenity->amenity_id }}"
                                            {{ in_array($amenity->amenity_id, request('amenities', [])) ? 'checked' : '' }}
                                            class="form-check-input">
                                        <label for="amenity-{{ $amenity->amenity_id }}" class="form-check-label">{{ $amenity->name }}</label>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label fw-semibold">@lang('Vị trí')</label>
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
            <div class="col-lg-9">
                <div class="row g-4">
                    @forelse ($hotels as $hotel)
                        <div class="col-lg-4 col-md-6">
                            <div class="hotel-card">
                                @if ($hotel->images->first())
                                    <img src="{{ asset('storage/' . $hotel->images->first()->url) }}" class="card-img-top" alt="{{ $hotel->name }}">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" class="card-img-top" alt="Placeholder">
                                @endif
                                @auth
                                    <div class="card-favorite {{ auth()->user()->favorites()->where('entity_type', 'hotel')->where('entity_id', $hotel->hotel_id)->exists() ? 'active' : '' }}"
                                        data-entity-type="hotel" data-entity-id="{{ $hotel->hotel_id }}">
                                        <i class="bi bi-heart"></i>
                                    </div>
                                @endauth
                                <div class="card-body">
                                    <h3 class="card-title">{{ $hotel->name }}</h3>
                                    <span class="badge badge-primary">{{ $hotel->type == 'hotel' ? __('Khách sạn') : __('Homestay') }}</span>
                                    <span class="badge badge-success">{{ $hotel->price_range }}</span>
                                    <p class="card-text mt-2">{!! Str::limit($hotel->description, 100) !!}</p>
                                    <p class="card-text small text-muted"><i class="bi bi-geo-alt-fill me-2"></i> {{ $hotel->address }}</p>
                                    <a href="{{ route('hotels.show', $hotel->hotel_id) }}" class="btn btn-primary w-100">@lang('Xem thêm')</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="empty-state">
                                <i class="bi bi-search text-primary display-1"></i>
                                <p class="mt-3 fs-5 text-muted">@lang('Không tìm thấy khách sạn phù hợp.')</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $hotels->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        // Handle multiple select for amenities
        document.getElementById('amenities').addEventListener('click', function(e) {
            // Ensure multiple selection works smoothly
        });

        // Handle favorite toggle
        document.querySelectorAll('.card-favorite').forEach(button => {
            button.addEventListener('click', function() {
                @auth
                    const entityType = this.dataset.entityType;
                    const entityId = this.dataset.entityId;
                    const isActive = this.classList.contains('active');

                    fetch('{{ route('favorites.toggle') }}', {
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
                @else
                    window.location.href = '{{ route('login') }}';
                @endauth
            });
        });
    </script>
@endsection