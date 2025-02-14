@extends('layouts.app')

@section('cssPage')
<style>
    /* Layout & Container */
    .container-fluid {
        padding: 2rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header Section */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .btn-primary {
        padding: 0.625rem 1.25rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    /* Table Container */
    .dataTables_wrapper {
        background: #ffffff;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
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
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">User Management</h1>
        <button class="btn btn-primary" id="addUser">
            <i class="bi bi-plus"></i> Add User
        </button>
    </div>

    <div class="table-responsive" style="margin-top: 20px;">
        <table class="table" id="usersTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="1" selected>User</option>
                            <option value="2">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>
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
                console.error('DataTables Error:', error);
                alert('Error loading data. Please check console for details.');
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role_name', name: 'role_name'},
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
            processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
            searchPlaceholder: "Search users...",
            search: "",
            lengthMenu: "_MENU_ per page",
        },
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm');
        }
    });

    // Handle Edit Button Click
    $('#usersTable').on('click', '.edit-user', function() {
        var userId = $(this).data('id');
        console.log('Edit user: ' + userId);
    });

    // Handle Delete Button Click
    $('#usersTable').on('click', '.delete-user', function() {
        var userId = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            console.log('Delete user: ' + userId);
        }
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
                    alert(response.message);
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
                    alert('An error occurred while creating the user. Please try again.');
                }
            },
            complete: function() {
                // Re-enable submit button
                $('#addUserForm button[type="submit"]').prop('disabled', false);
            }
        });
    });
});
</script>
@endsection 