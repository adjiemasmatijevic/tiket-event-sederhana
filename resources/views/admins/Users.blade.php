@extends('templates.dashboard')

@section('app_name', config('app.name'))
@section('title', 'User Checkers')

@section('content')
<div class="row">
    <div class="col-12 my-4">
        <h2 class="h3 mb-1 text-primary">User Checkers</h2>
        <p class="mb-3 text-dark">Manage checker and user roles</p>
        @if (session('success'))
        <div class="mb-3 alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if (session('error'))
        <div class="mb-3 alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <div class="modal fade" id="EditRoleModal" tabindex="-1" role="dialog" aria-labelledby="EditRoleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-primary" id="EditRoleModalLabel">Edit User Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('users.management.update.role') }}" method="post">
                        <div class="modal-body text-dark">
                            @csrf
                            <input type="hidden" id="edit_id" name="id">

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" id="edit_name" readonly disabled>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" id="edit_email" readonly disabled>
                            </div>

                            <div class="form-group">
                                <label for="edit_role">Role</label>
                                <select class="form-control" id="edit_role" name="role" required>
                                    <option value="checker">Checker</option>
                                    <option value="user">User</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn mb-2 btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="user-table" class="table table-bordered table-striped datatables" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>NO</th>
                                <th>NAME</th>
                                <th>EMAIL</th>
                                <th>ROLE</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('users.management.data') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [3, 'asc']
            ]
        });
    });

    $(document).on('click', '[data-target="#EditRoleModal"]', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        let email = $(this).data('email');
        let currentRole = $(this).data('role');

        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        $('#edit_role').val(currentRole);
    });
</script>
@endsection