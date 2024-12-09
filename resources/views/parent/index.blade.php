@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Parents</h4>

        <button type="button" class="btn btn-primary mb-3" id="addRecord" onclick="createUser()">
            <span class="tf-icons bx bx-plus"></span>&nbsp; Add Record
        </button>

        <div class="table-responsive">
            <table id="users-table" class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->middle_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone_number }}</td>
                        <td>{{ $user->branch->name }}</td>
                        <td>
                            <a href="javascript:void(0)" onclick="viewUser({{ $user->id }})" class="text-info">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="editUser({{ $user->id }})" class="text-warning me-2">
                                <i class="bx bx-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteUser({{ $user->id }})" class="text-danger me-2">
                                <i class="bx bx-trash"></i>
                            </a>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#users-table').DataTable();
        });

        // Create User Function
        function createUser() {
            $.ajax({
                url: '{{ route('branch.list') }}',
                type: 'GET',
                success: function(rooms) {
                    let branchOptions = '<option value="">Select Branch</option>';
                    rooms.forEach(room => {
                        branchOptions += `<option value="${room.id}">${room.name}</option>`;
                    });

                    Swal.fire({
                        title: '<h4 class="fw-bold text-primary">Create User</h4>',
                        html: `
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" id="first_name" class="form-control" placeholder="First Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" id="middle_name" class="form-control" placeholder="Middle Name">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" id="last_name" class="form-control" placeholder="Last Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" id="phone_number" class="form-control" placeholder="Phone Number">
                                </div>
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" id="address" class="form-control" placeholder="Address">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="province" class="form-label">Province</label>
                                    <input type="text" id="province" class="form-control" placeholder="Province">
                                </div>
                                <div class="col-md-6">
                                    <label for="birthdate" class="form-label">Birthdate</label>
                                    <input type="date" id="birthdate" class="form-control" placeholder="Birthdate">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" class="form-control" placeholder="Password">
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" id="password_confirmation" class="form-control" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="branch_id" class="form-label">Branch</label>
                                    <select id="branch_id" class="form-control">
                                        ${branchOptions}
                                    </select>
                                </div>
                            </div>
                        </div>
                    `,
                        customClass: {
                            popup: 'rounded shadow-lg p-4',
                            title: 'mb-3 text-center text-primary',
                            confirmButton: 'btn btn-primary btn-block',
                            cancelButton: 'btn btn-secondary btn-block ms-2'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Create',
                        cancelButtonText: 'Cancel',
                        focusConfirm: false,
                        preConfirm: () => {
                            const first_name = Swal.getPopup().querySelector('#first_name').value;
                            const middle_name = Swal.getPopup().querySelector('#middle_name').value;
                            const last_name = Swal.getPopup().querySelector('#last_name').value;
                            const email = Swal.getPopup().querySelector('#email').value;
                            const phone_number = Swal.getPopup().querySelector('#phone_number').value;
                            const address = Swal.getPopup().querySelector('#address').value;
                            const province = Swal.getPopup().querySelector('#province').value;
                            const birthdate = Swal.getPopup().querySelector('#birthdate').value;
                            const password = Swal.getPopup().querySelector('#password').value;
                            const password_confirmation = Swal.getPopup().querySelector('#password_confirmation').value;
                            const branch_id = Swal.getPopup().querySelector('#branch_id').value;

                            if (!first_name || !last_name || !email || !phone_number || !password || !password_confirmation) {
                                Swal.showValidationMessage('Please fill out all required fields.');
                            }
                            return { first_name, middle_name, last_name, email, phone_number, address, province, birthdate, password, password_confirmation, branch_id };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('parent.store') }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    ...result.value
                                },
                                success: function() {
                                    Swal.fire({
                                        title: '<h4 class="fw-bold text-success">Created!</h4>',
                                        text: 'User has been created successfully.',
                                        icon: 'success',
                                        customClass: {
                                            popup: 'rounded shadow-lg',
                                            confirmButton: 'btn btn-success btn-block'
                                        }
                                    }).then(() => {
                                        location.reload();
                                    });
                                },
                                error: function() {
                                    Swal.fire({
                                        title: '<h4 class="fw-bold text-danger">Error!</h4>',
                                        text: 'There was an error creating the user.',
                                        icon: 'error',
                                        customClass: {
                                            popup: 'rounded shadow-lg',
                                            confirmButton: 'btn btn-danger btn-block'
                                        }
                                    });
                                }
                            });
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        title: '<h4 class="fw-bold text-danger">Error!</h4>',
                        text: 'There was an error fetching the branches.',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded shadow-lg',
                            confirmButton: 'btn btn-danger btn-block'
                        }
                    });
                }
            });
        }

        function editUser(id) {
            $.ajax({
                url: '{{ route('parent.show', '') }}/' + id,
                type: 'GET',
                success: function(user) {
                    $.ajax({
                        url: '{{ route('branch.list') }}',
                        type: 'GET',
                        success: function(rooms) {
                            let branchOptions = '<option value="">Select Branch</option>';
                            rooms.forEach(room => {
                                branchOptions += `<option value="${room.id}" ${user.branch_id == room.id ? 'selected' : ''}>${room.name}</option>`;
                            });

                            Swal.fire({
                                title: '<h4 class="fw-bold text-primary">Edit User</h4>',
                                html: `
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" id="first_name" class="form-control" value="${user.first_name}" placeholder="First Name">
                                </div>
                                <div class="row mb-3">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" id="middle_name" class="form-control" value="${user.middle_name}" placeholder="Middle Name">
                                </div>
                                <div class="row mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" id="last_name" class="form-control" value="${user.last_name}" placeholder="Last Name">
                                </div>
                                <div class="row mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" class="form-control" value="${user.email}" placeholder="Email">
                                </div>
                                <div class="row mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="text" id="phone_number" class="form-control" value="${user.phone_number}" placeholder="Phone Number">
                                </div>
                                <div class="row mb-3">
                                    <label for="branch_id" class="form-label">Branch</label>
                                    <select id="branch_id" class="form-control">
                                        ${branchOptions}
                                    </select>
                                </div>
                            </div>
                        `,
                                customClass: {
                                    popup: 'rounded shadow-lg p-4',
                                    title: 'mb-3 text-center text-primary',
                                    confirmButton: 'btn btn-primary btn-block',
                                    cancelButton: 'btn btn-secondary btn-block ms-2'
                                },
                                showCancelButton: true,
                                confirmButtonText: 'Update',
                                cancelButtonText: 'Cancel',
                                focusConfirm: false,
                                preConfirm: () => {
                                    const first_name = Swal.getPopup().querySelector('#first_name').value;
                                    const middle_name = Swal.getPopup().querySelector('#middle_name').value;
                                    const last_name = Swal.getPopup().querySelector('#last_name').value;
                                    const email = Swal.getPopup().querySelector('#email').value;
                                    const phone_number = Swal.getPopup().querySelector('#phone_number').value;
                                    const branch_id = Swal.getPopup().querySelector('#branch_id').value;
                                    if (!first_name || !last_name || !email || !phone_number) {
                                        Swal.showValidationMessage('Please fill out all fields.');
                                    }
                                    return { first_name, middle_name, last_name, email, phone_number, branch_id };
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '{{ route('parent.update', '') }}/' + id,
                                        type: 'PUT',
                                        data: {
                                            _token: '{{ csrf_token() }}',
                                            ...result.value
                                        },
                                        success: function() {
                                            Swal.fire({
                                                title: '<h4 class="fw-bold text-success">Updated!</h4>',
                                                text: 'User has been updated successfully.',
                                                icon: 'success',
                                                customClass: {
                                                    popup: 'rounded shadow-lg',
                                                    confirmButton: 'btn btn-success btn-block'
                                                }
                                            }).then(() => {
                                                location.reload();
                                            });
                                        },
                                        error: function() {
                                            Swal.fire({
                                                title: '<h4 class="fw-bold text-danger">Error!</h4>',
                                                text: 'There was an error updating the user.',
                                                icon: 'error',
                                                customClass: {
                                                    popup: 'rounded shadow-lg',
                                                    confirmButton: 'btn btn-danger btn-block'
                                                }
                                            });
                                        }
                                    });
                                }
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: '<h4 class="fw-bold text-danger">Error!</h4>',
                                text: 'There was an error fetching the branches.',
                                icon: 'error',
                                customClass: {
                                    popup: 'rounded shadow-lg',
                                    confirmButton: 'btn btn-danger btn-block'
                                }
                            });
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        title: '<h4 class="fw-bold text-danger">Error!</h4>',
                        text: 'There was an error fetching the user details.',
                        icon: 'error',
                        customClass: {
                            popup: 'rounded shadow-lg',
                            confirmButton: 'btn btn-danger btn-block'
                        }
                    });
                }
            });
        }

        function deleteUser(id) {
            Swal.fire({
                title: '<h4 class="fw-bold text-danger">Are you sure?</h4>',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger btn-block',
                    cancelButton: 'btn btn-secondary btn-block ms-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('parent.destroy', '') }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            Swal.fire({
                                title: '<h4 class="fw-bold text-success">Deleted!</h4>',
                                text: 'User has been deleted successfully.',
                                icon: 'success',
                                customClass: {
                                    popup: 'rounded shadow-lg',
                                    confirmButton: 'btn btn-success btn-block'
                                }
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: '<h4 class="fw-bold text-danger">Error!</h4>',
                                text: 'There was an error deleting the user.',
                                icon: 'error',
                                customClass: {
                                    popup: 'rounded shadow-lg',
                                    confirmButton: 'btn btn-danger btn-block'
                                }
                            });
                        }
                    });
                }
            });
        }

        function viewUser(id) {
            window.location.href = '{{ url('parent-details/index') }}/' + id;
        }
    </script>
@endpush

