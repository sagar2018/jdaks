<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — JDAKS Infra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: linear-gradient(135deg, #1E293B 0%, #1E40AF 100%); min-height: 100vh; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
<div class="card border-0 shadow-lg" style="width:100%;max-width:420px;">
    <div class="card-body p-5">
        <h5 class="fw-bold mb-1">Reset Password</h5>
        <p class="text-muted small mb-4">Enter your email to receive a reset link.</p>

        @if(session('status'))
        <div class="alert alert-success small py-2">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" required autofocus>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold mb-3">Send Reset Link</button>
            <div class="text-center">
                <a href="{{ route('login') }}" class="small text-decoration-none">Back to login</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
