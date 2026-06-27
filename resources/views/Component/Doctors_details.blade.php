<link rel="stylesheet" href="{{ asset('css/Doctor_details.css') }}">
<section class="doctors-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Meet Our Expert Doctors</h2>
            <p class="section-description">Our team of highly skilled and compassionate doctors is dedicated to providing exceptional care and personalized treatment to our patients.</p>
        </div>
        <div class="container">
        <div class="row g-4" id="doctorsGrid">
            </div>

        <div class="view-all-container text-center">
            <a href="/Doctors" class="view-all-btn">
                View All Doctors <span>&rarr;</span>
            </a>
        </div>
    </div>
</div>

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
                    <button class="join-btn" onclick="window.location.href='/Token_form'">Join Queue</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="{{ asset('js/Doctors_details.js') }}"></script>

