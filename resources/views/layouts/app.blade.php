<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JDAKS Infra — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --accent-blue:  #1E40AF;
            --accent-green: #15803D;
            --accent-amber: #B45309;
            --accent-red:   #B91C1C;
        }
        body { background: #F8FAFC; }
        .app-sidebar {
            position: fixed; top: 0; left: 0; height: 100vh;
            width: 240px; background: #1E293B; z-index: 1040;
            overflow-y: auto; transition: transform .25s;
        }
        .app-sidebar .brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .app-sidebar .nav-link {
            color: rgba(255,255,255,.65);
            padding: .45rem 1rem;
            border-radius: 6px;
            margin: 1px 8px;
            font-size: .85rem;
        }
        .app-sidebar .nav-link:hover,
        .app-sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.08); }
        .app-sidebar .nav-section {
            font-size: .65rem; text-transform: uppercase; letter-spacing: .1em;
            color: rgba(255,255,255,.3); padding: .75rem 1rem .25rem;
        }
        .app-content {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .app-topbar {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            height: 56px;
            display: flex;
            align-items: center;
            padding: 0 1.25rem;
            position: sticky; top: 0; z-index: 100;
        }
        .app-main { padding: 1.5rem 1.25rem; flex: 1; }
        @media(max-width: 991px) {
            .app-sidebar { transform: translateX(-100%); }
            .app-sidebar.show { transform: translateX(0); }
            .app-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── Sidebar ── --}}
<aside class="app-sidebar" id="sidebar">
    <div class="brand d-flex align-items-center">
        <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
             style="width:34px;height:34px;background:var(--accent-blue);flex-shrink:0;">
            <i class="bi bi-building text-white" style="font-size:1rem;"></i>
        </div>
        <div>
            <div class="fw-bold text-white" style="font-size:.9rem;line-height:1.1;">JDAKS Infra</div>
            <div style="font-size:.65rem;color:rgba(255,255,255,.4);">Road Project Suite</div>
        </div>
    </div>

    <div class="mt-2">
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>

        @if(auth()->user()->hasRole('admin'))
        <div class="nav-section">Admin</div>
        <a href="{{ route('admin.users.index') }}"
           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i>Users
        </a>
        @endif

        <div class="nav-section">Projects</div>
        <a href="{{ route('projects.index') }}"
           class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
            <i class="bi bi-folder2-open me-2"></i>All Projects
        </a>
    </div>
</aside>

{{-- ── Content ── --}}
<div class="app-content">
    <header class="app-topbar">
        <button class="btn btn-sm me-2 d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="text-muted small fw-semibold d-none d-sm-inline">@yield('title', 'Dashboard')</span>
        <div class="ms-auto d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                    <li class="px-3 py-1">
                        <span class="badge bg-primary">{{ auth()->user()->getRoleNames()->implode(', ') }}</span>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-1"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="app-main">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2 small" role="alert">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2 small" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
        </div>
        @endif
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
