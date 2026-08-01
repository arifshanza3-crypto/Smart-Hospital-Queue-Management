<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Queue Admin')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @stack('styles')
</head>
<body>
    @include('Layout.side')
    @include('Layout.admin_nav')
    
    <div class="main-content" style="margin-left: 280px; margin-top: 80px; padding: 20px;">
        @if(session('success'))
            <div style="background: #d4edda; color: #155724; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #28a745;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #f8d7da; color: #721c24; padding: 14px 18px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #dc3545;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        @yield('content')
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/notification.js') }}"></script>
    @stack('scripts')
</body>
</html>