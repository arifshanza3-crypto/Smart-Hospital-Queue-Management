@extends('Layout.app')

@section('content')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
	<section class="hero-slider-section" >
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
<script src="{{ asset('js/home.js') }}"></script>

@include("component/Get_token")
@include("component/about-special")
@include("component/Doctors_details")
@include("component/contact_form")



@endsection
