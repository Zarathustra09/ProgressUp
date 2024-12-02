<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{env('APP_NAME')}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* Google Fonts Link */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Montserrat", sans-serif;
        }

        body {
            width: 100%;
            min-height: 100vh;
            display: flex;
            background: #626cd6;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 1000px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .register-header {
            background: #626cd6;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .register-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #626cd6;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #626cd6;
            box-shadow: 0 0 10px rgba(98, 108, 214, 0.1);
        }

        .btn-primary {
            background-color: #626cd6;
            border-color: #626cd6;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4954d0;
            border-color: #4954d0;
        }

        .input-group-text {
            background-color: #f8f9fa;
            color: #626cd6;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            .register-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="register-container">
    <div class="register-header">
        <h3>{{ config('app.name') }} Registration</h3>
    </div>
    <div class="register-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row">
                <div class="mb-3 col-md-6">
                    <label for="first_name" class="form-label">First Name</label>
                    <input
                        class="form-control"
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name') }}"
                        required
                        autofocus
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="middle_name" class="form-label">Middle Name</label>
                    <input
                        class="form-control"
                        type="text"
                        id="middle_name"
                        name="middle_name"
                        value="{{ old('middle_name') }}"
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input
                        class="form-control"
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name') }}"
                        required
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label class="form-label" for="phone_number">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text">PH (+63)</span>
                        <input
                            type="text"
                            id="phone_number"
                            name="phone_number"
                            class="form-control"
                            placeholder="912 345 6789"
                            value="{{ old('phone_number') }}"
                            required
                        />
                    </div>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="address" class="form-label">Address</label>
                    <input
                        type="text"
                        class="form-control"
                        id="address"
                        name="address"
                        placeholder="123 Street, City"
                        value="{{ old('address') }}"
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input
                        type="date"
                        class="form-control"
                        id="birthdate"
                        name="birthdate"
                        value="{{ old('birthdate') }}"
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="province" class="form-label">Province</label>
                    <input
                        class="form-control"
                        type="text"
                        id="province"
                        name="province"
                        placeholder="Enter Province"
                        value="{{ old('province') }}"
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input
                        class="form-control"
                        type="email"
                        id="email"
                        name="email"
                        placeholder="john.doe@example.com"
                        value="{{ old('email') }}"
                        required
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        class="form-control"
                        name="password"
                        required
                        autocomplete="new-password"
                    />
                </div>
                <div class="mb-3 col-md-6">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input
                        type="password"
                        id="password-confirm"
                        class="form-control"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="bx bx-user-plus me-1"></i> Register
                </button>
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i> Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
