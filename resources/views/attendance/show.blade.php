@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home / Attendance /</span> Attendance Details</h4>
            <button id="create-attendance-btn" class="btn btn-primary">Create Attendance</button>
        </div>
        @include('layouts.session')

        <button type="button" class="btn btn-secondary mb-4" onclick="window.history.back();">
            <i class="fas fa-arrow-left me-1"></i> Back
        </button>

        <div class="table-responsive">
            <table id="attendance-table" class="table table-hover">
                <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($studentSchedule->attendances as $attendance)
                    <tr>
                        <td>{{ $studentSchedule->event_name }}</td>
                        <td>{{ $attendance->date->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($attendance->status) }}</td>
                        <td>
                            <a href="#" class="btn btn-primary report-btn" data-attendance-id="{{ $attendance->id }}">
                                Loading...
                            </a>
                            <form action="{{ route('attendance.report.destroy', $attendance->id) }}" method="POST" style="display:inline;" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this report?');">
                                    Delete
                                </button>
                            </form>
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.report-btn').forEach(button => {
                const attendanceId = button.getAttribute('data-attendance-id');
                fetch(`{{ route('attendance.reports.check', '') }}/${attendanceId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            button.textContent = 'Show Report';
                            button.href = `{{ route('attendance.reports.show', '') }}/${attendanceId}`;
                            button.nextElementSibling.style.display = 'inline';
                        } else {
                            button.textContent = 'Create Report';
                            button.href = `{{ route('createAttendance') }}?student_id={{ $studentSchedule->student_id }}&attendance_id=${attendanceId}`;
                            button.nextElementSibling.style.display = 'none';
                        }
                    });
            });
        });

        document.getElementById('create-attendance-btn').addEventListener('click', function () {
            Swal.fire({
                title: 'Create Attendance',
                html: `
                    <input type="hidden" id="student_id" value="{{ $studentSchedule->student_id }}">
                    <input type="hidden" id="schedule_id" value="{{ $studentSchedule->id }}">
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" class="form-select">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Create',
                preConfirm: () => {
                    const student_id = document.getElementById('student_id').value;
                    const schedule_id = document.getElementById('schedule_id').value;
                    const date = document.getElementById('date').value;
                    const status = document.getElementById('status').value;

                    if (!date || !status) {
                        Swal.showValidationMessage('Please fill out all fields');
                        return false;
                    }

                    return { student_id, schedule_id, date, status };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const { student_id, schedule_id, date, status } = result.value;

                    fetch(`{{ route('attendance.store') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ student_id, schedule_id, date, status })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success', data.success, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.error, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Failed to create attendance.', 'error');
                        });
                }
            });
        });
    </script>
@endpush
