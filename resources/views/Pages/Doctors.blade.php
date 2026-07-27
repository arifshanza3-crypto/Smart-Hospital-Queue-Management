@extends('layout.app')

@section('title', 'Specialists - SMART QUEUE')

@section('content')
<link rel="stylesheet" href="{{ asset('css/Doctors.css') }}">

<div class="doctors-viewport">
    <div class="mesh-bg"></div>

    <section class="hero-slider-section">
        <div class="slider-container" id="doctorSlider">
            <div class="slide active">
                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?q=80&w=1200" class="slide-img">
                <div class="slide-overlay">
                    <div class="dr-info-card">
                        <span class="exp-badge">Premium Care</span>
                        <h1 class="dr-name">Expert Specialists</h1>
                        <p class="dr-intro">Our team of world-class doctors is here to provide exceptional care.</p>
                    </div>
                </div>
            </div>
            <div class="slide">
                <img src="https://images.unsplash.com/photo-1594824476967-48c8b964273f?q=80&w=1200" class="slide-img">
                <div class="slide-overlay">
                    <div class="dr-info-card">
                        <span class="exp-badge">Advanced Tech</span>
                        <h1 class="dr-name">Smart Solutions</h1>
                        <p class="dr-intro">Experience the future of healthcare queue management.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="search-section">
        <div class="container">
            <div class="search-wrapper">
                <span class="search-icon">🔍</span>
                <input type="text" id="drSearchInput" placeholder="Search by name or specialization (e.g. Dentist)...">
            </div>
        </div>
    </section>

    <section class="doctors-section">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Meet Our Expert Doctors</h2>
                <p id="resultCount" class="text-white-50">Loading specialists...</p>
            </div>
            <div class="row g-4 justify-content-center" id="doctorsGrid">
                {{-- Doctors will be rendered by JavaScript --}}
            </div>
        </div>
    </section>

    <div id="customDrModal" class="dr-popup-overlay">
        <div class="dr-popup-content">
            <span class="dr-popup-close">&times;</span>
            <div class="dr-popup-flex">
                <div class="dr-popup-image-container">
                    <div id="modalLoader" class="dr-loader"></div>
                    <img id="modalImg" src="" alt="Doctor">
                </div>
                <div class="dr-popup-details">
                    <h2 class="pop-title">Doctor Details</h2>
                    <div class="pop-info">
                        <p><strong>Name:</strong> <span id="modalName"></span></p>
                        <p><strong>Education:</strong> <span id="modalEdu"></span></p>
                        <p><strong>Specialty:</strong> <span id="modalProf"></span></p>
                        <p><strong>Shift:</strong> <span id="modalTime"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    </div>
                    <button class="join-btn" id="generateQueueBtn">Join Queue</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ FIXED: Pass doctors data from database to JavaScript --}}
<script>
    // ✅ Define doctorsData in global scope
    window.doctorsData = @json($doctors);
    
    // ✅ Debug - Check data in console
    console.log('=== DOCTORS DATA FROM BLADE ===');
    console.log('Total Doctors:', window.doctorsData.length);
    
    if (window.doctorsData.length > 0) {
        console.log('First doctor:', window.doctorsData[0]);
        console.log('Doctor Name:', window.doctorsData[0].name);
        console.log('Specialization:', window.doctorsData[0].specialization);
        console.log('Name exists?', window.doctorsData[0].name ? 'YES ✅' : 'NO ❌');
    } else {
        console.log('⚠️ No doctors found in database!');
    }
    console.log('================================');
</script>

<script src="{{ asset('js/Doctors.js') }}"></script>
@endsection