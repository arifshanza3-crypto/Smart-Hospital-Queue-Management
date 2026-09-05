@extends('Layout.app')

@section('content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">

{{-- ✅ HERO SLIDER SECTION --}}
<section class="hero-slider-section">
    <div class="hero-slider">
        <div class="hero-slide active">
            <div class="hero-content-container">
                <div class="hero-left">
                    <span class="hero-tag">Smart Solutions</span>
                    <h1 class="hero-title">Smart Queue <br>Management System</h1>
                    <p class="hero-description">Experience smarter healthcare with our real-time queue management system – reducing wait times, improving patient care, and optimizing hospital workflow.</p>
                    <div class="hero-btn-group">
                        <a href="{{ route('services') }}" class="btn-secondary-hero">Our Services</a>
                    </div>
                </div>
                <div class="hero-right">
                    <img src="https://images.unsplash.com/photo-1587351021759-3e566b6af7cc?auto=format&fit=crop&q=80&w=1000" alt="Smart Queue Management">
                </div>
            </div>
        </div>

        <!-- <div class="hero-slide">
            <div class="hero-content-container">
                <div class="hero-left">
                    <span class="hero-tag">Advanced Technology</span>
                    <h1 class="hero-title">Expert Doctors <br>& Expert Care</h1>
                    <p class="hero-description">Experience the next generation of patient care with real-time updates and seamless queue management.</p>
                    <!-- <div class="hero-btn-group">
                        <a href="#" class="btn-secondary-hero">Learn More</a>
                    </div> -->
                <!-- </div>
                <div class="hero-right">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=1000" alt="Medical Technology">
                </div>
            </div>
        </div>
    </div> --> -->

    {{-- ✅ TICKER BAR - Smart Queue Benefits --}}
    <div class="hero-bottom-bar">
        <div class="ticker-wrapper">
            <div class="ticker-content">
                <span>⚡ Real-Time Queue Updates</span> <span>•</span> 
                <span>⏱️ Reduce Wait Times</span> <span>•</span> 
                <span>📱 Instant Notifications</span> <span>•</span> 
                <span>🎯 Smart Patient Flow</span> <span>•</span> 
                <span>🔄 Seamless Experience</span> <span>•</span> 
                <span>📊 Live Analytics</span> <span>•</span> 
                <span>🏥 Efficient Care</span> <span>•</span>
                <span>⚡ Real-Time Queue Updates</span> <span>•</span> 
                <span>⏱️ Reduce Wait Times</span> <span>•</span> 
                <span>📱 Instant Notifications</span> <span>•</span> 
                <span>🎯 Smart Patient Flow</span> <span>•</span> 
                <span>🔄 Seamless Experience</span> <span>•</span> 
                <span>📊 Live Analytics</span> <span>•</span> 
                <span>🏥 Efficient Care</span> <span>•</span>
            </div>
        </div>
    </div>
</section>

<!-- {{-- ✅ TOKEN STATUS SECTION --}}
<section class="token-status-section">
    <div class="container">
        <div class="token-status-wrapper">
            
            {{-- ✅ All Active Serving Tokens --}}
            @if(isset($servingTokens) && $servingTokens->count() > 0)
                <div class="serving-tokens-section">
                    <h4 class="section-title">🟢 Currently Being Served</h4>
                    <div class="serving-tokens-grid">
                        @foreach($servingTokens as $token)
                        <div class="serving-token-card">
                            <div class="serving-token-number">{{ $token->token_number }}</div>
                            <div class="serving-token-details">
                                <span class="serving-patient">{{ $token->patient_name ?? 'N/A' }}</span>
                            </div>
                            <div class="serving-status-badge">SERVING</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif -->

            <!-- {{-- ✅ User's Own Active Token --}}
            @if(isset($userToken) && $userToken)
                <div class="user-token-card">
                    <div class="token-card-header">
                        <span class="token-icon">✅</span>
                        <h3>Your Active Token</h3>
                    </div> -->
                    <!-- <div class="token-card-body">
                        <div class="token-number-large">{{ $userToken->token_number }}</div>
                        <div class="token-details">
                            <div class="token-detail-item">
                                <span class="label">Status</span>
                                <span class="value status-{{ $userToken->status }}">{{ strtoupper($userToken->status) }}</span>
                            </div>
                            <div class="token-detail-item">
                                <span class="label">Position</span>
                                <span class="value">#{{ $userToken->position }}</span>
                            </div>
                            <div class="token-detail-item">
                                <span class="label">Est. Wait</span>
                                <span class="value">{{ $userToken->estimated_time }} min</span>
                            </div>
                        </div>
                    </div>
                    <div class="token-card-footer">
                        <a href="{{ route('status.page', ['token' => $userToken->token_number]) }}" class="btn-view-status">View Full Status →</a>
                    </div>
                </div>
            @endif -->
<!-- 
        </div>
    </div>
</section> -->

{{-- ✅ COMPONENTS --}}
@include("component/Doctors_details")

{{-- @include("component/about-special") --}}

@include("component/contact_form")

<script src="{{ asset('js/home.js') }}"></script>
@endsection