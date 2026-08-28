@extends('Layout.app')

@section('content')
<link href="{{ asset('css/contact.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <section class="contact-hero" 
             style="background: linear-gradient(rgba(26, 58, 58, 0.9), rgba(26, 58, 58, 0.8)), 
                    url('{{ asset('Assert/contact.jpg') }}'); background-size: cover; background-position: center; background-attachment: fixed;">
        
        <div class="hero-bg-shapes">
            <div class="shape blob-1"></div>
            <div class="shape blob-2"></div>
        </div>
        
        <div class="container">
            <div class="hero-text">
                <span class="hero-badge">Let's Connect</span>
                <h1>How Can We <span class="gradient-text">Help You?</span></h1>
                <p>Whether you're a hospital looking to optimize or a patient with a query, our team is just a message away.</p>
            </div>

            <div class="contact-quick-cards">
                <div class="q-card">
                    <div class="q-icon"><i class="fas fa-phone-volume"></i></div>
                    <h3>Call Us</h3>
                    <p>+92 300 1234567</p>
                </div>
                <div class="q-card">
                    <div class="q-icon"><i class="fas fa-envelope-open-text"></i></div>
                    <h3>Email Us</h3>
                    <p>support@smartqueue.com</p>
                </div>
                <div class="q-card">
                    <div class="q-icon"><i class="fas fa-location-dot"></i></div>
                    <h3>Visit Us</h3>
                    <p> Pakistan</p>
                </div>
            </div>
        </div>
    </section>

  
        @include("Component.contact_form")
    
@endsection