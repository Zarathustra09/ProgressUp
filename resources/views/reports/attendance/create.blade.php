@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home / Attendance Report /</span> Create Report</h4>
        @include('layouts.session')

        <form action="{{ route('attendance_reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" id="student_id" class="form-control">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="attendance_id" class="form-label">Attendance</label>
                <select name="attendance_id" id="attendance_id" class="form-control">
                    @foreach($attendances as $attendance)
                        <option value="{{ $attendance->id }}">{{ $attendance->date->format('Y-m-d') }} - {{ $attendance->status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="text" class="form-label">Text</label>
                <textarea name="text" id="text" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="attachment" class="form-label">Attachment</label>
                <input type="file" name="attachment" id="attachment" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create Report</button>
        </form>
    </div>
@endsection
