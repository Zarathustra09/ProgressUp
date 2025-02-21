@extends(auth()->user()->role_id == 2 ? 'layouts.app' : 'layouts.staff.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home / Student Schedules /</span> Schedule Details</h4>

            @if(auth()->user()->role_id == 2)
                <a href="{{ route('studentSchedules.create', ['room_id' => $student->branch_id, 'student_id' => $student->id]) }}" class="btn btn-primary">
                    Create Schedule
                </a>
            @endif

        </div>

        <div class="my-4">
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                <i class="fas fa-arrow-left me-1"></i> Back
            </button>
        </div>

        @include('layouts.session')

        @if($student->studentSchedules->isNotEmpty())
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Schedules for {{ $student->first_name }} {{ $student->last_name }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Event Name</th>
{{--                                <th class="text-nowrap">Description</th>--}}
                                <th class="text-nowrap">Start Time</th>
                                <th class="text-nowrap">End Time</th>
                                <th class="text-nowrap">Duration</th>
                                <th class="text-nowrap">Session</th>
                                <th class="text-nowrap">QR Code</th>
                                <th class="text-nowrap">Attendance Count</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($student->studentSchedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->event_name }}</td>
{{--                                    <td>{{ $schedule->description }}</td>--}}
                                    <td>
                                        <span class="badge bg-label-primary">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-danger">
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->diff(\Carbon\Carbon::parse($schedule->end_time))->format('%h hr %i min') }}
                                        </span>
                                    </td>
                                    <td>{{ $schedule->session }}</td>
                                    <td data-qr-url="{{ $schedule->qr_code_url }}" class="qr-code-cell">
                                        {!! QrCode::size(100)->generate($schedule->qr_code_url) !!}
                                    </td>
                                    <td>{{ $schedule->attendances->count() }}</td>
                                    <td>
                                        <a href="{{ route('attendance.show', $schedule->id) }}" class="btn btn-sm btn-info">View</a>
                                        @if(auth()->user()->role_id == 2)
                                          <button type="button" class="btn btn-sm btn-warning edit-button" data-id="{{ $schedule->id }}">Edit</button>

                                        <form action="{{ route('studentSchedules.destroy', $schedule->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-button">Delete</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center">
                    <p class="mb-0">No schedule details available.</p>
                </div>
            </div>
        @endif


    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script>
        document.querySelectorAll('.qr-code-cell').forEach(cell => {
            cell.addEventListener('click', function () {
                const qrUrl = this.getAttribute('data-qr-url');
                Swal.fire({
                    title: 'QR Code',
                    html: `<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(qrUrl)}" alt="QR Code">`,
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Close'
                });
            });
        });

        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');

                fetch(`{{ url('showSingleSchedule') }}/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        const schedule = data.schedule;

                        Swal.fire({
                            title: '<h4 class="fw-bold text-primary">Edit Schedule</h4>',
                            html: `
                                <div class="container-fluid">
                                    <div class="row mb-3">
                                        <label for="event_name" class="form-label">Event Name</label>
                                        <input type="text" id="event_name" class="form-control" value="${schedule.event_name}" placeholder="Event Name">
                                    </div>
                                    <div class="row mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea id="description" class="form-control" placeholder="Description">${schedule.description}</textarea>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="start_time" class="form-label">Start Time</label>
                                        <input type="time" id="start_time" class="form-control" value="${moment(schedule.start_time, 'HH:mm:ss').format('HH:mm')}" placeholder="Start Time">
                                    </div>
                                    <div class="row mb-3">
                                        <label for="end_time" class="form-label">End Time</label>
                                        <input type="time" id="end_time" class="form-control" value="${moment(schedule.end_time, 'HH:mm:ss').format('HH:mm')}" placeholder="End Time">
                                    </div>
                                    <div class="row mb-3">
                                        <label for="session" class="form-label">Session</label>
                                        <input type="number" id="session" class="form-control" value="${schedule.session}" placeholder="Session" min="1">
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
                                const event_name = Swal.getPopup().querySelector('#event_name').value;
                                const description = Swal.getPopup().querySelector('#description').value;
                                const start_time = Swal.getPopup().querySelector('#start_time').value;
                                const end_time = Swal.getPopup().querySelector('#end_time').value;
                                const session = Swal.getPopup().querySelector('#session').value;

                                if (!event_name || !start_time || !end_time || !session) {
                                    Swal.showValidationMessage('Please fill out all required fields.');
                                }
                                return {
                                    event_name,
                                    description,
                                    start_time: moment(start_time, 'HH:mm').format('HH:mm:ss'),
                                    end_time: moment(end_time, 'HH:mm').format('HH:mm:ss'),
                                    session
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `{{ url('updateSingleSchedule') }}/${id}`,
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        _method: 'PUT',
                                        event_name: result.value.event_name,
                                        description: result.value.description,
                                        start_time: result.value.start_time,
                                        end_time: result.value.end_time,
                                        session: result.value.session,
                                        student_id: schedule.student_id,
                                        room_id: schedule.room_id
                                    },
                                    success: function() {
                                        Swal.fire({
                                            title: '<h4 class="fw-bold text-success">Updated!</h4>',
                                            text: 'Schedule has been updated successfully.',
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
                                            text: 'There was an error updating the schedule.',
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
                    })
                    .catch(error => {
                        console.error('Error fetching schedule:', error);
                    });
            });
        });

        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-secondary'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
