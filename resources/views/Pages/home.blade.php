@extends('Layout.app')

@section('content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">

<section class="hero-slider-section">
    <div class="hero-slider">
        <div class="hero-slide active">
            <div class="hero-content-container">
                <div class="hero-left">
                    <span class="hero-tag">Smart Solutions</span>
                    <h1 class="hero-title">Medical Hospital <br> & Healthcare</h1>
                    <p class="hero-description">Efficiently managing your time with our advanced digital queuing system designed for modern healthcare facilities.</p>
                    <div class="hero-btn-group">
                        <a href="#" class="btn-secondary-hero">Our Services</a>
                    </div>
                </div>
                <div class="hero-right">
                    <img src="https://images.unsplash.com/photo-1551076805-e1869033e561?auto=format&fit=crop&q=80&w=1000" alt="Healthcare Professional">
                </div>
            </div>
        </div>

        <div class="hero-slide">
            <div class="hero-content-container">
                <div class="hero-left">
                    <span class="hero-tag">Advanced Technology</span>
                    <h1 class="hero-title">Expert Doctors <br> & Expert Care</h1>
                    <p class="hero-description">Experience the next generation of patient care with real-time updates and seamless queue management.</p>
                    <div class="hero-btn-group">
                        <a href="#" class="btn-secondary-hero">Learn More</a>
                    </div>
                </div>
                <div class="hero-right">
                    <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?auto=format&fit=crop&q=80&w=1000" alt="Medical Technology">
                </div>
            </div>
        </div>
    </div>

    <div class="hero-bottom-bar">
        <div class="ticker-wrapper">
            <div class="ticker-content">
                <span>WELLNESS</span> <span>•</span> <span>THERAPIST</span> <span>•</span> <span>NURSING</span> <span>•</span> 
                <span>PATIENT CARE</span> <span>•</span> <span>HEALTH CENTER</span> <span>•</span> <span>EMERGENCY</span> <span>•</span> 
                <span>MEDICAL SERVICES</span> <span>•</span>
                <span>WELLNESS</span> <span>•</span> <span>THERAPIST</span> <span>•</span> <span>NURSING</span> <span>•</span> 
                <span>PATIENT CARE</span> <span>•</span> <span>HEALTH CENTER</span> <span>•</span> <span>EMERGENCY</span> <span>•</span> 
                <span>MEDICAL SERVICES</span> <span>•</span>
            </div>
        </div>
    </div>
</section>

{{-- ✅ TOKEN STATUS SECTION --}}
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
                                <span class="serving-dept">{{ $token->department }}</span>
                                <span class="serving-patient">{{ $token->patient_name ?? 'N/A' }}</span>
                            </div>
                            <div class="serving-status-badge">SERVING</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ✅ User's Own Active Token --}}
            @if(isset($userToken) && $userToken)
                <div class="user-token-card">
                    <div class="token-card-header">
                        <span class="token-icon">✅</span>
                        <h3>Your Active Token</h3>
                    </div>
                    <div class="token-card-body">
                        <div class="token-number-large">{{ $userToken->token_number }}</div>
                        <div class="token-details">
                            <div class="token-detail-item">
                                <span class="label">Status</span>
                                <span class="value status-{{ $userToken->status }}">{{ strtoupper($userToken->status) }}</span>
                            </div>
                            <div class="token-detail-item">
                                <span class="label">Department</span>
                                <span class="value">{{ $userToken->department }}</span>
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
            @endif

            {{-- ✅ DOCTORS SECTION - DATABASE SE --}}
            <div class="doctors-home-section">
                <div class="section-header text-center">
                    <h2 class="section-title">🏥 Meet Our Expert Doctors</h2>
                    <p class="section-subtitle">Our team of highly skilled and compassionate doctors is dedicated to providing exceptional care and personalized treatment to our patients.</p>
                </div>
                
                <div class="doctors-home-grid">
                    @if(isset($doctors) && $doctors->count() > 0)
                        @foreach($doctors as $doctor)
                            <div class="doctor-home-card">
                                <div class="doctor-home-avatar">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <h5 class="doctor-home-name">Dr. {{ $doctor->name }}</h5>
                                <p class="doctor-home-specialty">{{ $doctor->specialization }}</p>
                                @if($doctor->qualification)
                                    <p class="doctor-home-qualification">{{ $doctor->qualification }}</p>
                                @endif
                                <span class="doctor-home-status status-{{ $doctor->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $doctor->status)) }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center py-4">
                            <p style="color: rgba(255,255,255,0.5);">No doctors available at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

<script src="{{ asset('js/home.js') }}"></script>

@include("component/Get_token")
@include("component/about-special")
{{-- ❌ DOCTORS_DETAILS REMOVED --}}
@include("component/contact_form")

<style>
    /* ============================================ */
    /* DOCTORS HOME SECTION STYLES                 */
    /* ============================================ */
    .doctors-home-section {
        margin-top: 40px;
        padding: 40px 0;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .section-header {
        margin-bottom: 30px;
    }

    .section-title {
        color: #ffffff;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .section-subtitle {
        color: rgba(255, 255, 255, 0.5);
        font-size: 14px;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .doctors-home-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 20px;
    }

    .doctor-home-card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 14px;
        padding: 24px 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .doctor-home-card:hover {
        transform: translateY(-6px);
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(0, 212, 255, 0.2);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    }

    .doctor-home-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0b2e33, #1a7a82);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        color: white;
        font-size: 28px;
    }

    .doctor-home-name {
        color: #ffffff;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .doctor-home-specialty {
        color: #00d4ff;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .doctor-home-qualification {
        color: rgba(255, 255, 255, 0.4);
        font-size: 12px;
        margin-bottom: 10px;
    }

    .doctor-home-status {
        display: inline-block;
        padding: 4px 16px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .doctor-home-status.status-active {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .doctor-home-status.status-on_duty {
        background: rgba(14, 165, 233, 0.15);
        color: #0ea5e9;
        border: 1px solid rgba(14, 165, 233, 0.2);
    }

    .doctor-home-status.status-inactive {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .doctors-home-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }

        .doctor-home-card {
            padding: 16px;
        }

        .doctor-home-avatar {
            width: 56px;
            height: 56px;
            font-size: 22px;
        }

        .section-title {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .doctors-home-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .doctor-home-card {
            padding: 12px;
        }

        .doctor-home-name {
            font-size: 13px;
        }

        .doctor-home-avatar {
            width: 44px;
            height: 44px;
            font-size: 18px;
        }
    }
</style>

@endsection