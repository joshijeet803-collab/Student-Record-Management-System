<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Message | SRMS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <style>
        body {
            background: #f4f8fc;
        }

        .edit-wrap {
            width: 100%;
            max-width: 482px;
            margin: 45px auto;
            padding: 0 15px;
        }

        .edit-card {
            width: 100%;
            background: #fff;
            padding: 30px 34px;
            border-radius: 9px;
            box-shadow: 0 5px 25px #14385c18;
        }

        .edit-card h2 {
            text-align: center;
            font-size: 30px;
            font-weight: 800;
            margin: 0 0 5px;
            color: #122033;
        }

        .edit-sub {
            text-align: center;
            color: #758497;
            font-size: 12px;
            margin-bottom: 25px;
        }

        .edit-card label {
            display: block;
            font-weight: 400;
            margin-bottom: 6px;
            color: #333;
        }

        .edit-card .form-control {
            width: 100%;
            min-height: 41px;
            height: 41px;
            margin-bottom: 13px;
            padding: 8px 12px;
            border: 1px solid #dbe3eb;
            border-radius: 5px;
            font: inherit;
        }

        .edit-card textarea.form-control {
            height: 42px;
            min-height: 42px;
            resize: vertical;
        }

        .edit-card .full-btn {
            width: 100%;
            min-height: 44px;
            margin: 0 0 7px;
            border-radius: 5px;
        }

        .cancel-btn {
            display: block;
            width: 100%;
            padding: 9px;
            text-align: center;
            border: 1px solid #888;
            border-radius: 5px;
            color: #666;
            text-decoration: none;
            background: #fff;
        }

        .cancel-btn:hover {
            background: #f1f3f5;
            color: #333;
        }

        @media(max-width: 500px) {
            .edit-wrap {
                margin: 30px auto;
            }

            .edit-card {
                padding: 25px 22px;
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

<main class="edit-wrap">

    <div class="edit-card">

        <h2>Edit Message</h2>
        <p class="edit-sub">Update your message details below.</p>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('messages.update', $message->id) }}">
            @csrf
            @method('PUT')

            <label for="name">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                value="{{ old('name', $message->name) }}"
                required>

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email', $message->email) }}"
                required>

            <label for="message">Message</label>
            <textarea
                id="message"
                name="message"
                class="form-control"
                required>{{ old('message', $message->message) }}</textarea>

            <button type="submit" class="full-btn">
                Save Changes
            </button>

            <a href="{{ route('messages.index') }}" class="cancel-btn">
                Cancel
            </a>
        </form>

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

</body>
</html>