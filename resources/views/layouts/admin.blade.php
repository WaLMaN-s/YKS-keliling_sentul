<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Administrasi' }} — YKS Keliling Sentul</title>

    <!-- Google Fonts: Inter & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --admin-sidebar-bg: #1F3D2B;
            --admin-sidebar-active: #304F3B;
            --admin-accent: #F26B1D;
            --admin-text-sub: #C1D1BE;
            --admin-bg-body: #F8F5EF;
            --admin-card-bg: #FFFFFF;
            --admin-border: #DFE4DC;
            --font-main: 'Inter', sans-serif;
            --font-head: 'Montserrat', sans-serif;
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
            width: 260px;
            background-color: var(--admin-sidebar-bg);
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            padding: 24px 16px;
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 24px;
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
            font-size: 1.25rem;
        }

        .brand-title {
            font-family: var(--font-head);
            font-size: 1.1rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .brand-subtitle {
            font-size: 0.75rem;
            color: var(--admin-text-sub);
            display: block;
        }

        /* User Profile in Sidebar */
        .admin-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            margin-bottom: 24px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }

        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #DFE4DC;
            color: var(--admin-sidebar-bg);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }

        .profile-info h4 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #FFFFFF;
        }

        .profile-info p {
            font-size: 0.72rem;
            color: var(--admin-text-sub);
        }

        /* Nav List */
        .nav-section-title {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--admin-text-sub);
            margin-bottom: 8px;
            margin-top: 10px;
            padding-left: 12px;
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
            gap: 12px;
            padding: 10px 14px;
            color: #FFFFFF;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
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
            font-size: 1.1rem;
            color: var(--admin-text-sub);
        }

        .admin-nav a.active i {
            color: var(--admin-accent);
        }

        /* Main Content */
        .admin-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .admin-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--admin-border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .breadcrumb {
            font-size: 0.875rem;
            color: #6B756E;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .breadcrumb span.current {
            color: #1F3D2B;
            font-weight: 600;
        }

        .admin-content {
            padding: 32px;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid var(--admin-border);
            border-radius: 12px;
            padding: 20px;
        }

        .btn-primary-yks {
            background-color: var(--admin-sidebar-bg);
            color: #FFFFFF;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>
<body>

    <!-- Sidebar Administrasi YKS -->
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <div class="brand-icon">
                <i class="bi bi-shield-check"></i>
            </div>
            <div>
                <span class="brand-title">ADMIN YKS</span>
                <span class="brand-subtitle">Keliling Sentul</span>
            </div>
        </a>

        <div class="admin-profile">
            <div class="profile-avatar">AD</div>
            <div class="profile-info">
                <h4>Admin YKS</h4>
                <p>Pengelola utama</p>
            </div>
        </div>

        <div class="nav-section-title">Menu Administrasi</div>
        <ul class="admin-nav">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i>
                    <span>Ringkasan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.verifications.index') }}" class="{{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Verifikasi Pembayaran</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reservations.index') }}" class="{{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    <span>Data Reservasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    <span>Jadwal & Slot</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.packages.index') }}" class="{{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                    <i class="bi bi-compass"></i>
                    <span>Kelola Paket</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i>
                    <span>Data Pelanggan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>Jam Operasional</span>
                </a>
            </li>
        </ul>

        <div style="margin-top: auto; padding-top: 20px;">
            <a href="{{ route('home') }}" style="color: var(--admin-text-sub); text-decoration: none; font-size: 0.8rem; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-box-arrow-up-right"></i> Lihat Website Publik
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <header class="admin-header">
            <div class="breadcrumb">
                <span>Administrasi</span>
                <i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i>
                <span class="current">{{ $title ?? 'Ringkasan' }}</span>
            </div>
            <div>
                <span style="font-size: 0.8rem; color: #6B756E;">Waktu acuan: {{ date('d M Y, H.i') }} WIB</span>
            </div>
        </header>

        <section class="admin-content">
            @if(session('success'))
                <div style="background: #EAF0E7; border-left: 4px solid #1F3D2B; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.875rem;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('warning'))
                <div style="background: #FEEFE6; border-left: 4px solid #F26B1D; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.875rem;">
                    {{ session('warning') }}
                </div>
            @endif

            @yield('content')
        </section>
    </main>

</body>
</html>
