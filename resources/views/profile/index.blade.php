@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
                        </li>
                    </ul>
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->
                        <div class="card-body">
                            <form id="formProfileImageUpload" method="POST" action="{{ route('profile.uploadImage') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                    <img
                                        src="{{ auth()->user()->profile_image ? asset('storage/' . auth()->user()->profile_image) : 'https://via.placeholder.com/100' }}"
                                        alt="user-avatar"
                                        class="d-block rounded"
                                        height="100"
                                        width="100"
                                        id="uploadedAvatar"
                                    />

                                    <div class="button-wrapper">
                                        <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                            <span class="d-none d-sm-block">Upload new photo</span>
                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                            <input
                                                type="file"
                                                id="upload"
                                                class="account-file-input"
                                                name="profile_image"
                                                hidden
                                                accept="image/png, image/jpeg"
                                                onchange="document.getElementById('formProfileImageUpload').submit();"
                                            />
                                        </label>
                                        <button type="button" class="btn btn-outline-secondary account-image-reset mb-4" onclick="resetImage()">
                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">Reset</span>
                                        </button>

                                        <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                    </div>
                                </div>
                            </form>
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
                                            value="{{ auth()->user()->first_name }}"
                                            autofocus
                                        />
                                    </div>

                                    <div class="mb-3 col-md-6">
                                        <label for="middleName" class="form-label">Middle Name</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            id="middleName"
                                            name="middle_name"
                                            value="{{ auth()->user()->middle_name }}"
                                        />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input class="form-control" type="text" name="last_name" id="lastName" value="{{ auth()->user()->last_name }}" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input
                                            class="form-control"
                                            type="text"
                                            id="email"
                                            name="email"
                                            value="{{ auth()->user()->email }}"
                                            placeholder="{{ auth()->user()->email }}"
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
                                                value="{{ auth()->user()->phone_number }}"
                                                placeholder="{{ auth()->user()->phone_number }}"
                                            />
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" value="{{ auth()->user()->address }}" id="address" name="address" placeholder="{{ auth()->user()->address }}" />
                                    </div>
                                    <div class="mb-3 col-md-12">
                                        <label for="province" class="form-label">Province</label>
                                        <input class="form-control" type="text" value="{{ auth()->user()->province }}" id="province" name="province" placeholder="{{ auth()->user()->province }}" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input class="form-control" type="password" id="password" name="password" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" />
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2">Save changes</button>
                                </div>
                            </form>
                        </div>
                        <!-- /Account -->
                    </div>
                    <div class="card">
                        <h5 class="card-header">Delete Account</h5>
                        <div class="card-body">
                            <div class="mb-3 col-12 mb-0">
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading fw-bold mb-1">Are you sure you want to delete your account?</h6>
                                    <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                                </div>
                            </div>
                            <form id="formAccountDeactivation" method="POST" action="{{ route('profile.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <div class="form-check mb-3">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="accountActivation"
                                        id="accountActivation"
                                        required
                                    />
                                    <label class="form-check-label" for="accountActivation">
                                        I confirm my account deactivation
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-danger deactivate-account">Deactivate Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-backdrop fade"></div>
    </div>
@endsection

@push('scripts')
    <script>
        function resetImage() {
            document.getElementById('upload').value = '';
            document.getElementById('uploadedAvatar').src = 'https://via.placeholder.com/100';

            // Send AJAX request to reset the profile image
            fetch('{{ route('profile.resetImage') }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.success);
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endpush
