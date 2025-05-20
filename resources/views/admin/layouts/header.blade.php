<div class="header">
    <nav aria-label="breadcrumb">
        @yield('breadcrumb')
    </nav>
    <div>
        <span class="me-3">{{ auth()->user()->full_name['vi'] ?? auth()->user()->email }}</span>
        <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
        </a>
    </div>
</div>