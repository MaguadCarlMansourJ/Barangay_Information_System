<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ config('app.name', 'Barangay Information System') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.12);
            z-index: 1;
        }
        .wrap{ position: relative; z-index: 2; width:100%; max-width: 430px; }
        .card{ background: rgba(255, 255, 255, 0.94); border: 1px solid rgba(255, 255, 255, 0.35); border-radius: 14px; box-shadow: 0 18px 55px rgba(0,0,0,.24); overflow: hidden; }
        .header{ padding: 22px 24px 12px 24px; background: #f5f7fb; border-bottom: 1px solid #d8e2dc; text-align:center; }
        .title{ font-weight: 800; letter-spacing: 0.2px; margin: 0; font-size: 20px; color: #14532d; }
        .sub{ margin: 8px 0 0 0; color: #5b6b86; font-size: 13px; }
        .body{ padding: 18px 24px 24px 24px; }
        .btn-main{ width:100%; border-radius: 8px; font-weight: 700; padding: 12px 14px; background: #14532d; border: 1px solid #14532d; }
        .btn-main:hover{ filter: brightness(0.98); }
        .form-label{ font-weight: 600; color: #23314f; margin-bottom: 6px; }
        .form-control{ border-radius: 8px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="header">
            <h1 class="title">Choose a New Password</h1>
            <p class="sub">Make sure it is at least 8 characters.</p>
        </div>

        <div class="body">
            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <label class="form-label" for="password">New Password</label>
                    <input id="password" type="password" name="password" class="form-control" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button class="btn btn-main" type="submit">Update Password</button>
            </form>

            <div class="mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none" style="color:#14532d; font-weight:600;">Back to login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>

