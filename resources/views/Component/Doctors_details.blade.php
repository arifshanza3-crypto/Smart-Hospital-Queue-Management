<link rel="stylesheet" href="{{ asset('css/Doctor_details.css') }}">

<section class="doctors-section no-bottom-gap">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Meet Our Expert Doctors</h2>
            <p class="section-description">Our team of highly skilled and compassionate doctors is dedicated to providing exceptional care and personalized treatment to our patients.</p>
        </div>
        
        {{-- Doctors Grid --}}
        <div class="row g-4" id="doctorsGrid">
            {{-- Doctors will be rendered by JavaScript --}}
        </div>

        <div class="view-all-container text-center">
            <a href="{{ route('doctors') }}" class="view-all-btn">
                View All Doctors <span>&rarr;</span>
            </a>
        </div>
    </div>

    {{-- Doctor Detail Modal --}}
    <div id="customDrModal" class="dr-popup-overlay">
        <div class="dr-popup-content">
            <span class="dr-popup-close" onclick="closeDoctorModal()">&times;</span>
            <div class="dr-popup-flex">
                <div class="dr-popup-image-container">
                    <div id="modalLoader" class="dr-loader">
                        <div class="spinner"></div>
                    </div>
                    <img id="modalImg" src="" alt="Doctor">
                </div>
                <div class="dr-popup-details">
                    <h2 class="pop-title">Doctor Details</h2>
                    <div class="pop-info">
                        <p><strong>Name:</strong> <span id="modalName">-</span></p>
                        <p><strong>Education:</strong> <span id="modalEdu">-</span></p>
                        <p><strong>Specialty:</strong> <span id="modalProf">-</span></p>
                        <p><strong>Shift:</strong> <span id="modalTime">-</span></p>
                        <p><strong>Experience:</strong> <span id="modalExperience">-</span></p>
                        <p><strong>Rating:</strong> <span id="modalRating">-</span></p>
                        <p><strong>Status:</strong> <span id="modalStatus">-</span></p>
                    </div>
                    <div class="modal-actions">
                        <button class="join-btn" onclick="joinQueue()">Join Queue</button>
                        <button class="appointment-btn" onclick="bookAppointment()">Book Appointment</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Pass doctors data to JavaScript --}}
<script>
    window.doctorsData = @json($doctors ?? []);
</script>
<script src="{{ asset('js/Doctors_details.js') }}"></script>