@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="display-6">
                        <i class="bi bi-speedometer2 text-primary me-2"></i>Admin Dashboard
                    </h1>
                    <p class="lead text-muted">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Users</h6>
                            <h2 class="my-2">{{ \App\Models\User::where('role', 1)->count() }}</h2>
                        </div>
                        <i class="bi bi-people-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Admins</h6>
                            <h2 class="my-2">{{ \App\Models\User::where('role', 2)->count() }}</h2>
                        </div>
                        <i class="bi bi-person-fill-gear display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">New Users</h6>
                            <h2 class="my-2">{{ \App\Models\User::whereDate('created_at', today())->count() }}</h2>
                        </div>
                        <i class="bi bi-person-plus-fill display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Roles</h6>
                            <h2 class="my-2">3</h2>
                        </div>
                        <i class="bi bi-shield-fill-check display-6"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-table me-2"></i>Recent Users
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 2)
                                            <span class="badge bg-success">Admin</span>
                                        @elseif($user->role == 1)
                                            <span class="badge bg-primary">User</span>
                                        @else
                                            <span class="badge bg-secondary">Guest</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-gear-fill me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-person-plus-fill me-2"></i>Add New User
                        </button>
                        <button class="btn btn-info text-white">
                            <i class="bi bi-people-fill me-2"></i>Manage Users
                        </button>
                        <button class="btn btn-success">
                            <i class="bi bi-shield-fill me-2"></i>Manage Roles
                        </button>
                        <button class="btn btn-warning text-white">
                            <i class="bi bi-gear-fill me-2"></i>System Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 