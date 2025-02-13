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
                    <tr>
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
            $('#reports-table').DataTable();
        });
    </script>
@endpush
