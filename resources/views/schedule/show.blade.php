@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home / Schedules /</span> Schedule Details</h4>
            <a href="{{ route('room_schedules.create', ['room_id' => $room->id]) }}" class="btn btn-primary">
                Create Schedule
            </a>
        </div>

       @include('layouts.session')

        @if($room->schedules->isNotEmpty())
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Schedules for {{ $room->name }} Branch</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Event Name</th>
                                <th class="text-nowrap">Start Time</th>
                                <th class="text-nowrap">End Time</th>
                                <th class="text-nowrap">Duration</th>
                                <th class="text-nowrap">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($room->schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->event_name }}</td>
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
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning edit-button" data-id="{{ $schedule->id }}" data-event="{{ $schedule->event_name }}" data-start="{{ $schedule->start_time }}" data-end="{{ $schedule->end_time }}">Edit</button>
                                        <form action="{{ route('room_schedules.destroy', $schedule->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger delete-button">Delete</button>
                                        </form>
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
    <script>
        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const event = this.getAttribute('data-event');
                const start = this.getAttribute('data-start');
                const end = this.getAttribute('data-end');

                Swal.fire({
                    title: 'Edit Schedule',
                    html: `
                        <form id="edit-form" action="{{ url('room-schedules') }}/${id}" method="POST">
                            @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="event_name" class="form-label">Event Name</label>
                        <input type="text" id="event_name" name="event_name" class="form-control" value="${event}" required>
                            </div>
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" id="start_time" name="start_time" class="form-control" value="${start}" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" id="end_time" name="end_time" class="form-control" value="${end}" required>
                            </div>
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        document.getElementById('edit-form').submit();
                    }
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
