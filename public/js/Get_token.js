document.addEventListener('DOMContentLoaded', () => {
    const tokenCard = document.getElementById('tokenCardContainer');
    
    // 1. Check if user has a token (Simulated)
    // Change this to true to see the live countdown
    let hasToken = false; 

    if (!hasToken) {
        renderNoTokenView();
    } else {
        initCountdown(12, 24);
    }

    // 2. Function to Render "No Token" UI
    function renderNoTokenView() {
        tokenCard.innerHTML = `
            <div class="no-token-view">
                <div class="mb-4">
                    <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#00d4ff" stroke-width="1.5">
                        <path d="M2 9V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4M2 15v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4M2 12h20"></path>
                    </svg>
                </div>
                <h2 class="text-white fw-bold">No Active Token</h2>
                <p class="text-white-50 mb-4">You don't have an active appointment. Join the queue to see a specialist.</p>
                <button id="goToServices" class="btn-get-token">Join Queue Now</button>
            </div>
        `;

        // REDIRECTION LOGIC
        document.getElementById('goToServices').addEventListener('click', () => {
            // This takes the user to your Service Page
            window.location.href = "/services"; 
        });
    }

    // 3. Countdown Logic
    function initCountdown(mins, secs) {
        let total = (mins * 60) + secs;
        const display = document.getElementById('remainingTime');

        const timer = setInterval(() => {
            let m = Math.floor(total / 60);
            let s = total % 60;
            
            if (display) {
                display.innerHTML = `${m < 10 ? '0'+m : m}:${s < 10 ? '0'+s : s}`;
            }

            if (total <= 0) {
                clearInterval(timer);
                if(display) display.innerHTML = "READY";
            }
            total--;
        }, 1000);
    }
});