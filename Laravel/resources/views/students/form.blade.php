<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($student) ? 'Edit Student' : 'Add Student' }} | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            background: #f4f8fc;
        }

        /* Header */

        .topbar {
            min-height: 68px;
            padding: 0 6%;
        }

        .topbar .main-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar .main-nav a {
            color: white;
            font-size: 13px;
            text-decoration: none;
        }

        .topbar .main-nav .nav-btn {
            background: #087bf5;
            color: white !important;
            padding: 9px 15px;
            border-radius: 6px;
        }

        /* Layout */

        .admin-page {
            display: flex;
            min-height: calc(100vh - 68px);
        }

        .sidebar {
            width: 181px !important;
            min-width: 181px;
            background: white;
            border-right: 1px solid #e1e8ef;
            padding-top: 20px;
        }

        .sidebar a {
            display: block;
            color: #14263a;
            padding: 13px 22px;
            font-size: 13px;
            text-decoration: none;
        }

        .sidebar a.active,
        .sidebar a:hover {
            background: #087bf5;
            color: white;
        }

        /* Form Position */

        .admin-content {
            flex: 1;
            min-width: 0;
            background: #f4f8fc;
            padding: 36px 0 30px 77px;
        }

        .admin-form {
            width: 576px !important;
            min-width: 576px;
            max-width: 576px !important;
            margin: 0;
        }

        .admin-form .form-card {
            width: 576px !important;
            max-width: 576px !important;
            margin: 0;
            padding: 24px;
            background: white;
            border-radius: 9px;
            box-shadow: 0 5px 22px #14385c12;
        }

        .admin-form .form-card h2 {
            text-align: left;
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 8px;
            color: #122033;
        }

        .admin-form .form-card .sub {
            display: none;
        }

        /* Inputs */

        .admin-form .form-control,
        .admin-form .form-select {
            width: 100%;
            height: 41px;
            min-height: 41px;
            margin-bottom: 12px;
            border: 1px solid #dbe3eb;
            border-radius: 5px;
            padding: 8px 12px;
            font-size: 15px;
        }

        .admin-form .form-control:focus,
        .admin-form .form-select:focus {
            border-color: #087bf5;
            box-shadow: 0 0 0 2px #087bf51a;
        }

        .admin-form .full-btn {
            width: 100%;
            height: 44px;
            min-height: 44px;
            background: #087bf5;
            color: white;
            border: 0;
            border-radius: 5px;
            cursor: pointer;
        }

        .admin-form .full-btn:hover {
            background: #0669d0;
        }

        /* Responsive */

        @media(max-width: 800px) {
            .topbar {
                padding: 12px 20px;
                flex-wrap: wrap;
            }

            .admin-page {
                display: block;
            }

            .sidebar {
                width: 100% !important;
                min-width: 100%;
                display: flex;
                overflow-x: auto;
                padding-top: 0;
            }

            .sidebar a {
                white-space: nowrap;
            }

            .admin-content {
                padding: 25px 18px;
            }

            .admin-form,
            .admin-form .form-card {
                width: 100% !important;
                min-width: 0;
                max-width: 100% !important;
            }
        }

        @media(max-width: 480px) {
            .admin-content {
                padding: 20px 12px;
            }

            .admin-form .form-card {
                padding: 20px;
            }

            .admin-form .form-card h2 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>

    <nav class="main-nav">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('messages.index') }}">My Messages</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('logout') }}" class="nav-btn">Logout</a>
    </nav>
</header>

<div class="admin-page">

    <aside class="sidebar">
        <a href="{{ route('dashboard') }}">
            <i class="bi bi-house-door-fill"></i> Dashboard
        </a>

        <a href="{{ route('students.create') }}" class="active">
            <i class="bi bi-person-plus-fill"></i> Add Student
        </a>

        <a href="{{ route('students.index') }}">
            <i class="bi bi-people-fill"></i> View Students
        </a>

        <a href="{{ route('logout') }}">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </aside>

    <main class="admin-content">

        <div class="admin-form">
            <div class="form-card">

                <h2>{{ isset($student) ? 'Edit Student' : 'Add New Student' }}</h2>

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

                <form
                    action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}"
                    method="POST">

                    @csrf

                    @if(isset($student))
                        @method('PUT')
                    @endif

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="Full Name"
                        value="{{ old('name', $student->name ?? '') }}"
                        required>

                    <input
                        type="text"
                        name="roll_no"
                        class="form-control"
                        placeholder="Roll Number"
                        value="{{ old('roll_no', $student->roll_no ?? '') }}"
                        required>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Email"
                        value="{{ old('email', $student->email ?? '') }}"
                        required>

                    <input
                        type="text"
                        name="course"
                        class="form-control"
                        placeholder="Course"
                        value="{{ old('course', $student->course ?? '') }}"
                        required>

                    <select name="year" class="form-select" required>
                        <option value="">Select Year</option>

                        @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year'] as $year)
                            <option value="{{ $year }}"
                                {{ old('year', $student->year ?? '') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    <input
                        type="tel"
                        name="phone"
                        class="form-control"
                        placeholder="Phone"
                        value="{{ old('phone', $student->phone ?? '') }}"
                        required>

                    <button type="submit" class="full-btn">
                        {{ isset($student) ? 'Update Student' : 'Add Student' }}
                    </button>

                    @if(isset($student))
                        <a href="{{ route('students.index') }}"
                           class="btn btn-outline-secondary w-100 mt-2">
                            Cancel
                        </a>
                    @endif

                </form>

            </div>
        </div>

    </main>

</div>

</body>
</html>