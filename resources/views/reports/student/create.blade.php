@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Student Report</h1>
        <form id="create-report-form" method="POST" action="{{ route('reports.student.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="student_id" value="{{ $studentId }}">
            <div class="mb-4">
                <label for="teacher_id" class="form-label">Teacher</label>
                <select name="teacher_id" id="teacher_id" class="form-control" required>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="schedule_id" class="form-label">Program</label>
                <select name="schedule_id" id="schedule_id" class="form-control" required>
                    @foreach($programs as $id => $program)
                        <option value="{{ $id }}">{{ $program }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" id="date" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="text" class="form-label">Report Details</label>
                <textarea name="text" id="text" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="attachment" class="form-label">Attachment</label>
                <input type="file" name="attachment" id="attachment" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create Report</button>
        </form>
    </div>
@endsection

@push('scripts')
    <style>
        /* Add your custom styles here */
    </style>
    <script>
        // Add your custom scripts here
    </script>
@endpush
