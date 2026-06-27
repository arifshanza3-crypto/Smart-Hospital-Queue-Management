window.addEventListener('scroll', () => {
  const cards = document.querySelectorAll('.card');
  const scrollPosition = window.scrollY;

  cards.forEach((card, index) => {
    const cardRect = card.getBoundingClientRect();
    const cardTop = card.offsetTop;
    
    // As the card hits the sticky point (50px from top)
    if (cardRect.top <= 50) {
      const diff = 50 - cardRect.top;
      const progress = Math.min(diff / 500, 1); // 500 is the scroll sensitivity
      
      // Apply scaling and opacity to cards underneath
      if (index < cards.length - 1) {
        const scale = 1 - (progress * 0.05); // Scale down to 0.95
        const opacity = 1 - (progress * 0.4); // Fade slightly
        card.querySelector('.card-inner').style.transform = `scale(${scale})`;
        card.querySelector('.card-inner').style.opacity = opacity;
      }
    } else {
      card.querySelector('.card-inner').style.transform = `scale(1)`;
      card.querySelector('.card-inner').style.opacity = 1;
    }
  });
});