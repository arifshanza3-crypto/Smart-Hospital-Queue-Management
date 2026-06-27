document.addEventListener('DOMContentLoaded', function() {
    const tokenForm = document.getElementById('tokenRequestForm');
    const userNumber = document.getElementById('userNumber');
    const infoModal = document.getElementById('infoModal');
    const patientModal = document.getElementById('patientModal');
    const errorMsg = document.getElementById('errorMsg');

    // 1. Modal trigger
    userNumber.addEventListener('click', function() {
        if (this.readOnly) infoModal.style.display = 'flex';
    });

    const unlockField = () => {
        infoModal.style.display = 'none';
        userNumber.readOnly = false;
        userNumber.focus();
    };

    document.getElementById('confirmInfo').addEventListener('click', unlockField);
    document.getElementById('closeInfo').addEventListener('click', unlockField);

    // 2. Strict Numeric Input Only
    userNumber.addEventListener('input', function(e) {
        // Remove any non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Prevent typing more than 11 characters
        if (this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });

    // 3. Final Form Validation
    tokenForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const phoneVal = userNumber.value;

        // Condition: Exactly 11 digits AND starts with 03
        const isValid = /^(03)\d{9}$/.test(phoneVal);

        if (!isValid) {
            errorMsg.classList.remove('d-none');
            userNumber.style.border = "1px solid #ff4b2b";
            return;
        }

        // Success
        errorMsg.classList.add('d-none');
        userNumber.style.border = "1px solid rgba(255,255,255,0.1)";
        patientModal.style.display = 'flex';

        setTimeout(() => {
            window.location.href = "/Status";
        }, 3500);
    });
});