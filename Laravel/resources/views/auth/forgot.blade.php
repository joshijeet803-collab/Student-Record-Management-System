<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | SRMS</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<header class="topbar">
    <a href="{{ route('home') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>

    <nav class="main-nav">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('login') }}" class="nav-btn light">Login</a>
        <a href="{{ route('register') }}" class="nav-btn">Register</a>
    </nav>
</header>

<main class="page">
    <div class="form-card">
        <h2>Forgot Password?</h2>
        <p class="sub">Enter your email to reset your password.</p>

        @if(session('success'))
            <div class="alert-msg success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert-msg error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-msg error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Email"
                value="{{ old('email') }}"
                required
            >

            <button type="submit" class="full-btn">Continue</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="small">Back to Login</a>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="footer-bottom">
        © {{ date('Y') }} Student Record Management System
    </div>
</footer>

</body>
</html>