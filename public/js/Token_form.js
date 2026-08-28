document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tokenRequestForm');
    const mobileInput = document.getElementById('mobileNumber');
    const mobileError = document.getElementById('mobileError');
    const patientModal = document.getElementById('patientModal');
    const closeModal = document.getElementById('closeModal');
    const modalOkBtn = document.getElementById('modalOkBtn');

    // ✅ Mobile Number Validation: Only numbers, max 11 digits, starts with 03
    mobileInput.addEventListener('input', function(e) {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Max 11 digits
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }

        // Validate in real-time
        if (this.value.length > 0) {
            const isValid = /^(03)\d{9}$/.test(this.value);
            if (this.value.length > 0 && !isValid) {
                mobileError.classList.remove('d-none');
                this.style.border = "1px solid #ff4b2b";
            } else {
                mobileError.classList.add('d-none');
                this.style.border = "1px solid rgba(255,255,255,0.1)";
            }
        }
    });

    // ✅ Form Submit Validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const mobileVal = mobileInput.value;
        const isValid = /^(03)\d{9}$/.test(mobileVal);

        if (!isValid) {
            mobileError.classList.remove('d-none');
            mobileInput.style.border = "1px solid #ff4b2b";
            mobileInput.focus();
            return;
        }

        // ✅ All good - show success modal
        patientModal.style.display = 'flex';
    });

    // ✅ Close Modal functions
    function closeModalFn() {
        patientModal.style.display = 'none';
        form.submit();
    }

    closeModal.addEventListener('click', closeModalFn);
    modalOkBtn.addEventListener('click', closeModalFn);

    // Close modal on outside click
    patientModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModalFn();
        }
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && patientModal.style.display === 'flex') {
            closeModalFn();
        }
    });
});