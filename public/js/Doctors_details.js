document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Array Data
    const doctors = [
        { id: 1, name: "Dr. Farhan Ali", edu: "MBBS", prof: "Dentist", time: "09:00 AM - 02:00 PM", status: "Active", img: "https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?q=80&w=800" },
        { id: 2, name: "Dr. Sarah Khan", edu: "MBBS, MD", prof: "Cardiologist", time: "03:00 PM - 08:00 PM", status: "Offline", img: "https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=800" },
        { id: 3, name: "Dr. Ahmed Hassan", edu: "BDS", prof: "Dentist", time: "10:00 AM - 04:00 PM", status: "Active", img: "https://images.unsplash.com/photo-1537368910025-700350fe46c7?q=80&w=800" },
        { id: 4, name: "Dr. Ayesha Malik", edu: "MBBS, MS", prof: "Gynecologist", time: "11:00 AM - 05:00 PM", status: "Offline", img: "https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?q=80&w=800" }, 
    ];

    const grid = document.getElementById('doctorsGrid');
    const modal = document.getElementById('customDrModal');

    // 2. Render Function (Showing only first 4 for the 'Preview' section)
    function render(list) {
        if(!grid) return;
        grid.innerHTML = '';
        
        // Use .slice(0, 4) if you only want to show a few on the main page
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
                            <h5 class="mb-1 text-white">${dr.name}</h5>
                            <p class="small text-info">${dr.prof}</p>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    // 3. Modal Function
    window.openDrModal = function(id) {
        const dr = doctors.find(d => d.id === id);
        if(!dr) return;

        document.getElementById('modalName').innerText = dr.name;
        document.getElementById('modalEdu').innerText = dr.edu;
        document.getElementById('modalProf').innerText = dr.prof;
        document.getElementById('modalTime').innerText = dr.time;
        document.getElementById('modalStatus').innerText = dr.status;
        
        const modalImg = document.getElementById('modalImg');
        const loader = document.getElementById('modalLoader');
        
        modalImg.style.display = 'none';
        loader.style.display = 'block';
        
        modalImg.src = dr.img;
        modalImg.onload = function() {
            loader.style.display = 'none';
            modalImg.style.display = 'block';
        };
        
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    // Close Modal
    const closeBtn = document.querySelector('.dr-popup-close');
    if(closeBtn) {
        closeBtn.onclick = () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        };
    }

    render(doctors); // Load the doctors into the grid
});