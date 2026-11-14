<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply Message | SRMS</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f4f7fb; }
        .reply-wrap { max-width:800px; margin:45px auto; padding:0 18px; }
        .reply-card { background:white; border:1px solid #e5ebf3; border-radius:18px; overflow:hidden; box-shadow:0 12px 35px #102a4310; }
        .reply-head { background:linear-gradient(120deg,#102f55,#1769aa); color:white; padding:25px 30px; }
        .reply-head h2 { font-weight:800; margin:0 0 6px; }
        .reply-body { padding:30px; }
        .sender { background:#f3f7fc; padding:18px; border-radius:12px; margin-bottom:20px; }
        .sender p { margin:5px 0; overflow-wrap:anywhere; }
        .original { background:#f8fafc; border-left:4px solid #1976d2; padding:18px; border-radius:8px; margin-bottom:24px; white-space:pre-wrap; overflow-wrap:anywhere; }
        .reply-body label { font-weight:700; color:#243b53; margin-bottom:9px; }
        .reply-body textarea { min-height:170px; resize:vertical; margin-bottom:20px; }
        .actions { display:flex; gap:12px; flex-wrap:wrap; }
        .back-btn { background:#edf2f7; color:#334e68; padding:12px 20px; border-radius:9px; text-decoration:none; font-weight:700; }
        @media(max-width:600px) { .reply-wrap { margin:25px auto; } .reply-head,.reply-body { padding:22px; } }
    </style>
</head>
<body>

<header class="topbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>
    <nav class="main-nav">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('messages.index') }}">Messages</a>
        <a href="{{ route('logout') }}" class="nav-btn">Logout</a>
    </nav>
</header>

<main class="reply-wrap">
    <div class="reply-card">
        <div class="reply-head">
            <h2><i class="bi bi-reply-fill"></i> Reply to Message</h2>
            <p class="mb-0">Respond to the student's enquiry.</p>
        </div>

        <div class="reply-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="sender">
                <p><strong>Message ID:</strong> #{{ $message->id }}</p>
                <p><strong>Student:</strong> {{ $message->name }}</p>
                <p><strong>Email:</strong> {{ $message->email }}</p>
            </div>

            <label>Student's Message</label>
            <div class="original">{{ $message->message }}</div>

            <form method="POST" action="{{ route('messages.reply.store', $message->id) }}">
                @csrf

                <label for="reply">Your Reply</label>
                <textarea id="reply" name="reply" class="form-control"
                          maxlength="10000" placeholder="Write your reply..." required>{{ old('reply', $message->admin_reply ?: $message->reply) }}</textarea>

                <div class="actions">
                    <button type="submit" class="full-btn">
                        <i class="bi bi-send-fill"></i> Send Reply
                    </button>
                    <a href="{{ route('messages.index') }}" class="back-btn">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="footer-main">
        <div class="footer-logo"><i class="bi bi-mortarboard-fill"></i> SRMS</div>
        <h4>Student Record Management System</h4>
        <p>Simple, smart and secure student management.</p>
    </div>
    <div class="footer-bottom">
        © {{ date('Y') }} Student Record Management System
    </div>
</footer>

</body>
</html>