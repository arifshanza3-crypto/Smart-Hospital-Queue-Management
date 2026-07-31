<link rel="stylesheet" href="{{ asset('css/Doctor_details.css') }}">

<section class="doctors-section">
    <div class="container">
        <div class="section-header text-center">
            <h2 class="section-title">Meet Our Expert Doctors</h2>
            <p class="section-description">Our team of highly skilled and compassionate doctors is dedicated to providing exceptional care and personalized treatment to our patients.</p>
        </div>
<<<<<<< HEAD
        
        {{-- Doctors Grid --}}
        <div class="row g-4" id="doctorsGrid">
            {{-- Doctors will be rendered by JavaScript --}}
        </div>

        <div class="view-all-container text-center">
            <a href="{{ route('doctors') }}" class="view-all-btn">
                View All Doctors <span>&rarr;</span>
            </a>
=======
        <div class="container">
            <div class="row g-4" id="doctorsGrid">
                {{-- ✅ Fallback: If JavaScript fails, show doctors via Blade --}}
                @if(isset($doctors) && $doctors->count() > 0)
                    @foreach($doctors as $doctor)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="dr-item-card" onclick="openDrModal({{ $doctor->id }})">
                                <div class="dr-card-img">
                                    @php
                                        $displayName = $doctor->name ?? $doctor->specialization;
                                        $statusColor = $doctor->status == 'active' ? '#10b981' : ($doctor->status == 'on_duty' ? '#0ea5e9' : '#ef4444');
                                        $statusText = ucfirst(str_replace('_', ' ', $doctor->status));
                                    @endphp
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($displayName) }}&background=00d4ff&color=fff&size=200" 
                                         alt="{{ $displayName }}" 
                                         onerror="this.src='https://ui-avatars.com/api/?name=D&background=00d4ff&color=fff&size=200'">
                                    <span class="status-badge" style="background:{{ $statusColor }}">{{ $statusText }}</span>
                                </div>
                                <div class="dr-card-body text-center">
                                    <h5 class="mb-1 text-white">Dr. {{ $doctor->name ?? $doctor->specialization }}</h5>
                                    <p class="small text-info">{{ $doctor->specialization }}</p>
                                    @if($doctor->qualification)
                                        <p class="small text-white-50">{{ $doctor->qualification }}</p>
                                    @endif
                                    @if($doctor->experience)
                                        <p class="small text-white-50">{{ $doctor->experience }} years experience</p>
                                    @endif
                                    @if($doctor->fee)
                                        <p class="small text-success">Fee: ${{ $doctor->fee }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="view-all-container text-center">
                <a href="/Doctors" class="view-all-btn">
                    View All Doctors <span>&rarr;</span>
                </a>
            </div>
>>>>>>> 2eb4cefef4397039eb351be26021243836677662
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

<<<<<<< HEAD
{{-- Pass doctors data to JavaScript --}}
<script>
    // ✅ Doctors data from backend
    window.doctorsData = @json($doctors ?? []);
    
    console.log('=== DOCTORS DETAILS PAGE ===');
    console.log('Total Doctors:', window.doctorsData.length);
    
    if (window.doctorsData.length > 0) {
        console.log('Sample Doctor:', window.doctorsData[0]);
        console.log('Doctor Name:', window.doctorsData[0].name);
        console.log('Specialization:', window.doctorsData[0].specialization);
    } else {
        console.log('⚠️ No doctors data received from controller!');
    }
=======
{{-- ✅ Pass doctors data to JavaScript for modal functionality --}}
<script>
    // ✅ Pass doctors data from Blade to JavaScript
    const doctorsData = @json($doctors ?? []);
    
    console.log('✅ Doctors in Doctors_details component:', doctorsData);
    console.log('✅ Total doctors:', doctorsData.length);
>>>>>>> 2eb4cefef4397039eb351be26021243836677662
</script>

<script src="{{ asset('js/Doctors_details.js') }}"></script>