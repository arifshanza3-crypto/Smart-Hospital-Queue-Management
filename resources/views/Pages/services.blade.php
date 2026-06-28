@extends('Layout.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="{{ asset('css/services.css') }}" rel="stylesheet">

<section class="services-hero">
    <div class="s-hero-container">
        <span class="s-badge">Our Expertise</span>
        <h1>Comprehensive <br><span class="text-accent">Smart Solutions</span></h1>
        <p class="s-subtitle">
            Optimizing the journey between patient arrival and medical care with high-precision digital queueing systems.
        </p>
    </div>
</section>

@include('Component.features')
@include('Component.process')
@include('Component.Doctors_details')
@include('Component.contact_form')

@endsection