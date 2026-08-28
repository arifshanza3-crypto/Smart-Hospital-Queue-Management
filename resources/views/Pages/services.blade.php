@extends('Layout.app')

@section('title', 'Our Services - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="{{ asset('css/services.css') }}" rel="stylesheet">

{{-- Services Hero --}}
<section class="services-hero">
    <div class="s-hero-container">
        <span class="s-badge">Our Expertise</span>
        <h1>Comprehensive <br><span class="text-accent">Smart Solutions</span></h1>
        <p class="s-subtitle">
            Optimizing the journey between patient arrival and medical care with high-precision digital queueing systems.
        </p>
    </div>
</section>

{{-- Services Grid --}}
<section class="services-grid-section">
    <div class="container">
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3>Expert Doctors</h3>
                <p>Consult with highly qualified and experienced medical professionals for personalized care.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Smart Queue</h3>
                <p>Real-time queue tracking with minimal waiting time and instant notifications.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h3>Emergency Care</h3>
                <p>24/7 emergency services with immediate medical attention and rapid response.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-hospital"></i>
                </div>
                <h3>Nursing Care</h3>
                <p>Professional nursing staff dedicated to patient recovery and comfort.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <h3>Pharmacy</h3>
                <p>On-site pharmacy with all essential medicines and prescriptions available.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-x-ray"></i>
                </div>
                <h3>Radiology</h3>
                <p>Advanced diagnostic imaging services for accurate and timely diagnosis.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3>Neurology</h3>
                <p>Specialized care for neurological disorders and complex conditions.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-child"></i>
                </div>
                <h3>Pediatrics</h3>
                <p>Comprehensive healthcare for children of all ages with compassionate care.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-tooth"></i>
                </div>
                <h3>Dentistry</h3>
                <p>Complete dental care services including routine checkups and treatments.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Ophthalmology</h3>
                <p>Expert eye care services with advanced diagnostic and treatment options.</p>
            </div>
        </div>
    </div>
</section>

@include('Component.features')
@include('Component.process')
@include('Component.Doctors_details')
@include('Component.contact_form')

@endsection