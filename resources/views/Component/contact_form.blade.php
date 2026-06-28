<link href="{{ asset('css/contect form.css') }}" rel="stylesheet">
<section class="dental-contact-section">
    <div class="section-container">
        <div class="content-side">
            <span class="label-accent">GET IN TOUCH</span>
            <h2 class="main-title">Ready to eliminate <br><span class="highlight">the waiting room?</span></h2>
            <p class="sub-text">Whether you are a healthcare provider looking to streamline your clinic or a patient needing assistance, our team is here to help.</p>
            
            <div class="contact-info-list">
                <div class="phone-highlight">Support: (303) 555-0105</div>
                
                <h4 class="info-heading">System Operations</h4>
                <ul class="details">
                    <li><i class="icon-loc"></i> Smart Queue HQ - Tech District, Sector 7</li>
                    <li><i class="icon-time"></i> Token Generation: 24/7 Available</li>
                    <li><i class="icon-time"></i> Admin Support: Mon-Sat (09.00am - 06.00pm)</li>
                </ul>
            </div>
        </div>

        <div class="form-side">
            <form id="contactForm" class="teal-form">
                @csrf
                <h3>Send a Message</h3>
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="example@hospital.com" required>
                </div>

                <div class="form-group">
                    <label>How can we help?</label>
                    <textarea name="message" rows="3" placeholder="Tell us about your clinic or your issue..."></textarea>
                </div>

                <button type="submit" class="btn-submit">SEND MESSAGE</button>
            </form>
        </div>
    </div>
</section>
<script src="{{ asset('js/contect form.js') }}"></script>