<header class="topbar">
    <a href="{{ route('home') }}" class="brand">
        <i class="bi bi-mortarboard-fill"></i>
        SRMS
    </a>

    <nav class="main-nav">

        @if(session()->has('srms_user_id'))

            <a href="{{ route('dashboard') }}">Dashboard</a>

            <a href="{{ route('messages.index') }}">My Messages</a>

            <a href="{{ route('contact') }}">Contact</a>

            <a href="{{ route('logout') }}" class="nav-btn">
                Logout
            </a>

        @else

            <a href="{{ route('home') }}">Home</a>

            <a href="{{ route('about') }}">About</a>

            <a href="{{ route('contact') }}">Contact</a>

            <a href="{{ route('login') }}" class="nav-btn light">
                Login
            </a>

            <a href="{{ route('register') }}" class="nav-btn">
                Register
            </a>

        @endif

    </nav>
</header>