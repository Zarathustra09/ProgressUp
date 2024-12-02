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
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Parent /</span> Details</h4>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <ul class="nav nav-pills flex-column flex-md-row mb-3">
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'parent-details.index' ? 'active' : '' }}" href="{{ route('parent-details.index', ['id' => $parent->id]) }}">
                                    <i class="bx bx-user me-1"></i> Account
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::currentRouteName() == 'parent-student.index' ? 'active' : '' }}" href="{{ route('parent-student.index', ['id' => $parent->id]) }}">
                                    <i class="bx bx-child me-1"></i> Children
                                </a>
                            </li>
                        </ul>
                    </ul>
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img
                                    src="https://via.placeholder.com/100"
                                    alt="user-avatar"
                                    class="d-block rounded"
                                    height="100"
                                    width="100"
                                    id="uploadedAvatar"
                                />

                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Print Profile</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input
                                            type="file"
                                            id="upload"
                                            class="account-file-input"
                                            hidden
                                            accept="image/png, image/jpeg"
                                        />
                                    </label>
                                    <p class="text-muted mb-0">{{$parent->address}} {{$parent->province}}</p>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body">
                            <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            id="firstName"
                                            name="first_name"
                                            value="{{ $parent->first_name }}"
                                            autofocus
                                            readonly
                                        />
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="middleName" class="form-label">Middle Name</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            id="middleName"
                                            name="middle_name"
                                            value="{{ $parent->middle_name }}"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input class="form-control" type="text" name="last_name" id="lastName" value="{{ $parent->last_name }}"readonly />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            id="email"
                                            name="email"
                                            value="{{ $parent->email }}"
                                            placeholder="{{ $parent->email }}"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label" for="phoneNumber">Phone Number</label>
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text">PH (+63)</span>
                                            <input
                                                type="text"
                                                id="phoneNumber"
                                                name="phone_number"
                                                class="form-control"
                                                value="{{ $parent->phone_number }}"
                                                placeholder="{{ $parent->phone_number }}"
                                                readonly
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                        <!-- /Account -->
                    </div>

                </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>
    </div>
@endsection
