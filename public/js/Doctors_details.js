/**
 * DOCTORS DETAILS - JavaScript
 * Handles displaying doctors and modal functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Doctors Details JS loaded');
    
    const doctorsGrid = document.getElementById('doctorsGrid');
    
    // Get doctors data from window
    const doctors = window.doctorsData || [];
    
    console.log('✅ Doctors data count:', doctors.length);
    
    // ============================================
    // RENDER DOCTORS
    // ============================================
    function renderDoctors() {
        if (!doctorsGrid) {
            console.error('❌ doctorsGrid element not found!');
            return;
        }
        
        if (!doctors || doctors.length === 0) {
            doctorsGrid.innerHTML = `
                <div class="col-12 text-center">
                    <div style="padding: 60px 0; color: rgba(255,255,255,0.7);">
                        <i class="fas fa-user-md" style="font-size: 48px; color: rgba(255,255,255,0.2);"></i>
                        <h3 style="color: #fff; margin-top: 16px;">No Doctors Available</h3>
                        <p style="color: rgba(255,255,255,0.5);">Please add doctors from the admin panel.</p>
                    </div>
                </div>
            `;
            return;
        }
        
        let html = '';
        
        doctors.forEach(function(doctor, index) {
            const statusClass = (doctor.status || 'available').toLowerCase();
            const displayName = doctor.name || 'Dr. Unknown';
            const specialty = doctor.specialization || 'General Practitioner';
            const experience = doctor.experience || '5+ Years';
            const rating = doctor.rating || '4.5';
            const imageUrl = doctor.image || `https://ui-avatars.com/api/?name=${encodeURIComponent(displayName)}&background=00d4ff&color=fff&size=300`;
            
            html += `
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="doctor-card" data-doctor-id="${doctor.id || index}">
                        <div class="doctor-card-inner">
                            <div class="doctor-image-wrapper">
                                <img 
                                    src="${imageUrl}" 
                                    alt="${displayName}" 
                                    class="doctor-image"
                                    loading="lazy"
                                    onerror="this.src='https://ui-avatars.com/api/?name=D&background=00d4ff&color=fff&size=300'"
                                >
                                <span class="doctor-status ${statusClass}">
                                    ${doctor.status || 'Available'}
                                </span>
                            </div>
                            <div class="doctor-info">
                                <h3 class="doctor-name">${displayName}</h3>
                                <p class="doctor-specialty">${specialty}</p>
                                <div class="doctor-meta">
                                    <span class="doctor-experience">
                                        <i class="fas fa-briefcase"></i>
                                        ${experience}
                                    </span>
                                    <span class="doctor-rating">
                                        <i class="fas fa-star"></i>
                                        ${rating}
                                    </span>
                                </div>
                                <button class="view-details-btn" onclick="openDoctorModal('${doctor.id || index}')">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        doctorsGrid.innerHTML = html;
        console.log('✅ Rendered', doctors.length, 'doctors');
    }
    
    // Initial render
    renderDoctors();
});

// ============================================
// OPEN DOCTOR MODAL
// ============================================
function openDoctorModal(doctorId) {
    console.log('🔍 Opening modal for doctor ID:', doctorId);
    
    const doctors = window.doctorsData || [];
    const doctor = doctors.find(function(d) {
        return d.id == doctorId;
    });
    
    if (!doctor) {
        console.error('❌ Doctor not found with ID:', doctorId);
        alert('Doctor details not found.');
        return;
    }
    
    console.log('✅ Found doctor:', doctor.name);
    
    // Get modal elements
    const modal = document.getElementById('customDrModal');
    const loader = document.getElementById('modalLoader');
    const modalImg = document.getElementById('modalImg');
    
    if (!modal) {
        console.error('❌ Modal element not found!');
        return;
    }
    
    // Show modal and loader
    modal.style.display = 'flex';
    loader.style.display = 'flex';
    modalImg.style.display = 'none';
    
    // Populate doctor details
    document.getElementById('modalName').textContent = doctor.name || 'Dr. Unknown';
    document.getElementById('modalEdu').textContent = doctor.education || doctor.qualification || 'MBBS, MD';
    document.getElementById('modalProf').textContent = doctor.specialization || 'General Practitioner';
    document.getElementById('modalTime').textContent = doctor.shift || '09:00 AM - 05:00 PM';
    document.getElementById('modalExperience').textContent = doctor.experience || '5+ Years';
    document.getElementById('modalRating').textContent = (doctor.rating || '4.5') + ' ⭐';
    
    // Set status with color
    const statusSpan = document.getElementById('modalStatus');
    const status = doctor.status || 'available';
    const statusDisplay = status.charAt(0).toUpperCase() + status.slice(1);
    statusSpan.textContent = statusDisplay;
    statusSpan.className = 'status-badge ' + status.toLowerCase();
    
    // Set image
    const imageUrl = doctor.image || `https://ui-avatars.com/api/?name=${encodeURIComponent(doctor.name || 'Doctor')}&background=00d4ff&color=fff&size=400`;
    modalImg.src = imageUrl;
    modalImg.onload = function() {
        loader.style.display = 'none';
        modalImg.style.display = 'block';
    };
    modalImg.onerror = function() {
        modalImg.src = 'https://ui-avatars.com/api/?name=D&background=00d4ff&color=fff&size=400';
        loader.style.display = 'none';
        modalImg.style.display = 'block';
    };
    
    // If image loads instantly
    if (modalImg.complete && modalImg.naturalHeight !== 0) {
        loader.style.display = 'none';
        modalImg.style.display = 'block';
    }
}

// ============================================
// CLOSE DOCTOR MODAL
// ============================================
function closeDoctorModal() {
    const modal = document.getElementById('customDrModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// ============================================
// JOIN QUEUE
// ============================================
function joinQueue() {
    const doctorName = document.getElementById('modalName').textContent;
    const specialty = document.getElementById('modalProf').textContent;
    
    if (doctorName && doctorName !== '-') {
        window.location.href = `/Token_form?doctor=${encodeURIComponent(doctorName)}&specialty=${encodeURIComponent(specialty)}`;
    } else {
        window.location.href = '/Token_form';
    }
}

// ============================================
// BOOK APPOINTMENT
// ============================================
function bookAppointment() {
    const doctorName = document.getElementById('modalName').textContent;
    const specialty = document.getElementById('modalProf').textContent;
    
    if (doctorName && doctorName !== '-') {
        window.location.href = `/appointment?doctor=${encodeURIComponent(doctorName)}&specialty=${encodeURIComponent(specialty)}`;
    } else {
        window.location.href = '/appointment';
    }
}

// ============================================
// CLOSE MODAL ON OUTSIDE CLICK OR ESCAPE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('customDrModal');
    
    if (modal) {
        // Close on overlay click
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDoctorModal();
            }
        });
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDoctorModal();
            }
        });
    }
});