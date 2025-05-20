<div class="sidebar" id="sidebar">
    <div class="sidebar-title">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="sidebar-logo">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.dashboard') }}">
                <i class="bi bi-house"></i>
                <span>Tổng quan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.favorites') }}">
                <i class="bi bi-heart"></i>
                <span>Yêu thích</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.itineraries') }}">
                <i class="bi bi-list"></i>
                <span>Lịch trình</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.bookings') }}">
                <i class="bi bi-calendar-check"></i>
                <span>Đặt dịch vụ</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.reviews') }}">
                <i class="bi bi-star"></i>
                <span>Đánh giá</span>
            </a>
        </li>

    </ul>
</div>