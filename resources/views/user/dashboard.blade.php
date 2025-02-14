@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="display-6">
                        <i class="bi bi-grid-fill text-primary me-2"></i>User Dashboard
                    </h1>
                    <p class="lead text-muted">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-person-circle display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Profile</h5>
                    <p class="card-text">Manage your profile information</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-gear-fill display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Settings</h5>
                    <p class="card-text">Configure your account settings</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-bell-fill display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Notifications</h5>
                    <p class="card-text">View your notifications</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 