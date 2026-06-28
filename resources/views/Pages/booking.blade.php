@extends('Layout.app')

@section('content')
<link href="{{ asset('css/booking.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<div class="booking-page-wrapper">
    <section class="booking-hero" 
             style="background: linear-gradient(rgba(26, 58, 58, 0.85), rgba(26, 58, 58, 0.85)), 
                    url('{{ asset('Assert/operation.png') }}'); background-size: cover; background-position: center;">
        
        <div class="container">
            <div class="booking-hero-content">
                <span class="booking-badge">Fast & Secure</span>
                <h1>Book Your <span class="text-accent">Digital Token</span></h1>
                <p>Select your preferred doctor or hospital in Gujranwala and join the queue from the comfort of your home.</p>
                
                <div class="hero-search-box">
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search Doctor in our Hospital...">
                    </div>
                    <button class="btn-search">Search Now</button>
                </div>
            </div>
        </div>
    </section>

   @include('Component.booking-steps')
   @include('Component.Doctors_details')
   @include('Component.contact_form')
</div>
@endsection