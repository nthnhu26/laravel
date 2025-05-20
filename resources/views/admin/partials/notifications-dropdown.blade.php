<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" id="navbarDropdownNotifications" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell"></i>
        <span class="badge bg-danger rounded-pill notification-badge" id="notificationBadge" style="display:none;"></span>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownNotifications" id="notificationDropdown">
        <li>
            <h6 class="dropdown-header">Thông báo của bạn</h6>
        </li>
        <div id="notificationList">
            <li><div class="dropdown-item text-center">Đang tải...</div></li>
        </div>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item text-center" href="{{ route('admin.notifications.user') }}">
                Xem tất cả thông báo
            </a>
        </li>
    </ul>
</li>