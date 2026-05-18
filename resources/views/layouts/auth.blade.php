<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sign In') — JDAKS Infra</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg-page);">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-6 col-lg-5">

            {{-- Brand --}}
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:56px;height:56px;background:var(--accent-blue);border-radius:14px;">
                    <span style="color:#fff;font-weight:800;font-size:22px;">JD</span>
                </div>
                <h1 class="h4 fw-bold mb-0" style="color:var(--accent-blue);">JDAKS Infra</h1>
                <p class="text-muted small mb-0">Road Project Management System</p>
            </div>

            {{-- Card --}}
            <div class="card auth-card shadow-sm p-4">
                @yield('content')
            </div>

        </div>
    </div>
</div>

@stack('scripts')
</body>
</html>
