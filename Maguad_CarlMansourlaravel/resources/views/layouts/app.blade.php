<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Coffee Stock Management')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #1a1e2e;
            --bg-secondary: #252b3d;
            --bg-card: #2d3548;
            --bg-hover: #353e56;
            --text-primary: #ffffff;
            --text-secondary: #a0aec0;
            --accent: #e14eca;
            --accent-hover: #d63384;
            --success: #00f2c3;
            --warning: #ff8d72;
            --danger: #fd5d93;
            --info: #1d8cf8;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-secondary);
            border-right: 1px solid rgba(255,255,255,0.05);
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            text-align: center;
        }

        .sidebar-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--accent);
            letter-spacing: 1px;
        }

        .sidebar-header p {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .nav-section {
            padding: 12px 0;
        }

        .nav-label {
            padding: 8px 20px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-item:hover,
        .nav-item.active {
            color: var(--text-primary);
            background: var(--bg-hover);
            border-left-color: var(--accent);
        }

        .nav-item i {
            width: 24px;
            margin-right: 12px;
            font-size: 0.95rem;
        }

        .nav-item .badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 999px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            background: var(--bg-secondary);
            padding: 16px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .page-title {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .top-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .user-menu:hover {
            background: var(--bg-hover);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--info));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .user-info {
            text-align: left;
        }

        .user-name {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--text-secondary);
        }

        /* Content Area */
        .content-area {
            padding: 28px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .stat-card.primary::before { background: linear-gradient(90deg, var(--info), var(--accent)); }
        .stat-card.success::before { background: linear-gradient(90deg, var(--success), #00c9a7); }
        .stat-card.warning::before { background: linear-gradient(90deg, var(--warning), #ff6b6b); }
        .stat-card.danger::before { background: linear-gradient(90deg, var(--danger), #ff4757); }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 16px;
        }

        .stat-card.primary .stat-icon { background: rgba(29, 140, 248, 0.15); color: var(--info); }
        .stat-card.success .stat-icon { background: rgba(0, 242, 195, 0.15); color: var(--success); }
        .stat-card.warning .stat-icon { background: rgba(255, 141, 114, 0.15); color: var(--warning); }
        .stat-card.danger .stat-icon { background: rgba(253, 93, 147, 0.15); color: var(--danger); }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Cards */
        .card {
            background: var(--bg-card);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .card-title {
            font-size: 1.05rem;
            font-weight: 600;
        }

        .card-body {
            padding: 24px;
        }

        /* Tables */
        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            text-align: left;
            padding: 14px 16px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .data-table td {
            padding: 14px 16px;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }

        .data-table tr:hover td {
            background: rgba(255,255,255,0.02);
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-badge.success { background: rgba(0, 242, 195, 0.1); color: var(--success); }
        .status-badge.success::before { background: var(--success); }
        .status-badge.warning { background: rgba(255, 141, 114, 0.1); color: var(--warning); }
        .status-badge.warning::before { background: var(--warning); }
        .status-badge.danger { background: rgba(253, 93, 147, 0.1); color: var(--danger); }
        .status-badge.danger::before { background: var(--danger); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(225, 78, 202, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), #00c9a7);
            color: var(--bg-primary);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger), #ff4757);
            color: white;
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info), #0b5ed7);
            color: white;
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.8rem;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        /* Forms */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg-primary);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 0.9rem;
            font-family: inherit;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
        }

        .form-control::placeholder {
            color: rgba(160, 174, 192, 0.5);
        }

        select.form-control {
            cursor: pointer;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        /* Alerts */
        .alert {
            padding: 14px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(0, 242, 195, 0.1);
            border: 1px solid rgba(0, 242, 195, 0.2);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(253, 93, 147, 0.1);
            border: 1px solid rgba(253, 93, 147, 0.2);
            color: var(--danger);
        }

        /* Action buttons in tables */
        .actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            background: rgba(255,255,255,0.05);
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-btn:hover {
            background: var(--accent);
            color: white;
        }

        .action-btn.delete:hover {
            background: var(--danger);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            list-style: none;
        }

        .pagination li a,
        .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            background: var(--bg-primary);
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .pagination li.active span {
            background: var(--accent);
            color: white;
        }

        .pagination li a:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        /* Mobile menu toggle */
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--text-primary);
            font-size: 1.3rem;
            cursor: pointer;
        }

        /* Logout button in sidebar */
        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px;
            background: rgba(253, 93, 147, 0.1);
            border: none;
            border-radius: 8px;
            color: var(--danger);
            font-family: inherit;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: rgba(253, 93, 147, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Stock level indicator */
        .stock-bar {
            width: 100%;
            height: 6px;
            background: rgba(255,255,255,0.08);
            border-radius: 3px;
            overflow: hidden;
            margin-top: 6px;
        }

        .stock-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .stock-bar-fill.high { background: var(--success); }
        .stock-bar-fill.medium { background: var(--warning); }
        .stock-bar-fill.low { background: var(--danger); }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2><i class="fas fa-coffee"></i> COFFEE STOCK</h2>
            <p>Inventory Management</p>
        </div>

        <nav class="nav-section">
            <div class="nav-label">Main</div>
            <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>

            <div class="nav-label">Inventory</div>
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->is('products*') ? 'active' : '' }}">
                <i class="fas fa-box"></i> Products
            </a>
            <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->is('suppliers*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i> Suppliers
            </a>
            <a href="{{ route('employees.index') }}" class="nav-item {{ request()->is('employees*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Employees
            </a>

            <div class="nav-label">Transactions</div>
            <a href="{{ route('stock-ins.index') }}" class="nav-item {{ request()->is('stock-ins*') ? 'active' : '' }}">
                <i class="fas fa-arrow-down"></i> Stock In
            </a>
            <a href="{{ route('stock-outs.index') }}" class="nav-item {{ request()->is('stock-outs*') ? 'active' : '' }}">
                <i class="fas fa-arrow-up"></i> Stock Out
            </a>
            <a href="{{ route('damaged.index') }}" class="nav-item {{ request()->is('damaged*') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle"></i> Damaged Goods
            </a>
            <a href="{{ route('expired.index') }}" class="nav-item {{ request()->is('expired*') ? 'active' : '' }}">
                <i class="fas fa-calendar-times"></i> Expired Goods
            </a>
        </nav>

        <div class="sidebar-footer">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div style="display:flex;align-items:center;gap:16px;">
                <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="top-actions">
                <div class="user-menu">
                    <div class="user-avatar">{{ substr(auth()->user()->firstname ?? 'A', 0, 1) }}</div>
                    <div class="user-info">
                        <div class="user-name">{{ auth()->user()->firstname ?? 'Admin' }} {{ auth()->user()->lastname ?? '' }}</div>
                        <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @yield('scripts')
</body>
</html>
