@extends('Layout.app')

@section('title', 'About Us - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="{{ asset('css/about-hero.css') }}" rel="stylesheet">

{{-- About Hero --}}
<section class="about-hero hero-with-bg" 
         style="background: linear-gradient(rgba(7, 26, 28, 0.88), rgba(11, 46, 51, 0.82)), 
                url('{{ asset('Assert/about-hero.png') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    <div class="hero-container">
        <div class="hero-content">
            <span class="badge-outline">🌟 About Us</span>
            <h1 class="hero-title">Redefining Healthcare <br><span class="text-accent">With Smart Technology</span></h1>
            <p class="hero-subtitle">
                We are revolutionizing the healthcare experience by combining cutting-edge technology 
                with compassionate care. Our smart queue management system ensures every patient 
                receives timely, efficient, and personalized attention.
            </p>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number" data-count="50000">0+</span>
                    <span class="stat-label">Patients Served</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="98">0%</span>
                    <span class="stat-label">Satisfaction Rate</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="1500">0+</span>
                    <span class="stat-label">Partner Hospitals</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="200">0+</span>
                    <span class="stat-label">Expert Doctors</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Mission & Vision --}}
<section class="mission-vision-section">
    <div class="container">
        <div class="mv-grid">
            <div class="mv-card">
                <div class="mv-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h3>Our Mission</h3>
                <p>To transform healthcare delivery by eliminating unnecessary waiting times and providing seamless patient experiences through intelligent queue management and digital innovation.</p>
            </div>
            <div class="mv-card">
                <div class="mv-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h3>Our Vision</h3>
                <p>To become the global standard for healthcare queue management, creating a world where every patient's time is valued and every medical facility operates with peak efficiency.</p>
            </div>
            <div class="mv-card">
                <div class="mv-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Our Values</h3>
                <p>Innovation, compassion, integrity, and excellence drive everything we do. We believe in putting patients first and leveraging technology to enhance human connections.</p>
            </div>
        </div>
    </div>
</section>

{{-- Why Choose Us --}}
<section class="why-choose-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">Why Choose Us</span>
            <h2>Empowering Healthcare <span class="text-accent">Through Innovation</span></h2>
            <p>Discover how our smart solutions are transforming the healthcare landscape</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <h4>AI-Powered Predictions</h4>
                <p>Advanced algorithms predict patient wait times with 95% accuracy, allowing for better resource allocation and patient satisfaction.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h4>Real-Time Analytics</h4>
                <p>Live dashboard monitoring provides instant insights into patient flow, staff performance, and operational efficiency.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h4>Staff Optimization</h4>
                <p>Intelligent scheduling and workload management ensure medical staff can focus on what matters most - patient care.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h4>Seamless Experience</h4>
                <p>Patients receive real-time updates, estimated wait times, and notifications directly on their mobile devices.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h4>Secure & Reliable</h4>
                <p>Enterprise-grade security ensures patient data is protected while maintaining 99.9% system availability.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h4>Patient-Centric Care</h4>
                <p>Every feature is designed with the patient in mind, reducing anxiety and improving overall healthcare experience.</p>
            </div>
        </div>
    </div>
</section>

@include('Component.features')
@include('Component.process')
@include('Component.about-special')
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