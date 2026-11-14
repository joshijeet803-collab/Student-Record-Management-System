<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"><style>
    body {
        margin: 0;
        background: #f3f7fd;
        font-family: Arial, sans-serif;
    }

    .register-navbar {
        height: 70px;
        background: #0b2e50;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 6%;
    }

    .register-brand {
        color: white;
        font-size: 23px;
        font-weight: bold;
        text-decoration: none;
    }

    .register-brand i {
        margin-right: 8px;
    }

    .register-links {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .register-links a {
        color: white;
        text-decoration: none;
        font-size: 14px;
    }

    .register-links .login-link {
        background: white;
        color: #263746;
        padding: 10px 18px;
        border-radius: 7px;
    }

    .register-links .register-link {
        background: #087cf0;
        color: white;
        padding: 10px 18px;
        border-radius: 7px;
    }

    .register-wrapper {
        min-height: calc(100vh - 70px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 52px 15px;
    }

    .register-card {
        width: 100%;
        max-width: 452px;
        background: white;
        padding: 34px;
        border-radius: 9px;
        box-shadow: 0 8px 30px rgba(30, 55, 80, 0.10);
    }

    .register-card h1 {
        text-align: center;
        font-size: 30px;
        font-weight: 750;
        color: #17283c;
        margin: 0 0 5px;
    }

    .register-subtitle {
        text-align: center;
        color: #858b94;
        font-size: 12px;
        margin-bottom: 26px;
    }

    .register-input {
        width: 100%;
        height: 41px;
        border: 1px solid #d9dfe5;
        border-radius: 6px;
        padding: 10px 12px;
        margin-bottom: 13px;
        font-size: 14px;
        outline: none;
    }

    .register-input:focus {
        border-color: #087cf0;
        box-shadow: 0 0 0 2px rgba(8, 124, 240, 0.10);
    }

    .register-submit {
        width: 100%;
        height: 44px;
        border: none;
        border-radius: 6px;
        background: #087cf0;
        color: white;
        font-size: 14px;
        margin-top: 0;
    }

    .register-submit:hover {
        background: #066bd0;
    }

    .register-login-text {
        text-align: center;
        margin: 17px 0 0;
        font-size: 14px;
        color: #333;
    }

    .register-login-text a {
        color: #4777ad;
        text-decoration: none;
    }

    .register-error {
        color: #dc3545;
        font-size: 12px;
        margin: -8px 0 10px;
    }

    @media (max-width: 600px) {
        .register-navbar {
            padding: 0 15px;
        }

        .register-links {
            gap: 10px;
        }

        .register-links a {
            font-size: 12px;
        }

        .register-links .login-link,
        .register-links .register-link {
            padding: 8px 10px;
        }

        .register-card {
            padding: 28px 22px;
        }
    }
</style>

</head>
<body><nav class="register-navbar">
    <a href="{{ route('home') }}" class="register-brand">
        <i class="bi bi-mortarboard-fill"></i>SRMS
    </a><div class="register-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('contact') }}">Contact</a>
    <a href="{{ route('login') }}" class="login-link">Login</a>
    <a href="{{ route('register') }}" class="register-link">Register</a>
</div>

</nav><main class="register-wrapper">
    <div class="register-card">
        <h1>Create Account</h1>
        <p class="register-subtitle">Register to access the system</p>    @if ($errors->any())
        <div class="alert alert-danger py-2">
            @foreach ($errors->all() as $error)
                <div class="small">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf

        <input
            type="text"
            name="name"
            class="register-input"
            placeholder="Full Name"
            value="{{ old('name') }}"
            required
        >

        <input
            type="email"
            name="email"
            class="register-input"
            placeholder="Email"
            value="{{ old('email') }}"
            required
        >

        <input
            type="password"
            name="password"
            class="register-input"
            placeholder="Password"
            required
        >

        <input
            type="password"
            name="password_confirmation"
            class="register-input"
            placeholder="Confirm Password"
            required
        >

        <button type="submit" class="register-submit">Register</button>
    </form>

    <p class="register-login-text">
        Already have an account? <a href="{{ route('login') }}">Login</a>
    </p>
</div>

</main></body>
</html>