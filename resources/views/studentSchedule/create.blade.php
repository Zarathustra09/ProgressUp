@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home / Student Schedules /</span> Create Student Schedule</h4>
        @include('layouts.session')
        <div class="card">
            <div class="card-body">
                <form action="{{ route('studentSchedules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="room_id" name="room_id" value="{{ $roomId }}">
                    <input type="hidden" id="student_id" name="student_id" value="{{ $studentId }}">
                    <div class="mb-3">
                        <label for="event_name" class="form-label">Event Name</label>
                        <input type="text" id="event_name" name="event_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" oninput="limitWords(this, 50)"></textarea>
                        <small id="wordCount" class="form-text text-muted">0/50 words</small>
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" id="start_time" name="start_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" id="end_time" name="end_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="session" class="form-label">Session</label>
                        <input type="number" id="session" name="session" class="form-control" required min="1">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                            <i class="fas fa-arrow-left me-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">Create Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function limitWords(textarea, maxWords) {
            const words = textarea.value.split(/\s+/).filter(word => word.length > 0);
            if (words.length > maxWords) {
                textarea.value = words.slice(0, maxWords).join(' ');
            }
            document.getElementById('wordCount').innerText = `${words.length}/${maxWords} words`;
        }
    </script>
@endsection
