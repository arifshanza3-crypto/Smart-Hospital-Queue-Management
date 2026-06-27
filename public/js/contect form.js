document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.querySelector('#contactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = contactForm.querySelector('.btn-submit');
            const originalText = submitBtn.innerText;
            
            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerText = 'PROCESSING...';

            // Simulate API Call (Replace with your actual Laravel route)
            setTimeout(() => {
                alert('Thank you! Your inquiry has been sent.');
                submitBtn.disabled = false;
                submitBtn.innerText = originalText;
                contactForm.reset();
            }, 1500);
        });
    }
});