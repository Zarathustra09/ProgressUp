@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Home /</span> Student Reports</h4>
{{--        <button type="button" class="btn btn-primary mb-3" id="addRecord" onclick="createReport()">--}}
{{--            <span class="tf-icons bx bx-plus"></span>&nbsp; Add Report--}}
{{--        </button>--}}

        <div class="table-responsive">
            <table id="users-table" class="table table-hover">
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roomStudent->room->name ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('reports.student.show', ['id' => $user->id]) }}" class="text-primary me-2">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('report.student.create', ['student_id' => $user->id]) }}" class="text-primary me-2">
                                <i class="bx bxs-file-plus"></i>
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
            $('#users-table').DataTable();
        });

        function createReport() {
            // Add your create report logic here
        }
    </script>
@endpush
