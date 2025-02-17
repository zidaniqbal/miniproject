@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-primary">Create Account</h2>
                <p class="text-muted">Please complete your registration</p>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger bg-danger-subtle border-0 rounded-3">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label small fw-medium">Full Name</label>
                            <input type="text" class="form-control form-control-lg bg-light border-0" id="name" name="name" value="{{ old('name') }}" required placeholder="Enter your full name">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-medium">Email Address</label>
                            <input type="email" class="form-control form-control-lg bg-light border-0" id="email" name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label small fw-medium">Password</label>
                            <div class="input-group bg-light rounded-3">
                                <input type="password" class="form-control form-control-lg bg-light border-0" id="password" name="password" required placeholder="Create a password">
                                <button class="btn toggle-password" type="button">
                                    <i class="bi bi-eye fs-5"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label small fw-medium">Confirm Password</label>
                            <div class="input-group bg-light rounded-3">
                                <input type="password" class="form-control form-control-lg bg-light border-0" id="password_confirmation" name="password_confirmation" required placeholder="Confirm your password">
                                <button class="btn toggle-password" type="button">
                                    <i class="bi bi-eye fs-5"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 rounded-3">Create Account</button>
                    </form>
                    <div class="text-center">
                        <p class="mb-0 text-muted small">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none fw-medium">Sign In</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-password').click(function() {
        const passwordInput = $(this).parent().find('input');
        const icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    });
});
</script>

<style>
:root {
    --primary-color: #4F46E5;
    --primary-hover: #4338CA;
    --bg-light: #F9FAFB;
}

body {
    background-color: var(--bg-light);
}

.form-control:focus {
    background-color: var(--bg-light) !important;
    box-shadow: none;
    border-color: var(--primary-color);
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.btn-primary {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    padding: 12px;
}

.btn-primary:hover {
    background-color: var(--primary-hover);
    border-color: var(--primary-hover);
}

.input-group {
    border: none;
}

.input-group .toggle-password {
    border: none;
    background-color: transparent;
    color: #6B7280;
    padding-right: 16px;
}

.input-group .toggle-password:hover {
    color: var(--primary-color);
}

.input-group input:focus + .toggle-password {
    border: none;
    outline: none;
}

.alert-danger {
    color: #991B1B;
}

.rounded-4 {
    border-radius: 16px !important;
}

a {
    color: var(--primary-color);
}

a:hover {
    color: var(--primary-hover);
}
</style>
@endpush
