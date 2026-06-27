document.addEventListener('DOMContentLoaded', function() {
<<<<<<< HEAD
=======
    // ✅ Get all elements
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.querySelector('.btn-login-submit');
    const userRoleSelect = document.getElementById('userRole');
    const identifierLabel = document.getElementById('identifierLabel');
    const identifierInput = document.getElementById('identifierInput');
<<<<<<< HEAD

    // Function to update fields based on role
    const updateFormFields = (role) => {
=======
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');

    // ✅ Debug - Check if elements exist
    console.log('login.js loaded successfully');
    console.log('forgotPasswordLink:', forgotPasswordLink);

    // Function to update fields based on role
    const updateFormFields = (role) => {
        if (!identifierLabel || !identifierInput) return;
        
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
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
<<<<<<< HEAD
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
=======
    if (userRoleSelect) {
        userRoleSelect.addEventListener('change', function() {
            updateFormFields(this.value);
        });
    }

    // ✅ FORGOT PASSWORD FUNCTIONALITY
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Forgot password clicked');
            
            // ✅ Direct redirect to forgot password page
            window.location.href = '/forgot-password';
        });
    } else {
        console.error('Forgot password link not found! Check ID: forgotPasswordLink');
    }

    // Handle Form Submission
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const selectedRole = userRoleSelect ? userRoleSelect.value : 'patient';

            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
                submitBtn.style.opacity = '0.8';
            }

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
    }

    // Background Particles Logic
>>>>>>> ad8262ee5046eced21425c2cc6aa14495d6f4a02
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