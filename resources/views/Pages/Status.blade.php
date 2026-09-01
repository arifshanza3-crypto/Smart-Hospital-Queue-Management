@extends('layout.app')

@section('title', 'Token Status - SMART QUEUE')

@section('content')

{{-- ✅ Include CSS --}}
<link rel="stylesheet" href="{{ asset('css/status.css') }}">

<div class="status-container">
    <div class="status-card">
        <h2>Patient Portal</h2>
        <h3>Your Token Status</h3>
        <p class="subtitle">Real-time update of your queue position</p>

        <div class="token-status-display">
            <div class="token-number">
                <span class="label">YOUR TOKEN</span>
                <span class="value" id="patientTokenNumber">{{ $token->token_number ?? 'N/A' }}</span>
                <span class="badge status-{{ $token->status ?? 'waiting' }}" id="tokenBadge">{{ ucfirst($token->status ?? 'Waiting') }}</span>
            </div>

            <div class="status-grid">
                {{-- PATIENT NAME --}}
                <div class="status-item">
                    <span class="label">PATIENT</span>
                    <span class="value" id="patientName">{{ $token->patient_name ?? 'N/A' }}</span>
                </div>

                {{-- STATUS --}}
                <div class="status-item">
                    <span class="label">STATUS</span>
                    <span class="value status-{{ $token->status ?? 'waiting' }}" id="patientStatus">{{ ucfirst($token->status ?? 'Waiting') }}</span>
                </div>

                {{-- POSITION NUMBER --}}
                <div class="status-item">
                    <span class="label">POSITION</span>
                    <span class="value" id="patientPosition">#{{ $token->position ?? 'N/A' }}</span>
                </div>

                {{-- ESTIMATED WAITING TIME --}}
                <div class="status-item">
                    <span class="label">EST. WAIT</span>
                    <span class="value wait-time-update" id="patientWaitTime">
                        {{ $token->estimated_time ?? 'N/A' }} min
                    </span>
                </div>

                {{-- NOW SERVING --}}
                <div class="status-item">
                    <span class="label">NOW SERVING</span>
                    <span class="value now-serving-value" id="patientServing">{{ $nowServing ?? 'N/A' }}</span>
                </div>

                {{-- GENERATED TIME --}}
                <div class="status-item">
                    <span class="label">GENERATED</span>
                    <span class="value" id="patientTime">
                        @php
                            $time = 'N/A';
                            if ($token->created_at) {
                                $timestamp = strtotime($token->created_at);
                                $timestamp = $timestamp - (2 * 3600);
                                $time = date('h:i A', $timestamp);
                            }
                        @endphp
                        {{ $time }}
                    </span>
                </div>
            </div>

            <div class="status-actions">
                <button onclick="refreshStatus()" class="btn-refresh">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <a href="/" class="btn-home">Home</a>
            </div>
        </div>
    </div>
</div>

{{-- ✅ JavaScript --}}
<script src="{{ asset('js/status.js') }}"></script>

@endsection