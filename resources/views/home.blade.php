@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Welcome Back! {{auth()->user()->last_name}}</h5>
                                <p class="mb-4">
                                <p><em>"{{ $quoteContent }}"</em></p>
                                <p><em>{{ $quoteAuthor }}</em></p>
                                </p>
                                {{--                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a>--}}
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img
                                    src="{{asset('dashboard/assets/img/illustrations/man-with-laptop-light.png')}}"
                                    height="140"
                                    alt="View Badge User"
                                    data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img
                                    src="{{asset('dashboard/assets/img/icons/unicons/4.png')}}"
                                    alt="chart success"
                                    class="rounded"
                                />
                            </div>
                            <div class="dropdown">
                                <button
                                    class="btn p-0"
                                    type="button"
                                    id="cardOpt3"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                    <a class="dropdown-item" href="{{route('student.index')}}">View More</a>
                                    {{--                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>--}}
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Students</span>
                        <h3 class="card-title mb-2">{{$studentCount}}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img
                                    src="{{asset('dashboard/assets/img/icons/unicons/5.png')}}"
                                    alt="Credit Card"
                                    class="rounded"
                                />
                            </div>
                            <div class="dropdown">
                                <button
                                    class="btn p-0"
                                    type="button"
                                    id="cardOpt6"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                                    <a class="dropdown-item" href="{{route('parent.index')}}">View More</a>
                                    {{--                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>--}}
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Parents</span>
                        <h3 class="card-title mb-2">{{$parentCount}}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <img src="{{asset('dashboard/assets/img/icons/unicons/6.png')}}" alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                                <button
                                    class="btn p-0"
                                    type="button"
                                    id="cardOpt4"
                                    data-bs-toggle="dropdown"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                    <a class="dropdown-item" href="{{route('room.index')}}">View More</a>
                                    {{--                                    <a class="dropdown-item" href="javascript:void(0);">Delete</a>--}}
                                </div>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Branches</span>
                        <h3 class="card-title mb-2">{{$roomCount}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
