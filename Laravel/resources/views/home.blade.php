<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<header class="topbar">
    <a class="brand" href="{{ route('home') }}">
        <i class="bi bi-mortarboard-fill"></i>
        <span>SRMS</span>
    </a>

    <nav>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a class="nav-btn light" href="{{ route('login') }}">Login</a>
        <a class="nav-btn" href="{{ route('register') }}">Register</a>
        <a class="nav-btn admin-btn" href="{{ route('admin.login') }}">
            <i class="bi bi-shield-lock-fill"></i> Admin
        </a>
    </nav>
</header>

<section class="hero">
    <div class="hero-text">
        <h1>Manage<br>Student Records<br>Easily</h1>
        <p>A Simple, Smart and Secure System for Student Management.</p>

        <a class="btn-main" href="{{ route('login') }}">
            Get Started <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="hero-art">
        <i class="bi bi-mortarboard-fill"></i>
    </div>
</section>

<section class="feature-row">
    <div class="feature">
        <i class="bi bi-tools"></i>
        <h6>Easy to Use</h6>
        <p>Simple interface</p>
    </div>

    <div class="feature">
        <i class="bi bi-shield-lock-fill"></i>
        <h6>Secure Data</h6>
        <p>Your data is safe</p>
    </div>

    <div class="feature">
        <i class="bi bi-speedometer2"></i>
        <h6>Fast Access</h6>
        <p>Quick and reliable</p>
    </div>
</section>

<footer class="footer">
    <div class="footer-main">
        <div class="footer-logo">
            <i class="bi bi-mortarboard-fill"></i> SRMS
        </div>

        <h4>Student Record Management System</h4>
        <p>A simple solution for a better tomorrow.</p>

        <div class="socials">
            <span><i class="bi bi-facebook"></i></span>
            <span><i class="bi bi-twitter"></i></span>
            <span><i class="bi bi-instagram"></i></span>
            <span><i class="bi bi-linkedin"></i></span>
        </div>
    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} SRMS. All Rights Reserved.
    </div>
</footer>

<style>
    .admin-btn {
        background: linear-gradient(120deg, #164d86, #2378c9);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 8px;
        color: #fff !important;
        padding: 10px 16px;
        font-weight: 600;
    }

    .admin-btn:hover {
        background: #0d5eaa;
        color: #fff !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>