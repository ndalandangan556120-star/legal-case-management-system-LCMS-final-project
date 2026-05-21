<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LCMS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            :root {
                --sidebar-bg: #0f172a;
                --sidebar-hover: #1e293b;
                --sidebar-active: #1e293b;
                --sidebar-text: #94a3b8;
                --sidebar-text-active: #f8fafc;
                --accent-gold: #f59e0b;
                --topbar-bg: #ffffff;
                --topbar-border: #e2e8f0;
                --bg-main: #f1f5f9;
            }

            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { font-family: 'Figtree', sans-serif; background: var(--bg-main); }

            /* ── LAYOUT ── */
            .layout { display: flex; min-height: 100vh; }

            /* ── SIDEBAR ── */
            .sidebar {
                width: 230px;
                flex-shrink: 0;
                background: var(--sidebar-bg);
                display: flex;
                flex-direction: column;
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }

            .sidebar-logo {
                padding: 20px 16px 18px;
                border-bottom: 1px solid #1e293b;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .sidebar-logo .logo-icon {
                font-size: 22px;
                color: var(--accent-gold);
            }
            .sidebar-logo .logo-text {
                font-size: 15px;
                font-weight: 700;
                color: #f8fafc;
                letter-spacing: 0.02em;
            }
            .sidebar-logo .logo-sub {
                font-size: 10px;
                color: #64748b;
                margin-top: 1px;
            }

            .nav-section-label {
                font-size: 10px;
                font-weight: 600;
                letter-spacing: 0.08em;
                color: #475569;
                padding: 16px 16px 6px;
                text-transform: uppercase;
            }

            .nav-link {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 9px 16px;
                font-size: 13px;
                font-weight: 500;
                color: var(--sidebar-text);
                text-decoration: none;
                transition: background 0.15s, color 0.15s, border-color 0.15s;
                border-left: 3px solid transparent;
            }
            .nav-link:hover {
                background: var(--sidebar-hover);
                color: #f8fafc;
            }
            .nav-link.active {
                background: var(--sidebar-active);
                color: var(--sidebar-text-active);
                border-left-color: var(--accent-gold);
            }
            .nav-link .nav-icon { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }

            /* Sidebar user footer */
            .sidebar-user {
                margin-top: auto;
                padding: 14px 16px;
                border-top: 1px solid #1e293b;
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .sidebar-user .avatar {
                width: 34px;
                height: 34px;
                border-radius: 50%;
                background: #1e40af;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 13px;
                font-weight: 600;
                color: #bfdbfe;
                flex-shrink: 0;
            }
            .sidebar-user .user-info { flex: 1; min-width: 0; }
            .sidebar-user .user-name { font-size: 12px; font-weight: 600; color: #f8fafc; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .sidebar-user .user-role { font-size: 10px; color: #64748b; }

            /* ── MAIN WRAPPER ── */
            .main-wrapper { flex: 1; display: flex; flex-direction: column; min-width: 0; }

            /* ── TOPBAR ── */
            .topbar {
                background: var(--topbar-bg);
                border-bottom: 1px solid var(--topbar-border);
                padding: 0 28px;
                height: 68px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                position: sticky;
                top: 0;
                z-index: 40;
                box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
            }
            .topbar-title { font-size: 18px; font-weight: 700; color: #0f172a; letter-spacing: 0.01em; }
            .topbar-actions { display: flex; align-items: center; gap: 18px; }
            .topbar-search {
                display: flex;
                align-items: center;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 9999px;
                padding: 10px 16px;
                gap: 10px;
                font-size: 14px;
                color: #94a3b8;
                width: min(520px, 100%);
            }
            .topbar-search input {
                background: none;
                border: none;
                outline: none;
                font-size: 14px;
                color: #0f172a;
                width: 100%;
            }
            .topbar-search input::placeholder { color: #94a3b8; }
            .notif-btn {
                position: relative;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 9999px;
                width: 44px;
                height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 18px;
                color: #475569;
            }
            .notif-badge {
                position: absolute;
                top: -4px;
                right: -4px;
                background: var(--accent-gold);
                color: #78350f;
                font-size: 9px;
                font-weight: 700;
                width: 16px;
                height: 16px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* ── CONTENT ── */
            .main-content { flex: 1; padding: 24px 28px; overflow-y: auto; }

            /* ── FLASH MESSAGE ── */
            .flash-success {
                margin-bottom: 20px;
                padding: 12px 16px;
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                color: #166534;
                border-radius: 8px;
                font-size: 13px;
            }

            /* ── RESPONSIVE ── */
            @media (max-width: 768px) {
                .sidebar { display: none; }
                .topbar-search { display: none; }
            }
        </style>
    </head>
    <body>
        <div class="layout">

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar">

                {{-- Logo --}}
                <div class="sidebar-logo">
                    <span class="logo-icon">⚖️</span>
                    <div>
                        <div class="logo-text">LEX jurist</div>
                        <div class="logo-sub">Legal Case Management System</div>
                    </div>
                </div>

                {{-- Navigation --}}
                <nav style="flex:1; padding-top:8px;">

                    <div class="nav-section-label">Case Management</div>
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="nav-icon">🏠</span> Dashboard
                    </a>
                    <a href="{{ route('cases.index') }}"
                       class="nav-link {{ request()->routeIs('cases.*') ? 'active' : '' }}">
                        <span class="nav-icon">📁</span> Cases
                    </a>
                    <a href="{{ route('hearings.index') }}"
                       class="nav-link {{ request()->routeIs('hearings.*') ? 'active' : '' }}">
                        <span class="nav-icon">🔨</span> Hearings
                    </a>
                    <a href="{{ route('documents.index') }}"
                       class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                        <span class="nav-icon">📄</span> Documents
                    </a>

                    <div class="nav-section-label" style="margin-top:8px;">People</div>
                    <a href="{{ route('clients.index') }}"
                       class="nav-link {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                        <span class="nav-icon">👤</span> Clients
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('users.index') }}"
                           class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <span class="nav-icon">👥</span> Users
                        </a>
                    @endif

                </nav>

                {{-- User Footer --}}
                <div class="sidebar-user">
                    <div class="avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">{{ Auth::user()->role ?? 'User' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="flex-shrink:0;">
                        @csrf
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:8px;border-radius:9999px;background:#2563eb;color:#ffffff;padding:10px 14px;font-size:14px;font-weight:700;cursor:pointer;border:1px solid rgba(255,255,255,0.16);box-shadow:0 8px 20px rgba(15,23,42,0.08);"
                                title="Logout">
                            <span style="font-size:16px;line-height:1;">⇦</span>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>

            </aside>

            {{-- ── MAIN WRAPPER ── --}}
            <div class="main-wrapper">

                {{-- Top Bar --}}
                <header class="topbar">
                    <span class="topbar-title">@yield('page-title', $pageTitle ?? 'Dashboard')</span>
                </header>

                {{-- Main Content --}}
                <main class="main-content">
                    @if(session('success'))
                        <div class="flash-success">{{ session('success') }}</div>
                    @endif

                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>