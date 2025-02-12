@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Student Report</h1>
        <form action="{{ route('reports.student.store') }}" method="POST">
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
                <label for="grades" class="form-label">Grades</label>
                <div id="grades-container">
                    <table class="table table-bordered" id="grades">
                        <thead>
                        <tr>
                            <th>Schedule</th>
                            <th>Criteria</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="grades-table-body">
                        <!-- Grade items will be added here dynamically -->
                        </tbody>
                    </table>
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
        $(document).ready(function() {
            $('#grades').DataTable();
        });

        const gradeOptions = @json(config('grade'));

        document.getElementById('add-grade-set').addEventListener('click', function () {
            Swal.fire({
                title: 'Select Student Schedule',
                input: 'select',
                inputOptions: {
                    @foreach($student->studentSchedules as $schedule)
                    '{{ $schedule->id }}': '{{ $schedule->event_name }} ({{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i a') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i a') }})',
                    @endforeach
                },
                inputPlaceholder: 'Select a schedule',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to select a schedule!'
                    }
                },
                customClass: {
                    container: 'custom-swal-container',
                    popup: 'custom-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const scheduleId = result.value;
                    const scheduleName = document.querySelector(`option[value="${scheduleId}"]`).textContent;
                    Swal.fire({
                        title: 'Add Grade Set',
                        html: `
                        <div id="criteria-container">
                            <div class="grade-item">
                                <span>Criterion 1</span>
                                <input type="text" class="form-control" required>
                                <select class="form-control mt-2" required>
                                    ${Object.entries(gradeOptions).map(([key, value]) => `<option value="${key}">${value}</option>`).join('')}
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary mt-3" id="add-criterion">Add Criterion</button>
                        <button type="button" class="btn btn-primary mt-3" id="save-criteria">Save</button>
                        <button type="button" class="btn btn-danger mt-3" id="cancel-criteria">Cancel</button>
                    `,
                        focusConfirm: false,
                        showConfirmButton: false,
                    });

                    document.getElementById('add-criterion').addEventListener('click', function () {
                        const criteriaContainer = document.getElementById('criteria-container');
                        const criterionIndex = criteriaContainer.children.length + 1;
                        const newCriterion = document.createElement('div');
                        newCriterion.classList.add('grade-item');
                        newCriterion.innerHTML = `
                            <span>Criterion ${criterionIndex}</span>
                            <input type="text" class="form-control" required>
                            <select class="form-control mt-2" required>
                                ${Object.entries(gradeOptions).map(([key, value]) => `<option value="${key}">${value}</option>`).join('')}
                            </select>
                        `;
                        criteriaContainer.appendChild(newCriterion);
                    });

                    document.getElementById('save-criteria').addEventListener('click', function () {
                        const criteria = [];
                        document.querySelectorAll('#criteria-container .grade-item').forEach((item, index) => {
                            const criterion = item.querySelector('input').value;
                            const grade = item.querySelector('select').value;
                            if (!criterion || !grade) {
                                Swal.showValidationMessage('Please enter all criteria');
                            }
                            criteria.push({ criterion, grade });
                        });

                        const tableBody = document.getElementById('grades-table-body');
                        const gradeRow = document.createElement('tr');
                        const criteriaHtml = criteria.map((criterion, index) => `
                            <div>${criterion.criterion} (${criterion.grade})</div>
                            <input type="hidden" name="grades[${scheduleId}][criterion${index + 1}]" value="${criterion.criterion}" required>
                            <input type="hidden" name="grades[${scheduleId}][criterion${index + 1}Grade]" value="${criterion.grade}" required>
                        `).join('');
                        gradeRow.innerHTML = `
                            <td>${scheduleName}</td>
                            <td>${criteriaHtml}</td>
                            <td>
                                <button type="button" class="btn btn-danger remove-grade-set">Remove</button>
                            </td>
                        `;
                        tableBody.appendChild(gradeRow);
                        $('#grades').DataTable().row.add(gradeRow).draw();
                        Swal.close();
                    });

                    document.getElementById('cancel-criteria').addEventListener('click', function () {
                        Swal.close();
                    });
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-grade-set')) {
                const row = e.target.closest('tr');
                $('#grades').DataTable().row(row).remove().draw();
            }
        });

        @if(session('success'))
        alert('{{ session('success') }}');
        location.reload();
        @endif
    </script>
@endsection
