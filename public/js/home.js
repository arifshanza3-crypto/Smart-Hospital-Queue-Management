document.addEventListener('DOMContentLoaded', () => {
    const slides = document.querySelectorAll('.hero-slide');
    let currentSlideIndex = 0;
    const slideInterval = 7000; // 7 seconds for better readability

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));

        if (index >= slides.length) currentSlideIndex = 0;
        else if (index < 0) currentSlideIndex = slides.length - 1;
        else currentSlideIndex = index;

        slides[currentSlideIndex].classList.add('active');
    }

    function nextSlide() {
        currentSlideIndex++;
        showSlide(currentSlideIndex);
    }

    let slideTimer = setInterval(nextSlide, slideInterval);

    // Pause on interaction
    const heroSection = document.querySelector('.hero-slider-section');
    heroSection.addEventListener('mouseenter', () => clearInterval(slideTimer));
    heroSection.addEventListener('mouseleave', () => slideTimer = setInterval(nextSlide, slideInterval));
});