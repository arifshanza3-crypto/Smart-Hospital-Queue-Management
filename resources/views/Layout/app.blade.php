<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMART QUEUE')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           ROOT VARIABLES
           ============================================ */
        :root {
            --primary-teal: #00d4ff;
            --primary-dark: #0b2e33;
            --primary-mid: #1a4a50;
            --primary-light: #0d3b42;
            --nav-bg: rgba(7, 26, 28, 0.96);  /* CHANGED: #071a1c */
            --glass-border: rgba(255, 255, 255, 0.08);
            --shadow-color: rgba(0, 0, 0, 0.3);
            --card-bg: rgba(11, 46, 51, 0.85);
            --text-muted: rgba(255, 255, 255, 0.55);
        }

        /* ============================================
           BASE STYLES
           ============================================ */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            box-sizing: border-box;
        }

        html { 
            scroll-behavior: smooth; 
        }

        body { 
            background: linear-gradient(145deg, #0b2e33 0%, #1a4a50 50%, #0d3b42 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            color: #ffffff;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at 20% 50%, rgba(0, 212, 255, 0.03) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ============================================
           MODERN GLASS NAVBAR
           ============================================ */
        .navbar-wrapper {
            padding: 16px 24px !important;
            display: flex !important;
            justify-content: center !important;
            position: fixed !important;
            width: 100% !important;
            top: 0 !important;
            z-index: 1030 !important;
            background: transparent !important;
            transition: all 0.4s ease !important;
        }

        .navbar-wrapper.scrolled {
            padding: 8px 24px !important;
        }

        .navbar-wrapper.scrolled .navbar {
            background: var(--nav-bg) !important;
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.4) !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
        }

        .navbar {
            background: rgba(7, 26, 28, 0.7) !important;  /* CHANGED: #071a1c */
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-radius: 16px !important;
            padding: 4px 12px !important;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.2), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.06) !important;
            border: 1px solid var(--glass-border) !important;
            width: 100% !important;
            max-width: 98% !important;
            min-height: auto !important;
            height: auto !important;
            transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1) !important;
        }

        .navbar:hover {
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.35), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.08) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* ---- Brand / Logo ---- */
        .navbar-brand {
            display: flex !important;
            align-items: center !important;
            padding: 4px 0 !important;
            margin-right: 12px !important;
            gap: 10px !important;
            text-decoration: none !important;
            transition: transform 0.3s ease !important;
        }

        .navbar-brand:hover {
            transform: scale(1.02) !important;
        }

        .nav-logo-img {
            height: 40px !important;
            width: auto !important;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
            filter: drop-shadow(0 0 24px rgba(0, 212, 255, 0.12)) !important;
        }

        .nav-logo-img:hover {
            transform: scale(1.06) rotate(-1deg) !important;
            filter: drop-shadow(0 0 36px rgba(0, 212, 255, 0.25)) !important;
        }

        .brand-text {
            color: white !important;
            font-size: 20px !important;
            font-weight: 800 !important;
            letter-spacing: 0.3px !important;
            background: linear-gradient(135deg, #ffffff 55%, #00d4ff 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
            background-clip: text !important;
            line-height: 1.2 !important;
        }

        /* ---- Nav Links ---- */
        .navbar-nav {
            gap: 2px !important;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.5) !important;
            margin: 0 2px !important;
            font-weight: 500 !important;
            text-transform: uppercase !important;
            font-size: 11px !important;
            letter-spacing: 0.6px !important;
            position: relative !important;
            padding: 8px 18px !important;
            transition: all 0.3s ease !important;
            border-radius: 10px !important;
            background: transparent !important;
        }

        .nav-link::before {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            border-radius: 10px !important;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.06), rgba(0, 212, 255, 0.01)) !important;
            opacity: 0 !important;
            transition: opacity 0.3s ease !important;
        }

        .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.04) !important;
            transform: translateY(-1px) !important;
        }

        .nav-link:hover::before {
            opacity: 1 !important;
        }

        .nav-link.active {
            color: #ffffff !important;
            background: rgba(0, 212, 255, 0.08) !important;
        }

        .nav-link.active::after {
            content: '' !important;
            position: absolute !important;
            bottom: 4px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 18px !important;
            height: 2.5px !important;
            background: var(--primary-teal) !important;
            border-radius: 4px !important;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3) !important;
        }

        /* ============================================
           MODERN BUTTONS
           ============================================ */
        .btn-pill {
            border-radius: 10px !important;
            padding: 7px 20px !important;
            font-weight: 600 !important;
            font-size: 11px !important;
            text-transform: uppercase !important;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
            border: none !important;
            margin-left: 4px !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            line-height: 1.4 !important;
            letter-spacing: 0.4px !important;
            position: relative !important;
            overflow: hidden !important;
        }

        .btn-pill::after {
            content: '' !important;
            position: absolute !important;
            inset: 0 !important;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent 60%) !important;
            opacity: 0 !important;
            transition: opacity 0.3s ease !important;
            border-radius: 10px !important;
        }

        .btn-pill:hover::after {
            opacity: 1 !important;
        }

        .btn-book { 
            background: linear-gradient(135deg, #00d4ff, #0088b3) !important; 
            color: white !important; 
            border: 1px solid rgba(255,255,255,0.1) !important;
            box-shadow: 0 4px 20px rgba(0, 212, 255, 0.2) !important;
        }
        
        .btn-book:hover { 
            transform: translateY(-2px) scale(1.03) !important; 
            box-shadow: 0 8px 40px rgba(0, 212, 255, 0.35) !important;
            color: white !important;
        }
        
        .btn-login { 
            background: rgba(255, 255, 255, 0.04) !important; 
            color: rgba(255, 255, 255, 0.8) !important; 
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(8px) !important;
        }
        
        .btn-login:hover { 
            background: rgba(255, 255, 255, 0.08) !important;
            transform: translateY(-2px) !important;
            border-color: rgba(255, 255, 255, 0.18) !important;
            color: white !important;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2) !important;
        }

        /* ============================================
           AUTH BUTTONS CONTAINER
           ============================================ */
        .auth-buttons {
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            flex-wrap: wrap !important;
        }

        /* ============================================
           NOTIFICATION BELL - MODERN
           ============================================ */
        .notification-wrapper {
            position: relative !important;
            display: inline-block !important;
        }

        .notification-bell {
            cursor: pointer !important;
            color: #ffd700 !important;
            font-size: 14px !important;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
            background: rgba(255, 215, 0, 0.04) !important;
            padding: 6px 12px !important;
            border-radius: 10px !important;
            border: 1px solid rgba(255, 215, 0, 0.06) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 4px !important;
            text-decoration: none !important;
            position: relative !important;
        }

        .notification-bell:hover {
            transform: scale(1.04) !important;
            background: rgba(255, 215, 0, 0.08) !important;
            border-color: rgba(255, 215, 0, 0.15) !important;
            box-shadow: 0 0 40px rgba(255, 215, 0, 0.06) !important;
        }

        .notification-bell .bell-icon {
            font-size: 16px !important;
        }

        .notification-badge {
            position: absolute !important;
            top: -4px !important;
            right: -4px !important;
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            color: white !important;
            font-size: 8px !important;
            font-weight: 700 !important;
            padding: 2px 7px !important;
            border-radius: 50% !important;
            min-width: 18px !important;
            text-align: center !important;
            display: none !important;
            animation: pulse-badge 2s ease-in-out infinite !important;
            border: 2px solid rgba(7, 26, 28, 0.9) !important;  /* CHANGED: #071a1c */
            box-shadow: 0 2px 12px rgba(239, 68, 68, 0.3) !important;
        }

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.12); }
        }

        /* ============================================
           PROFILE DROPDOWN - MODERN
           ============================================ */
        .profile-wrapper {
            position: relative !important;
            display: inline-block !important;
            margin-left: 2px !important;
            padding: 0 !important;
            max-width: none !important;
        }

        .profile-btn {
            cursor: pointer !important;
            color: #ffffff !important;
            font-size: 12px !important;
            transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
            background: rgba(255, 255, 255, 0.03) !important;
            padding: 3px 12px 3px 3px !important;
            border-radius: 10px !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            text-decoration: none !important;
            backdrop-filter: blur(8px) !important;
        }

        .profile-btn:hover {
            background: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            transform: scale(1.02) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
        }

        .profile-avatar {
            width: 28px !important;
            height: 28px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #00d4ff, #006699) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            font-weight: 700 !important;
            font-size: 10px !important;
            overflow: hidden !important;
            border: 2px solid rgba(255, 255, 255, 0.1) !important;
            flex-shrink: 0 !important;
            transition: all 0.3s ease !important;
        }

        .profile-btn:hover .profile-avatar {
            border-color: rgba(0, 212, 255, 0.4) !important;
            box-shadow: 0 0 24px rgba(0, 212, 255, 0.12) !important;
        }

        .profile-avatar img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        .profile-name-display {
            font-size: 11px !important;
            font-weight: 500 !important;
            color: rgba(255, 255, 255, 0.8) !important;
            max-width: 80px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .profile-chevron {
            color: rgba(255, 255, 255, 0.2) !important;
            font-size: 8px !important;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        .profile-btn:hover .profile-chevron {
            transform: rotate(180deg) !important;
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* ---- Profile Dropdown Menu ---- */
        .profile-dropdown {
            position: absolute !important;
            top: 40px !important;
            right: 0 !important;
            width: 230px !important;
            background: rgba(7, 26, 28, 0.97) !important;  /* CHANGED: #071a1c */
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            border-radius: 12px !important;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.5) !important;
            z-index: 1000 !important;
            overflow: hidden !important;
            display: none !important;
            padding: 8px 0 !important;
        }

        .profile-dropdown.active {
            display: block !important;
            animation: slideDown 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px) scale(0.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .profile-dropdown .dropdown-item {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 9px 18px !important;
            color: rgba(255, 255, 255, 0.6) !important;
            text-decoration: none !important;
            transition: all 0.25s ease !important;
            cursor: pointer !important;
            border: none !important;
            background: none !important;
            width: 100% !important;
            text-align: left !important;
            font-size: 12px !important;
            font-weight: 500 !important;
            border-radius: 6px !important;
            margin: 0 6px !important;
            width: calc(100% - 12px) !important;
        }

        .profile-dropdown .dropdown-item:hover {
            background: rgba(0, 212, 255, 0.06) !important;
            color: #ffffff !important;
            transform: translateX(4px) !important;
        }

        .profile-dropdown .dropdown-item i {
            width: 18px !important;
            color: rgba(255, 255, 255, 0.25) !important;
            font-size: 13px !important;
            transition: color 0.3s ease !important;
        }

        .profile-dropdown .dropdown-item:hover i {
            color: #00d4ff !important;
        }

        .profile-dropdown .dropdown-divider {
            height: 1px !important;
            background: rgba(255, 255, 255, 0.04) !important;
            margin: 6px 14px !important;
        }

        .profile-dropdown .dropdown-item.logout-item {
            color: #f87171 !important;
        }

        .profile-dropdown .dropdown-item.logout-item i {
            color: #ef4444 !important;
        }

        .profile-dropdown .dropdown-item.logout-item:hover {
            background: rgba(239, 68, 68, 0.06) !important;
            color: #ef4444 !important;
        }

        /* ============================================
           NOTIFICATION DROPDOWN - MODERN
           ============================================ */
        .notification-dropdown {
            position: absolute !important;
            top: 40px !important;
            right: 0 !important;
            width: 380px !important;
            max-height: 460px !important;
            background: rgba(7, 26, 28, 0.97) !important;  /* CHANGED: #071a1c */
            backdrop-filter: blur(24px) !important;
            -webkit-backdrop-filter: blur(24px) !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            border-radius: 12px !important;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.5) !important;
            z-index: 1000 !important;
            overflow: hidden !important;
            display: none !important;
        }

        .notification-dropdown.active {
            display: block !important;
            animation: slideDown 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        .notification-header {
            padding: 14px 20px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04) !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
        }

        .notification-header h4 {
            margin: 0 !important;
            color: #fff !important;
            font-size: 14px !important;
            font-weight: 700 !important;
        }

        .notification-header .mark-all {
            color: #ffd700 !important;
            font-size: 10px !important;
            cursor: pointer !important;
            text-decoration: none !important;
            opacity: 0.6 !important;
            transition: all 0.3s ease !important;
            font-weight: 500 !important;
        }

        .notification-header .mark-all:hover {
            opacity: 1 !important;
            text-decoration: underline !important;
        }

        .notification-list {
            max-height: 350px !important;
            overflow-y: auto !important;
            padding: 4px 0 !important;
        }

        .notification-list::-webkit-scrollbar {
            width: 3px !important;
        }

        .notification-list::-webkit-scrollbar-track {
            background: transparent !important;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: rgba(255, 215, 0, 0.3) !important;
            border-radius: 10px !important;
        }

        .notification-item {
            padding: 12px 18px !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.02) !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: flex-start !important;
            gap: 12px !important;
            margin: 0 4px !important;
            border-radius: 6px !important;
        }

        .notification-item:hover {
            background: rgba(255, 215, 0, 0.03) !important;
        }

        .notification-item.unread {
            background: rgba(255, 215, 0, 0.03) !important;
            border-left: 3px solid #ffd700 !important;
            border-radius: 6px !important;
        }

        .notification-item .notification-icon {
            font-size: 16px !important;
            min-width: 28px !important;
            margin-top: 2px !important;
        }

        .notification-item .notification-content {
            flex: 1 !important;
        }

        .notification-item .notification-title {
            color: #fff !important;
            font-weight: 600 !important;
            font-size: 12px !important;
        }

        .notification-item .notification-message {
            color: rgba(255, 255, 255, 0.5) !important;
            font-size: 11px !important;
            margin-top: 2px !important;
            line-height: 1.4 !important;
        }

        .notification-item .notification-time {
            color: rgba(255, 255, 255, 0.2) !important;
            font-size: 9px !important;
            margin-top: 4px !important;
        }

        .notification-empty {
            padding: 40px 20px !important;
            text-align: center !important;
            color: rgba(255, 255, 255, 0.2) !important;
        }

        .notification-empty .icon {
            font-size: 36px !important;
            margin-bottom: 10px !important;
            display: block !important;
        }

        .notification-empty p {
            font-size: 13px !important;
        }

        .notification-footer {
            padding: 10px 20px !important;
            border-top: 1px solid rgba(255, 255, 255, 0.04) !important;
            text-align: center !important;
        }

        .notification-footer a {
            color: #ffd700 !important;
            font-size: 11px !important;
            text-decoration: none !important;
            opacity: 0.6 !important;
            transition: opacity 0.3s ease !important;
            font-weight: 500 !important;
        }

        .notification-footer a:hover {
            opacity: 1 !important;
            text-decoration: underline !important;
        }

        /* ============================================
           TOGGLER (Mobile)
           ============================================ */
        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            padding: 6px 10px !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }

        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.04) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        .navbar-toggler-icon {
            filter: invert(1) !important;
            opacity: 0.8 !important;
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        main { 
            padding-top: 90px !important; 
            min-height: 80vh !important; 
            position: relative !important;
            z-index: 1 !important;
        }

        /* ============================================
           FOOTER - MODERN
           ============================================ */
        .footer-main {
            background: rgba(11, 46, 51, 0.95) !important;
            backdrop-filter: blur(20px) !important;
            color: #FFFFFF !important;
            padding: 60px 0 40px 0 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.04) !important;
            position: relative !important;
            z-index: 1 !important;
        }

        .footer-logo-text {
            font-size: 24px !important;
            font-weight: 800 !important;
            color: #FFFFFF !important;
            text-decoration: none !important;
            display: flex !important;
            align-items: center !important;
            gap: 14px !important;
            margin-bottom: 12px !important;
            transition: transform 0.3s ease !important;
        }

        .footer-logo-text:hover {
            transform: scale(1.02) !important;
        }

        .footer-logo-text img {
            height: 48px !important;
            width: auto !important;
        }

        .footer-desc {
            color: rgba(255,255,255,0.45) !important;
            font-size: 13px !important;
            margin-bottom: 24px !important;
            line-height: 1.7 !important;
            max-width: 420px !important;
        }

        .social-container { 
            display: flex !important; 
            align-items: center !important; 
            gap: 16px !important; 
        }
        
        .social-links { 
            display: flex !important;
            gap: 8px !important;
        }

        .social-links a { 
            width: 36px !important; 
            height: 36px !important;
            background: rgba(255,255,255,0.03) !important;
            display: inline-flex !important; 
            align-items: center !important; 
            justify-content: center !important;
            border-radius: 50% !important; 
            color: rgba(255,255,255,0.5) !important; 
            transition: all 0.3s ease !important; 
            text-decoration: none !important;
            border: 1px solid rgba(255, 255, 255, 0.04) !important;
            font-size: 14px !important;
        }
        
        .social-links a:hover { 
            background: var(--primary-teal) !important; 
            transform: translateY(-3px) !important;
            border-color: var(--primary-teal) !important;
            box-shadow: 0 8px 28px rgba(0, 212, 255, 0.2) !important;
            color: white !important;
        }

        .footer-main h6 {
            font-size: 13px !important;
            font-weight: 700 !important;
            letter-spacing: 1px !important;
            text-transform: uppercase !important;
            color: rgba(255, 255, 255, 0.7) !important;
            margin-bottom: 20px !important;
        }

        .footer-links {
            display: flex !important;
            flex-direction: column !important;
            gap: 10px !important;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.35) !important;
            text-decoration: none !important;
            font-size: 13px !important;
            transition: all 0.3s ease !important;
        }

        .footer-links a:hover {
            color: #ffffff !important;
            transform: translateX(4px) !important;
        }

        .footer-main p {
            color: rgba(255, 255, 255, 0.35) !important;
            font-size: 13px !important;
            line-height: 1.7 !important;
        }

        .footer-main p i {
            color: rgba(255, 255, 255, 0.2) !important;
            width: 20px !important;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(10px) !important;
            padding: 16px 0 !important;
            font-size: 12px !important;
            color: rgba(255,255,255,0.2) !important;
            position: relative !important;
            z-index: 1 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.02) !important;
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 992px) {
            .navbar {
                max-width: 100% !important;
                border-radius: 0 !important;
            }
            .navbar-wrapper {
                padding: 0 !important;
            }
            main {
                padding-top: 72px !important;
            }
            .profile-name-display {
                display: none !important;
            }
            .auth-buttons {
                flex-wrap: wrap !important;
                gap: 4px !important;
                padding: 8px 0 !important;
            }
            .nav-logo-img {
                height: 34px !important;
            }
            .brand-text {
                font-size: 17px !important;
            }
            .btn-pill {
                padding: 6px 16px !important;
                font-size: 10px !important;
            }
            .nav-link {
                font-size: 10px !important;
                padding: 6px 14px !important;
            }
            .navbar-nav {
                padding: 8px 0 !important;
                gap: 0 !important;
            }
            .navbar-collapse {
                background: rgba(7, 26, 28, 0.98) !important;  /* CHANGED: #071a1c */
                backdrop-filter: blur(20px) !important;
                border-radius: 12px !important;
                padding: 8px 12px !important;
                margin-top: 8px !important;
                border: 1px solid rgba(255, 255, 255, 0.04) !important;
            }
        }

        @media (max-width: 768px) {
            .navbar-wrapper {
                padding: 0 !important;
            }
            .navbar {
                border-radius: 0 !important;
                max-width: 100% !important;
            }
            main {
                padding-top: 64px !important;
            }
            .nav-logo-img {
                height: 30px !important;
            }
            .brand-text {
                font-size: 15px !important;
            }
            .btn-pill {
                padding: 5px 14px !important;
                font-size: 9px !important;
            }
            .profile-avatar {
                width: 24px !important;
                height: 24px !important;
                font-size: 9px !important;
            }
            .profile-btn {
                padding: 2px 10px 2px 2px !important;
            }
            .notification-bell {
                padding: 4px 10px !important;
                font-size: 12px !important;
            }
            .notification-bell .bell-icon {
                font-size: 14px !important;
            }
            .notification-dropdown {
                width: 320px !important;
                right: -10px !important;
            }
            .profile-dropdown {
                width: 210px !important;
                right: -10px !important;
            }
            .nav-link {
                font-size: 9px !important;
                padding: 5px 12px !important;
            }
            .footer-logo-text {
                font-size: 20px !important;
            }
            .footer-logo-text img {
                height: 40px !important;
            }
        }

        @media (max-width: 576px) {
            .btn-pill {
                padding: 4px 12px !important;
                font-size: 8px !important;
            }
            .nav-logo-img {
                height: 26px !important;
            }
            .brand-text {
                font-size: 13px !important;
            }
            .notification-dropdown {
                width: 290px !important;
                right: -5px !important;
            }
            .profile-dropdown {
                width: 190px !important;
                right: -5px !important;
            }
            main {
                padding-top: 58px !important;
            }
            .nav-link {
                font-size: 8px !important;
                padding: 4px 10px !important;
            }
            .profile-name-display {
                display: none !important;
            }
            .profile-btn {
                padding: 2px 6px 2px 2px !important;
            }
            .profile-avatar {
                width: 20px !important;
                height: 20px !important;
                font-size: 8px !important;
            }
            .auth-buttons {
                gap: 3px !important;
            }
            .footer-main {
                padding: 40px 0 24px 0 !important;
            }
            .footer-logo-text {
                font-size: 18px !important;
            }
            .footer-logo-text img {
                height: 34px !important;
            }
            .social-container {
                flex-wrap: wrap !important;
            }
        }
    </style>
</head>
<body>

    <div class="navbar-wrapper" id="navbarWrapper">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('Assert/logo.png') }}" alt="Logo" class="nav-logo-img">
                    <span class="brand-text">SMART QUEUE</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="/about">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="/services">Services</a></li>
                        <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                    </ul>
                    
                    <div class="auth-buttons">
                        {{-- Book Now Button --}}
                        <a href="/Token_form" class="btn btn-pill btn-book">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </a>
                        
                        @auth
                            {{-- Profile Dropdown --}}
                            <div class="profile-wrapper">
                                <div class="profile-btn" onclick="toggleProfileDropdown()" aria-expanded="false" aria-label="Profile menu">
                                    <div class="profile-avatar">
                                        @if(auth()->user()->avatar)
                                            <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Profile">
                                        @else
                                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                        @endif
                                    </div>
                                    <span class="profile-name-display">{{ auth()->user()->name ?? 'User' }}</span>
                                    <i class="fas fa-chevron-down profile-chevron"></i>
                                </div>
                                
                                <div class="profile-dropdown" id="profileDropdown" role="menu">
                                    <a href="{{ route('profile.index') }}" class="dropdown-item" role="menuitem">
                                        <i class="fas fa-user"></i> My Profile
                                    </a>
                                    
                                    @if(auth()->user()->role === 'admin')
                                        <a href="/admin/doctor-management" class="dropdown-item" role="menuitem">
                                            <i class="fas fa-chart-line"></i> Admin Dashboard
                                        </a>
                                        <a href="/staff/dashboard" class="dropdown-item" role="menuitem">
                                            <i class="fas fa-users-cog"></i> Staff Dashboard
                                        </a>
                                        <a href="/" class="dropdown-item" role="menuitem">
                                            <i class="fas fa-globe"></i> Website
                                        </a>
                                    @elseif(auth()->user()->role === 'staff')
                                        <a href="/staff/dashboard" class="dropdown-item" role="menuitem">
                                            <i class="fas fa-users-cog"></i> Staff Dashboard
                                        </a>
                                        <a href="/" class="dropdown-item" role="menuitem">
                                            <i class="fas fa-globe"></i> Website
                                        </a>
                                    @endif
                                    
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item logout-item" role="menuitem" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </div>
                            </div>

                            {{-- Notification Bell --}}
                            <div class="notification-wrapper">
                                <div class="notification-bell" onclick="toggleNotifications()" aria-expanded="false" aria-label="Notifications">
                                    <i class="bi bi-bell-fill bell-icon"></i>
                                    <span class="notification-badge" id="notificationBadge">0</span>
                                </div>
                                <div class="notification-dropdown" id="notificationDropdown">
                                    <div class="notification-header">
                                        <h4>Notifications</h4>
                                        <a href="#" class="mark-all" onclick="markAllRead()">Mark all as read</a>
                                    </div>
                                    <div class="notification-list" id="notificationList">
                                        <div class="notification-empty">
                                            <span class="icon">🔔</span>
                                            <p>No notifications yet</p>
                                        </div>
                                    </div>
                                    <div class="notification-footer">
                                        <a href="{{ route('notifications.page') }}">View all notifications →</a>
                                    </div>
                                </div>
                            </div>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-pill btn-login">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer-main">
        <div class="container">
            <div class="row g-4"> 
                <div class="col-lg-5 col-md-6">
                    <a href="/" class="footer-logo-text">
                        <img src="{{ asset('Assert/logo.png') }}" alt="Logo">
                        SMART QUEUE
                    </a>
                    <p class="footer-desc">
                        Efficiently managing your time with our advanced digital queuing system. 
                        Experience seamless scheduling and reduced wait times.
                    </p>
                    <div class="social-container">
                        <div class="social-links">
                            <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                            <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                        </div>
                        <a href="/booking" class="btn btn-pill btn-book">
                            <i class="fas fa-calendar-check"></i> Book Appointment
                        </a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 offset-lg-1">
                    <h6>Quick Links</h6>
                    <div class="footer-links">
                        <a href="/">Home</a>
                        <a href="/about">About Us</a>
                        <a href="/services">Services</a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6>Contact Info</h6>
                    <p>
                        <i class="bi bi-geo-alt me-2"></i> Corporate Plaza, Gujranwala, Pakistan<br>
                        <i class="bi bi-envelope me-2"></i> support@smartqueue.com
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <div class="footer-bottom text-center">
        <div class="container">
            <span>© 2026 SMART QUEUE. All rights reserved.</span>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    
    <script>
        // ============================================
        // NAVBAR SCROLL EFFECT
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            const navbarWrapper = document.getElementById('navbarWrapper');
            
            function handleScroll() {
                if (window.scrollY > 20) {
                    navbarWrapper.classList.add('scrolled');
                } else {
                    navbarWrapper.classList.remove('scrolled');
                }
            }
            
            window.addEventListener('scroll', handleScroll, { passive: true });
            handleScroll(); // Initial check
        });

        // ============================================
        // TOGGLE FUNCTIONS
        // ============================================
        
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const isOpen = dropdown.classList.contains('active');
            closeAllDropdowns();
            if (!isOpen) {
                dropdown.classList.add('active');
                const btn = document.querySelector('.profile-btn');
                btn.setAttribute('aria-expanded', 'true');
            }
        }

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            const isOpen = dropdown.classList.contains('active');
            closeAllDropdowns();
            if (!isOpen) {
                dropdown.classList.add('active');
                const btn = document.querySelector('.notification-bell');
                btn.setAttribute('aria-expanded', 'true');
            }
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.profile-dropdown, .notification-dropdown').forEach(el => {
                el.classList.remove('active');
            });
            document.querySelectorAll('[aria-expanded="true"]').forEach(el => {
                el.setAttribute('aria-expanded', 'false');
            });
        }

        // Close dropdowns on outside click
        document.addEventListener('click', function(event) {
            const isClickInside = event.target.closest('.profile-wrapper') || 
                                 event.target.closest('.notification-wrapper') ||
                                 event.target.closest('.profile-dropdown') ||
                                 event.target.closest('.notification-dropdown');
            if (!isClickInside) {
                closeAllDropdowns();
            }
        });

        // Close dropdowns on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        // ============================================
        // ACTIVE NAV LINK
        // ============================================
        document.addEventListener("DOMContentLoaded", function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                const href = link.getAttribute('href');
                if (href === currentPath || (href !== '/' && currentPath.startsWith(href))) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });

        // ============================================
        // NOTIFICATION MARK ALL READ
        // ============================================
        function markAllRead() {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                item.classList.remove('unread');
            });
            const badge = document.getElementById('notificationBadge');
            if (badge) {
                badge.textContent = '0';
                badge.style.display = 'none';
            }
        }
    </script>
</body>
</html>