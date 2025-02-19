@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-lg border-0 my-4">
            <div class="card-header bg-primary text-white">
                <h1 class="h3 mb-0 text-white">Create Student Report</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('storeAttendance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $studentId }}">
                    <input type="hidden" name="attendance_id" value="{{ request()->query('attendance_id') }}">
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
                        <label class="form-label fw-bold">Activities</label>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="activities">
                                <thead class="table-light">
                                <tr>
                                    <th>Activity</th>
                                    <th>Descriptions</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="activities-table-body">
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success" id="add-activity-set">
                            <i class="fas fa-plus me-2"></i>Add Activity Set
                        </button>
                    </div>

                    <div class="mb-4">
                        <label for="overall_grade" class="form-label fw-bold">Overall Grade</label>
                        <select class="form-select" id="overall_grade" name="overall_grade" required>
                            <option value="">Select Grade</option>
                            @foreach(config('grade') as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#activities').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search records..."
                }
            });
        });

        document.getElementById('add-activity-set').addEventListener('click', function () {
            Swal.fire({
                title: 'Add Activity Set',
                html: `
                    <div id="activity-container" class="overflow-auto px-2" style="max-height: 60vh;">
                        <div class="activity-item bg-light rounded p-3 mb-3">
                            <span class="d-block fw-medium mb-2">Activity</span>
                            <input type="text" class="form-control" placeholder="Enter activity" required>
                            <textarea class="form-control mt-2" placeholder="Enter descriptions (one per line)" required></textarea>
                            <button type="button" class="btn btn-danger btn-sm remove-activity mt-2">Remove</button>
                        </div>
                    </div>
                    <div class="btn-group d-flex gap-2 mt-4">
                        <button type="button" class="btn btn-secondary flex-fill" id="add-activity">
                            <i class="fas fa-plus me-2"></i>Add Activity
                        </button>
                        <button type="button" class="btn btn-success flex-fill" id="save-activities">
                            <i class="fas fa-save me-2"></i>Save
                        </button>
                        <button type="button" class="btn btn-danger flex-fill" id="cancel-activities">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                    </div>
                `,
                showConfirmButton: false,
                width: '600px',
                background: '#ffffff',
                backdrop: `rgba(0,0,0,0.4)`,
                customClass: {
                    popup: 'modal-content border-0',
                    title: 'h5 fw-bold mb-4',
                    htmlContainer: 'p-3'
                }
            });

            document.getElementById('add-activity').addEventListener('click', function () {
                const activityContainer = document.getElementById('activity-container');
                const newActivity = document.createElement('div');
                newActivity.classList.add('activity-item', 'bg-light', 'rounded', 'p-3', 'mb-3');
                newActivity.innerHTML = `
                    <span class="d-block fw-medium mb-2">Activity</span>
                    <input type="text" class="form-control" placeholder="Enter activity" required>
                    <textarea class="form-control mt-2" placeholder="Enter descriptions (one per line)" required></textarea>
                    <button type="button" class="btn btn-danger btn-sm remove-activity mt-2">Remove</button>
                `;
                activityContainer.appendChild(newActivity);
            });

            document.getElementById('activity-container').addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-activity')) {
                    e.target.closest('.activity-item').remove();
                }
            });

            document.getElementById('save-activities').addEventListener('click', function () {
                const activities = {};
                document.querySelectorAll('#activity-container .activity-item').forEach((item, index) => {
                    const activity = item.querySelector('input').value;
                    const descriptions = item.querySelector('textarea').value.split('\n').filter(desc => desc.trim() !== '');
                    if (activity && descriptions.length > 0) {
                        activities[`activity${index + 1}`] = { key: activity, descriptions: descriptions };
                    }
                });

                if (Object.keys(activities).length === 0) {
                    Swal.fire({
                        toast: true,
                        icon: 'warning',
                        iconColor: '#ffc107',
                        title: 'Please add at least one activity.',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        background: '#ffffff',
                        customClass: {
                            popup: 'shadow border-0',
                            title: 'fs-6 fw-medium'
                        }
                    });
                    return;
                }

                const tableBody = document.getElementById('activities-table-body');
                const activityRow = document.createElement('tr');
                const activitiesHtml = Object.values(activities).map((activity) => `
                    <div class="mb-2">
                        <span class="fw-bold">${activity.key}</span>
                        ${activity.descriptions.map(desc => `<div>- ${desc}</div>`).join('')}
                    </div>
                    <input type="hidden" name="activities[${attendanceId}][${activity.key}]" value="${activity.key}" required>
                    ${activity.descriptions.map((desc) => `<input type="hidden" name="activities[${attendanceId}][${activity.key}][descriptions][]" value="${desc}" required>`).join('')}
                `).join('');
                activityRow.innerHTML = `
                    <td>${activitiesHtml}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-activity-set">
                            <i class="fas fa-trash me-1"></i>Remove
                        </button>
                    </td>
                `;
                tableBody.appendChild(activityRow);
                $('#activities').DataTable().row.add(activityRow).draw();
                Swal.close();
            });

            document.getElementById('cancel-activities').addEventListener('click', function () {
                Swal.close();
            });
        });

        document.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-activity-set')) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This activity set will be removed.",
                    icon: 'warning',
                    iconColor: '#ffc107',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#0d6efd',
                    confirmButtonText: 'Yes, remove it!',
                    background: '#ffffff',
                    backdrop: `rgba(0,0,0,0.4)`,
                    customClass: {
                        popup: 'modal-content border-0',
                        title: 'h5 fw-bold',
                        confirmButton: 'btn btn-danger px-4',
                        cancelButton: 'btn btn-primary px-4',
                        actions: 'd-flex gap-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const row = e.target.closest('tr');
                        $('#activities').DataTable().row(row).remove().draw();
                        Swal.fire({
                            title: 'Removed!',
                            text: 'The activity set has been removed.',
                            icon: 'success',
                            iconColor: '#198754',
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#ffffff',
                            backdrop: `rgba(0,0,0,0.4)`,
                            customClass: {
                                popup: 'modal-content border-0',
                                title: 'h5 fw-bold'
                            }
                        });
                    }
                });
            }
        });

        @if(session('success'))
        Swal.fire({
            toast: true,
            icon: 'success',
            iconColor: '#198754',
            title: 'Success!',
            text: '{{ session('success') }}',
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            background: '#ffffff',
            customClass: {
                popup: 'shadow border-0',
                title: 'fs-6 fw-medium'
            }
        }).then(() => {
            location.reload();
        });
        @endif
    </script>
@endpush
