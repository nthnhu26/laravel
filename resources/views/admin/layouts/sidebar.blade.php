<div class="sidebar">
    <div class="sidebar-header">
        Ba Động Tourism
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}" href="{{ route('admin.hotels.index') }}">
                <i class="fas fa-hotel me-2"></i> Khách sạn
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.restaurants.*') ? 'active' : '' }}" href="{{ route('admin.restaurants.index') }}">
                <i class="fas fa-utensils me-2"></i> Nhà hàng
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.tours.*') ? 'active' : '' }}" href="{{ route('admin.tours.index') }}">
                <i class="fas fa-map-signs me-2"></i> Tour
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.transports.*') ? 'active' : '' }}" href="{{ route('admin.transports.index') }}">
                <i class="fas fa-car me-2"></i> Phương tiện
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.amenities.*') ? 'active' : '' }}" href="{{ route('admin.amenities.index') }}">
                <i class="fas fa-concierge-bell me-2"></i> Tiện ích
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.images.*') ? 'active' : '' }}" href="{{ route('admin.images.index') }}">
                <i class="fas fa-image me-2"></i> Hình ảnh
            </a>
        </li>
    </ul>
</div>