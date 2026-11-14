<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | SRMS</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body><header class="topbar">
    <a href="{{ route('home') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a><nav class="main-nav">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{ route('about') }}">About</a>
    <a href="{{ route('contact') }}">Contact</a>
    <a href="{{ route('login') }}" class="nav-btn light">Login</a>
    <a href="{{ route('register') }}" class="nav-btn">Register</a>
</nav>

</header><main class="page"><div class="section-title">
    <h2>About Our System</h2>
    <p>Simple, secure and efficient student management.</p>
</div>

<div class="card-box text-center mx-auto" style="max-width:730px; padding:28px;">
    <p style="margin:0; line-height:1.7;">
        The Student Record Management System (SRMS) is designed to manage student information in a simple, secure and efficient way. This system helps to store, update, view and manage student records easily.
    </p>
</div>

<div class="about-grid">
    <div class="info-card">
        <i class="bi bi-check2-square"></i>
        <h6>Our Goal</h6>
        <p>Simple record management</p>
    </div>

    <div class="info-card">
        <i class="bi bi-mortarboard"></i>
        <h6>Our Vision</h6>
        <p>Digital and efficient campus</p>
    </div>

    <div class="info-card">
        <i class="bi bi-person-badge"></i>
        <h6>Our Team</h6>
        <p>Students for students</p>
    </div>
</div>

</main><footer class="footer">
    <div class="footer-main">
        <div class="footer-logo">
            <i class="bi bi-mortarboard-fill"></i> SRMS
        </div>
        <h4>Student Record Management System</h4>
        <p>Simple, smart and secure student management.</p>
    </div>
    <div class="footer-bottom">
        © {{ date('Y') }} Student Record Management System
    </div>
</footer></body>
</html>