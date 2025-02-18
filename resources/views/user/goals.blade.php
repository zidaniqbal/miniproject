@extends('layouts.app')

@section('cssPage')
<style>
    /* Main Content Layout */
    .main-content {
        padding: 20px;
    }

    /* Card List Styles */
    .goal-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .goal-header {
        padding: 1rem;
        cursor: pointer;
        border-bottom: 1px solid #E5E7EB;
    }

    .title-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .header-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin: 0;
    }

    .goal-details {
        padding: 1rem;
        display: none;
    }

    .toggle-icon {
        transition: transform 0.2s ease;
    }

    .goal-header.active .toggle-icon {
        transform: rotate(180deg);
    }

    /* Progress Bar */
    .progress {
        height: 12px;
        background-color: #E5E7EB;
        border-radius: 6px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .progress-bar {
        background-color: #4F46E5;
        transition: width 0.3s ease;
    }

    .progress-info {
        font-size: 0.875rem;
        color: #6B7280;
    }

    /* Remaining Time Styles */
    .remaining-time {
        font-size: 0.875rem;
        color: #6B7280;
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        background-color: #F3F4F6;
    }

    .remaining-time.urgent {
        color: #DC2626;
        background-color: #FEE2E2;
    }

    .remaining-time.warning {
        color: #D97706;
        background-color: #FEF3C7;
    }

    /* Sort Controls */
    .header-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
    }

    .sort-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .sort-button {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        background-color: #fff;
        border: 1px solid #E5E7EB;
        color: #374151;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .sort-button.active {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #fff;
    }

    .sort-button.active .sort-direction {
        color: #fff;
    }

    .sort-direction {
        font-size: 0.75rem;
        color: #6B7280;
        margin-left: 0.25rem;
    }

    /* Two Column Layout */
    .goal-item {
        width: 50%;
        padding: 0.5rem;
        float: left;
    }

    @media (max-width: 768px) {
        .goal-item {
            width: 100%;
        }
        .header-controls {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        .sort-buttons {
            flex-direction: column;
        }
    }

    /* Badge Styles */
    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.75rem;
        white-space: nowrap;
    }

    .badge.priority-high { background-color: #DC2626; color: white; }
    .badge.priority-medium { background-color: #F59E0B; color: white; }
    .badge.priority-low { background-color: #2563EB; color: white; }

    .badge.status-completed { background-color: #059669; color: white; }
    .badge.status-in_progress { background-color: #4F46E5; color: white; }
    .badge.status-not_started { background-color: #6B7280; color: white; }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 2px solid #edf2f7;
        padding: 1.5rem;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a1a1a;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 2px solid #edf2f7;
        padding: 1.5rem;
    }

    /* Form Controls */
    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: #111827;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-select {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        padding: 0.625rem 2.25rem 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: #111827;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out;
    }

    .form-select:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Button Styles */
    .btn {
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #4F46E5;
        border-color: #4F46E5;
        color: #ffffff;
    }

    .btn-primary:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }

    .btn-light {
        background-color: #F9FAFB;
        border-color: #E5E7EB;
        color: #374151;
    }

    .btn-light:hover {
        background-color: #F3F4F6;
        border-color: #D1D5DB;
    }

    .btn-danger {
        background-color: #DC2626;
        border-color: #DC2626;
        color: #ffffff;
    }

    .btn-danger:hover {
        background-color: #B91C1C;
        border-color: #B91C1C;
    }

    /* Toast Container */
    .toast-container {
        z-index: 1056;
    }

    .toast {
        background-color: white;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .toast.bg-success {
        background-color: #059669 !important;
    }

    .toast.bg-danger {
        background-color: #DC2626 !important;
    }

    /* Progress Slider Styles - New Version */
    .progress-input-container {
        margin: 20px 0;
    }

    .progress-value-display {
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        color: #4F46E5;
        margin-bottom: 10px;
    }

    .slider-container {
        position: relative;
        height: 40px;
        display: flex;
        align-items: center;
    }

    .progress-track {
        position: absolute;
        width: 100%;
        height: 8px;
        background: #E5E7EB;
        border-radius: 4px;
    }

    .progress-fill {
        position: absolute;
        height: 8px;
        background: #4F46E5;
        border-radius: 4px;
        transition: width 0.2s ease;
    }

    input[type="range"].progress-input {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 40px;
        background: transparent;
        position: relative;
        z-index: 2;
        margin: 0;
    }

    input[type="range"].progress-input::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        background: #4F46E5;
        border: 3px solid #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0 0 1px #4F46E5, 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    input[type="range"].progress-input::-moz-range-thumb {
        width: 24px;
        height: 24px;
        background: #4F46E5;
        border: 3px solid #fff;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0 0 1px #4F46E5, 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    input[type="range"].progress-input:focus::-webkit-slider-thumb {
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px #4F46E5, 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    input[type="range"].progress-input:focus::-moz-range-thumb {
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px #4F46E5, 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 8px;
        color: #6B7280;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header mb-4">
        <h1 class="page-title">Target Saya</h1>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <!-- Progress Chart -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">Statistik Progress Target</h5>
                        <div class="text-muted small">Update terakhir: {{ now()->format('d M Y') }}</div>
                    </div>
                    <div class="chart-container" style="position: relative; height:420px;">
                        <canvas id="goalsProgressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribution Charts -->
        <div class="col-lg-4">
            <div class="row h-100">
                <!-- Status Chart -->
                <div class="col-md-6 col-lg-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Distribusi Status</h6>
                            <div class="chart-container" style="position: relative; height:190px;">
                                <canvas id="goalsStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Priority Chart -->
                <div class="col-md-6 col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Distribusi Prioritas</h6>
                            <div class="chart-container" style="position: relative; height:190px;">
                                <canvas id="goalsPriorityChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controls Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="sort-buttons d-flex gap-2 flex-wrap">
                    <button class="sort-button" id="sortTimeRemaining">
                        <i class="bi bi-clock"></i>
                        Urutkan Waktu Tersisa
                        <span class="sort-direction">(Paling Sedikit)</span>
                    </button>
                    <button class="sort-button" id="sortCreatedAt">
                        <i class="bi bi-calendar"></i>
                        Urutkan Tanggal Dibuat
                        <span class="sort-direction">(Paling Baru)</span>
                    </button>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createGoalModal">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Target Baru
                </button>
            </div>
        </div>
    </div>

    <div class="settings-wrapper">
        <div class="row" id="goalsContainer">
            @forelse($goals as $goal)
                @php
                    $now = \Carbon\Carbon::now();
                    $targetDate = \Carbon\Carbon::parse($goal->target_date);
                    $diffInMonths = $now->diffInMonths($targetDate);
                    $diffInDays = $now->diffInDays($targetDate);
                    $diffInHours = $now->diffInHours($targetDate);
                    
                    // Menentukan kelas untuk styling
                    $isUrgent = $diffInHours <= 24;
                    $isWarning = $diffInDays <= 7;
                    $remainingClass = $isUrgent ? 'urgent' : ($isWarning ? 'warning' : '');
                    
                    // Format teks waktu tersisa
                    if ($targetDate->isPast()) {
                        $remainingText = 'Telah lewat';
                    } else {
                        if ($diffInMonths > 0) {
                            $remainingText = 'Waktu tersisa: ' . $diffInMonths . ' bulan';
                        } elseif ($diffInDays > 0) {
                            $remainingText = 'Waktu tersisa: ' . $diffInDays . ' hari';
                        } else {
                            $remainingText = 'Waktu tersisa: ' . $diffInHours . ' jam';
                        }
                    }
                @endphp
                <div class="goal-item" 
                     data-remaining="{{ $goal->target_date->isPast() ? -1 : $goal->target_date->diffInMinutes(now()) }}"
                     data-created="{{ $goal->created_at->timestamp }}">
                    <div class="goal-card">
                        <div class="goal-header">
                            <div class="title-section">
                                <h5 class="card-title">{{ $goal->title }}</h5>
                            </div>
                            <div class="header-info">
                                <div class="remaining-time {{ $remainingClass }}">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $remainingText }}
                                </div>
                                <span class="badge status-{{ $goal->status }}">
                                    {{ $goal->status === 'completed' ? 'Selesai' : 
                                       ($goal->status === 'in_progress' ? 'Sedang Dikerjakan' : 'Belum Dimulai') }}
                                </span>
                                <i class="bi bi-chevron-down toggle-icon"></i>
                            </div>
                        </div>
                        <div class="goal-details" style="display: none;">
                            @if($goal->description)
                                <p class="card-text goal-description">{{ $goal->description }}</p>
                            @else
                                <p class="card-text goal-description text-muted fst-italic">Tidak ada deskripsi</p>
                            @endif
                            <button class="btn btn-sm btn-light edit-description mb-3" data-goal-id="{{ $goal->id }}">
                                <i class="bi bi-pencil me-1"></i>Edit Deskripsi
                            </button>
                            
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" 
                                     style="width: {{ $goal->progress }}%" 
                                     aria-valuenow="{{ $goal->progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <div class="progress-info d-flex justify-content-between text-muted small mt-1">
                                <span>Progres: {{ $goal->progress }}%</span>
                                <span>Target: {{ $goal->target_date->format('d M Y') }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="badge priority-{{ $goal->priority }}">
                                    {{ $goal->priority === 'high' ? 'Tinggi' : ($goal->priority === 'medium' ? 'Sedang' : 'Rendah') }}
                                </span>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <button class="btn btn-sm btn-primary update-progress" 
                                        data-bs-toggle="modal"
                                        data-bs-target="#updateProgressModal"
                                        data-goal-id="{{ $goal->id }}"
                                        data-current-progress="{{ $goal->progress }}"
                                        data-current-status="{{ $goal->status }}">
                                    <i class="bi bi-arrow-up-circle me-1"></i>Update Progres
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-goal"
                                        data-goal-id="{{ $goal->id }}">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle me-2"></i>
                        Anda belum memiliki target. Mulai dengan menambahkan target baru!
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Create Goal Modal -->
<div class="modal fade" id="createGoalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Target Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createGoalForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="target_date" class="form-label">Tanggal Target</label>
                        <input type="date" class="form-control" id="target_date" name="target_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Prioritas</label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low">Rendah</option>
                            <option value="medium" selected>Sedang</option>
                            <option value="high">Tinggi</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Buat Target</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Progress Modal -->
<div class="modal fade" id="updateProgressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Progress</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateProgressForm">
                <div class="modal-body">
                    <div class="progress-input-container">
                        <div class="progress-value-display">
                            <span class="progress-percentage">0</span>%
                        </div>
                        <div class="slider-container">
                            <div class="progress-track"></div>
                            <div class="progress-fill"></div>
                            <input type="range" 
                                   class="progress-input" 
                                   name="progress" 
                                   min="0" 
                                   max="100" 
                                   value="0">
                        </div>
                        <div class="progress-labels">
                            <span>0%</span>
                            <span>50%</span>
                            <span>100%</span>
                        </div>
                    </div>
                    <input type="hidden" name="status" id="status">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Goal Modal -->
<div class="modal fade" id="deleteGoalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Target</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus target ini? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Description Modal -->
<div class="modal fade" id="editDescriptionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Deskripsi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDescriptionForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4" placeholder="Tambahkan deskripsi target Anda..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
        </div>
    </div>
</div>
@endsection

@section('jsPage')
<script>
$(document).ready(function() {
    // Get last sort state from localStorage
    const lastSortType = localStorage.getItem('goalSortType') || 'time'; // default to time
    const lastSortDirection = localStorage.getItem('goalSortDirection') || 'asc'; // default to ascending

    // Function to sort items
    function sortItems(type, direction) {
        const container = $('#goalsContainer');
        const items = $('.goal-item').get();
        
        items.sort(function(a, b) {
            let aVal, bVal;
            
            if (type === 'time') {
                aVal = parseInt($(a).data('remaining'));
                bVal = parseInt($(b).data('remaining'));
            } else { // type === 'created'
                aVal = parseInt($(a).data('created'));
                bVal = parseInt($(b).data('created'));
            }
            
            return direction === 'asc' ? aVal - bVal : bVal - aVal;
        });
        
        container.append(items);
    }

    // Function to update sort button UI
    function updateSortButtonUI(button, direction) {
        // Remove active class from all sort buttons
        $('.sort-button').removeClass('active');
        // Add active class to clicked button
        button.addClass('active');

        if (button.attr('id') === 'sortTimeRemaining') {
            button.find('i').toggleClass('bi-clock bi-clock-history');
            button.find('.sort-direction').text(
                direction === 'asc' ? '(Paling Sedikit)' : '(Paling Lama)'
            );
        } else {
            button.find('i').toggleClass('bi-calendar bi-calendar-check');
            button.find('.sort-direction').text(
                direction === 'asc' ? '(Paling Lama)' : '(Paling Baru)'
            );
        }
    }

    // Apply last sort and active state on page load
    if (lastSortType === 'time') {
        sortItems('time', lastSortDirection);
        updateSortButtonUI($('#sortTimeRemaining'), lastSortDirection);
    } else {
        sortItems('created', lastSortDirection);
        updateSortButtonUI($('#sortCreatedAt'), lastSortDirection);
    }

    // Sort by Time Remaining
    let timeRemainingAsc = lastSortType === 'time' ? (lastSortDirection === 'asc') : true;
    $('#sortTimeRemaining').click(function() {
        timeRemainingAsc = !timeRemainingAsc;
        sortItems('time', timeRemainingAsc ? 'asc' : 'desc');
        updateSortButtonUI($(this), timeRemainingAsc ? 'asc' : 'desc');
        
        // Save sort state
        localStorage.setItem('goalSortType', 'time');
        localStorage.setItem('goalSortDirection', timeRemainingAsc ? 'asc' : 'desc');
    });

    // Sort by Created At
    let createdAtAsc = lastSortType === 'created' ? (lastSortDirection === 'asc') : true;
    $('#sortCreatedAt').click(function() {
        createdAtAsc = !createdAtAsc;
        sortItems('created', createdAtAsc ? 'asc' : 'desc');
        updateSortButtonUI($(this), createdAtAsc ? 'asc' : 'desc');
        
        // Save sort state
        localStorage.setItem('goalSortType', 'created');
        localStorage.setItem('goalSortDirection', createdAtAsc ? 'asc' : 'desc');
    });

    // Toggle goal details
    $(document).on('click', '.goal-header', function() {
        $(this).toggleClass('active');
        $(this).siblings('.goal-details').slideToggle(200);
    });

    // Add CSRF token to all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toast function
    function showToast(message, type = 'success') {
        const toast = $('#toast');
        toast.removeClass('bg-success bg-danger');
        
        if (type === 'success') {
            toast.addClass('bg-success');
        } else {
            toast.addClass('bg-danger');
        }
        
        $('.toast-body').text(message);
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    // Validation functions
    function validateGoalForm(formData) {
        const title = formData.get('title');
        const targetDate = formData.get('target_date');
        const priority = formData.get('priority');
        
        if (!title || title.trim() === '') {
            showToast('Judul target tidak boleh kosong', 'error');
            return false;
        }
        
        if (!targetDate) {
            showToast('Tanggal target harus diisi', 'error');
            return false;
        }
        
        const today = new Date();
        const targetDateObj = new Date(targetDate);
        if (targetDateObj <= today) {
            showToast('Tanggal target harus lebih dari hari ini', 'error');
            return false;
        }
        
        if (!priority) {
            showToast('Prioritas harus dipilih', 'error');
            return false;
        }
        
        return true;
    }

    function validateProgressForm(formData) {
        const progress = formData.get('progress');
        const status = formData.get('status');
        
        if (progress === null || progress === undefined) {
            showToast('Progress harus diisi', 'error');
            return false;
        }
        
        if (!status) {
            showToast('Status harus dipilih', 'error');
            return false;
        }
        
        return true;
    }

    // Create Goal Form Submit
    $('#createGoalForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '{{ route("user.goals.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('Target berhasil dibuat!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    const firstError = Object.values(errors)[0][0];
                    showToast(firstError, 'error');
                } else {
                    showToast('Terjadi kesalahan saat membuat target', 'error');
                }
            }
        });
    });

    // Progress Modal Handling
    let currentGoalId = null;

    $('.update-progress').on('click', function() {
        const goalId = $(this).data('goal-id');
        const currentProgress = $(this).data('current-progress');
        
        currentGoalId = goalId;
        
        // Set current values
        $('.progress-input').val(currentProgress);
        $('.progress-fill').css('width', `${currentProgress}%`);
        $('.progress-percentage').text(currentProgress);
        updateStatusBasedOnProgress(currentProgress);
    });

    // Progress input handling
    $('.progress-input').on('input', function() {
        const progress = $(this).val();
        $('.progress-fill').css('width', `${progress}%`);
        $('.progress-percentage').text(progress);
        updateStatusBasedOnProgress(progress);
    });

    // Update status based on progress
    function updateStatusBasedOnProgress(progress) {
        let status;
        if (progress == 0) {
            status = 'not_started';
        } else if (progress == 100) {
            status = 'completed';
        } else {
            status = 'in_progress';
        }
        $('#status').val(status);
    }

    // Form submission
    $('#updateProgressForm').on('submit', function(e) {
        e.preventDefault();
        if (!currentGoalId) return;

        const formData = new FormData(this);
        
        $.ajax({
            url: `/user/goals/${currentGoalId}/progress`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('Progress berhasil diperbarui!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(response.message || 'Terjadi kesalahan saat memperbarui progress', 'error');
                }
            },
            error: function(xhr) {
                console.error('Error updating progress:', xhr);
                const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat memperbarui progress';
                showToast(errorMessage, 'error');
            }
        });
    });

    // Delete Goal
    let goalToDelete = null;
    let goalItemToDelete = null;

    $(document).on('click', '.delete-goal', function() {
        goalToDelete = $(this).data('goal-id');
        goalItemToDelete = $(this).closest('.goal-item');
        $('#deleteGoalModal').modal('show');
    });

    $('#confirmDelete').on('click', function() {
        if (!goalToDelete) return;
        
        $.ajax({
            url: `/user/goals/${goalToDelete}`,
            method: 'DELETE',
            success: function(response) {
                if (response.success) {
                    showToast('Target berhasil dihapus!', 'success');
                    goalItemToDelete.fadeOut(300, function() {
                        $(this).remove();
                        if ($('.goal-item').length === 0) {
                            $('#goalsContainer').html(`
                                <div class="col-12">
                                    <div class="alert alert-info text-center">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Anda belum memiliki target. Mulai dengan menambahkan target baru!
                                    </div>
                                </div>
                            `);
                        }
                    });
                    $('#deleteGoalModal').modal('hide');
                }
            },
            error: function() {
                showToast('Terjadi kesalahan saat menghapus target', 'error');
            },
            complete: function() {
                goalToDelete = null;
                goalItemToDelete = null;
            }
        });
    });

    // Set minimum date for target_date input
    const today = new Date().toISOString().split('T')[0];
    $('#target_date').attr('min', today);

    // Chart Initialization
    function initializeCharts() {
        const goals = @json($goals);
        
        // Progress Over Time Chart
        const progressData = goals.map(goal => ({
            date: new Date(goal.created_at),
            progress: goal.progress
        })).sort((a, b) => a.date - b.date);

        // Common chart options
        const commonChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 11
                        }
                    }
                }
            }
        };

        // Progress Chart
        new Chart(document.getElementById('goalsProgressChart'), {
            type: 'line',
            data: {
                labels: progressData.map(d => d.date.toLocaleDateString()),
                datasets: [{
                    label: 'Progress Target (%)',
                    data: progressData.map(d => d.progress),
                    borderColor: '#4F46E5',
                    tension: 0.3
                }]
            },
            options: {
                ...commonChartOptions,
                plugins: {
                    ...commonChartOptions.plugins,
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: value => `${value}%`,
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            maxRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.3,
                        borderWidth: 2
                    },
                    point: {
                        radius: 3,
                        hoverRadius: 5
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCounts = {
            completed: goals.filter(g => g.status === 'completed').length,
            in_progress: goals.filter(g => g.status === 'in_progress').length,
            not_started: goals.filter(g => g.status === 'not_started').length
        };

        // Doughnut charts options
        const doughnutOptions = {
            ...commonChartOptions,
            cutout: '65%',
            plugins: {
                ...commonChartOptions.plugins,
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 11
                        }
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            }
        };

        // Apply to status and priority charts
        new Chart(document.getElementById('goalsStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Selesai', 'Sedang Dikerjakan', 'Belum Dimulai'],
                datasets: [{
                    data: [statusCounts.completed, statusCounts.in_progress, statusCounts.not_started],
                    backgroundColor: ['#059669', '#4F46E5', '#6B7280']
                }]
            },
            options: doughnutOptions
        });

        // Priority Distribution Chart
        const priorityCounts = {
            high: goals.filter(g => g.priority === 'high').length,
            medium: goals.filter(g => g.priority === 'medium').length,
            low: goals.filter(g => g.priority === 'low').length
        };

        new Chart(document.getElementById('goalsPriorityChart'), {
            type: 'doughnut',
            data: {
                labels: ['Tinggi', 'Sedang', 'Rendah'],
                datasets: [{
                    data: [priorityCounts.high, priorityCounts.medium, priorityCounts.low],
                    backgroundColor: ['#DC2626', '#F59E0B', '#2563EB']
                }]
            },
            options: doughnutOptions
        });
    }

    initializeCharts();

    // Edit Description Handling
    let descriptionGoalId = null;

    $(document).on('click', '.edit-description', function(e) {
        e.preventDefault();
        descriptionGoalId = $(this).data('goal-id');
        
        // Get current description
        const currentDescription = $(this).siblings('.goal-description').text().trim();
        const description = currentDescription === 'Tidak ada deskripsi' ? '' : currentDescription;
        
        // Set current description in modal
        $('#editDescriptionModal textarea[name="description"]').val(description);
        
        // Show modal
        $('#editDescriptionModal').modal('show');
    });

    // Edit Description Form Submit
    $('#editDescriptionForm').on('submit', function(e) {
        e.preventDefault();
        if (!descriptionGoalId) return;

        const formData = new FormData(this);
        
        $.ajax({
            url: `/user/goals/${descriptionGoalId}/description`,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showToast('Deskripsi berhasil diperbarui!', 'success');
                    
                    // Update description in the card
                    const newDescription = formData.get('description').trim();
                    const descriptionElement = $(`.edit-description[data-goal-id="${descriptionGoalId}"]`)
                        .siblings('.goal-description');
                    
                    if (newDescription) {
                        descriptionElement.text(newDescription).removeClass('text-muted fst-italic');
                    } else {
                        descriptionElement.text('Tidak ada deskripsi').addClass('text-muted fst-italic');
                    }
                    
                    // Close modal
                    $('#editDescriptionModal').modal('hide');
                } else {
                    showToast(response.message || 'Terjadi kesalahan saat memperbarui deskripsi', 'error');
                }
            },
            error: function(xhr) {
                console.error('Error updating description:', xhr);
                const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat memperbarui deskripsi';
                showToast(errorMessage, 'error');
            },
            complete: function() {
                descriptionGoalId = null;
            }
        });
    });

    // Reset form when modal is closed
    $('#editDescriptionModal').on('hidden.bs.modal', function() {
        $('#editDescriptionForm')[0].reset();
        descriptionGoalId = null;
    });
});
</script>
@endsection 