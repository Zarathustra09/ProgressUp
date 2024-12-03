@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Rooms</h4>

        <button type="button" class="btn btn-primary mb-3" id="addRoom" onclick="createRoom()">
            <span class="tf-icons bx bx-plus"></span>&nbsp; Add Room
        </button>

        <div class="table-responsive">
            <table id="rooms-table" class="table table-hover">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Capacity</th>
                    <th>Location</th>
                    <th>Student Count</th> <!-- New column -->
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rooms as $room)
                    <tr>
                        <td>{{ $room->id }}</td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->description }}</td>
                        <td>{{ $room->capacity }}</td>
                        <td>{{ $room->location }}</td>
                        <td>{{ $room->students_count }}</td> <!-- Display student count -->
                        <td>
                            <a href="javascript:void(0)" onclick="viewRoom({{ $room->id }})" class="text-info">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="editRoom({{ $room->id }})" class="text-warning me-2">
                                <i class="bx bx-edit"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteRoom({{ $room->id }})" class="text-danger me-2">
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
            $('#rooms-table').DataTable();
        });

        // Create Room Function
        function createRoom() {
            Swal.fire({
                title: '<h4 class="fw-bold text-primary">Create Room</h4>',
                html: `
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="col-md-6">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" id="capacity" class="form-control" placeholder="Capacity">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" class="form-control" placeholder="Description"></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" id="location" class="form-control" placeholder="Location">
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
                    const name = Swal.getPopup().querySelector('#name').value;
                    const capacity = Swal.getPopup().querySelector('#capacity').value;
                    const description = Swal.getPopup().querySelector('#description').value;
                    const location = Swal.getPopup().querySelector('#location').value;

                    if (!name || !capacity || !location) {
                        Swal.showValidationMessage('Please fill out all required fields.');
                    }
                    return { name, capacity, description, location };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('room.store') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ...result.value
                        },
                        success: function() {
                            Swal.fire({
                                title: '<h4 class="fw-bold text-success">Created!</h4>',
                                text: 'Room has been created successfully.',
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
                                text: 'There was an error creating the room.',
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

        function editRoom(id) {
            $.get('{{ route('room.show', '') }}/' + id, function(room) {
                Swal.fire({
                    title: '<h4 class="fw-bold text-primary">Edit Room</h4>',
                    html: `
                <div class="container-fluid">
                    <div class="row mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" class="form-control" value="${room.name}" placeholder="Name">
                    </div>
                    <div class="row mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" id="capacity" class="form-control" value="${room.capacity}" placeholder="Capacity">
                    </div>
                    <div class="row mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" class="form-control" placeholder="Description">${room.description}</textarea>
                    </div>
                    <div class="row mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" id="location" class="form-control" value="${room.location}" placeholder="Location">
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
                        const name = Swal.getPopup().querySelector('#name').value;
                        const capacity = Swal.getPopup().querySelector('#capacity').value;
                        const description = Swal.getPopup().querySelector('#description').value;
                        const location = Swal.getPopup().querySelector('#location').value;

                        if (!name || !capacity || !location) {
                            Swal.showValidationMessage('Please fill out all required fields.');
                        }
                        return { name, capacity, description, location };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('room.update', '') }}/' + id,
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: result.value.name,
                                capacity: result.value.capacity,
                                description: result.value.description,
                                location: result.value.location
                            },
                            success: function() {
                                Swal.fire({
                                    title: '<h4 class="fw-bold text-success">Updated!</h4>',
                                    text: 'Room has been updated successfully.',
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
                                    text: 'There was an error updating the room.',
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
            });
        }

        function deleteRoom(id) {
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
                        url: '{{ route('room.destroy', '') }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            Swal.fire({
                                title: '<h4 class="fw-bold text-success">Deleted!</h4>',
                                text: 'Room has been deleted successfully.',
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
                                text: 'There was an error deleting the room.',
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

        function viewRoom(id) {
            window.location.href = '{{ route('room.show', '') }}/' + id;
        }
    </script>
@endpush
