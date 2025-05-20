<div class="sidebar" id="sidebar">
    <div class="sidebar-title">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="sidebar-logo">
    </div>
    <ul class="nav flex-column">
        <!-- Tổng quan -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i>
                <span>Tổng quan</span>
            </a>
        </li>

        <!-- Quản lý người dùng -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i>
                <span>Quản lý người dùng</span>
            </a>
        </li>

        <!-- Quản lý danh mục -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.categories.index') }}">
                <i class="fas fa-list-ul"></i>
                <span>Quản lý danh mục</span>
            </a>
        </li>

        <!-- Quản lý tiện ích -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.amenities.index') }}">
                <i class="fas fa-check-circle"></i>
                <span>Quản lý tiện ích</span>
            </a>
        </li>

        <!-- Quản lý địa điểm -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.places.index') }}">
                <i class="fas fa-map-marker-alt"></i>
                <span>Quản lý địa điểm</span>
            </a>
        </li>

        <!-- Quản lý nhà cung cấp dịch vụ -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.service-providers.index') }}">
                <i class="fas fa-briefcase"></i>
                <span>Nhà cung cấp dịch vụ</span>
            </a>
        </li>

        <!-- Quản lý tour du lịch -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.tours.index') }}">
                <i class="fas fa-map"></i>
                <span>Quản lý tour du lịch</span>
            </a>
        </li>

        <!-- Quản lý sự kiện -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.events.index') }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Quản lý sự kiện</span>
            </a>
        </li>

        <!-- Quản lý đặt dịch vụ (Dropdown) -->
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#bookingsMenu" role="button" aria-expanded="false" aria-controls="bookingsMenu">
                <i class="fas fa-calendar-check"></i>
                <span>Quản lý đặt dịch vụ</span>
            </a>
            <div class="collapse" id="bookingsMenu">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a class="nav-link" href="">Đặt chỗ địa điểm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Đặt tour du lịch</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Đặt phương tiện</a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Quản lý đánh giá -->
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="fas fa-star"></i>
                <span>Quản lý đánh giá</span>
            </a>
        </li>

        <!-- Quản lý bài viết -->
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="fas fa-file-alt"></i>
                <span>Quản lý bài viết</span>
            </a>
        </li>

        <!-- Quản lý thông báo -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.notifications.index') }}">
                <i class="fas fa-bell"></i>
                <span>Quản lý thông báo</span>
            </a>
        </li>

        <!-- Quản lý liên hệ/phản hồi -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.contacts.index') }}">
                <i class="fas fa-comments"></i>
                <span>Quản lý liên hệ</span>
            </a>
        </li>

        <!-- Quản lý thống kê -->
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="fas fa-chart-bar"></i>
                <span>Thống kê lượt xem</span>
            </a>
        </li>

        <!-- Quản lý chatbot -->
        <li class="nav-item">
            <a class="nav-link" href="">
                <i class="fas fa-robot"></i>
                <span>Quản lý chatbot</span>
            </a>
        </li>

        <!-- Quản lý lịch sử tìm kiếm -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.search-history.index') }}">
                <i class="fas fa-search"></i>
                <span>Lịch sử tìm kiếm</span>
            </a>
        </li>
    </ul>
</div>