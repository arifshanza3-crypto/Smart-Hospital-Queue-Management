document.addEventListener('DOMContentLoaded', function() {
    const target = document.getElementById('mechanism-target');
    const toggleBtn = document.getElementById('switchStateBtn');
    
    // Initial State: No Token
    let hasToken = false;

    // "Get_token" Component Logic
    function showGetTokenComponent() {
        target.innerHTML = `
            <div class="status-card">
                <span class="component-label">Component: Get_token</span>
                <h2 class="fw-bold text-white">Ready to join?</h2>
                <p class="text-white-50 mt-3 mb-4">No active token was found for your session.</p>
                <button onclick="window.location.href='/token-form'" class="btn btn-outline-info px-5 py-3" style="border-radius: 15px; font-weight: 800; border-width: 2px;">
                    Generate New Token
                </button>
            </div>
        `;
    }

    // "Token_detail" Component Logic
    function showTokenDetailComponent() {
        target.innerHTML = `
            <div class="status-card">
                <span class="component-label">Component: Token_detail</span>
                <div class="mb-2" style="color: #00ff88; font-weight: 800; font-size: 14px;">
                    <span style="display: inline-block; width: 10px; height: 10px; background: #00ff88; border-radius: 50%; margin-right: 8px;"></span>
                    ACTIVE IN QUEUE
                </div>
                <h3 class="text-white-50">Your Number</h3>
                <div class="token-big-number">11</div>
                <hr style="border-color: rgba(255,255,255,0.1); margin: 30px 0;">
                <div class="d-flex justify-content-between text-start">
                    <div>
                        <small class="text-white-50 d-block">Serving</small>
                        <span class="h4 fw-bold text-accent-cyan">08</span>
                    </div>
                    <div class="text-end">
                        <small class="text-white-50 d-block">Wait Time</small>
                        <span class="h4 fw-bold text-white">~12m</span>
                    </div>
                </div>
            </div>
        `;
    }

    // Load initial component
    showGetTokenComponent();

    // The "Mechanism" Swapper
    toggleBtn.addEventListener('click', function() {
        hasToken = !hasToken;
        if(hasToken) {
            showTokenDetailComponent();
            this.innerText = "System: Token Generated";
            this.style.background = "#fff";
        } else {
            showGetTokenComponent();
            this.innerText = "Toggle Generation Logic";
            this.style.background = "#00d4ff";
        }
    });
});