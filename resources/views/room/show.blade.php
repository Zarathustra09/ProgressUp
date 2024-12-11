@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">Home / Branches /</span>
                    {{ $room->name }}
                </h4>
            </div>
            @include('layouts.session')
            <div>
                @if($room->schedules->isNotEmpty())
                    <a href="{{ route('room_schedules.show', ['id' => $room->schedules->first()->id]) }}" class="btn btn-primary">
                        View Schedules
                    </a>
                @else
                    <p>No schedules available.</p>
                @endif
                    <a href="{{ route('room_schedules.create', ['room_id' => $room->id]) }}" class="btn btn-secondary">
                        Create Schedule
                    </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Staff Members</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($room->roomStaff as $staff)
                                <li class="list-group-item">
                                    <strong>{{ $staff->staff->first_name }} {{ $staff->staff->last_name }}</strong><br>
                                    <small>{{ $staff->staff->email }}</small><br>
                                    <small>{{ $staff->staff->phone_number }}</small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary mb-3" id="addStudent" onclick="createStudent()">
            <span class="tf-icons bx bx-plus"></span>&nbsp; Add Student
        </button>
        <div class="table-responsive">
            <table id="students-table" class="table table-hover">
                <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($room->students as $student)
                    <tr>
                        <td>{{ $student->studentSchoolDetails->student_id }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->middle_name }}</td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone_number }}</td>
                        <td>
                            <a href="javascript:void(0)" onclick="deleteStudent({{ $student->id }})" class="text-danger me-2">
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
            $('#students-table').DataTable();
        });

        function createStudent() {
            $.get('{{ route('students.list') }}', function(students) {
                let studentOptions = '';
                students.forEach(student => {
                    studentOptions += `<option value="${student.id}">${student.first_name} ${student.last_name}</option>`;
                });

                Swal.fire({
                    title: '<h4 class="fw-bold text-primary">Add Student</h4>',
                    html: `
                        <div class="container-fluid">
                            <div class="row mb-3">
                                <label for="student_id" class="form-label">Select Student</label>
                                <select id="student_id" class="form-control">
                                    ${studentOptions}
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
                    confirmButtonText: 'Add',
                    cancelButtonText: 'Cancel',
                    focusConfirm: false,
                    preConfirm: () => {
                        const student_id = Swal.getPopup().querySelector('#student_id').value;
                        if (!student_id) {
                            Swal.showValidationMessage('Please select a student.');
                        }
                        return { student_id };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('room-student.store') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                room_id: '{{ $room->id }}',
                                student_id: result.value.student_id
                            },
                            success: function() {
                                Swal.fire({
                                    title: '<h4 class="fw-bold text-success">Added!</h4>',
                                    text: 'Student has been added successfully.',
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
                                    text: 'There was an error adding the student.',
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

        function deleteStudent(id) {
            console.log('Attempting to delete student with ID:', id);
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
                        url: '{{ route('room-student.destroy', '') }}/' + id,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            console.log('Successfully deleted student with ID:', id);
                            Swal.fire({
                                title: '<h4 class="fw-bold text-success">Deleted!</h4>',
                                text: 'Student has been deleted successfully.',
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
                            console.error('Error deleting student with ID:', id);
                            Swal.fire({
                                title: '<h4 class="fw-bold text-danger">Error!</h4>',
                                text: 'There was an error deleting the student.',
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
    </script>
@endpush
