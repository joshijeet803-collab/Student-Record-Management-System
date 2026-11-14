<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/student-view.css') }}" rel="stylesheet">
</head>
<body>

<header class="profile-header">
    <a class="profile-brand" href="{{ route('dashboard') }}">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>

    <nav class="profile-nav">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('messages.index') }}">My Messages</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('logout') }}" class="logout-btn">Logout</a>
    </nav>
</header>

<main class="profile-page">

    <div class="profile-card">

        <div class="profile-heading">
            <h2>Student Profile</h2>
            <p>Complete information about the selected student.</p>
        </div>

        <div class="profile-grid">

            <div class="profile-info">
                <span>Student ID</span>
                <strong>{{ $student->id }}</strong>
            </div>

            <div class="profile-info">
                <span>Student Name</span>
                <strong>{{ $student->name }}</strong>
            </div>

            <div class="profile-info">
                <span>Roll Number</span>
                <strong>{{ $student->roll_no }}</strong>
            </div>

            <div class="profile-info">
                <span>Email Address</span>
                <strong>{{ $student->email }}</strong>
            </div>

            <div class="profile-info">
                <span>Course</span>
                <strong>{{ $student->course }}</strong>
            </div>

            <div class="profile-info">
                <span>Academic Year</span>
                <strong>{{ $student->year }}</strong>
            </div>

            <div class="profile-info">
                <span>Phone Number</span>
                <strong>{{ $student->phone ?: 'Not provided' }}</strong>
            </div>

        </div>

        <div class="profile-actions">
            <a href="{{ route('students.index') }}" class="back-btn">
                <i class="bi bi-arrow-left"></i> Back to Students
            </a>

            <a href="{{ route('dashboard') }}" class="dashboard-btn">
                Dashboard
            </a>
        </div>

    </div>

</main>

<footer class="profile-footer">
    <div class="footer-main">
        <div class="footer-logo">
            <i class="bi bi-mortarboard-fill"></i> SRMS
        </div>

        <h4>Student Record Management System</h4>
        <p>A Simple Solution for a Better Tomorrow.</p>

        <div class="social-links">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-twitter"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
        </div>
    </div>

    <div class="footer-bottom">
        © {{ date('Y') }} SRMS. All Rights Reserved.
    </div>
</footer>

</body>
</html>