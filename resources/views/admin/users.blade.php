@extends('layouts.app')

@section('cssPage')
<style>
    /* Container & Wrapper */
    .container-fluid {
        padding: 0;
        width: 100%;
    }

    .settings-wrapper {
        background: transparent;
        width: 100%;
        margin: 0;
        padding: 1.5rem;
    }

    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 0 0.5rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    /* Card Styles */
    .settings-card {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        margin-bottom: 1rem;
        border: 1px solid #edf2f7;
    }

    /* Table Styling */
    #usersTable {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: separate;
        border-spacing: 0;
    }

    #usersTable thead th {
        background-color: #f8fafc;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        padding: 1rem 1.5rem;
        border-bottom: 2px solid #edf2f7;
        color: #64748b;
        white-space: nowrap;
    }

    #usersTable tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #edf2f7;
    }

    #usersTable tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .action-buttons .btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    /* DataTable Controls */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        width: 280px;
        outline: none;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
    }

    /* Pagination Styling */
    .dataTables_wrapper .dataTables_paginate {
        margin-top: 1.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .pagination {
        gap: 0.35rem;
    }

    .dataTables_wrapper .dataTables_paginate .page-item .page-link {
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        background-color: #ffffff;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_paginate .page-item:hover .page-link {
        background-color: #f3f4f6;
        color: #4f46e5;
    }

    .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: #ffffff;
    }

    .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
        background-color: #f9fafb;
        color: #9ca3af;
        border-color: #e5e7eb;
    }

    /* Length Menu Styling */
    .dataTables_wrapper .dataTables_length select {
        border-radius: 8px;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #4b5563;
        transition: all 0.2s ease;
    }

    .dataTables_wrapper .dataTables_length select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .page-header {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100%;
        }

        #usersTable td,
        #usersTable th {
            padding: 0.75rem 1rem;
        }
    }

    /* Badge Styling */
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-radius: 6px;
    }

    .badge.bg-danger {
        background-color: #dc3545 !important;
    }

    .badge.bg-success {
        background-color: #198754 !important;
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
    }

    .modal-body {
        padding: 1rem 1.5rem;
    }

    .modal-footer {
        padding: 1rem 1.5rem 1.5rem;
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
    .modal .btn {
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.625rem 1rem;
        border-radius: 8px;
        transition: all 0.15s ease-in-out;
    }

    .modal .btn-light {
        background-color: #F3F4F6;
        border-color: #F3F4F6;
        color: #374151;
    }

    .modal .btn-light:hover {
        background-color: #E5E7EB;
        border-color: #E5E7EB;
    }

    .modal .btn-success {
        background-color: #10B981;
        border-color: #10B981;
    }

    .modal .btn-success:hover {
        background-color: #059669;
        border-color: #059669;
    }

    .modal .btn-primary {
        background-color: #4F46E5;
        border-color: #4F46E5;
    }

    .modal .btn-primary:hover {
        background-color: #4338CA;
        border-color: #4338CA;
    }

    /* Spacing */
    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .px-4 {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 1rem;
        }
    }

    /* Toast Styles */
    .toast-container {
        z-index: 1056;
    }

    .toast {
        background-color: white;
        border-radius: 8px;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    /* Delete Modal Specific Styles */
    .modal .btn-danger {
        background-color: #DC2626;
        border-color: #DC2626;
    }

    .modal .btn-danger:hover {
        background-color: #B91C1C;
        border-color: #B91C1C;
    }

    .modal .btn-danger:disabled {
        background-color: #DC2626;
        border-color: #DC2626;
        opacity: 0.65;
    }

    /* Loading Spinner */
    .spinner-border {
        width: 1rem;
        height: 1rem;
        margin-right: 0.5rem;
        vertical-align: -0.125em;
    }

    /* Modal Body Text */
    .modal-body p {
        color: #4B5563;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Password Toggle Styles */
    .input-group .toggle-password {
        border: none;
        background-color: white;
        color: #6c757d;
        transition: all 0.2s ease;
    }

    .input-group .toggle-password:hover {
        color: #4F46E5;
        background-color: #f8fafc;
    }

    .input-group input:focus + .toggle-password {
        border: none;
        outline: none;
    }

    .input-group {
        border: 1px solid #E5E7EB;
        border-radius: 8px;
    }

    .input-group input {
        border: none !important;
    }

    .input-group input:focus {
        outline: none;
        box-shadow: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manajemen Pengguna</h1>
        <button class="btn btn-primary" id="addUser">
            <i class="bi bi-person-plus"></i> Tambah Pengguna
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="1">User</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_user_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role" class="form-label">Role</label>
                        <select class="form-select" id="edit_role" name="role" required>
                            <option value="1">User</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Hapus Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.</p>
                <input type="hidden" id="delete_user_id">
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger px-4" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>
@endsection

@section('jsPage')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        scrollX: false,
        ajax: {
            url: "{{ route('admin.users.data') }}",
            error: function (xhr, error, thrown) {
                console.error('Error DataTables:', error);
                alert('Gagal memuat data. Silakan periksa konsol untuk detail.');
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {
                data: 'role_name',
                name: 'role_name',
                render: function(data, type, row) {
                    if (data === 'Admin') {
                        return '<span class="badge bg-danger">Admin</span>';
                    } else {
                        return '<span class="badge bg-success">User</span>';
                    }
                }
            },
            {data: 'created_at_formatted', name: 'created_at'},
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return '<div class="action-buttons">' + data + '</div>';
                }
            }
        ],
        order: [[4, 'desc']],
        language: {
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Memuat...</span></div>',
            searchPlaceholder: "Cari pengguna...",
            search: "",
            lengthMenu: "_MENU_ per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            emptyTable: "Tidak ada data tersedia",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
        }
    });

    // Handle Edit Button Click
    $('#usersTable').on('click', '.edit-user', function() {
        var userId = $(this).data('id');
        
        // Reset form and error states
        $('#editUserForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Get user data
        $.ajax({
            url: "{{ route('admin.users.show', '') }}/" + userId,
            type: "GET",
            success: function(response) {
                $('#edit_user_id').val(response.id);
                $('#edit_name').val(response.name);
                $('#edit_email').val(response.email);
                $('#edit_role').val(response.role);
                $('#editUserModal').modal('show');
            },
            error: function() {
                alert('Error fetching user data');
            }
        });
    });

    // Handle Edit User Form Submit
    $('#editUserForm').submit(function(e) {
        e.preventDefault();
        
        var userId = $('#edit_user_id').val();
        
        // Reset error states
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        $.ajax({
            url: "{{ route('admin.users.update', '') }}/" + userId,
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#editUserForm button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    $('#editUserModal').modal('hide');
                    table.ajax.reload();
                    showToast(response.message, 'success');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(field, messages) {
                        let input = $('#edit_' + field);
                        input.addClass('is-invalid');
                        input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                    });
                } else {
                    showToast('An error occurred while updating the user.', 'error');
                }
            },
            complete: function() {
                $('#editUserForm button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Handle Delete Button Click
    $('#usersTable').on('click', '.delete-user', function() {
        var userId = $(this).data('id');
        $('#delete_user_id').val(userId);
        $('#deleteUserModal').modal('show');
    });

    // Handle Delete Confirmation
    $('#confirmDelete').click(function() {
        var userId = $('#delete_user_id').val();
        
        $.ajax({
            url: "{{ route('admin.users.destroy', '') }}/" + userId,
            type: "DELETE",
            data: {
                "_token": "{{ csrf_token() }}"
            },
            beforeSend: function() {
                $('#confirmDelete').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteUserModal').modal('hide');
                    table.ajax.reload();
                    showToast(response.message, 'success');
                }
            },
            error: function() {
                showToast('An error occurred while deleting the user.', 'error');
            },
            complete: function() {
                $('#confirmDelete').prop('disabled', false).text('Delete');
            }
        });
    });

    // Handle Add User Button Click
    $('#addUser').click(function() {
        $('#addUserModal').modal('show');
    });

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
                // Disable submit button
                $('#addUserForm button[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.success) {
                    // Reset form
                    $('#addUserForm')[0].reset();
                    $('#addUserModal').modal('hide');
                    
                    // Reload DataTable
                    table.ajax.reload();
                    
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
                // Re-enable submit button
                $('#addUserForm button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Toast function
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

    // Password Toggle Functionality
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
@endsection 