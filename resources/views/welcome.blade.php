@extends('layouts.welcome')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h1 class="display-4 font-weight-bold">Cloud Cataloging</h1>
                    <p class="lead mt-3">Your library has never looked so good.<br>
                    Books, Board Games, Movies, Music and Video Games.</p>
                </div>
                <div class="card-body text-center">
                    <a href="{{ route('register') }}" class="btn btn-info btn-lg px-5">Get Started</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
