document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Array Data
    const doctors = [
        { id: 1, name: "Dr. Farhan Ali", edu: "MBBS", prof: "Dentist", time: "09:00 AM - 02:00 PM", status: "Active", img: "https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=800" },
        { id: 2, name: "Dr. Sarah Khan", edu: "MBBS, MD", prof: "Cardiologist", time: "03:00 PM - 08:00 PM", status: "Offline", img: "https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=800" },
        { id: 3, name: "Dr. Ahmed Hassan", edu: "BDS", prof: "Dentist", time: "10:00 AM - 04:00 PM", status: "Active", img: "https://images.unsplash.com/photo-1537368910025-700350fe46c7?q=80&w=800" },
        { id: 4, name: "Dr. Maria Qureshi", edu: "MD Neurology", prof: "Neurologist", time: "11:00 AM - 05:00 PM", status: "Active", img: "https://images.unsplash.com/photo-1594824476967-48c8b964273f?q=80&w=1200" },
        { id: 5, name: "Dr. Usman Sheikh", edu: "MBBS", prof: "Orthopedic", time: "02:00 PM - 09:00 PM", status: "Active", img: "https://images.unsplash.com/photo-1622253692010-333f2da6031d?q=80&w=1200" },
        { id: 6, name: "Dr. Zainab Malik", edu: "MBBS, MD", prof: "Pediatrician", time: "01:00 PM - 06:00 PM", status: "Offline", img: "https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=800" },
        { id: 7, name: "Dr. Bilal Raza", edu: "BDS", prof: "Dentist", time: "12:00 PM - 05:00 PM", status: "Active", img: "https://images.unsplash.com/photo-1537368910025-700350fe46c7?q=80&w=800" },
        { id: 8, name: "Dr. Hina Shahid", edu: "MD Neurology", prof: "Neurologist", time: "12:30 PM - 06:30 PM", status: "Active", img: "https://images.unsplash.com/photo-1594824476967-48c8b964273f?q=80&w=1200" },
       
    ];

    const grid = document.getElementById('doctorsGrid');
    const searchInput = document.getElementById('drSearchInput');
    const modal = document.getElementById('customDrModal');

    // 2. Render Function
    function render(list) {
        grid.innerHTML = '';
        list.forEach(dr => {
            const badgeColor = dr.status === 'Active' ? '#00ff88' : '#ff4b2b';
            grid.innerHTML += `
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="dr-item-card" onclick="openDrModal(${dr.id})">
                        <div class="dr-card-img">
                            <img src="${dr.img}" alt="${dr.name}">
                            <span class="status-badge" style="background:${badgeColor}">${dr.status}</span>
                        </div>
                        <div class="dr-card-body text-center">
                            <h5 class="mb-1">${dr.name}</h5>
                            <p class="small text-info">${dr.prof}</p>
                        </div>
                    </div>
                </div>
            `;
        });
        document.getElementById('resultCount').innerText = `Showing ${list.length} specialists`;
    }

    // 3. Modal Function
    window.openDrModal = function(id) {
        const dr = doctors.find(d => d.id === id);
        document.getElementById('modalName').innerText = dr.name;
        document.getElementById('modalEdu').innerText = dr.edu;
        document.getElementById('modalProf').innerText = dr.prof;
        document.getElementById('modalTime').innerText = dr.time;
        document.getElementById('modalStatus').innerText = dr.status;
        document.getElementById('modalImg').src = dr.img;
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Stop scrolling
    };

    // Close Modal
    document.querySelector('.dr-popup-close').onclick = () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };

    // Join Queue Link
    document.getElementById('generateQueueBtn').onclick = () => {
        window.location.href = '/Token_form';
    };

    // Search Logic
    searchInput.oninput = (e) => {
        const term = e.target.value.toLowerCase();
        const filtered = doctors.filter(d => 
            d.name.toLowerCase().includes(term) || d.prof.toLowerCase().includes(term)
        );
        render(filtered);
    };

    // Auto Slider
    const slides = document.querySelectorAll('.slide');
    let cur = 0;
    setInterval(() => {
        slides[cur].classList.remove('active');
        cur = (cur + 1) % slides.length;
        slides[cur].classList.add('active');
    }, 5000);

    render(doctors); // Initial load
});