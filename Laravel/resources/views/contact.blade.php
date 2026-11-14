<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | SRMS</title>

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

        @if(session()->has('srms_user_id'))

            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('messages.index') }}">My Messages</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('logout') }}" class="nav-btn">Logout</a>

        @else

            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
            <a href="{{ route('login') }}" class="nav-btn light">Login</a>
            <a href="{{ route('register') }}" class="nav-btn">Register</a>

        @endif

    </nav>
</header>

<main class="page">

    <div class="section-title">
        <h2>Contact Us</h2>
        <p>Feel free to reach out for any queries or support.</p>
    </div>

    <div class="contact-grid">

        <div>
            <div class="contact-info">
                <i class="bi bi-geo-alt-fill"></i>
                <div>
                    <h6>Address</h6>
                    <p>Marwadi University, Rajkot</p>
                </div>
            </div>

            <div class="contact-info">
                <i class="bi bi-envelope-fill"></i>
                <div>
                    <h6>Email</h6>
                    <p>support@srms.com</p>
                </div>
            </div>

            <div class="contact-info">
                <i class="bi bi-telephone-fill"></i>
                <div>
                    <h6>Phone</h6>
                    <p>+91 9876543210</p>
                </div>
            </div>

            <div class="contact-info">
                <i class="bi bi-clock-fill"></i>
                <div>
                    <h6>Working Hours</h6>
                    <p>Mon - Sat, 9:00 AM - 5:00 PM</p>
                </div>
            </div>
        </div>

        <div class="card-box">

            @if(session('success'))
                <div class="alert-msg success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-msg error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Your Name"
                    value="{{ old('name', session('srms_user_name')) }}"
                    required>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Your Email"
                    value="{{ old('email') }}"
                    required>

                <textarea
                    name="message"
                    class="form-control"
                    rows="1"
                    placeholder="Your Message"
                    required>{{ old('message') }}</textarea>

                <button type="submit" class="full-btn">
                    Send Message
                </button>
            </form>

        </div>

    </div>
</main>

<footer class="footer">
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
</footer>

</body>
</html>