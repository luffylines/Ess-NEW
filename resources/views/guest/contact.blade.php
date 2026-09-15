@extends('layouts.welcome')

@section('title', 'Contact & Feedback | Place Of Beauty ESS')

@section('content')
<section class="public-page-hero">
    <div class="container">
        <div class="eyebrow" data-reveal><i class="bi bi-chat-heart"></i> Contact & feedback</div>
        <h1 class="hero-title mx-auto" style="max-width:850px;" data-reveal data-reveal-delay="1">Help us make ESS <span class="accent">better to use.</span></h1>
        <p class="hero-copy" data-reveal data-reveal-delay="2">Report an issue, share a suggestion or tell us what could make your employee experience clearer and easier.</p>
    </div>
</section>

<section class="public-section pt-3">
    <div class="container">
        @include('partials.flash-messages')
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-7" data-reveal>
                <div class="content-panel h-100">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="feature-icon"><i class="bi bi-send"></i></div>
                        <div><h2 class="h4 fw-bold mb-1">Send feedback</h2><p class="small text-secondary mb-0">We read feedback to improve the system experience.</p></div>
                    </div>
                    <form action="{{ route('submitFeedback') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Your name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                            </div>
                            <div class="col-12">
                                <label for="feedback" class="form-label">Message</label>
                                <textarea class="form-control" id="feedback" name="feedback" rows="6" placeholder="Tell us what happened or what you would like to improve…" required>{{ old('feedback') }}</textarea>
                            </div>
                            <div class="col-12 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
                                <small class="text-secondary"><i class="bi bi-shield-check me-1"></i> Please avoid including passwords or sensitive credentials.</small>
                                <button type="submit" class="btn-pob-primary border-0"><i class="bi bi-send"></i> Submit feedback</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-5" data-reveal data-reveal-delay="1">
                <div class="d-grid gap-3 h-100">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-geo-alt"></i></div>
                        <h3 class="h5 fw-bold mt-3">Development contact</h3>
                        <p class="section-copy mb-3">Christian Aring<br>Quezon City, Metro Manila, Philippines</p>
                        <a href="https://www.google.com/maps/search/?api=1&query=Quezon+City+Metro+Manila" target="_blank" rel="noopener noreferrer" class="btn-pob-secondary w-100"><i class="bi bi-map"></i> Open location in Maps</a>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-envelope-heart"></i></div>
                        <h3 class="h5 fw-bold mt-3">Email support</h3>
                        <p class="section-copy">For feedback and system-related concerns, you can also contact the development team by email.</p>
                        <a href="mailto:chba.aring.sjc@phinmaed.com?subject=Feedback%20on%20ESS%20System" class="btn-pob-secondary w-100"><i class="bi bi-envelope"></i> Compose email</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
