@extends('Layout.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="{{ asset('css/about-hero.css') }}" rel="stylesheet">


    <section class="about-hero hero-with-bg" 
             style="background: linear-gradient(rgba(26, 58, 58, 0.85), rgba(62, 134, 134, 0.75)), 
                    url('{{ asset('Assert/about-hero.png') }}');">
        
        <div class="hero-container">
            <div class="hero-content">
                <span class="badge-outline">Our Mission</span>
                <h1 class="hero-title">Revolutionizing the <br><span class="text-accent">Patient Experience</span></h1>
                <p class="hero-subtitle">
                    We are bridging the gap between healthcare providers and patients through AI-driven queue management. No more long lines—just smart, seamless care.
                </p>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">0%</span>
                        <span class="stat-label">Waiting Stress</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Token Access</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">Real-time</span>
                        <span class="stat-label">Updates</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('Component.features')
    @include('Component.process')
    @include('Component.about-special')
    @include('Component.contact_form')


@endsection