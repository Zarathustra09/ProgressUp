<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Form in HTML and CSS | CodingNepal</title>
    <style>
        /* Google Fonts Link */
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        /* Resetting default styling and setting font-family */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Montserrat", sans-serif;
        }

        body {
            width: 100%;
            min-height: 100vh;
            padding: 0 10px;
            display: flex;
            background: #626cd6;
            justify-content: center;
            align-items: center;
        }

        /* Register form styling */
        .register_form {
            width: 100%;
            max-width: 435px;
            background: #fff;
            border-radius: 6px;
            padding: 41px 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .register_form h3 {
            font-size: 20px;
            text-align: center;
        }

        form .input_box label {
            display: block;
            font-weight: 500;
            margin-bottom: 8px;
        }

        /* Input field styling */
        form .input_box input {
            width: 100%;
            height: 57px;
            border: 1px solid #DADAF2;
            border-radius: 5px;
            outline: none;
            background: #F8F8FB;
            font-size: 17px;
            padding: 0px 20px;
            margin-bottom: 25px;
            transition: 0.2s ease;
        }

        form .input_box input:focus {
            border-color: #626cd6;
        }

        a {
            text-decoration: none;
            color: #626cd6;
            font-weight: 500;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Register button styling */
        form button {
            width: 100%;
            height: 56px;
            border-radius: 5px;
            border: none;
            outline: none;
            background: #626CD6;
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            text-transform: uppercase;
            cursor: pointer;
            margin-bottom: 28px;
            transition: 0.3s ease;
        }

        form button:hover {
            background: #4954d0;
        }
    </style>
</head>
<body>
<div class="register_form">
    <!-- Register form container -->
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h3>{{ config('app.name') }}</h3>

        <!-- Name input box -->
        <div class="input_box">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" value="{{ old('name') }}" required autocomplete="name" autofocus />
        </div>

        <!-- Email input box -->
        <div class="input_box">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter email address" value="{{ old('email') }}" required autocomplete="email" />
        </div>

        <!-- Password input box -->
        <div class="input_box">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="new-password" />
        </div>

        <!-- Confirm Password input box -->
        <div class="input_box">
            <label for="password-confirm">Confirm Password</label>
            <input type="password" id="password-confirm" name="password_confirmation" placeholder="Confirm your password" required autocomplete="new-password" />
        </div>

        <!-- Register button -->
        <button type="submit">Register</button>

        <p class="sign_up">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
    </form>
</div>
</body>
</html>
