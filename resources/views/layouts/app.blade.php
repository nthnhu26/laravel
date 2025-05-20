<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Biển Ba Động - @yield('title', 'Khám phá thiên đường biển')</title>
    <!-- Bootstrap 5 CSS -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    @auth
    <meta name="auth-check" content="true">
    @endauth
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/home.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/attractions.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/chatbot.css') }}" rel="stylesheet">

    @yield('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.jpg') }}" alt="Logo Biển Ba Động" class="rounded-circle" style="width: 40px; height: 40px;">
                <span class="logo-text ms-2">@lang('badongbeach')</span>
            </a>

            <!-- Toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Main navigation -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">@lang('home')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('attractions.index') ? 'active' : '' }}" href="{{ route('attractions.index') }}">@lang('attractions')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('hotels.index') ? 'active' : '' }}" href="{{ route('hotels.index') }}">@lang('accommodation')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('restaurants.index') ? 'active' : '' }}" href="{{ route('restaurants.index') }}">@lang('dining')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('tours.index') ? 'active' : '' }}" href="{{ route('tours.index') }}">@lang('Tour')</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('events.index') ? 'active' : '' }}" href="{{ route('events.index') }}">@lang('events')</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">@lang('contact')</a>
                    </li>
                </ul>

                <!-- Right-side elements -->
                <div class="d-flex align-items-center flex-wrap gap-2">
                    <!-- Language switch -->
                    <div class="dropdown">
                        <button class="btn language-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-globe"></i> {{ strtoupper(App::getLocale()) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('change.language', ['locale' => 'vi', 'redirect' => App::getLocale() === 'vi' ? request()->path() : substr(request()->path(), 3)]) }}">Tiếng Việt</a></li>
                            <li><a class="dropdown-item" href="{{ route('change.language', ['locale' => 'en', 'redirect' => App::getLocale() === 'vi' ? request()->path() : substr(request()->path(), 3)]) }}">English</a></li>

                        </ul>
                    </div>

                    <!-- Theme toggle -->
                    <div class="theme-toggle-wrapper">
                        <div class="theme-toggle" id="themeToggle">
                            <div class="toggle-thumb"><i class="bi bi-sun-fill"></i></div>
                        </div>
                    </div>

                    <!-- Authentication -->
                    <div class="auth-buttons d-flex gap-2">
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-auth btn-login"><i class="bi bi-box-arrow-in-right"></i> @lang('signin')</a>
                        <a href="{{ route('register') }}" class="btn btn-auth btn-register"><i class="bi bi-person-plus"></i> @lang('signup')</a>
                        @else
                        <div class="dropdown">
                            <button class="btn btn-auth btn-login dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">@lang('profile')</a></li>
                                <li><a class="dropdown-item" href="#">@lang('bookings')</a></li>
                                <li><a class="dropdown-item" href="#">@lang('dashboard')</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        @lang('logout')
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
 <x-chatbot />
    <!-- @include('layouts.chatbot') -->

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-12">
                    <h3 class="fs-4 fw-bold mb-3">Du Lịch Ba Động</h3>
                    <p class="text-white-50 mb-3">Khám phá vẻ đẹp thiên nhiên tuyệt vời của Ba Động cùng chúng tôi.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50 fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h4 class="fs-5 fw-semibold mb-3">Liên kết nhanh</h4>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="" class="text-white-50 text-decoration-none">Trang chủ</a></li>
                        <li class="mb-2"><a href="" class="text-white-50 text-decoration-none">Địa điểm du lịch</a></li>
                        <li class="mb-2"><a href="" class="text-white-50 text-decoration-none">Tour du lịch</a></li>
                        <li class="mb-2"><a href="" class="text-white-50 text-decoration-none">Blog</a></li>
                        <li class="mb-2"><a href="" class="text-white-50 text-decoration-none">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-12">
                    <h4 class="fs-5 fw-semibold mb-3">Liên hệ</h4>
                    <address class="text-white-50">
                        <p class="mb-2">123 Đường Du Lịch, Ba Động</p>
                        <p class="mb-2">Trà Vinh, Việt Nam</p>
                        <p class="mb-2">Email: info@dulichbadong.vn</p>
                        <p>Điện thoại: +84 123 456 789</p>
                    </address>
                </div>
            </div>
            <div class="border-top border-secondary mt-4 pt-4 text-center text-white-50">
                <p>&copy; {{ date('Y') }} Du Lịch Ba Động. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
  
    <!-- <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger
        intent="WELCOME"
        chat-title="BaDongBeach"
        agent-id="91eb2c34-2d91-4747-a6c7-04381881f647"
        language-code="vi"></df-messenger> -->
    @yield('scripts')
</body>

</html>