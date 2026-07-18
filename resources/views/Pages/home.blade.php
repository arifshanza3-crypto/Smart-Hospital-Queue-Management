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
            @php
                $tokenNumber = session('current_token');
                $userToken = null;
                if ($tokenNumber) {
                    $userToken = \App\Models\Token::where('token_number', $tokenNumber)
                                                  ->whereIn('status', ['waiting', 'calling', 'serving'])
                                                  ->first();
                }
                
                // All departments status
                $departments = ['OPD', 'Pharmacy', 'Radiology'];
                $allDepartments = [];
                foreach ($departments as $dept) {
                    $allDepartments[$dept] = [
                        'total' => \App\Models\Token::where('department', $dept)
                                                    ->whereIn('status', ['waiting', 'calling', 'serving'])
                                                    ->count(),
                        'serving' => \App\Models\Token::where('department', $dept)
                                                      ->where('status', 'serving')
                                                      ->first()
                    ];
                }
            @endphp

            {{-- ✅ Only show if user has active token --}}
            @if($userToken)
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

            {{-- All Departments Status --}}
            <div class="all-departments-status">
                <h4>Live Department Status</h4>
                <div class="departments-grid">
                    @foreach($allDepartments as $dept => $data)
                    <div class="dept-status-card">
                        <h5>{{ $dept }}</h5>
                        <div class="dept-stats">
                            <div class="dept-stat">
                                <span class="stat-label">In Queue</span>
                                <span class="stat-number">{{ $data['total'] }}</span>
                            </div>
                            <div class="dept-stat">
                                <span class="stat-label">Serving</span>
                                <span class="stat-number">{{ $data['serving'] ? $data['serving']->token_number : '--' }}</span>
                            </div>
                        </div>
                        <a href="/Status?dept={{ $dept }}" class="dept-view-link">View Queue →</a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/home.js') }}"></script>

@include("component/Get_token")
@include("component/about-special")
@include("component/Doctors_details")
@include("component/contact_form")

@endsection