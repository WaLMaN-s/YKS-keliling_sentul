<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Ringkasan' }} — Administrasi YKS Keliling Sentul</title>

    <!-- Google Fonts: Inter & Montserrat (Non-blocking) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap"></noscript>
    
    <!-- Bootstrap Icons (Local Offline) -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap-icons/bootstrap-icons.min.css">
    
    <style>
        :root {
            --admin-sidebar-bg: #1F3D2B;
            --admin-sidebar-active: #304F3B;
            --admin-accent: #F26B1D;
            --admin-text-sub: #C1D1BE;
            --admin-bg-body: #F8F5EF;
            --admin-card-bg: #FFFFFF;
            --admin-border: #DFE4DC;
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

        /* Sidebar */
        .admin-sidebar {
            width: 256px;
            background-color: var(--admin-sidebar-bg);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            padding: 24px 14px;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            text-decoration: none;
            color: #FFFFFF;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background-color: #304F3B;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--admin-accent);
            font-size: 1.2rem;
            font-weight: 800;
        }

        .brand-title {
            font-family: var(--font-head);
            font-size: 1.05rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            display: block;
        }

        .brand-subtitle {
            font-size: 0.68rem;
            color: var(--admin-text-sub);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            display: block;
            margin-top: 2px;
        }

        /* Nav List */
        .nav-section-title {
            font-size: 0.66rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--admin-text-sub);
            margin-bottom: 8px;
            margin-top: 18px;
            padding-left: 12px;
            font-weight: 600;
        }

        .admin-nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
            list-style: none;
        }

        .admin-nav a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            color: #FFFFFF;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.18s ease;
        }

        .admin-nav a .nav-left {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .admin-nav a:hover {
            background-color: var(--admin-sidebar-active);
        }

        .admin-nav a.active {
            background-color: var(--admin-sidebar-active);
            border-left: 3px solid var(--admin-accent);
            font-weight: 600;
        }

        .admin-nav a i {
            font-size: 1.05rem;
            color: var(--admin-text-sub);
        }

        .admin-nav a.active i {
            color: var(--admin-accent);
        }

        .nav-badge {
            background: var(--admin-accent);
            color: #FFFFFF;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 99px;
        }

        /* Main Content */
        .admin-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .admin-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--admin-border);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .breadcrumb {
            font-size: 0.85rem;
            color: #6B756E;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .breadcrumb span.current {
            color: #1F3D2B;
            font-weight: 600;
        }

        .header-right-meta {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 9px;
            padding-left: 14px;
            border-left: 1px solid var(--admin-border);
        }

        .header-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #1F3D2B;
            color: #FFFFFF;
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-content {
            padding: 28px 32px;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            padding: 18px 20px;
        }

        .btn-primary-yks {
            background-color: var(--admin-sidebar-bg);
            color: #FFFFFF;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity 0.15s;
        }
        .btn-primary-yks:hover {
            opacity: 0.92;
        }

        .btn-outline-yks {
            background-color: #FFFFFF;
            color: #1F3D2B;
            border: 1px solid var(--admin-border);
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline-yks:hover {
            background-color: #F8F5EF;
        }

        /* Pagination Styling */
        .pagination {
            display: flex;
            list-style: none;
            gap: 6px;
            align-items: center;
            margin-top: 12px;
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
            font-size: 0.85rem;
            font-weight: 500;
        }
        .page-item.active .page-link {
            background: var(--admin-sidebar-bg);
            color: #FFFFFF;
            border-color: var(--admin-sidebar-bg);
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

        /* Mobile Responsive Admin Sidebar & Hamburger */
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
                box-shadow: 12px 0 32px rgba(14, 33, 24, 0.25);
            }
            .admin-sidebar.open {
                transform: translateX(0);
            }
            .admin-sidebar-backdrop.open {
                display: block;
            }
            .admin-header {
                padding: 12px 16px;
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

    <!-- Sidebar Backdrop (Mobile) -->
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" onclick="toggleAdminSidebar()"></div>

    <!-- Sidebar Administrasi YKS (Figma Order) -->
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <div class="brand-icon">YKS.</div>
            <div>
                <span class="brand-title">Yuk Keliling Sentul</span>
                <span class="brand-subtitle">Ruang Administrasi</span>
            </div>
        </a>

        <div class="nav-section-title">Operasional Utama</div>
        <ul class="admin-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Ringkasan</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-compass"></i>
                        <span>Kelola Paket</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-calendar3"></i>
                        <span>Jadwal & Slot</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reservations.index') }}" class="{{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-journal-check"></i>
                        <span>Data Reservasi</span>
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.verifications.index') }}" class="{{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-receipt-cutoff"></i>
                        <span>Verifikasi Pembayaran</span>
                    </span>
                    @if($pendingVerifCount > 0)
                        <span class="nav-badge">{{ $pendingVerifCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-people"></i>
                        <span>Data Pelanggan</span>
                    </span>
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
            <li>
                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <span class="nav-left">
                        <i class="bi bi-clock"></i>
                        <span>Jam Operasional</span>
                    </span>
                </a>
            </li>
        </ul>

        <!-- Footer Links (Figma: ↗ Lihat situs pelanggan, ↪ Keluar) -->
        <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); display: flex; flex-direction: column; gap: 8px;">
            <a href="{{ route('home') }}" target="_blank" style="color: var(--admin-text-sub); text-decoration: none; font-size: 0.8rem; padding: 6px 10px; display: flex; align-items: center; gap: 8px;">
                <span>↗</span> <span>Lihat situs pelanggan</span>
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" style="width: 100%; background: none; border: none; color: #FCA5A5; text-align: left; font-family: inherit; font-size: 0.8rem; font-weight: 600; padding: 6px 10px; display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <span>↪</span> <span>Keluar Sesi Admin</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <header class="admin-header">
            <div class="breadcrumb">
                <button type="button" class="admin-mobile-toggle" onclick="toggleAdminSidebar()" aria-label="Buka Menu Admin">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <span>Administrasi</span>
                <span>/</span>
                <span class="current">{{ $title ?? 'Ringkasan' }}</span>
            </div>

            <div class="header-right-meta">
                <span style="background: #F8F5EF; border: 1px solid #DFE4DC; padding: 4px 10px; border-radius: 99px; font-size: 0.74rem; color: #6B756E; font-weight: 500;">
                    Waktu acuan: {{ date('d M Y, H.i') }} WIB
                </span>

                <div class="header-profile">
                    <div class="header-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</div>
                    <div>
                        <div style="font-size: 0.8rem; font-weight: 700; color: #1F3D2B; line-height: 1.1;">{{ Auth::user()->name ?? 'Admin YKS' }}</div>
                        <div style="font-size: 0.7rem; color: #6B756E;">{{ Auth::user()->email ?? 'Pengelola utama' }}</div>
                    </div>
                </div>
            </div>
        </header>

        <section class="admin-content">
            @if(session('success'))
                <div style="background: #EAF0E7; border-left: 4px solid #1F3D2B; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.86rem; color: #1F3D2B; font-weight: 500;">
                    <i class="bi bi-check-circle-fill" style="margin-right: 6px;"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div style="background: #FEEFE6; border-left: 4px solid #F26B1D; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.86rem; color: #B2450A; font-weight: 500;">
                    <i class="bi bi-exclamation-triangle-fill" style="margin-right: 6px;"></i> {{ session('warning') }}
                </div>
            @endif

            @yield('content')
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
