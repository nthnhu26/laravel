<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" id="navbar">
    <div class="container-fluid">
        <button class="btn btn-outline-secondary me-2" id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a class="navbar-brand" href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">
            Quản Lý Biển Ba Động
        </a>

        <div class="ms-auto">
            <ul class="navbar-nav">
                <!-- Thêm vào navbar của layout chính -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownNotifications" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(Auth::check())
                        <span class="notification-badge badge bg-danger rounded-pill" style="{{ Auth::user()->unreadNotificationsCount() > 0 ? '' : 'display: none;' }}">
                            {{ Auth::user()->unreadNotificationsCount() }}
                        </span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownNotifications">
                        <li>
                            <h6 class="dropdown-header">Thông báo mới</h6>
                        </li>
                        <div class="notification-dropdown-items">
                            @if(Auth::check() && Auth::user()->recentNotifications()->count() > 0)
                            @foreach(Auth::user()->recentNotifications() as $notification)
                            <li>
                                <a class="dropdown-item {{ $notification->is_read ? '' : 'fw-bold' }}" href="#"
                                    onclick="markNotificationAsRead({{ $notification->notification_id }})">
                                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                    <div class="notification-text">{{ Str::limit($notification->message, 50) }}</div>
                                </a>
                            </li>
                            @if(!$loop->last)
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endif
                            @endforeach
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-center" href="">Xem tất cả thông báo</a></li>
                            @else
                            <li><a class="dropdown-item text-center py-3">Không có thông báo mới</a></li>
                            @endif
                        </div>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <span class="me-2">Xin chào, {{ Auth::user()->full_name ?? Auth::user()->name }}</span>
                        <img src="{{ Auth::user()->avatar ? (filter_var(Auth::user()->avatar, FILTER_VALIDATE_URL) ? Auth::user()->avatar : asset('storage/' . Auth::user()->avatar)) : asset('admin_assets/images/default-avatar.png') }}" alt="Avatar" class="rounded-circle" width="30" height="30">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank">Trang chủ</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">Đăng xuất</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>