@extends('layouts.app')

@section('cssPage')
<style>
    /* Dashboard Styles */
    .dashboard-header {
        background: linear-gradient(to right, #4F46E5, #6366F1);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
    }

    .dashboard-header .display-6 {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .dashboard-header .lead {
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Card Styles */
    .quick-action-card {
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .quick-action-card .card-body {
        padding: 1.5rem;
    }

    .quick-action-card .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(79, 70, 229, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .quick-action-card .bi {
        font-size: 28px;
        color: #4F46E5;
    }

    .quick-action-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1F2937;
    }

    .quick-action-card .card-text {
        font-size: 0.9rem;
        color: #6B7280;
        margin-bottom: 0;
    }

    /* Goals Overview Card */
    .goals-overview-card {
        border: none;
        border-radius: 12px;
        height: 100%;
    }

    .goals-overview-card .card-body {
        padding: 1.5rem;
    }

    .goals-overview-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1F2937;
    }

    .goals-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 8px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4F46E5;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #6B7280;
    }

    /* Chart Container */
    .chart-container {
        position: relative;
        height: 250px;
        margin: 1rem 0;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }

        .goals-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .goals-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="dashboard-header shadow-sm">
        <h1 class="display-6">
            <i class="bi bi-grid-fill me-2"></i>Dasbor Pengguna
        </h1>
        <p class="lead">Selamat datang kembali, {{ auth()->user()->name }}!</p>
    </div>

    <div class="row g-4">
        <!-- Quick Action Cards -->
        <div class="col-md-6 col-lg-3">
            <div class="quick-action-card card shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h5 class="card-title">Profil Saya</h5>
                    <p class="card-text">Kelola informasi profil Anda</p>
                    <a href="{{ route('user.settings') }}" class="btn btn-light btn-sm mt-3">
                        <i class="bi bi-arrow-right me-1"></i>Kelola
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="quick-action-card card shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-gear-fill"></i>
                    </div>
                    <h5 class="card-title">Pengaturan</h5>
                    <p class="card-text">Konfigurasi akun Anda</p>
                    <a href="{{ route('user.settings') }}" class="btn btn-light btn-sm mt-3">
                        <i class="bi bi-arrow-right me-1"></i>Atur
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="quick-action-card card shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <h5 class="card-title">Notifikasi</h5>
                    <p class="card-text">Lihat notifikasi Anda</p>
                    <button class="btn btn-light btn-sm mt-3">
                        <i class="bi bi-arrow-right me-1"></i>Lihat
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="quick-action-card card shadow-sm">
                <div class="card-body text-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-newspaper"></i>
                    </div>
                    <h5 class="card-title">Berita</h5>
                    <p class="card-text">Lihat berita terbaru</p>
                    <a href="{{ route('user.news') }}" class="btn btn-light btn-sm mt-3">
                        <i class="bi bi-arrow-right me-1"></i>Baca
                    </a>
                </div>
            </div>
        </div>

        <!-- Goals Overview -->
        <div class="col-12">
            <div class="goals-overview-card card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Ringkasan Target</h5>
                        <a href="{{ route('user.goals') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i>Kelola Target
                        </a>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-lg-8">
                            <div class="chart-container">
                                <canvas id="dashboardGoalsChart"></canvas>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div id="goalsStats" class="goals-stats">
                                <!-- Stats will be inserted here by JS -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    $.get('{{ route("user.dashboard.goals-data") }}', function(response) {
        const goals = response.goals;
        
        // Calculate status counts
        const statusCounts = {
            completed: goals.filter(g => g.status === 'completed').length,
            in_progress: goals.filter(g => g.status === 'in_progress').length,
            not_started: goals.filter(g => g.status === 'not_started').length
        };

        const totalGoals = goals.length;
        
        // Update stats
        const statsHtml = `
            <div class="stat-item">
                <div class="stat-value">${totalGoals}</div>
                <div class="stat-label">Total Target</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${statusCounts.completed}</div>
                <div class="stat-label">Selesai</div>
            </div>
            <div class="stat-item">
                <div class="stat-value">${statusCounts.in_progress}</div>
                <div class="stat-label">Sedang Dikerjakan</div>
            </div>
        `;
        $('#goalsStats').html(statsHtml);

        // Create chart
        new Chart(document.getElementById('dashboardGoalsChart'), {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Sedang Dikerjakan', 'Belum Dimulai'],
                datasets: [{
                    data: [statusCounts.completed, statusCounts.in_progress, statusCounts.not_started],
                    backgroundColor: ['#059669', '#4F46E5', '#6B7280'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    });
});
</script>
@endpush
@endsection 