<<<<<<< HEAD
function toggleFields() {
    const role = document.getElementById('roleSelect').value;
    const sections = ['patient-fields', 'doctor-fields', 'staff-fields'];
    
    sections.forEach(id => {
        const el = document.getElementById(id);
        if (id.startsWith(role)) {
            el.classList.remove('d-none');
        } else {
            el.classList.add('d-none');
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signupForm');
    const successModal = document.getElementById('successModal');

    signupForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent standard POST for demo/animation

        // Trigger beautiful success popup
        successModal.classList.remove('d-none');

        // Redirect after showing the message
        setTimeout(() => {
            window.location.href = "/"; // Goes to Home Page
        }, 3000);
    });
    
    // Initialize correct fields
    toggleFields();
=======
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="role"]');
    
    function toggleFields() {
        const role = roleSelect?.value;
        
        document.getElementById('patient-fields')?.classList.add('d-none');
        document.getElementById('staff-fields')?.classList.add('d-none');
        
        if (role === 'patient') {
            document.getElementById('patient-fields')?.classList.remove('d-none');
        } else if (role === 'staff') {
            document.getElementById('staff-fields')?.classList.remove('d-none');
        }
    }
    
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    }
    
    // Show success modal on successful form submission via query param
    if (window.location.search.includes('success=1')) {
        const modal = document.getElementById('successModal');
        if (modal) modal.classList.remove('d-none');
        setTimeout(() => window.location.href = '/login', 3000);
    }
>>>>>>> 3f9cfec078e8d9879dc8f908935c7d8b28740f60
});