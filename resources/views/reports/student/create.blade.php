@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-lg border-0 my-4">
            <div class="card-header bg-primary text-white">
                <h1 class="h3 mb-0 text-white">Create Student Report</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.student.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $studentId }}">

                    <div class="mb-4">
                        <label for="teacher_id" class="form-label fw-bold">Teacher's Name</label>
                        <select class="form-select" id="teacher_id" name="teacher_id" required>
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Grades</label>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="grades">
                                <thead class="table-light">
                                <tr>
                                    <th>Schedule</th>
                                    <th>Criteria</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="grades-table-body">
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success" id="add-grade-set">
                            <i class="fas fa-plus me-2"></i>Add Grade Set
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="remarks" class="form-label fw-bold">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="5" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-file-export me-2"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('#grades').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });
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
                confirmButtonText: 'Next',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to select a schedule!'
                    }
                },
                customClass: {
                    container: 'custom-swal-container',
                    popup: 'custom-swal-popup',
                    input: 'form-select'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const scheduleId = result.value;
                    const scheduleName = document.querySelector(`option[value="${scheduleId}"]`).textContent;

                    Swal.fire({
                        title: 'Add Grade Set',
                        html: `
                    <div id="criteria-container">
                        <div class="grade-item shadow-sm">
                            <span>Criterion 1</span>
                            <input type="text" class="form-control" placeholder="Enter criterion" required>
                            <select class="form-select mt-2" required>
                                ${Object.entries(gradeOptions).map(([key, value]) => `<option value="${key}">${value}</option>`).join('')}
                            </select>
                            <button type="button" class="btn btn-danger btn-sm remove-criterion mt-2">Remove</button>
                        </div>
                    </div>
                    <div class="btn-group mt-4 w-100">
                        <button type="button" class="btn btn-secondary" id="add-criterion">
                            <i class="fas fa-plus me-2"></i>Add Criterion
                        </button>
                        <button type="button" class="btn btn-success" id="save-criteria">
                            <i class="fas fa-save me-2"></i>Save
                        </button>
                        <button type="button" class="btn btn-danger" id="cancel-criteria">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                    </div>
                `,
                        showConfirmButton: false,
                        width: '600px',
                        customClass: {
                            container: 'custom-swal-container',
                            popup: 'custom-swal-popup'
                        }
                    });

                    document.getElementById('add-criterion').addEventListener('click', function () {
                        const criteriaContainer = document.getElementById('criteria-container');
                        const criterionIndex = criteriaContainer.children.length + 1;
                        const newCriterion = document.createElement('div');
                        newCriterion.classList.add('grade-item', 'shadow-sm');
                        newCriterion.innerHTML = `
                    <span>Criterion ${criterionIndex}</span>
                    <input type="text" class="form-control" placeholder="Enter criterion" required>
                    <select class="form-select mt-2" required>
                        ${Object.entries(gradeOptions).map(([key, value]) => `<option value="${key}">${value}</option>`).join('')}
                    </select>
                    <button type="button" class="btn btn-danger btn-sm remove-criterion mt-2">Remove</button>
                `;
                        criteriaContainer.appendChild(newCriterion);
                    });

                    document.getElementById('criteria-container').addEventListener('click', function (e) {
                        if (e.target && e.target.classList.contains('remove-criterion')) {
                            e.target.closest('.grade-item').remove();
                        }
                    });

                    document.getElementById('save-criteria').addEventListener('click', function () {
                        const criteria = [];
                        document.querySelectorAll('#criteria-container .grade-item').forEach((item, index) => {
                            const criterion = item.querySelector('input').value;
                            const grade = item.querySelector('select').value;
                            if (criterion && grade) {
                                criteria.push({ criterion, grade });
                            }
                        });

                        if (criteria.length === 0) {
                            Swal.fire({
                                toast: true,
                                icon: 'warning',
                                title: 'Please add at least one criterion.',
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                customClass: {
                                    container: 'custom-swal-container',
                                    popup: 'custom-swal-popup'
                                }
                            });
                            return;
                        }

                        const tableBody = document.getElementById('grades-table-body');
                        const gradeRow = document.createElement('tr');
                        const criteriaHtml = criteria.map((criterion, index) => `
                    <div class="mb-2">
                        <span class="fw-bold">${criterion.criterion}</span>
                        <span class="badge bg-primary ms-2">${criterion.grade}</span>
                    </div>
                    <input type="hidden" name="grades[${scheduleId}][criterion${index + 1}]" value="${criterion.criterion}" required>
                    <input type="hidden" name="grades[${scheduleId}][criterion${index + 1}Grade]" value="${criterion.grade}" required>
                `).join('');

                        gradeRow.innerHTML = `
                    <td>${scheduleName}</td>
                    <td>${criteriaHtml}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-grade-set">
                            <i class="fas fa-trash me-1"></i>Remove
                        </button>
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
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This grade set will be removed.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!',
                    customClass: {
                        container: 'custom-swal-container',
                        popup: 'custom-swal-popup'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const row = e.target.closest('tr');
                        $('#grades').DataTable().row(row).remove().draw();
                        Swal.fire({
                            title: 'Removed!',
                            text: 'The grade set has been removed.',
                            icon: 'success',
                            customClass: {
                                container: 'custom-swal-container',
                                popup: 'custom-swal-popup'
                            }
                        });
                    }
                });
            }
        });

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1500,
            customClass: {
                container: 'custom-swal-container',
                popup: 'custom-swal-popup'
            }
        }).then(() => {
            location.reload();
        });
        @endif
    </script>
@endsection
