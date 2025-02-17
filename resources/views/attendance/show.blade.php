@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home / Attendance /</span> Attendance Details</h4>
        @include('layouts.session')

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
                            <button class="btn btn-primary report-btn" data-attendance-id="{{ $attendance->id }}">Loading...</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .report-btn {
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .create-report-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        .show-report-btn {
            background-color: #2196F3;
            color: white;
            border: none;
        }

        .report-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .swal2-popup {
            width: 60em !important;
            padding: 0 !important;
        }

        .swal2-title {
            padding: 1rem !important;
            margin: 0 !important;
            border-bottom: 1px solid #eee;
        }

        .swal2-content {
            padding: 0 !important;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 10px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #2196F3;
            box-shadow: 0 0 0 2px rgba(33, 150, 243, 0.1);
        }

        .split-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            padding: 24px;
        }

        .image-section {
            border-right: 1px solid #eee;
            padding-right: 24px;
        }

        .form-section {
            padding-right: 24px;
        }

        .attachment-preview {
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .attachment-preview img {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }

        .attachment-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            color: #555;
        }

        .no-image-placeholder {
            text-align: center;
            padding: 2rem;
            background: #f8f9fa;
            border-radius: 8px;
            color: #666;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.report-btn').forEach(button => {
                const attendanceId = button.getAttribute('data-attendance-id');
                fetch(`/attendance-reports/check/${attendanceId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            button.textContent = 'Show Report';
                            button.classList.add('show-report-btn');
                        } else {
                            button.textContent = 'Create Report';
                            button.classList.add('create-report-btn');
                        }
                    });
            });

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('create-report-btn')) {
                    const attendanceId = event.target.getAttribute('data-attendance-id');
                    Swal.fire({
                        title: 'Create Attendance Report',
                        html: `
                        <div class="split-layout">
                            <div class="image-section">
                                <div class="no-image-placeholder">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <p>No image uploaded yet</p>
                                </div>
                            </div>
                            <div class="form-section">
                                <form id="create-report-form">
                                    <input type="hidden" name="student_id" value="{{ $studentSchedule->student_id }}">
                                    <input type="hidden" name="attendance_id" value="${attendanceId}">
                                    <div class="mb-4">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" name="date" id="date" class="form-control" required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="text" class="form-label">Report Details</label>
                                        <textarea name="text" id="text" class="form-control" rows="4" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <div class="attachment-label">
                                            <i class="fas fa-paperclip"></i>
                                            <label for="attachment" class="form-label mb-0">Attachment</label>
                                        </div>
                                        <input type="file" name="attachment" id="attachment" class="form-control">
                                    </div>
                                </form>
                            </div>
                        </div>
                    `,
                        showCancelButton: true,
                        confirmButtonText: 'Create',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#4CAF50',
                        cancelButtonColor: '#6c757d',
                        preConfirm: () => {
                            const form = document.getElementById('create-report-form');
                            const formData = new FormData(form);
                            return fetch('{{ route('attendance_reports.store') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: formData
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error(response.statusText);
                                    }
                                    return response.json();
                                })
                                .catch(error => {
                                    console.error('Request failed:', error);
                                    Swal.showValidationMessage(`Request failed: ${error}`);
                                });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Success', 'Attendance report created successfully.', 'success');
                            location.reload();
                        }
                    });
                } else if (event.target.classList.contains('show-report-btn')) {
                    const attendanceId = event.target.getAttribute('data-attendance-id');
                    // In the 'show-report-btn' click event handler
                    fetch(`/attendance-reports/${attendanceId}`)
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: 'Edit Attendance Report',
                                html: `
            <div class="split-layout">
                <div class="image-section">
                    ${data.attachment ? `
                        <div class="attachment-preview">
                            <img src="/storage/${data.attachment}" alt="Attachment">
                        </div>
                    ` : `
                        <div class="no-image-placeholder">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p>No image uploaded yet</p>
                        </div>
                    `}
                </div>
                <div class="form-section">
                    <form id="edit-report-form" method="POST" action="/attendance-reports/${attendanceId}" enctype="multipart/form-data">
                        @csrf
                                @method('PUT')
                                <input type="hidden" name="student_id" value="{{ $studentSchedule->student_id }}">
                        <input type="hidden" name="attendance_id" value="${attendanceId}">
                        <div class="mb-4">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="${data.date ? data.date.split('T')[0] : ''}" required>
                        </div>
                        <div class="mb-4">
                            <label for="text" class="form-label">Report Details</label>
                            <textarea name="text" id="text" class="form-control" rows="4" required>${data.text}</textarea>
                        </div>
                        <div class="mb-3">
                            <div class="attachment-label">
                                <i class="fas fa-paperclip"></i>
                                <label for="attachment" class="form-label mb-0">Update Attachment</label>
                            </div>
                            <input type="file" name="attachment" id="attachment" class="form-control">
                        </div>
                    </form>
                </div>
            </div>
            `,
                                showCancelButton: true,
                                confirmButtonText: 'Update',
                                cancelButtonText: 'Cancel',
                                confirmButtonColor: '#2196F3',
                                cancelButtonColor: '#6c757d',
                                preConfirm: () => {
                                    const form = document.getElementById('edit-report-form');
                                    const formData = new FormData(form);
                                    return fetch(`/attendance-reports/${attendanceId}`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: formData
                                    })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error(response.statusText);
                                            }
                                            return response.json();
                                        })
                                        .catch(error => {
                                            console.error('Request failed:', error);
                                            Swal.showValidationMessage(`Request failed: ${error}`);
                                        });
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire('Success', 'Attendance report updated successfully.', 'success');
                                }
                            });
                        });
                }
            });
        });
    </script>
@endpush
