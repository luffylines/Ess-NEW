@extends('layouts.welcome')

@section('title', 'Attendance | Place Of Beauty ESS')

@section('content')
<section class="public-page-hero"><div class="container"><div class="eyebrow" data-reveal><i class="bi bi-fingerprint"></i> Attendance management</div><h1 class="hero-title mx-auto" style="max-width:900px;" data-reveal data-reveal-delay="1">Time tracking that feels <span class="accent">clear and immediate.</span></h1><p class="hero-copy" data-reveal data-reveal-delay="2">ESS keeps daily attendance, work hours and history in one accessible place so employees and HR share the same view of the workday.</p></div></section>
<section class="public-section pt-3"><div class="container"><div class="row g-3">
@php $items=[['bi-fingerprint','Simple time records','Record and review workday attendance from a focused employee experience.'],['bi-clock-history','Visible history','See attendance records and timestamps without digging through manual logs.'],['bi-graph-up-arrow','Useful trends','Review attendance patterns and monthly summaries through visual reporting.']]; @endphp
@foreach($items as $i=>$item)<div class="col-md-4" data-reveal data-reveal-delay="{{ $i+1 }}"><div class="feature-card"><div class="feature-icon"><i class="bi {{ $item[0] }}"></i></div><h3 class="h5 fw-bold mt-3">{{ $item[1] }}</h3><p class="section-copy mb-0">{{ $item[2] }}</p></div></div>@endforeach
</div><div class="content-panel mt-4" data-reveal><div class="row align-items-center g-4"><div class="col-lg-8"><div class="section-kicker">Ready for your workday?</div><h2 class="section-title mb-2">Open ESS and check your attendance.</h2><p class="section-copy mb-0">Authorized employees can sign in to view their current attendance tools and history.</p></div><div class="col-lg-4 text-lg-end"><a href="{{ route('login') }}" class="btn-pob-primary"><i class="bi bi-arrow-right-circle"></i> Employee login</a></div></div></div></div></section>
@endsection
