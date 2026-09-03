@extends('Layout.app')

@section('title', 'Our Services - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="{{ asset('css/services.css') }}" rel="stylesheet">

{{-- Services Hero --}}
<section class="services-hero" style="background: linear-gradient(135deg, rgba(10, 42, 58, 0.92) 0%, rgba(26, 122, 130, 0.80) 100%), url('{{ asset('Assert/services-hero.png') }}'); background-size: cover; background-position: center; min-height: 60vh; display: flex; align-items: center; padding: 60px 20px;">
    <div class="s-hero-container" style="max-width: 900px; margin: 0 auto; text-align: center;">
        <span class="s-badge" style="color: #4fc3f7; font-weight: 700; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; display: inline-block; padding: 6px 18px; border: 2px solid #4fc3f7; border-radius: 50px; margin-bottom: 16px;">🌟 Our Expertise</span>
        <h1 style="color: #ffffff; font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 16px;">
            Comprehensive <br><span style="color: #4fc3f7;">Smart Solutions</span>
        </h1>
        <p class="s-subtitle" style="color: rgba(255, 255, 255, 0.85); font-size: 1.05rem; max-width: 600px; margin: 0 auto; line-height: 1.8;">
            Optimizing the journey between patient arrival and medical care with high-precision digital queueing systems.
        </p>
    </div>
</section>

{{-- Services Grid --}}
<section class="services-grid-section" style="padding: 70px 0; background: linear-gradient(135deg, #f0f7fa 0%, #e0ecf0 50%, #d4e4e8 100%);">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="section-header" style="text-align: center; margin-bottom: 40px;">
            <span class="section-badge" style="color: #1a7a82; font-weight: 700; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 6px;">What We Offer</span>
            <h2 style="color: #0a2a3a; font-size: 2.2rem; font-weight: 700; margin-bottom: 8px;">Our <span style="color: #1a7a82;">Healthcare Services</span></h2>
            <p style="color: #3a5a6a; font-size: 1rem; max-width: 600px; margin: 0 auto;">Comprehensive solutions designed to improve patient experience and hospital efficiency</p>
        </div>

        <div class="services-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px;">

            {{-- 1. Expert Doctors --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Expert Doctors</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Consult with highly qualified and experienced medical professionals for personalized care.</p>
            </div>

            {{-- 2. Smart Queue --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Smart Queue</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Real-time queue tracking with minimal waiting time and instant notifications.</p>
            </div>

            {{-- 3. Emergency Care --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Emergency Care</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">24/7 emergency services with immediate medical attention and rapid response.</p>
            </div>

            {{-- 4. Nursing Care --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-hospital-user"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Nursing Care</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Professional nursing staff dedicated to patient recovery and comfort.</p>
            </div>

            {{-- 5. Pharmacy --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-pills"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Pharmacy</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">On-site pharmacy with all essential medicines and prescriptions available.</p>
            </div>

            {{-- 6. Radiology --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-x-ray"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Radiology</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Advanced diagnostic imaging services for accurate and timely diagnosis.</p>
            </div>

            {{-- 7. Neurology --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-brain"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Neurology</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Specialized care for neurological disorders and complex conditions.</p>
            </div>

            {{-- 8. Pediatrics --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-child"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Pediatrics</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Comprehensive healthcare for children of all ages with compassionate care.</p>
            </div>

            {{-- 9. Dentistry --}}
            <div class="service-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="service-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-tooth"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Dentistry</h3>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Complete dental care services including routine checkups and treatments.</p>
            </div>

        </div>
    </div>
</section>

{{-- Components --}}
@include('Component.features')
@include('Component.process')
@include('Component.Doctors_details')
@include('Component.contact_form')

@endsection