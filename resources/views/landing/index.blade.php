@extends('layouts.landing')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 fw-bold mb-4">Shorten Your Long URLs</h1>
            <p class="lead mb-5">Create short links, QR Codes, and Link-in-bio pages. Share them anywhere.</p>
            
            @guest
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
                    <button class="btn btn-primary btn-lg px-4 me-sm-3" data-bs-toggle="modal" data-bs-target="#registerModal">
                        Get Started - It's Free
                    </button>
                    <button class="btn btn-outline-primary btn-lg px-4" data-bs-toggle="modal" data-bs-target="#loginModal">
                        Learn More
                    </button>
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

@section('modals')
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('images/profile-logo.png') }}" alt="Profile" class="mb-4" height="60">
                <h5 class="modal-title mb-4">Login with Email</h5>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email *" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password *" required>
                    </div>
                    <div class="mb-3 text-end">
                        <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                    <div class="mt-3">
                        <span>Don't have an account? </span>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Register Now</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ asset('images/profile-logo.png') }}" alt="Profile" class="mb-4" height="60">
                <h5 class="modal-title mb-4">Register</h5>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full Name *" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email *" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password *" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password *" required>
                    </div>
                    <div class="mb-3 form-check text-start">
                        <input type="checkbox" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">I have read and agree with the Terms of Service</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                    <div class="mt-3">
                        <span>Already have an account? </span>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 