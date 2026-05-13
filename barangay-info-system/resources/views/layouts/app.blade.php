<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Barangay Information System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            color: var(--ink);
            background: var(--surface);
            font-family: Arial, Helvetica, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: #0f2f24;
            position: sticky;
            top: 0;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.86);
            padding: 0.75rem 1rem;
            margin: 0.2rem 0;
            border-radius: 8px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.12);
            color: white;
        }
        .main-content {
            background-color: var(--surface);
            min-height: 100vh;
        }
        .navbar {
            border-radius: 0 0 8px 8px;
        }
        .card {
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: none;
        }
        .btn-primary {
            background: var(--brand);
            border-color: var(--brand);
        }
        .btn-primary:hover {
            background: #0b3b20;
            border-color: #0b3b20;
        }
        .stats-card {
            border: 1px solid var(--line);
            border-radius: 8px;
            box-shadow: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <nav class="col-md-3 col-lg-2 d-md-block sidebar p-0">
                <div class="p-3 text-center text-white border-bottom">
<img src="{{ asset('Barangay Information System logo design.png') }}" alt="Barangay BIS" style="height: 50px; width: auto;" class="mb-2">
<h5 class="mb-0">Barangay BIS</h5>
                    <small>Operations System</small>
                </div>
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('residents.*') ? 'active' : '' }}" href="{{ route('residents.index') }}">
                                <i class="fas fa-users me-2"></i> Residents
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('households.*') ? 'active' : '' }}" href="{{ route('households.index') }}">
                                <i class="fas fa-home me-2"></i> Households
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('health-visits.*') ? 'active' : '' }}" href="{{ route('health-visits.index') }}">
                                <i class="fas fa-stethoscope me-2"></i> Health Center
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}" href="{{ route('documents.index') }}">
                                <i class="fas fa-file-alt me-2"></i> Document Requests
                            </a>
                        </li>
                        @hasanyrole('Captain|Secretary|Treasurer')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" href="{{ route('payments.index') }}">
                                <i class="fas fa-money-bill-wave me-2"></i> Payments
                            </a>
                        </li>
                        @endhasanyrole
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.index') }}">
                                <i class="fas fa-calendar-alt me-2"></i> Events
                            </a>
                        </li>
                        @hasanyrole('Captain|Secretary|Staff')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('blotters.*') ? 'active' : '' }}" href="{{ route('blotters.index') }}">
                                <i class="fas fa-gavel me-2"></i> Blotters
                            </a>
                        </li>
                        @endhasanyrole
                        @role('Captain')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-user-shield me-2"></i> User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                                <i class="fas fa-chart-bar me-2"></i> Reports
                            </a>
                        </li>
                        @endrole

                    </ul>
                </div>
            </nav>

            
            {{-- Main Content --}}
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <nav class="navbar navbar-light bg-white border-bottom py-3 mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h5">Welcome, {{ Auth::user()->name }}</span>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

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
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>
</html>
