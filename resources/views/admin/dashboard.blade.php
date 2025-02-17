@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Dasbor Admin</h1>
    </div>

    <!-- Dashboard Content Wrapper -->
    <div class="settings-wrapper">
        <!-- Statistics Cards Row -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="settings-card bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Pengguna</h6>
                            <h2 class="my-2">{{ \App\Models\User::where('role', 1)->count() }}</h2>
                            <p class="mb-0 small">
                                <i class="bi bi-arrow-up-short"></i>
                                {{ \App\Models\User::where('role', 1)->whereMonth('created_at', now()->month)->count() }} bulan ini
                            </p>
                        </div>
                        <i class="bi bi-people-fill display-6"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="settings-card bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Admin Aktif</h6>
                            <h2 class="my-2">{{ \App\Models\User::where('role', 2)->count() }}</h2>
                            <p class="mb-0 small">
                                <i class="bi bi-shield-check"></i>
                                Mengelola {{ \App\Models\User::count() }} total akun
                            </p>
                        </div>
                        <i class="bi bi-person-fill-gear display-6"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="settings-card bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pengguna Baru Hari Ini</h6>
                            <h2 class="my-2">{{ \App\Models\User::whereDate('created_at', today())->count() }}</h2>
                            <p class="mb-0 small">
                                <i class="bi bi-clock-history"></i>
                                {{ \App\Models\User::whereDate('created_at', today()->subDay())->count() }} kemarin
                            </p>
                        </div>
                        <i class="bi bi-person-plus-fill display-6"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="settings-card bg-warning text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Pertumbuhan Pengguna</h6>
                            <h2 class="my-2">
                                {{ number_format((\App\Models\User::whereMonth('created_at', now()->month)->count() / 
                                   max(\App\Models\User::whereMonth('created_at', now()->subMonth()->month)->count(), 1) - 1) * 100, 1) }}%
                            </h2>
                            <p class="mb-0 small">
                                <i class="bi bi-graph-up"></i>
                                Dibandingkan bulan lalu
                            </p>
                        </div>
                        <i class="bi bi-graph-up-arrow display-6"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="settings-card">
                    <h2 class="settings-section-title">Tren Pertumbuhan Pengguna</h2>
                    <div id="userGrowthChart"></div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="settings-card">
                    <h2 class="settings-section-title">Distribusi Pengguna</h2>
                    <div id="userDistributionChart"></div>
                </div>
            </div>
        </div>

        <!-- Recent Users and Quick Actions Row -->
        <div class="row">
            <div class="col-md-8 mb-4">
                <div class="settings-card">
                    <h2 class="settings-section-title">Pengguna Terbaru</h2>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran</th>
                                    <th>Bergabung</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <span class="text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            {{ $user->name }}
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 2)
                                            <span class="badge bg-danger">Admin</span>
                                        @else
                                            <span class="badge bg-success">Pengguna</span>
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
            <div class="col-md-4 mb-4">
                <div class="settings-card">
                    <h2 class="settings-section-title">Aksi Cepat</h2>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users') }}" class="btn btn-primary">
                            <i class="bi bi-people-fill me-2"></i>Kelola Pengguna
                        </a>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus-fill me-2"></i>Tambah Pengguna Baru
                        </button>
                        <button class="btn btn-info text-white">
                            <i class="bi bi-gear-fill me-2"></i>Pengaturan Sistem
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Peran</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="1">Pengguna</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Tambah Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Card Styles */
.settings-card {
    background: #ffffff;
    padding: 1.5rem;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    margin-bottom: 1rem;
    border: 1px solid #edf2f7;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.settings-section-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0;
    padding: 1.25rem;
}

.card-body {
    padding: 1.25rem;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0;
}

/* Modal Styles */
.modal-content {
    border: none;
    border-radius: 16px;
    background: #fff;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

.modal-header {
    padding: 1.5rem 1.5rem 1rem;
    border: 0;
}

.modal-body {
    padding: 1rem 1.5rem;
}

.modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    border: 0;
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
}

.btn-close {
    background-size: 0.8em;
    opacity: 0.5;
}

/* Form Styles */
.form-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border: 1px solid #E5E7EB;
    border-radius: 8px;
    padding: 0.625rem 0.875rem;
    font-size: 0.875rem;
    color: #111827;
    background-color: #fff;
    transition: border-color 0.15s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: #4F46E5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-control::placeholder {
    color: #9CA3AF;
}

/* Button Styles */
.btn {
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.625rem 1rem;
    border-radius: 8px;
    transition: all 0.15s ease-in-out;
}

.btn-light {
    background-color: #F3F4F6;
    border-color: #F3F4F6;
    color: #374151;
}

.btn-light:hover {
    background-color: #E5E7EB;
    border-color: #E5E7EB;
}

.btn-success {
    background-color: #10B981;
    border-color: #10B981;
}

.btn-success:hover {
    background-color: #059669;
    border-color: #059669;
}

.btn-primary {
    background-color: #4F46E5;
    border-color: #4F46E5;
}

.btn-primary:hover {
    background-color: #4338CA;
    border-color: #4338CA;
}

.btn-info {
    background-color: #0EA5E9;
    border-color: #0EA5E9;
}

.btn-info:hover {
    background-color: #0284C7;
    border-color: #0284C7;
}

/* Table Styles */
.table {
    margin-bottom: 0;
}

.table > :not(caption) > * > * {
    padding: 1rem 1.25rem;
}

.table tbody tr:hover {
    background-color: #F8FAFC;
}

/* Badge Styles */
.badge {
    padding: 0.5em 0.8em;
    font-weight: 500;
    font-size: 0.75rem;
    border-radius: 6px;
}

.badge.bg-success {
    background-color: #10B981 !important;
}

.badge.bg-danger {
    background-color: #DC2626 !important;
}

/* Avatar Styles */
.avatar-sm {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Chart Styles */
.apexcharts-canvas {
    margin: 0 auto;
}

.apexcharts-tooltip {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.apexcharts-tooltip-title {
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 8px 12px;
    font-size: 0.875rem;
}

.apexcharts-tooltip-y-group {
    padding: 8px 12px;
}

/* Loading Spinner */
.spinner-border {
    width: 1rem;
    height: 1rem;
    margin-right: 0.5rem;
    vertical-align: -0.125em;
}

/* Statistics Card */
.settings-card.bg-primary,
.settings-card.bg-success,
.settings-card.bg-info,
.settings-card.bg-warning {
    min-height: 140px;
}

/* Chart Container */
#userGrowthChart,
#userDistributionChart {
    width: 100%;
    min-height: 350px;
}

/* Recent Users Table */
.table-responsive {
    flex: 1;
    min-height: 0;
    max-height: 400px;
    overflow-y: auto;
}

.table thead th {
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 10;
}

/* Quick Actions */
.d-grid {
    gap: 0.75rem !important;
}

/* Responsive */
@media (max-width: 768px) {
    .settings-card {
        padding: 1rem;
    }

    .statistics-card {
        min-height: auto;
    }

    #userGrowthChart,
    #userDistributionChart {
        min-height: 250px;
    }

    .table-responsive {
        max-height: 300px;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.settings-card {
    animation: fadeIn 0.3s ease-out;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Growth Chart
    const userGrowthOptions = {
        series: [{
            name: 'New Users',
            data: @json($userGrowthData['counts'])
        }],
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        xaxis: {
            categories: @json($userGrowthData['dates']),
            labels: {
                style: {
                    colors: '#64748b'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#64748b'
                }
            }
        },
        tooltip: {
            x: {
                format: 'dd MMM yyyy'
            },
        },
        colors: ['#4f46e5'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        }
    };

    // User Distribution Chart
    const userDistributionOptions = {
        series: @json($userDistributionData['counts']),
        chart: {
            type: 'donut',
            height: 350
        },
        labels: @json($userDistributionData['labels']),
        colors: ['#4f46e5', '#dc3545'],
        plotOptions: {
            pie: {
                donut: {
                    size: '70%'
                }
            }
        },
        legend: {
            position: 'bottom',
            labels: {
                colors: '#64748b'
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            }
        }
    };

    const userGrowthChart = new ApexCharts(document.querySelector("#userGrowthChart"), userGrowthOptions);
    const userDistributionChart = new ApexCharts(document.querySelector("#userDistributionChart"), userDistributionOptions);

    userGrowthChart.render();
    userDistributionChart.render();
});
</script>

<script>
$(document).ready(function() {
    // Handle Add User Form Submit
    $('#addUserForm').submit(function(e) {
        e.preventDefault();
        
        // Reset error states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.ajax({
            url: "{{ route('admin.users.store') }}",
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#addUserForm button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#addUserForm')[0].reset();
                    $('#addUserModal').modal('hide');
                    
                    // Reload table data
                    location.reload();
                    
                    // Show success message
                    showToast(response.message, 'success');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        let input = $('#' + field);
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                    });
                } else {
                    showToast('An error occurred while creating the user.', 'error');
                }
            },
            complete: function() {
                $('#addUserForm button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Toast function (if not already defined)
    function showToast(message, type = 'success') {
        const toast = $('#toast');
        toast.removeClass('bg-success bg-danger bg-warning bg-info');
        
        switch(type) {
            case 'success':
                toast.addClass('bg-success');
                break;
            case 'error':
                toast.addClass('bg-danger');
                break;
            case 'warning':
                toast.addClass('bg-warning');
                break;
            case 'info':
                toast.addClass('bg-info');
                break;
        }
        
        $('.toast-body').text(message);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }
});
</script>
@endpush
@endsection 