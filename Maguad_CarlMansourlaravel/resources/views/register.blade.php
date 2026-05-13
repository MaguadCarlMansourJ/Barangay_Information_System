<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Coffee Stock Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-primary: #1a1e2e;
            --bg-secondary: #252b3d;
            --bg-card: #2d3548;
            --text-primary: #ffffff;
            --text-secondary: #a0aec0;
            --accent: #e14eca;
            --accent-hover: #d63384;
            --danger: #fd5d93;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at 10% 20%, rgba(225,78,202,0.08) 0%, transparent 40%),
                              radial-gradient(circle at 90% 80%, rgba(29,140,248,0.08) 0%, transparent 40%);
            padding: 40px 20px;
        }
        .register-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 48px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), #1d8cf8);
        }
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .logo i {
            font-size: 3rem;
            color: var(--accent);
            margin-bottom: 12px;
        }
        .logo h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .logo p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 4px;
        }
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--text-secondary);
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px 12px 42px;
            background: var(--bg-primary);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(225,78,202,0.1);
        }
        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(225,78,202,0.3);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }
        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover { text-decoration: underline; }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 0.9rem;
        }
        .alert-danger {
            background: rgba(253,93,147,0.1);
            border: 1px solid rgba(253,93,147,0.2);
            color: var(--danger);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo">
            <i class="fas fa-coffee"></i>
            <h1>Create Account</h1>
            <p>Join Coffee Stock Management</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="firstname" class="form-control" value="{{ old('firstname') }}" required placeholder="First name">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="lastname" class="form-control" value="{{ old('lastname') }}" required placeholder="Last name">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Enter your email">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <div class="input-wrapper">
                    <i class="fas fa-briefcase"></i>
                    <select name="role" class="form-control" required style="padding-left:42px;">
                        <option value="">Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" class="form-control" required placeholder="Min 8 characters">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" class="form-control" required placeholder="Confirm password">
                </div>
            </div>
            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ url('/login') }}">Sign in</a>
        </div>
    </div>
</body>
</html>

