@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold py-3 mb-0"><span class="text-muted fw-light">Parent /</span> Students</h4>
                <form action="{{ route('student.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search students..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <h5 class="card-header">Children Details</h5>
                        <div class="card-body">
                            @if($students->isEmpty())
                                <p class="text-muted">There are no associated students.</p>
                            @else
                                <div class="row">
                                    @foreach($students as $student)
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                        <img
                                                            src="{{ $student->profile_image ? asset('storage/' . $student->profile_image) : 'https://via.placeholder.com/100' }}"
                                                            alt="student-avatar"
                                                            class="d-block rounded"
                                                            height="100"
                                                            width="100"
                                                        />
                                                        <div>
                                                            <h5 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h5>
                                                            <p class="card-text">Student ID: {{ $student->student_id }}</p>
                                                            <p class="card-text">
                                                                Parent:
                                                                <a href="{{ route('parent-details.index', ['id' => $student->parent_id]) }}">
                                                                    {{ $student->parent_first_name }} {{ $student->parent_last_name }}
                                                                </a>
                                                            </p>
                                                            <p class="card-text">Status: {{ $student->studentSchoolDetails->status }}</p>
                                                            <p class="card-text">Status: {{ $student->branch->name }}</p>
                                                            <a href="{{ route('parent-student.show', ['id' => $student->parent_id, 'studentId' => $student->id]) }}" class="btn btn-primary">View</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $students->appends(request()->input())->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
