<link href="{{ asset('css/about-features.css') }}" rel="stylesheet">

<section class="features-section" style="padding: 40px 20px 60px; background: linear-gradient(135deg, #f0f7fa 0%, #e0ecf0 50%, #d4e4e8 100%);">
    <div class="features-container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">

        {{-- Section Header --}}
        <div class="section-header" style="text-align: center; margin-bottom: 28px;">
            <span class="tagline" style="color: #1a7a82; font-weight: 700; font-size: 0.75rem; letter-spacing: 2px; text-transform: uppercase; display: block; margin-bottom: 4px;">
                The Smart Advantage
            </span>
            <h2 class="section-title" style="color: #0a2a3a; font-size: 1.8rem; font-weight: 700; margin: 0 0 6px 0;">
                Why <span style="color: #1a7a82;">Smart Queue</span> Matters
            </h2>
            <p class="section-desc" style="color: #3a5a6a; font-size: 0.95rem; text-align: center; max-width: 550px; margin: 0 auto;">
                We've re-engineered the waiting experience to save time for both hospitals and patients.
            </p>
        </div>

        {{-- Features Grid --}}
        <div class="features-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px;">

            {{-- Feature 1: Instant Tokens --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 24px 20px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="icon-box" style="font-size: 32px; color: #1a7a82; margin-bottom: 2px;">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1rem; font-weight: 700; margin: 0 0 4px 0;">
                    Instant Tokens
                </h3>
                <p style="color: #3a5a6a; line-height: 1.6; font-size: 0.85rem; margin: 0;">
                    Generate digital tokens in seconds from anywhere, anytime.
                </p>
            </div>

            {{-- Feature 2: Live Updates --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 24px 20px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="icon-box" style="font-size: 32px; color: #1a7a82; margin-bottom: 2px;">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1rem; font-weight: 700; margin: 0 0 4px 0;">
                    Live Updates
                </h3>
                <p style="color: #3a5a6a; line-height: 1.6; font-size: 0.85rem; margin: 0;">
                    Stay updated with real-time push notifications.
                </p>
            </div>

            {{-- Feature 3: Hospital Analytics --}}
            <div class="feature-card" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px); padding: 24px 20px; border-radius: 16px; box-shadow: 0 4px 20px rgba(10, 42, 58, 0.04); border: 1px solid rgba(10, 42, 58, 0.04); text-align: center; transition: all 0.3s ease;">
                <div class="icon-box" style="font-size: 32px; color: #1a7a82; margin-bottom: 2px;">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3 style="color: #0a2a3a; font-size: 1rem; font-weight: 700; margin: 0 0 4px 0;">
                    Hospital Analytics
                </h3>
                <p style="color: #3a5a6a; line-height: 1.6; font-size: 0.85rem; margin: 0;">
                    Clinics get detailed insights into patient flow and resources.
                </p>
            </div>

        </div>

    </div>
</section>