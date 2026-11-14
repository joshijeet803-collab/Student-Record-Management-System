<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

@php
    $isAdmin = session('srms_user_role') === 'admin';
@endphp

<header class="topbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>

    <nav class="main-nav">
        <a href="{{ route('dashboard') }}">Dashboard</a>

        @if($isAdmin)
            <a href="{{ route('students.index') }}">Students</a>
            <a href="{{ route('students.create') }}">Add Student</a>
            <a href="{{ route('messages.index') }}">All Messages</a>
        @else
            <a href="{{ route('messages.index') }}">My Messages</a>
            <a href="{{ route('contact') }}">Contact</a>
        @endif

        <a href="{{ route('logout') }}" class="nav-btn">Logout</a>
    </nav>
</header>

<main class="dashboard-wrap">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="welcome-row">
        <div>
            <h1>Welcome, {{ session('srms_user_name') }}!</h1>
            <p>Student Record Management System Dashboard</p>
        </div>

        @if($isAdmin)
            <a href="{{ route('students.create') }}" class="btn btn-primary px-4 py-3">
                + Add Student
            </a>
        @endif
    </div>

    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <div class="stat-card">
                <p>Total Students</p>
                <h2>{{ $total }}</h2>
                <a href="{{ route('students.index') }}">
                    View all students <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card">
                <p>Total Courses</p>
                <h2>{{ $courses }}</h2>
                <a href="{{ route('students.index') }}">
                    Manage records <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="recent-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4>Recently Added Students</h4>
                <p class="text-secondary mb-0">Your latest student records.</p>
            </div>

            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                View All Students
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Roll Number</th>
                        <th>Course</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($recentStudents as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->roll_no }}</td>
                            <td>{{ $student->course }}</td>
                            <td>
                                <a href="{{ route('students.view', $student->id) }}"
                                   class="btn btn-sm btn-success">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                No student records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</main>

<footer class="footer">
    <div class="footer-main">
        <div class="footer-logo">
            <i class="bi bi-mortarboard-fill"></i> SRMS
        </div>
        <h4>Student Record Management System</h4>
        <p>A Simple Solution for a Better Tomorrow.</p>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>