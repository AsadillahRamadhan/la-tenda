@extends('layout')
@section('container')
    <div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
        </div>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
    <form method="POST" onsubmit="submitForm(this, 'store')" action="{{ route('users.store') }}" class="modal fade"
        id="createModal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input required type="text" name="username" id="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input required type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="pasword">Password</label>
                        <input required type="text" id="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('js')
    <script>
        let table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url()->current() }}",
            columnDefs: [{
                "className": "dt-center",
                "targets": "_all"
            }],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'username',
                    name: 'username',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'role',
                    name: 'role',
                    orderable: true,
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: true
                },
            ]
        });
    </script>
@endpush
