document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="role"]');
    const signupForm = document.getElementById('signupForm');
    const successModal = document.getElementById('successModal');
    
    // Toggle fields based on role
    function toggleFields() {
        const role = roleSelect?.value;
        
        document.getElementById('patient-fields')?.classList.add('d-none');
        document.getElementById('staff-fields')?.classList.add('d-none');
        document.getElementById('doctor-fields')?.classList.add('d-none');
        
        if (role === 'patient') {
            document.getElementById('patient-fields')?.classList.remove('d-none');
        } else if (role === 'staff') {
            document.getElementById('staff-fields')?.classList.remove('d-none');
        } else if (role === 'doctor') {
            document.getElementById('doctor-fields')?.classList.remove('d-none');
        }
    }
    
    if (roleSelect) {
        roleSelect.addEventListener('change', toggleFields);
        toggleFields();
    }
    
    // Form submission
    if (signupForm) {
        signupForm.addEventListener('submit', function(e) {
            // Let the form submit naturally - do NOT prevent default
            // The success modal will show after redirect via session
            
            // If you want to show modal before submit, uncomment below:
            // e.preventDefault();
            // if (successModal) {
            //     successModal.classList.remove('d-none');
            //     setTimeout(() => {
            //         signupForm.submit();
            //     }, 2000);
            // }
        });
    }
    
    // Show success modal if query param exists
    if (window.location.search.includes('success=1')) {
        if (successModal) {
            successModal.classList.remove('d-none');
            setTimeout(() => {
                window.location.href = '/login';
            }, 3000);
        }
    }
});