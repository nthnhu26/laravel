<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="goong-api-key" content="{{ config('services.goong.key') }}">
    <meta name="goong-maptiles-key" content="{{ config('services.goong.maptiles_key') }}">
    @if(isset($entity) && $entity->latitude && $entity->longitude)
        <meta name="initial-lat" content="{{ $entity->latitude }}">
        <meta name="initial-lng" content="{{ $entity->longitude }}">
    @endif

    <title>{{ $title ?? 'Ba Động Tourism' }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
</head>
<body>
    <div id="app">
        @yield('breadcrumb')
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@goongmaps/goong-js@1.0.9/dist/goong-js.js"></script>
    <script src="{{ asset('assets/js/location-image.js') }}"></script>
    @yield('scripts')
</body>
</html>