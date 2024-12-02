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
                        <td>
                            <a href="javascript:void(0)" onclick="editUser({{ $user->id }})" class="text-warning me-2">
                                <i class="bx bx-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteUser({{ $user->id }})" class="text-danger">
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

        function createUser() {
            Swal.fire({
                title: 'Create User',
                html: `
                    <input type="text" id="first_name" class="swal2-input" placeholder="First Name">
                    <input type="text" id="middle_name" class="swal2-input" placeholder="Middle Name">
                    <input type="text" id="last_name" class="swal2-input" placeholder="Last Name">
                    <input type="email" id="email" class="swal2-input" placeholder="Email">
                    <input type="text" id="phone_number" class="swal2-input" placeholder="Phone Number">
                    <input type="password" id="password" class="swal2-input" placeholder="Password">
                    <input type="password" id="password_confirmation" class="swal2-input" placeholder="Confirm Password">
                `,
                confirmButtonText: 'Create',
                focusConfirm: false,
                preConfirm: () => {
                    const first_name = Swal.getPopup().querySelector('#first_name').value;
                    const middle_name = Swal.getPopup().querySelector('#middle_name').value;
                    const last_name = Swal.getPopup().querySelector('#last_name').value;
                    const email = Swal.getPopup().querySelector('#email').value;
                    const phone_number = Swal.getPopup().querySelector('#phone_number').value;
                    const password = Swal.getPopup().querySelector('#password').value;
                    const password_confirmation = Swal.getPopup().querySelector('#password_confirmation').value;
                    if (!first_name || !last_name || !email || !phone_number || !password || !password_confirmation) {
                        Swal.showValidationMessage(`Please fill out all fields`);
                    }
                    return { first_name, middle_name, last_name, email, phone_number, password, password_confirmation };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('parent.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            first_name: result.value.first_name,
                            middle_name: result.value.middle_name,
                            last_name: result.value.last_name,
                            email: result.value.email,
                            phone_number: result.value.phone_number,
                            password: result.value.password,
                            password_confirmation: result.value.password_confirmation
                        },
                        success: function(response) {
                            Swal.fire('Created!', 'User has been created.', 'success').then(() => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire('Error!', 'There was an error creating the user.', 'error');
                        }
                    });
                }
            });
        }

        function editUser(id) {
            $.get('{{ route('parent.show', '') }}/' + id, function(user) {
                Swal.fire({
                    title: 'Edit User',
                    html: `
                        <input type="text" id="first_name" class="swal2-input" value="${user.first_name}">
                        <input type="text" id="middle_name" class="swal2-input" value="${user.middle_name}">
                        <input type="text" id="last_name" class="swal2-input" value="${user.last_name}">
                        <input type="email" id="email" class="swal2-input" value="${user.email}">
                        <input type="text" id="phone_number" class="swal2-input" value="${user.phone_number}">
                    `,
                    confirmButtonText: 'Update',
                    focusConfirm: false,
                    preConfirm: () => {
                        const first_name = Swal.getPopup().querySelector('#first_name').value;
                        const middle_name = Swal.getPopup().querySelector('#middle_name').value;
                        const last_name = Swal.getPopup().querySelector('#last_name').value;
                        const email = Swal.getPopup().querySelector('#email').value;
                        const phone_number = Swal.getPopup().querySelector('#phone_number').value;
                        if (!first_name || !last_name || !email || !phone_number) {
                            Swal.showValidationMessage(`Please fill out all fields`);
                        }
                        return { first_name, middle_name, last_name, email, phone_number };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('parent.update', '') }}/' + id,
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                first_name: result.value.first_name,
                                middle_name: result.value.middle_name,
                                last_name: result.value.last_name,
                                email: result.value.email,
                                phone_number: result.value.phone_number
                            },
                            success: function(response) {
                                Swal.fire('Updated!', 'User has been updated.', 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: function(response) {
                                Swal.fire('Error!', 'There was an error updating the user.', 'error');
                            }
                        });
                    }
                });
            });
        }

        function deleteUser(id) {
            $.get('{{ route('parent.show', '') }}/' + id, function(user) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You won't be able to revert this!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('parent.destroy', '') }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'User has been deleted.', 'success').then(() => {
                                    location.reload();
                                });
                            },
                            error: function(response) {
                                Swal.fire('Error!', 'There was an error deleting the user.', 'error');
                            }
                        });
                    }
                });
            });
        }
    </script>
@endpush
