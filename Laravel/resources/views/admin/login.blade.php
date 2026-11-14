<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        .admin-login {
            max-width: 450px;
            margin: 52px auto;
            padding: 35px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            text-align: center;
        }
        .admin-login h2 {
            font-size: 30px;
            font-weight: 800;
            color: #102a43;
        }
        .admin-login .form-control {
            padding: 11px;
            margin-bottom: 13px;
        }
        .admin-login .full-btn {
            width: 100%;
            padding: 12px;
        }
        .student-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #1769aa;
        }
    </style>
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
        <a href="{{ route('admin.login') }}" class="nav-btn">Admin</a>
    </nav>
</header>

<main class="admin-login">
    <h2>Admin Login</h2>
    <p class="text-secondary mb-4">Secure administrator access</p>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.store') }}" method="POST">
        @csrf

        <input type="email" name="email" class="form-control"
               placeholder="Name or Email" value="{{ old('email') }}" required>

        <input type="password" name="password" class="form-control"
               placeholder="Password" required>

        <button type="submit" class="full-btn">
            Login as Admin
        </button>
    </form>

    <a href="{{ route('login') }}" class="student-link">Student Login</a>
</main>

</body>
</html>