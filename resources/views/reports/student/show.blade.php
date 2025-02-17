@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Student Reports</h4>

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
                            <a href="{{ route('reports.student.viewPdf', $report->id) }}" class="text-primary me-2">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('reports.student.print', $report->id) }}" class="text-success me-2">
                                <i class="bx bx-download"></i>
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
    <script>
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
