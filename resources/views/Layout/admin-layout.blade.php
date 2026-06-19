<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Smart Queue Admin')</title>
    @yield('styles')
</head>
<body>
    @include('Layout.side')  {{-- Your sidebar --}}
    @include('Layout.admin_nav')  {{-- Your admin nav --}}
    
    <div class="main-content" style="margin-left: 280px; margin-top: 100px; padding: 20px;">
        @yield('content')
    </div>
    
    @yield('scripts')
</body>
</html>