@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Student Reports</h4>

        <div class="mb-4">
            <a href="{{ route('report.student.create', ['student_id' => $reports->first()->student_id]) }}" class="btn btn-primary">Create Report</a>
        </div>

        <div class="table-responsive">
            <table id="reports-table" class="table table-hover">
                <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Report Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($reports as $report)
                    <tr id="report-row-{{ $report->id }}">
                        <td>{{ $report->student->first_name }} {{ $report->student->last_name }}</td>
                        <td>{{ $report->student->email }}</td>
                        <td>{{ $report->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="javascript:void(0);" class="text-primary me-2 show-report-btn" data-report-id="{{ $report->id }}" data-student-id="{{ $report->student_id }}">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="javascript:void(0);" class="text-danger me-2" onclick="confirmDelete({{ $report->id }})">
                                <i class="bx bx-trash"></i>
                            </a>
                            <form id="delete-form-{{ $report->id }}" action="{{ route('reports.student.destroy', $report->id) }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
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


    <style>
        .report-btn {
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
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

        $(document).ready(function() {
            $('#reports-table').DataTable();
        });



        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.show-report-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const reportId = button.getAttribute('data-report-id');
                    const studentId = button.getAttribute('data-student-id');
                    fetch(`/reports/student/single/${reportId}`)
                        .then(response => response.json())
                        .then(data => {
                            const report = data[0];
                            Swal.fire({
                                title: 'Report Details',
                                html: `
                                    <div class="split-layout">
                                        <div class="image-section">
                                            ${report.attachment ? `
                                                <div class="attachment-preview">
                                                    <img src="/storage/${report.attachment}" alt="Attachment">
                                                </div>
                                            ` : `
                                                <div class="no-image-placeholder">
                                                    <i class="fas fa-image fa-3x mb-3"></i>
                                                    <p>No image uploaded yet</p>
                                                </div>
                                            `}
                                        </div>
                                        <div class="form-section">
                                            <div class="mb-4">
                                                <label for="date" class="form-label">Date</label>
                                                <input type="date" name="date" id="date" class="form-control" value="${report.date ? report.date.split('T')[0] : ''}" readonly>
                                            </div>
                                            <div class="mb-4">
                                                <label for="text" class="form-label">Report Details</label>
                                                <textarea name="text" id="text" class="form-control" rows="4" readonly>${report.text}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                `,
                                showCancelButton: false,
                                confirmButtonText: 'Close',
                                confirmButtonColor: '#2196F3',
                            });
                        });
                });
            });
        });

        function confirmDelete(reportId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                customClass: {
                    container: 'custom-swal-container',
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('reports/student') }}/' + reportId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.success,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                customClass: {
                                    container: 'custom-swal-container',
                                    popup: 'custom-swal-popup'
                                }
                            });
                            // Optionally, remove the deleted row from the table
                            $('#report-row-' + reportId).remove();
                        }
                    });
                }
            });
        }

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                container: 'custom-swal-container',
                popup: 'custom-swal-popup'
            }
        });
        @endif
    </script>
@endpush
