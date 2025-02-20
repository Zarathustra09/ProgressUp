@extends(auth()->user()->role_id == 2 ? 'layouts.app' : 'layouts.staff.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Home /</span> Create Student Report
        </h4>

        <div class="card">
            <div class="card-body">
                <form id="create-report-form" method="POST" action="{{ route('reports.student.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $studentId }}">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="teacher_id" class="form-label">Teacher</label>
                            <select name="teacher_id" id="teacher_id" class="form-select" required>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="schedule_id" class="form-label">Program</label>
                            <select name="schedule_id" id="schedule_id" class="form-select" required>
                                @foreach($programs as $id => $program)
                                    <option value="{{ $id }}">{{ $program }}</option>
                                @endforeach
                            </select>
                            @error('schedule_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                            @error('date')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="text" class="form-label">Report Details</label>
                        <textarea name="text" id="text" class="form-control" rows="4" required></textarea>
                        @error('text')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" name="attachment" id="attachment" class="form-control" accept=".jpeg,.jpg,.png">
                        @error('attachment')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i> Create Report
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .card {
            box-shadow: 0 2px 6px 0 rgba(67, 89, 113, 0.12);
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #696cff;
            box-shadow: 0 0 0.25rem rgba(105, 108, 255, 0.1);
        }
    </style>
    <script>
        $(document).ready(function() {
            // Any additional JavaScript can go here
        });
    </script>
@endpush
