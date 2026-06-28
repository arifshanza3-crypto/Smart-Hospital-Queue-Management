let currentPos = 0;

function moveSlider(direction) {
    const track = document.getElementById('sliderTrack');
    const cards = document.querySelectorAll('.info-card');
    if (!track || cards.length === 0) return;

    const cardWidth = cards[0].offsetWidth;
    const visibleCount = window.innerWidth > 1024 ? 4 : (window.innerWidth > 600 ? 2 : 1);
    const maxIndex = cards.length - visibleCount;

    currentPos += direction;

    if (currentPos < 0) currentPos = 0;
    if (currentPos > maxIndex) currentPos = maxIndex;

    track.style.transform = `translateX(-${currentPos * cardWidth}px)`;
}