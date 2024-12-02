@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Parent /</span> Add Student</h4>

            <div class="card mb-4">
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
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input
                                    type="file"
                                    id="upload"
                                    class="account-file-input"
                                    name="profile_image"
                                    hidden
                                    accept="image/png, image/jpeg"
                                    onchange="previewImage(event)"
                                />
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4" onclick="resetImage()">
                                <i class="bx bx-reset d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>

                            <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                        </div>
                    </div>
                </div>

                <h5 class="card-header">Student Information</h5>
                <div class="card-body">
                    <form id="formProfileImageUpload" method="POST" action="{{ route('parent-student.store', ['id' => $id]) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="profileImageData" name="profile_image_data" />
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input class="form-control" type="text" id="firstName" name="first_name" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="middleName" class="form-label">Middle Name</label>
                                <input class="form-control" type="text" id="middleName" name="middle_name" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input class="form-control" type="text" id="lastName" name="last_name" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input class="form-control" type="email" id="email" name="email" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input class="form-control" type="text" id="phoneNumber" name="phone_number" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input class="form-control" type="text" id="address" name="address" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="province" class="form-label">Province</label>
                                <input class="form-control" type="text" id="province" name="province" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input class="form-control" type="date" id="birthdate" name="birthdate" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <input class="form-control" type="password" id="password" name="password" required />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input class="form-control" type="password" id="password_confirmation" name="password_confirmation" required />
                            </div>
                        </div>
                        <h5 class="card-header mt-4">Student School Details</h5>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <h5 class="card-header mt-4">Student Medical Information</h5>
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="allergies" class="form-label">Allergies</label>
                                <input class="form-control" type="text" id="allergies" name="allergies" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="notes" class="form-label">Notes</label>
                                <input class="form-control" type="text" id="notes" name="notes" />
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="medication" class="form-label">Medication</label>
                                <input class="form-control" type="text" id="medication" name="medication" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-primary">Add Student</button>
                            <a href="{{ route('parent-student.index', ['id' => $id]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('uploadedAvatar');
                output.src = reader.result;
                document.getElementById('profileImageData').value = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        function resetImage() {
            document.getElementById('upload').value = '';
            document.getElementById('uploadedAvatar').src = 'https://via.placeholder.com/100';
            document.getElementById('profileImageData').value = '';
        }
    </script>
@endsection
