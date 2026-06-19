document.addEventListener('DOMContentLoaded', () => {
    // Simulating a 12:45 countdown
    let totalSeconds = (12 * 60) + 45;

    const timeDisplay = document.getElementById('remainingTime');

    const countdown = setInterval(() => {
        let minutes = Math.floor(totalSeconds / 60);
        let seconds = totalSeconds % 60;

        // Add leading zero
        seconds = seconds < 10 ? '0' + seconds : seconds;
        minutes = minutes < 10 ? '0' + minutes : minutes;

        timeDisplay.innerHTML = `${minutes}:${seconds}`;

        if (totalSeconds <= 0) {
            clearInterval(countdown);
            timeDisplay.innerHTML = "NOW";
            timeDisplay.style.color = "#00ff88"; // Turn green when it's time
        } else {
            totalSeconds--;
        }
    }, 1000);
});