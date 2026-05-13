<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name', 'Barangay Information System') }}</title>

    <!-- Bootstrap (keep lightweight, intranet-like) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
body{
            min-height: 100vh;
            background:
                linear-gradient(rgba(10, 35, 28, .72), rgba(10, 35, 28, .72)),
                url('{{ asset("Barangay Information System logo design.png") }}') no-repeat center center / contain;
            background-color: #0f2f24;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 12px;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji","Segoe UI Emoji";
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.12);
            z-index: 1;
        }

        .login-wrap {
            position: relative;
            z-index: 2;
        }

        .login-wrap{
            width: 100%;
            max-width: 430px;
        }

        .login-card{
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 14px;
            box-shadow: 0 18px 55px rgba(0,0,0,.24);
            overflow: hidden;
        }

        .login-header{
            padding: 22px 24px 12px 24px;
            background: #f5f7fb;
            border-bottom: 1px solid #d8e2dc;
        }

        .login-title{
            font-weight: 800;
            letter-spacing: 0.2px;
            margin: 0;
            font-size: 20px;
            color: #14532d;
        }

        .login-subtitle{
            margin: 8px 0 0 0;
            color: #5b6b86;
            font-size: 13px;
        }

        .login-body{
            padding: 18px 24px 24px 24px;
        }

        .form-label{
            font-weight: 600;
            color: #23314f;
            margin-bottom: 6px;
        }

        .form-control{
            border-radius: 8px;
        }

        .password-toggle{
            cursor: pointer;
            user-select: none;
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c7a93;
        }

        .input-with-icon{
            position: relative;
        }

        .btn-login{
            width: 100%;
            border-radius: 8px;
            font-weight: 700;
            padding: 12px 14px;
            background: #14532d;
            border: 1px solid #14532d;
        }
        .btn-login:hover{
            filter: brightness(0.98);
        }

        .help-row{
            margin-top: 10px;
        }

        .forgot-link{
            color: #14532d;
            text-decoration: none;
        }
        .forgot-link:hover{
            text-decoration: underline;
        }

        .alert{
            border-radius: 8px;
        }
    </style>
</head>

<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-header text-center">
            <h1 class="login-title">Barangay Information System</h1>
            <p class="login-subtitle">Sign in to continue.</p>
        </div>

        <div class="login-body">

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route($loginRouteName ?? 'login') }}">

                @csrf

                <div class="mb-4">
                    <label class="form-label" for="email">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="Enter your email"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3 input-with-icon">
                    <label class="form-label" for="password">Password</label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Enter your password"
                        required
                    >

                    <span class="password-toggle" id="togglePassword" aria-label="Toggle password visibility" role="button">
                        <i class="fa-solid fa-eye"></i>
                    </span>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4 help-row">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                            id="remember"
                            value="1"
                        >
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

<a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>Sign in
                </button>
            </form>
        </div>
</div>
</div>

<script>
    (function () {
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        if (!toggle || !password) return;

        toggle.addEventListener('click', function () {
            const isPassword = password.getAttribute('type') === 'password';
            password.setAttribute('type', isPassword ? 'text' : 'password');

            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', isPassword);
                icon.classList.toggle('fa-eye-slash', !isPassword);
            }
        });
    })();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
