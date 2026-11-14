<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | SRMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<header class="topbar">
    <a class="brand" href="{{ route('home') }}">🎓 SRMS</a>
</header>

<main class="page">
    <div class="form-card">
        <h2>Reset Password</h2>
        <p class="text-muted">Enter your new password below.</p>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input class="form-control mb-3" type="password" name="password"
                   placeholder="New Password (minimum 6 characters)" required>

            <input class="form-control mb-3" type="password"
                   name="password_confirmation" placeholder="Confirm New Password" required>

            <button class="btn-main w-100" type="submit">Update Password</button>
        </form>
    </div>
</main>

<footer class="footer">
    <div class="footer-main">
        <div class="footer-logo">🎓 SRMS</div>
        <h4>Student Record Management System</h4>
    </div>
    <div class="footer-bottom">© 2026 SRMS. All Rights Reserved.</div>
</footer>

</body>
</html>