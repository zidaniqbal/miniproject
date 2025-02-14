@extends('layouts.landing')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 fw-bold mb-4">Shorten Your Long URLs</h1>
            <p class="lead mb-5">Create short links, QR Codes, and Link-in-bio pages. Share them anywhere.</p>
            
            @guest
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 me-sm-3">
                        Get Started - It's Free
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">
                        Login
                    </a>
                </div>
            @else
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
                    <a href="{{ auth()->user()->role == 1 ? route('user.dashboard') : route('admin.dashboard') }}" 
                       class="btn btn-primary btn-lg px-4">
                        Go to Dashboard
                    </a>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection 