document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('doctorModal');
    const openBtn = document.getElementById('addDoctorBtn');
    const closeBtn = document.getElementById('closeModal');

    if (openBtn) {
        openBtn.onclick = function() {
            console.log("Button Clicked!"); // Yeh browser console mein check karein
            modal.style.setProperty('display', 'flex', 'important');
        }
    }

    if (closeBtn) {
        closeBtn.onclick = function() {
            modal.style.display = 'none';
        }
    }

    // Modal ke bahar click karne se band karne ke liye
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
});