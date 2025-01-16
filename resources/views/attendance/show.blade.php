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
                </tr>
                </thead>
                <tbody>
                @foreach($studentSchedule->attendances as $attendance)
                    <tr>
                        <td>{{ $studentSchedule->event_name }}</td>
                        <td>{{ $attendance->date->format('Y-m-d') }}</td>
                        <td>{{ ucfirst($attendance->status) }}</td>
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
            $('#attendance-table').DataTable();
        });
    </script>
@endpush
