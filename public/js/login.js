document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.querySelector('.btn-login-submit');
    const userRoleSelect = document.getElementById('userRole');
    const identifierLabel = document.getElementById('identifierLabel');
    const identifierInput = document.getElementById('identifierInput');

    // Function to update fields based on role
    const updateFormFields = (role) => {
        if (role === 'patient') {
            identifierLabel.innerText = 'Email Address';
            identifierInput.type = 'email';
            identifierInput.placeholder = 'Enter your email';
        } else if (role === 'staff') {
            identifierLabel.innerText = 'Username';
            identifierInput.type = 'text';
            identifierInput.placeholder = 'Enter your username';
        } else if (role === 'admin') {
            identifierLabel.innerText = 'Email or Username';
            identifierInput.type = 'text';
            identifierInput.placeholder = 'Enter email or username';
        }
    };

    // Listen for dropdown changes
    userRoleSelect.addEventListener('change', function() {
        updateFormFields(this.value);
    });

    // Handle Form Submission
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const selectedRole = userRoleSelect.value;

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
        submitBtn.style.opacity = '0.8';

        setTimeout(() => {
            if (selectedRole === 'patient') {
                window.location.href = "/";
            } else if (selectedRole === 'staff') {
                window.location.href = "/staff";
            } else if (selectedRole === 'admin') {
                window.location.href = "/admin";
            }
        }, 1200);
    });

    // Background Particles Logic (Same as before)
    const container = document.querySelector('.particles-container');
    if (container) {
        for (let i = 0; i < 15; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.cssText = `
                position: absolute;
                width: ${Math.random() * 5 + 2}px;
                height: ${Math.random() * 5 + 2}px;
                background: rgba(0, 212, 255, 0.3);
                border-radius: 50%;
                top: ${Math.random() * 100}%;
                left: ${Math.random() * 100}%;
                filter: blur(2px);
                animation: float ${Math.random() * 10 + 10}s linear infinite;
            `;
            container.appendChild(p);
        }
    }
});