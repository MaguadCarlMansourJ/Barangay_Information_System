<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resident Portal - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --brand: #14532d;
            --brand-2: #0f766e;
            --surface: #f5f7fb;
            --ink: #172026;
            --muted: #667085;
            --line: #d8e2dc;
        }

        body {
            background: var(--surface);
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
        }

        .portal-sidebar {
            min-height: 100vh;
            background: #0f2f24;
            color: #fff;
            position: sticky;
            top: 0;
        }

        .portal-sidebar .nav-link {
            color: rgba(255,255,255,.82);
            border-radius: 8px;
            margin-bottom: 4px;
            padding: 11px 14px;
        }

        .portal-sidebar .nav-link.active,
        .portal-sidebar .nav-link:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
        }

        .portal-main {
            min-height: 100vh;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid var(--line);
        }

        .metric-card,
        .portal-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 8px;
        }

        .metric-card {
            padding: 20px;
            height: 100%;
        }

        .metric-card strong {
            display: block;
            font-size: 1.9rem;
            color: var(--brand);
        }

        .btn-brand {
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .btn-brand:hover {
            background: #0b3b20;
            border-color: #0b3b20;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-2 col-md-3 portal-sidebar p-3">
                <div class="text-center border-bottom border-light border-opacity-25 pb-3 mb-3">
                    <img src="{{ asset('Barangay Information System logo design.png') }}" alt="Barangay seal" style="height: 58px; width: auto;">
                    <h6 class="mb-0 mt-2">Resident Portal</h6>
                    <small class="text-white-50">{{ config('app.name') }}</small>
                </div>

                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('resident-portal.dashboard') ? 'active' : '' }}" href="{{ route('resident-portal.dashboard') }}">
                        <i class="fas fa-gauge me-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('resident-portal.profile') ? 'active' : '' }}" href="{{ route('resident-portal.profile') }}">
                        <i class="fas fa-id-card me-2"></i> My Profile
                    </a>
                    <a class="nav-link {{ request()->routeIs('resident-portal.requests*') ? 'active' : '' }}" href="{{ route('resident-portal.requests') }}">
                        <i class="fas fa-file-signature me-2"></i> My Requests
                    </a>
                    <a class="nav-link {{ request()->routeIs('resident-portal.events') ? 'active' : '' }}" href="{{ route('resident-portal.events') }}">
                        <i class="fas fa-calendar-check me-2"></i> Activities
                    </a>
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="fas fa-globe me-2"></i> Public Site
                    </a>
                </nav>
            </aside>

            <main class="col-lg-10 col-md-9 portal-main px-0">
                <div class="topbar px-4 py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Welcome, {{ Auth::user()->name }}</strong>
                        <div class="small text-muted">Access your barangay records and requests.</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-right-from-bracket me-1"></i> Logout
                        </button>
                    </form>
                </div>

                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Please check the form.</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
