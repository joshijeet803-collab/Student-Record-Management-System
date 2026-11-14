<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/students.css') }}" rel="stylesheet">
</head>
<body>

<header class="topbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>

    <nav class="main-nav">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('students.index') }}">My Students</a>
        <a href="{{ route('students.create') }}">Add Student</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-btn">Logout</button>
        </form>
    </nav>
</header>

<main class="students-page">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="students-heading">
        <div>
            <h1>Student List</h1>
            <p>Search, filter and manage student records.</p>
        </div>

        <a href="{{ route('students.create') }}" class="btn-main">
            + Add Student
        </a>
    </div>

    <div class="students-card">

        <form action="{{ route('students.index') }}" method="GET" class="students-filters">

            <input
                type="text"
                name="search"
                class="form-control search-input"
                placeholder="Search name or roll number"
                value="{{ request('search') }}">

            <select name="course" class="form-select course-select">
                <option value="">All Courses</option>

                @foreach($courses as $course)
                    <option value="{{ $course }}"
                        {{ request('course') == $course ? 'selected' : '' }}>
                        {{ $course }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary">
                Search
            </button>

            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                Clear
            </a>

            <a href="{{ route('students.export') }}" class="btn btn-success">
                <i class="bi bi-download"></i> Export CSV
            </a>
        </form>

        <div class="table-responsive">
            <table class="table students-table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Name</th>
                        <th>Roll Number</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->roll_no }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->course }}</td>
                            <td>{{ $student->year }}</td>
                            <td>{{ $student->phone }}</td>

                            <td>
                                <div class="student-actions">
                                    <a href="{{ route('students.view', $student->id) }}"
                                       class="btn btn-success btn-sm">View</a>

                                    <a href="{{ route('students.edit', $student->id) }}"
                                       class="btn btn-primary btn-sm">Edit</a>

                                    <form action="{{ route('students.destroy', $student->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this student?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>