<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bg-color: #0f2027;
            --text-color: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.08);
            --primary: #00d4ff;
            --font: 'Poppins', sans-serif;
            --radius: 15px;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            --bg-dark: #0f2027;
            --bg-light: #f2f2f2;
            --card-bg: rgba(255, 255, 255, 0.06);
            --radius: 15px;
        }

        body.light-mode {
            --bg-color: #f2f2f2;
            --text-color: #111111;
            --card-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: var(--font);
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background 0.3s, color 0.3s;
            margin: 0;
            padding-bottom: 40px;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
        }

        .navbar-brand img {
            height: 36px;
            margin-right: 8px;
        }

        .mode-toggle {
            cursor: pointer;
            font-size: 1.2rem;
            color: var(--primary);
        }

        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
            color: var(--text-color);
        }

        .footer {
            text-align: center;
            font-size: 0.9rem;
            color: #aaa;
            padding-top: 20px;
        }

        .table th, .table td {
            color: var(--text-color);
        }

        .table-transparent {
            background-color: transparent !important;
            color: var(--text-color);
        }

        .table-transparent thead th,
        .table-transparent tbody td,
        .table-transparent tbody th {
            background-color: transparent !important;
            border-color: rgba(255, 255, 255, 0.1); /* atau rgba(0,0,0,0.1) untuk light mode */
        }

        .table-hover tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Tombol Umum */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        /* Tombol Utama */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #000;
        }

        .btn-primary:hover {
            background-color: #00c2e0;
            border-color: #00c2e0;
            color: #000;
        }

        /* Tombol Success */
        .btn-success {
            background-color: #28c76f;
            border-color: #28c76f;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #20b162;
            border-color: #20b162;
        }

        /* Tombol Danger */
        .btn-danger {
            background-color: #ea5455;
            border-color: #ea5455;
            color: #fff;
        }
        .btn-danger:hover {
            background-color: #d43f3f;
            border-color: #d43f3f;
        }

        /* Tombol Warning */
        .btn-warning {
            background-color: #ff9f43;
            border-color: #ff9f43;
            color: #000;
        }
        .btn-warning:hover {
            background-color: #f3903f;
        }

        /* Tombol Outline Modern */
        .btn-outline-light {
            border-color: var(--text-color);
            color: var(--text-color);
        }
        .btn-outline-light:hover {
            background-color: var(--text-color);
            color: var(--bg-dark);
        }

        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-color);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: 0.3s;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 212, 255, 0.25);
            border-color: var(--primary);
            background-color: rgba(255, 255, 255, 0.08);
        }
        /* Styling option (diperbolehkan di beberapa browser) */
        select.form-select option {
            background-color: var(--bg-dark);
            color: var(--text-dark);
        }

        /* Untuk mode terang */
        body.light-mode select.form-select option {
            background-color: var(--bg-light);
            color: var(--text-light);
        }
        /* Umum */
        input.form-control,
        select.form-control {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-dark);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Untuk input type time */
        input[type="time"] {
            color-scheme: dark; /* agar default UI time picker menyesuaikan */
        }

        /* Light mode fix */
        body.light-mode input.form-control,
        body.light-mode select.form-control {
            background-color: rgba(0, 0, 0, 0.05);
            color: var(--text-light) !important;
            border: 1px solid rgba(0, 0, 0, 0.2);
        }

        body.light-mode input[type="time"] {
            color-scheme: light;
            color: var(--text-light);
        }

    </style>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('logo.png') }}" alt="Logo">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="ms-auto mode-toggle" onclick="toggleMode()" title="Ganti Mode">
                    <i id="mode-icon" class="fas fa-moon"></i>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"></ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="btn btn-outline-light ms-2">
                                    <i class="fas fa-globe"></i> Public
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="btn btn-outline-light ms-2">
                                    <i class="fas fa-globe"></i> Public
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMode() {
            const body = document.body;
            const icon = document.getElementById("mode-icon");

            body.classList.toggle("light-mode");
            const isLight = body.classList.contains("light-mode");

            icon.classList.toggle("fa-moon", !isLight);
            icon.classList.toggle("fa-sun", isLight);

            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        }

        // Load saved mode on page load
        window.onload = function () {
            const saved = localStorage.getItem('theme');
            const icon = document.getElementById("mode-icon");
            if (saved === 'light') {
                document.body.classList.add("light-mode");
                icon.classList.replace("fa-moon", "fa-sun");
            }
        };
    </script>
</body>
</html>
