document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('doctorModal');
    const openBtn = document.getElementById('addDoctorBtn'); // Jo button aapne pehle banaya tha
    const closeBtn = document.getElementById('closeModal');

    // 1. Button click par modal dikhao
    openBtn.addEventListener('click', function() {
        modal.style.display = 'flex';
    });

    // 2. Cross (X) par click karne se band karo
    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    // 3. Agar user modal ke bahar click kare tab bhi band ho jaye
    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
});