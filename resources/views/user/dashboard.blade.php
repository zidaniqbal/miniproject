@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="display-6">
                        <i class="bi bi-grid-fill text-primary me-2"></i>Dasbor Pengguna
                    </h1>
                    <p class="lead text-muted">Selamat datang kembali, {{ auth()->user()->name }}!</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-person-circle display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Profil</h5>
                    <p class="card-text">Kelola informasi profil Anda</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-gear-fill display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Pengaturan</h5>
                    <p class="card-text">Konfigurasi akun Anda</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="bi bi-bell-fill display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Notifikasi</h5>
                    <p class="card-text">Lihat notifikasi Anda</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 