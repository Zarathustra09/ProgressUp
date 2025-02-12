@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Student Report</h1>
        <form action="" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ $studentId }}">

            <div class="mb-3">
                <label for="teacher_name" class="form-label">Teacher's Name</label>
                <select class="form-control" id="teacher_name" name="teacher_name" required>
                    <option value="">Select Teacher</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="programs" class="form-label">Program/s</label>
                <select class="form-control" id="programs" name="programs[]" multiple required>
                    @foreach($programs as $program)
                        <option value="{{ $program }}">{{ $program }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="grades" class="form-label">Grades</label>
                <div id="grades-container">
                    <!-- Grade items will be added here dynamically -->
                </div>
                <button type="button" class="btn btn-success" id="add-grade-set">Add Grade Set</button>
            </div>

            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
    </div>

    <script>
        document.getElementById('add-grade-set').addEventListener('click', function () {
            Swal.fire({
                title: 'Select Student Schedule',
                input: 'select',
                inputOptions: {
                    @foreach($student->studentSchedules as $schedule)
                    '{{ $schedule->id }}': '{{ $schedule->event_name }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})',
                    @endforeach
                },
                inputPlaceholder: 'Select a schedule',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to select a schedule!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const scheduleId = result.value;
                    Swal.fire({
                        title: 'Add Grade Set',
                        html: `
                        <div class="grade-item">
                            <span>Criterion 1</span>
                            <input type="text" class="form-control" id="criterion1" required>
                        </div>
                        <div class="grade-item">
                            <span>Criterion 2</span>
                            <input type="text" class="form-control" id="criterion2" required>
                        </div>
                        <div class="grade-item">
                            <span>Criterion 3</span>
                            <input type="text" class="form-control" id="criterion3" required>
                        </div>
                    `,
                        focusConfirm: false,
                        preConfirm: () => {
                            const criterion1 = document.getElementById('criterion1').value;
                            const criterion2 = document.getElementById('criterion2').value;
                            const criterion3 = document.getElementById('criterion3').value;
                            if (!criterion1 || !criterion2 || !criterion3) {
                                Swal.showValidationMessage('Please enter all criteria');
                            }
                            return { criterion1, criterion2, criterion3, scheduleId };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const container = document.getElementById('grades-container');
                            const gradeSet = document.createElement('div');
                            gradeSet.classList.add('grade-set');
                            gradeSet.innerHTML = `
                            <div class="grade-item">
                                <span>Schedule: ${result.value.scheduleId}</span>
                            </div>
                            <div class="grade-item">
                                <span>Criterion 1: ${result.value.criterion1}</span>
                                <input type="text" class="form-control" name="grades[${result.value.scheduleId}][criterion1][]" value="${result.value.criterion1}" required>
                            </div>
                            <div class="grade-item">
                                <span>Criterion 2: ${result.value.criterion2}</span>
                                <input type="text" class="form-control" name="grades[${result.value.scheduleId}][criterion2][]" value="${result.value.criterion2}" required>
                            </div>
                            <div class="grade-item">
                                <span>Criterion 3: ${result.value.criterion3}</span>
                                <input type="text" class="form-control" name="grades[${result.value.scheduleId}][criterion3][]" value="${result.value.criterion3}" required>
                            </div>
                            <button type="button" class="btn btn-danger remove-grade-set">Remove Grade Set</button>
                        `;
                            container.appendChild(gradeSet);
                        }
                    });
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-grade-set')) {
                e.target.parentElement.remove();
            }
        });
    </script>
@endsection
