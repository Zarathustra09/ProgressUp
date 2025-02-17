<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ProgressUp - School Attendance Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
        }

        .login {
            flex: 1;
            padding: 48px;
            background: white;
        }

        .login h1 {
            color: #1a1a1a;
            font-size: 32px;
            margin-bottom: 32px;
            font-weight: 600;
        }

        .input-group {
            margin-bottom: 24px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input:focus {
            border-color: #8b24c6;
            outline: none;
            background: white;
        }

        .checkbox-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 24px 0;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }

        .forgot-password {
            color: #8b24c6;
            text-decoration: none;
            font-weight: 500;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            background: #8b24c6;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .login-button:hover {
            background: #7b1fb0;
            transform: translateY(-2px);
        }

        .register {
            flex: 1;
            background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
            padding: 48px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .register h1 {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 24px;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, #fff 0%, rgba(255,255,255,0.9) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .register p {
            font-size: 18px;
            line-height: 1.6;
            margin: 24px 0;
            opacity: 0.9;
        }

        .feature-list {
            text-align: left;
            margin: 32px 0;
            width: 100%;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            font-size: 16px;
        }

        .feature-item i {
            margin-right: 12px;
            font-size: 20px;
        }

        .trial-button {
            background: transparent;
            border: 2px solid white;
            color: white;
            padding: 12px 24px;
            border-radius: 24px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 16px;
        }

        .trial-button:hover {
            background: white;
            color: #FF6B6B;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .login, .register {
                padding: 32px;
            }

            .register {
                order: -1;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="login">
        <h1>Log in</h1>
        @if ($errors->any())
            <div style="color: red; margin-bottom: 20px;">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="checkbox-container">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
                <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
            </div>
            <button type="submit" class="login-button">Log in</button>
        </form>
    </div>
    <div class="register">
        <h1>ProgressUp</h1>
        <p>Track student attendance easily. Make better decisions with accurate data.</p>
        <div class="feature-list">
            <div class="feature-item">
                <i class="fas fa-check-circle"></i>
                <span>Track attendance instantly</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-chart-line"></i>
                <span>Get automatic reports</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-mobile-alt"></i>
                <span>Use on any Android device</span>
            </div>
        </div>
        <button class="trial-button">DOWNLOAD APP <i class="fas fa-arrow-right"></i></button>
        <p style="font-size: 14px; margin-top: 24px;">Progress Up NOW!</p>
    </div>
</div>
</body>
</html>
