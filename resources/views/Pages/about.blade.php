@extends('Layout.app')

@section('title', 'About Us - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="{{ asset('css/about-hero.css') }}" rel="stylesheet">

{{-- About Hero --}}
<section class="about-hero " 
         style="background: linear-gradient(135deg, rgba(10, 42, 58, 0.88) 0%, rgba(26, 122, 130, 0.75) 100%), 
                url('{{ asset('Assert/about-hero.png') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    <div class="hero-container">
        <div class="hero-content">
            <span class="badge-outline" style="color: #4fc3f7; border-color: #4fc3f7; display: inline-block; padding: 6px 18px; border: 2px solid #4fc3f7; border-radius: 50px; font-size: 0.75rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 16px;">
                 About Us
            </span>
            <h1 class="hero-title" style="color: #ffffff; font-size: 3.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 16px;">
                Smarter Healthcare <br><span style="color: #4fc3f7;">No More Waiting</span>
            </h1>
            <p class="hero-subtitle" style="color: rgba(255, 255, 255, 0.85); font-size: 1.05rem; max-width: 550px; line-height: 1.8; margin-bottom: 30px;">
                We help hospitals and clinics manage patient queues with smart technology. Our system makes sure patients don't wait too long and get the care they need quickly and easily.
            </p>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="mission-vision-section" style="padding: 70px 0; background: linear-gradient(135deg, #f0f7fa 0%, #e0ecf0 50%, #d4e4e8 100%);">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="mv-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;">

            {{-- Mission Card --}}
            <div class="mv-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 32px 28px; border-radius: 16px; text-align: center; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.06); border: 1px solid rgba(10, 42, 58, 0.06); transition: all 0.3s ease;">
                <div class="mv-icon" style="font-size: 38px; color: #1a7a82; margin-bottom: 12px;">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.2rem; font-weight: 700; margin-bottom: 10px;">Our Mission</h3>
                <p style="color: #3a5a6a; line-height: 1.8; font-size: 0.95rem;">
                    To make healthcare simple and smooth by reducing wait times, improving patient flow, and providing smart digital solutions that benefit both patients and hospitals.
                </p>
            </div>

            {{-- Vision Card --}}
            <div class="mv-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 32px 28px; border-radius: 16px; text-align: center; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.06); border: 1px solid rgba(10, 42, 58, 0.06); transition: all 0.3s ease;">
                <div class="mv-icon" style="font-size: 38px; color: #1a7a82; margin-bottom: 12px;">
                    <i class="fas fa-eye"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.2rem; font-weight: 700; margin-bottom: 10px;">Our Vision</h3>
                <p style="color: #3a5a6a; line-height: 1.8; font-size: 0.95rem;">
                    To become Pakistan's top healthcare technology platform, connecting patients with hospitals easily and making quality healthcare accessible to all.
                </p>
            </div>

            {{-- Values Card --}}
            <div class="mv-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 32px 28px; border-radius: 16px; text-align: center; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.06); border: 1px solid rgba(10, 42, 58, 0.06); transition: all 0.3s ease;">
                <div class="mv-icon" style="font-size: 38px; color: #1a7a82; margin-bottom: 12px;">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1.2rem; font-weight: 700; margin-bottom: 10px;">Our Values</h3>
                <p style="color: #3a5a6a; line-height: 1.8; font-size: 0.95rem;">
                    We value innovation, care, and honesty. Patients are always our first priority, and we use technology to build a better and healthier future.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- Why Choose Us --}}
<section class="why-choose-section" style="padding: 70px 0; background: #ffffff;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div class="section-header" style="text-align: center; margin-bottom: 40px;">
            <span class="section-badge" style="color: #1a7a82; font-weight: 700; font-size: 0.8rem; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 6px;">Why Choose Us</span>
            <h2 style="color: #0a2a3a; font-size: 2.2rem; font-weight: 700; margin-bottom: 8px;">Smart Solutions <span style="color: #1a7a82;">For Better Healthcare</span></h2>
            <p style="color: #3a5a6a; font-size: 1rem; max-width: 600px; margin: 0 auto;">Here's why hospitals and patients trust our queue management system</p>
        </div>

        <div class="features-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px;">

            {{-- 1. Smart Queue Management --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="feature-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-people-arrows"></i>
                </div>
                <h4 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Smart Queue Management</h4>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Our system organizes patient flow efficiently, reducing overcrowding and making sure everyone gets their turn fairly.</p>
            </div>

            {{-- 2. Real-Time Dashboard --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="feature-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-chart-simple"></i>
                </div>
                <h4 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Live Dashboard</h4>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Hospitals get a clear view of all patient queues, staff availability, and waiting times in real-time on one screen.</p>
            </div>

            {{-- 3. Staff Management --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="feature-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-user-clock"></i>
                </div>
                <h4 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Staff Management</h4>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Doctors and staff can track their schedules, patient lists, and workload easily through our simple and organized system.</p>
            </div>

            {{-- 4. Patient Updates --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="feature-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-bell"></i>
                </div>
                <h4 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Patient Notifications</h4>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Patients get real-time updates about their turn, estimated wait time, and when to come to the hospital — right on their phone.</p>
            </div>

            {{-- 5. Secure & Safe --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="feature-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-shield"></i>
                </div>
                <h4 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Secure & Safe</h4>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Patient data is kept private and secure. Our system is reliable and works smoothly so hospitals can trust it every day.</p>
            </div>

            {{-- 6. Simple & Easy --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.88); backdrop-filter: blur(10px); padding: 26px 22px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="feature-icon" style="font-size: 36px; color: #1a7a82; margin-bottom: 10px;">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h4 style="color: #0a2a3a; font-size: 1.05rem; font-weight: 700; margin-bottom: 8px;">Simple & Easy to Use</h4>
                <p style="color: #3a5a6a; line-height: 1.7; font-size: 0.9rem;">Our platform is designed to be user-friendly for both hospital staff and patients. No complicated steps — just easy and smooth experience.</p>
            </div>

        </div>
    </div>
</section>

{{-- Components --}}
@include('Component.features')
@include('Component.process')
{{--@include('Component.about-special')--}}
@include('Component.contact_form')

<script>
// Statistics Counter Animation
document.addEventListener('DOMContentLoaded', function() {
    const statNumbers = document.querySelectorAll('.stat-number');
    
    const animateCounter = (element) => {
        const text = element.textContent;
        const hasPlus = text.includes('+');
        const hasPercent = text.includes('%');
        const targetText = text.replace(/[^0-9]/g, '');
        const target = parseInt(targetText) || 0;
        let current = 0;
        const increment = Math.ceil(target / 80);
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            let display = current.toLocaleString();
            if (hasPlus) display += '+';
            if (hasPercent) display += '%';
            element.textContent = display;
        }, 30);
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    statNumbers.forEach(num => observer.observe(num));
});
</script>

@endsection