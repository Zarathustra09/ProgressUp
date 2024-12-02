@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        @if(session('error'))
            <div class="alert alert-success">
                {{ session('error') }}
            </div>
        @endif
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Parent /</span> Student</h4>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'parent-details.index' ? 'active' : '' }}" href="{{ route('parent-details.index', ['id' => $id]) }}">
                                <i class="bx bx-user me-1"></i> Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Route::currentRouteName() == 'parent-student.index' ? 'active' : '' }}" href="{{ route('parent-student.index', ['id' => $id]) }}">
                                <i class="bx bx-child me-1"></i> Children
                            </a>
                        </li>
                    </ul>


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
                                                    <h5 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h5>
                                                    <p class="card-text">Email: {{ $student->email }}</p>
                                                    <p class="card-text">Phone: {{ $student->phone_number }}</p>
                                                    <p class="card-text">Address: {{ $student->address }}</p>
                                                    <p class="card-text">Province: {{ $student->province }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mb-3" id="addRecord" onclick="createUser()">
                        <span class="tf-icons bx bx-plus"></span>&nbsp; Add Record
                    </button>
                </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>
    </div>
@endsection
