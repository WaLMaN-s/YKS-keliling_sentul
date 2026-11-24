<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — Ruang Kerja Admin YKS</title>

    <!-- Google Fonts: Inter & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800;900&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800;900&display=swap"></noscript>

    <!-- Bootstrap Icons (Local Offline) -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons/bootstrap-icons.min.css">

    <style>
        :root {
            --admin-sidebar-bg: #1F3D2B;
            --admin-sidebar-active: #35553F;
            --admin-accent: #F26B1D;
            --admin-text-sub: #BDCCBE;
            --admin-bg-body: #F8F5EF;
            --admin-card-bg: #FFFFFF;
            --admin-border: #E1E4DA;
            --font-main: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            --font-head: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--admin-bg-body);
            color: #1F3D2B;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar (Figma ON GOING 240px) */
        .admin-sidebar {
            width: 240px;
            background-color: var(--admin-sidebar-bg);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            gap: 26px;
            flex-shrink: 0;
            padding: 30px 16px 24px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-brand-wrap {
            display: flex;
            flex-direction: column;
            gap: 18px;
            padding: 0 8px;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: #FFFFFF;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background-color: #35553F;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
            flex-shrink: 0;
        }

        .brand-title {
            font-family: var(--font-head);
            font-size: 1.55rem;
            font-weight: 900;
            line-height: 1.1;
            display: block;
            color: #FFFFFF;
        }

        .brand-subtitle {
            font-size: 0.65rem;
            color: var(--admin-text-sub);
            display: block;
            margin-top: 2px;
        }

        .workspace-label {
            font-size: 0.64rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--admin-text-sub);
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 6px;
            list-style: none;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 46px;
            padding: 12px;
            color: var(--admin-text-sub);
            text-decoration: none;
            font-size: 0.78rem;
            font-weight: 400;
            border-radius: 8px;
            transition: all 0.16s ease;
        }

        .admin-nav a .nav-left {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .admin-nav a:hover {
            background-color: rgba(53, 85, 63, 0.65);
            color: #FFFFFF;
        }

        .admin-nav a.active {
            background-color: var(--admin-sidebar-active);
            color: #FFFFFF;
            font-weight: 600;
        }

        .admin-nav a i {
            font-size: 1rem;
            color: var(--admin-text-sub);
        }

        .admin-nav a.active i {
            color: #FFFFFF;
        }

        .nav-count-orange {
            color: var(--admin-accent);
            font-size: 0.72rem;
            font-weight: 700;
        }

        /* Service Info Card in Sidebar (Figma ON GOING #184:225) */
        .sidebar-service-box {
            background-color: var(--admin-sidebar-active);
            border-radius: 10px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .sidebar-service-title {
            font-size: 0.76rem;
            font-weight: 600;
            color: #FFFFFF;
        }

        .sidebar-service-time {
            font-size: 0.7rem;
            color: var(--admin-text-sub);
            line-height: 1.5;
        }

        /* Main Area */
        .admin-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--admin-border);
            height: 76px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .breadcrumb {
            font-size: 0.78rem;
            color: #718074;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .breadcrumb span.current {
            color: #24382D;
            font-weight: 600;
        }

        .header-right-meta {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .sample-badge {
            background: #FFF0E4;
            color: #A54B10;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 100px;
        }

        .header-date {
            font-size: 0.76rem;
            color: #718074;
        }

        .header-divider {
            width: 1px;
            height: 24px;
            background: var(--admin-border);
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #EAF0E7;
            color: #24382D;
            font-size: 0.7rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-content {
            padding: 32px;
            display: flex;
            flex-direction: column;
            gap: 24px;
            flex-grow: 1;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            padding: 20px 24px;
        }

        .btn-primary-yks {
            background-color: var(--admin-sidebar-bg);
            color: #FFFFFF;
            border: none;
            padding: 9px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.15s;
        }
        .btn-primary-yks:hover {
            opacity: 0.92;
        }

        .btn-orange-yks {
            background-color: var(--admin-accent);
            color: #1F3D2B;
            border: none;
            height: 42px;
            padding: 0 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline-yks {
            background-color: #FFFFFF;
            color: #1F3D2B;
            border: 1px solid var(--admin-border);
            min-height: 42px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-outline-yks:hover {
            background-color: #F8F5EF;
        }

        .admin-footer-note {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            padding-top: 8px;
            font-size: 0.68rem;
            color: #718074;
            margin-top: auto;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            list-style: none;
            gap: 6px;
            align-items: center;
        }
        .page-item .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            background: #FFFFFF;
            color: #1F3D2B;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .page-item.active .page-link {
            background: #EAF0E7;
            color: #1F3D2B;
            border-color: #EAF0E7;
            font-weight: 700;
        }
        .page-item.disabled .page-link {
            opacity: 0.5;
            pointer-events: none;
        }

        @media print {
            .admin-sidebar, .admin-header, .no-print, .admin-sidebar-backdrop {
                display: none !important;
            }
            .admin-content {
                padding: 0 !important;
            }
            body {
                background: #FFFFFF !important;
            }
        }

        .admin-mobile-toggle {
            display: none;
            width: 38px;
            height: 38px;
            border-radius: 9px;
            background: #F8F5EF;
            border: 1px solid var(--admin-border);
            color: #1F3D2B;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            padding: 0;
        }
        .admin-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(14, 33, 24, 0.58);
            backdrop-filter: blur(3px);
            z-index: 1999;
        }
        @media (max-width: 900px) {
            .admin-mobile-toggle {
                display: inline-flex;
            }
            .admin-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100dvh;
                z-index: 2000;
                transform: translateX(-100%);
                transition: transform 0.28s cubic-bezier(0.22, 1, 0.36, 1);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-sidebar-backdrop.open {
                display: block;
            }
            .admin-header {
                padding: 12px 16px;
                height: auto;
            }
            .admin-content {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>

    @php
        try {
            $pendingVerifCount = \Illuminate\Support\Facades\DB::table('yks_bookings')
                ->where('payment_status', 'Menunggu Verifikasi')
                ->count();
        } catch (\Throwable $e) {
            $pendingVerifCount = 0;
        }
    @endphp

    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" onclick="toggleAdminSidebar()"></div>

    <!-- Navigasi Admin (Figma ON GOING #178:18492) -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand-wrap">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                <div class="brand-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                    </svg>
                </div>
                <div>
                    <span class="brand-title">YKS</span>
                    <span class="brand-subtitle">Yuk Keliling Sentul</span>
                </div>
            </a>
            <span class="workspace-label">RUANG KERJA ADMIN</span>
        </div>

        <!-- 7 Menu Operasional Sesuai Figma ON GOING -->
        <ul class="admin-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-compass"></i>
                        <span>Paket & Layanan</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-calendar3"></i>
                        <span>Jadwal & Kapasitas</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-people"></i>
                        <span>Pelanggan</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reservations.index') }}" class="{{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-ticket-perforated"></i>
                        <span>Reservasi</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.verifications.index') }}" class="{{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-wallet2"></i>
                        <span>Pembayaran</span>
                    </span>
                    @if($pendingVerifCount > 0)
                        <span class="nav-count-orange">{{ $pendingVerifCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-bar-chart-line"></i>
                        <span>Laporan</span>
                    </span>
                </a>
            </li>
        </ul>

        <!-- Informasi Layanan (Figma ON GOING #184:225) -->
        <div class="sidebar-service-box">
            <span class="sidebar-service-title">Admin siap melayani</span>
            <div class="sidebar-service-time">
                <div>08.00–19.00 WIB</div>
                <div>Senin–Minggu</div>
            </div>
        </div>

        <!-- Keluar Akun & Lihat Situs -->
        <div style="display: flex; flex-direction: column; gap: 6px;">
            <a href="{{ route('home') }}" target="_blank" style="color: var(--admin-text-sub); text-decoration: none; font-size: 0.76rem; padding: 8px 12px; display: flex; align-items: center; gap: 10px;">
                <i class="bi bi-box-arrow-up-right"></i>
                <span>Lihat situs publik</span>
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" style="width: 100%; background: none; border: none; color: var(--admin-text-sub); text-align: left; font-family: inherit; font-size: 0.78rem; padding: 10px 12px; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Area Kerja -->
    <main class="admin-main">
        <header class="admin-header">
            <div class="breadcrumb">
                <button type="button" class="admin-mobile-toggle" onclick="toggleAdminSidebar()" aria-label="Buka Menu Admin">
                    <i class="bi bi-list" style="font-size: 1.2rem;"></i>
                </button>
                <span>Panel admin</span>
                <span>/</span>
                <span class="current">{{ $title ?? 'Dashboard' }}</span>
            </div>

            <div class="header-right-meta">
                <span class="sample-badge">Data contoh</span>
                <span class="header-date">{{ \Carbon\Carbon::now()->translatedFormat('l, j M Y') }}</span>
                <div class="header-divider"></div>
                <div class="header-profile">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</div>
                    <span style="font-size: 0.78rem; font-weight: 600; color: #24382D;">{{ Auth::user()->name ?? 'Admin YKS' }}</span>
                </div>
            </div>
        </header>

        <section class="admin-content">
            @if(session('success'))
                <div style="background: #EAF0E7; border-left: 4px solid #1F3D2B; padding: 12px 16px; border-radius: 8px; font-size: 0.84rem; color: #1F3D2B; font-weight: 500;">
                    <i class="bi bi-check-circle-fill" style="margin-right: 6px;"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div style="background: #FFF0E4; border-left: 4px solid #F26B1D; padding: 12px 16px; border-radius: 8px; font-size: 0.84rem; color: #A54B10; font-weight: 500;">
                    <i class="bi bi-exclamation-triangle-fill" style="margin-right: 6px;"></i> {{ session('warning') }}
                </div>
            @endif

            @yield('content')

            <div class="admin-footer-note no-print">
                <span>© 2026 Yuk Keliling Sentul · Panel operasional</span>
                <span>Seluruh identitas dan transaksi adalah data contoh.</span>
            </div>
        </section>
    </main>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            if (sidebar) sidebar.classList.toggle('open');
            if (backdrop) backdrop.classList.toggle('open');
        }
    </script>
</body>
</html>
