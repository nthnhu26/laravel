<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Quản Lý Biển Ba Động</title>
    <link href="{{ asset('admin_assets/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    @yield('styles')
</head>

<body>
    <!-- Navbar -->
    @include('admin.partials.navbar')

    <!-- Sidebar and Content -->
    <div class="d-flex">
        <!-- Sidebar -->
        @auth
        @if (Auth::user()->role === 'admin')
        @include('admin.partials.sidebar_admin')
        @else
        @include('admin.partials.sidebar_user')
        @endif
        @endauth

        <!-- Main wrapper -->
        <div class="main-wrapper" id="mainWrapper">
            <!-- Content -->
            <div class="content">
                @yield('content')
            </div>
            <!-- Footer -->
            @include('admin.partials.footer')
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('admin_assets/script.js') }}"></script>
    <script src="{{ asset('admin_assets/admin.js') }}"></script>
    @yield('scripts')
</body>

</html>