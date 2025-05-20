<!-- resources/views/frontend/nhà/contact.blade.php -->
@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<section class="section-padding">
    <div class="container">
        <!-- Title Section -->
        <div class="text-center mb-5">
            <h1>Liên hệ với chúng tôi</h1>
            <p class="text-muted lead">Chúng tôi sẵn sàng hỗ trợ và lắng nghe ý kiến từ bạn!</p>
        </div>

        <!-- Contact Form and Info Section -->
        <div class="row g-4 justify-content-center">
            <!-- Contact Form -->
            <div class="col-lg-6 col-md-8">
                <!-- Notifications -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Form -->
                <div class="card hover-card fade-in">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Gửi tin nhắn</h3>
                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="message" class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-2">Gửi ngay</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-8">
                <div class="card hover-card fade-in">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Thông tin liên hệ</h3>
                        <ul class="list-unstyled">
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-geo-alt-fill text-primary me-3 fs-4"></i>
                                <span>123 Bãi biển Ba Đông, Trà Vinh, Việt Nam</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-telephone-fill text-primary me-3 fs-4"></i>
                                <span>+84 123 456 789</span>
                            </li>
                            <li class="d-flex align-items-center mb-3">
                                <i class="bi bi-envelope-fill text-primary me-3 fs-4"></i>
                                <span>contact@badongtourism.com</span>
                            </li>
                        </ul>
                        <!-- Google Maps -->
                        <div class="mt-4">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3925.123456789!2d106.3385!3d9.9347!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zOUMzNCc1NC4xIk4gMTA2wrAyMCcxOC4yIkU!5e0!3m2!1svi!2s!4v1698765432100!5m2!1svi!2s"
                                width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
    <style>
        .section-padding {
            padding: 60px 0;
        }
        
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
       
        .btn-primary {
            background: linear-gradient(45deg, #0078D4, #00C4B4);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #005a9e, #00897b);
        }
        .alert-success, .alert-danger {
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background-color: #e6f7e6;
            border-color: #28a745;
            color: #28a745;
        }
        .alert-danger {
            background-color: #f7e6e6;
            border-color: #dc3545;
            color: #dc3545;
        }
        .text-primary {
            color: #0078D4 !important;
        }
        .badge-primary {
            background: linear-gradient(45deg, #0078D4, #00C4B4) !important;
            border-radius: 4px;
        }
        .list-unstyled li:hover i {
            color: #00C4B4;
        }
        /* Fade-in Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeIn 0.6s ease-out forwards;
        }
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 768px) {
            .section-padding {
                padding: 40px 0;
            }
            .section-title {
                font-size: 1.5rem;
            }
            .card {
                margin-bottom: 20px;
            }
            
            .card-body {
                padding: 20px;
            }
            iframe {
                height: 150px;
            }
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Form submission loading state
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Đang gửi...';
                });
            }
        });
    </script>
@endsection