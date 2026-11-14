<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages | SRMS</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f4f7fb; }
        .wrap { max-width:1250px; margin:40px auto; padding:0 18px; }
        .heading { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:25px; }
        .heading h2 { color:#102a43; font-weight:800; }
        .panel { background:white; border:1px solid #e5ebf3; border-radius:16px; overflow:hidden; box-shadow:0 10px 30px #102a4310; }
        .panel-top { padding:20px 24px; border-bottom:1px solid #edf1f6; font-weight:700; }
        .table { min-width:900px; vertical-align:middle; margin:0; }
        .table th { background:#f5f8fc; color:#526579; font-size:12px; text-transform:uppercase; padding:16px; }
        .table td { padding:16px; color:#334e68; }
        .message { min-width:180px; max-width:260px; overflow-wrap:anywhere; }
        .reply { background:#f0fdf4; border:1px solid #bbf7d0; border-radius:9px; padding:10px; min-width:180px; max-width:250px; overflow-wrap:anywhere; }
        .status { padding:6px 11px; border-radius:20px; font-size:12px; font-weight:700; background:#eaf2ff; color:#175cd3; }
        .Replied { background:#e4f8ed; color:#087443; }
        .Read { background:#fff3d6; color:#8a5700; }
        .actions { display:flex; gap:6px; flex-wrap:wrap; }
        .actions a, .actions button { border:0; border-radius:7px; padding:7px 10px; font-size:12px; font-weight:700; text-decoration:none; }
        .edit { background:#eaf2ff; color:#175cd3; }
        .reply-btn { background:#e4f8ed; color:#087443; }
        .delete { background:#fff0f0; color:#c62828; }
        .empty { text-align:center; padding:55px 15px; color:#718096; }
        @media(max-width:600px) { .wrap { margin:25px auto; padding:0 10px; } }
    </style>
</head>
<body>

<header class="topbar">
    <a href="{{ route('dashboard') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i> SRMS
    </a>
    <nav class="main-nav">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('logout') }}" class="nav-btn">Logout</a>
    </nav>
</header>

<main class="wrap">
    <div class="heading">
        <div>
            <h2><i class="bi bi-chat-square-text-fill"></i>
                {{ session('srms_user_role') === 'admin' ? 'All Messages' : 'My Messages' }}
            </h2>
            <p class="text-muted mb-0">
                {{ session('srms_user_role') === 'admin' ? 'Manage student enquiries and replies.' : 'View your messages and admin replies.' }}
            </p>
        </div>
        <span class="badge text-bg-primary p-3">{{ $messages->count() }} Messages</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel">
        <div class="panel-top">
            <i class="bi bi-inbox-fill"></i> Message Records
        </div>

        @if($messages->count())
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        @if(session('srms_user_role') === 'admin')
                            <th>Name</th>
                            <th>Email</th>
                        @endif
                        <th>Message</th>
                        <th>Admin Reply</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                    <tr>
                        <td>#{{ $message->id }}</td>

                        @if(session('srms_user_role') === 'admin')
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                        @endif

                        <td class="message">{{ $message->message }}</td>

                        <td>
                            @if($message->admin_reply ?: $message->reply)
                                <div class="reply">
                                    {{ $message->admin_reply ?: $message->reply }}
                                    @if($message->replied_at)
                                        <small class="d-block text-muted mt-2">
                                            {{ $message->replied_at }}
                                        </small>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">Not replied yet</span>
                            @endif
                        </td>

                        <td>
                            <span class="status {{ $message->status }}">
                                {{ $message->status }}
                            </span>
                        </td>

                        <td>{{ $message->created_at }}</td>

                        <td>
                            <div class="actions">
                                @if(session('srms_user_role') === 'admin')
                                    <a class="reply-btn" href="{{ route('messages.reply', $message->id) }}">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </a>
                                @endif

                                <a class="edit" href="{{ route('messages.edit', $message->id) }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form method="POST" action="{{ route('messages.destroy', $message->id) }}"
                                      onsubmit="return confirm('Delete this message?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete" type="submit">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="empty">
                <i class="bi bi-chat-square-dots fs-1"></i>
                <h5 class="mt-3">No Messages Found</h5>
                <p>Your messages will appear here.</p>
            </div>
        @endif
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