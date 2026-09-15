@extends('layouts.welcome')

@section('title', 'Employee Tasks | Place Of Beauty ESS')

@section('content')
<section class="public-page-hero"><div class="container"><div class="eyebrow" data-reveal><i class="bi bi-check2-square"></i> Employee workflows</div><h1 class="hero-title mx-auto" style="max-width:900px;" data-reveal data-reveal-delay="1">Daily actions with <span class="accent">less friction.</span></h1><p class="hero-copy" data-reveal data-reveal-delay="2">ESS keeps common employee actions—attendance, schedule checks, requests and payroll access—organized into one predictable workspace.</p></div></section>
<section class="public-section pt-3"><div class="container"><div class="row g-3">
@php $items=[['bi-calendar2-check','Know what is next','See work schedules and upcoming activity without jumping between systems.'],['bi-send-check','Submit requests','Leave and overtime workflows keep status and approvals easier to follow.'],['bi-bell','Stay informed','Important work updates remain close to the employee dashboard and related tools.']]; @endphp
@foreach($items as $i=>$item)<div class="col-md-4" data-reveal data-reveal-delay="{{ $i+1 }}"><div class="feature-card"><div class="feature-icon"><i class="bi {{ $item[0] }}"></i></div><h3 class="h5 fw-bold mt-3">{{ $item[1] }}</h3><p class="section-copy mb-0">{{ $item[2] }}</p></div></div>@endforeach
</div><div class="content-panel mt-4" data-reveal><div class="row align-items-center g-4"><div class="col-lg-8"><div class="section-kicker">One workspace</div><h2 class="section-title mb-2">Spend less time finding the right screen.</h2><p class="section-copy mb-0">The redesigned navigation groups actions around the employee workday and adapts to each account role.</p></div><div class="col-lg-4 text-lg-end"><a href="{{ route('login') }}" class="btn-pob-primary"><i class="bi bi-person-lock"></i> Employee login</a></div></div></div></div></section>
@endsection
