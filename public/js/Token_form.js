document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('tokenRequestForm');
    const mobileInput = document.getElementById('mobileNumber');
    const mobileError = document.getElementById('mobileError');

    // Mobile Number Validation: Only numbers, max 11 digits, starts with 03
    mobileInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }

        if (this.value.length > 0) {
            const isValid = /^(03)\d{9}$/.test(this.value);
            if (!isValid) {
                mobileError.classList.remove('d-none');
                this.style.border = "1px solid #ff4b2b";
            } else {
                mobileError.classList.add('d-none');
                this.style.border = "1px solid rgba(255,255,255,0.1)";
            }
        }
    });

    // ✅ Form Submit - No Popup, Direct Submit
    form.addEventListener('submit', function(e) {
        const mobileVal = mobileInput.value;
        const isValid = /^(03)\d{9}$/.test(mobileVal);

        if (!isValid) {
            e.preventDefault();
            mobileError.classList.remove('d-none');
            mobileInput.style.border = "1px solid #ff4b2b";
            mobileInput.focus();
            return;
        }

        // ✅ Allow form to submit directly (no modal)
        // Form will submit normally
    });
});