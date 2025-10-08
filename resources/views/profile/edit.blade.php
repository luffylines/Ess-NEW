@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mx-auto" style="max-width: 1000px;">
        <h2 class="h4 fw-bold mb-4">{{ __('Profile') }}</h2>
        
        <div class="row g-4">
            <div class="col-12">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="col-12">
                <div class="card shadow-sm border-danger">
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
