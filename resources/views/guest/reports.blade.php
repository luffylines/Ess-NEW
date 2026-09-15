@extends('layouts.welcome')

@section('title', 'Reports & Insights | Place Of Beauty ESS')

@section('content')
<section class="public-page-hero"><div class="container"><div class="eyebrow" data-reveal><i class="bi bi-bar-chart"></i> Reports & insights</div><h1 class="hero-title mx-auto" style="max-width:900px;" data-reveal data-reveal-delay="1">Work records turned into <span class="accent">useful visibility.</span></h1><p class="hero-copy" data-reveal data-reveal-delay="2">ESS helps employees and authorized HR users understand attendance and workforce activity through straightforward summaries and reports.</p></div></section>
<section class="public-section pt-3"><div class="container"><div class="row g-3">
@php $items=[['bi-calendar3','Attendance summaries','Review attendance records across useful date ranges.'],['bi-pie-chart','Readable analytics','Charts and summaries make patterns easier to understand at a glance.'],['bi-file-earmark-arrow-down','Export-ready workflows','Authorized users can generate reports for operational and administrative needs.']]; @endphp
@foreach($items as $i=>$item)<div class="col-md-4" data-reveal data-reveal-delay="{{ $i+1 }}"><div class="feature-card"><div class="feature-icon"><i class="bi {{ $item[0] }}"></i></div><h3 class="h5 fw-bold mt-3">{{ $item[1] }}</h3><p class="section-copy mb-0">{{ $item[2] }}</p></div></div>@endforeach
</div><div class="content-panel mt-4" data-reveal><div class="row align-items-center g-4"><div class="col-lg-8"><div class="section-kicker">Role-aware access</div><h2 class="section-title mb-2">The right insights for the right user.</h2><p class="section-copy mb-0">Employee, HR, manager and admin views remain separated so reports and controls are shown only where they belong.</p></div><div class="col-lg-4 text-lg-end"><a href="{{ route('login') }}" class="btn-pob-primary"><i class="bi bi-arrow-right-circle"></i> Sign in to ESS</a></div></div></div></div></section>
@endsection
