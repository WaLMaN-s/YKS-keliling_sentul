<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — ' . config('app.name', 'YKS - Keliling Sentul') : config('app.name', 'YKS - Keliling Sentul') . ' · Petualangan Jelajah Alam & Wisata Sentul' }}</title>
    <meta name="description" content="Yuk Keliling Sentul (YKS) - Jelajahi rute perbukitan, jalur tanah offroad 4x4, curug tersembunyi, dan pesona alam Sentul bersama pemandu lokal berpengalaman.">
    
    <!-- Google Fonts: Montserrat (Heading) & Inter (Body) from Figma -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Style (Figma Tokens) -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}">
</head>
<body>

<!-- Main Header (Figma 88px Desktop / 76px Mobile) -->
<header class="main-header">
    <div class="container header-inner">
        <!-- Logo & Brand Identity -->
        <a href="{{ route('home') }}" class="brand-logo">
            <div class="logo-icon-box">
                <!-- Mountain SVG Icon matching Figma #7:62198 -->
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                </svg>
            </div>
            <div class="logo-brand-info">
                <span class="logo-brand-title">YKS</span>
                <span class="logo-brand-subtitle">Yuk Keliling Sentul</span>
            </div>
        </a>

        <!-- Desktop Navigation Menu -->
        <nav class="nav-menu">
            <a href="{{ route('home') }}" class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('about') }}" class="nav-link-item {{ request()->routeIs('about') ? 'active' : '' }}">Tentang YKS</a>
            <a href="{{ route('packages.index') }}" class="nav-link-item {{ request()->routeIs('packages.*') ? 'active' : '' }}">Paket</a>
            <a href="{{ route('gallery') }}" class="nav-link-item {{ request()->routeIs('gallery') ? 'active' : '' }}">Galeri & Testimoni</a>
            <a href="{{ route('info') }}" class="nav-link-item {{ request()->routeIs('info') ? 'active' : '' }}">Informasi</a>
        </nav>

        <!-- Header Actions & CTA -->
        <div class="header-right">
            <a href="{{ route('booking.track') }}" class="nav-link-item d-none-sm" title="Lacak Pesanan" style="display: flex; align-items: center; gap: 6px;">
                <i class="bi bi-search"></i>
                <span style="font-size: 0.85rem;">Lacak Trip</span>
            </a>

            @auth
                <div class="user-menu-wrapper d-none-mobile">
                    <button class="user-pill-btn" onclick="toggleUserDropdown()" id="userBtn">
                        <div class="user-pill-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                        <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
                        <i class="bi bi-chevron-down" style="font-size: 0.75rem;"></i>
                    </button>
                    <div class="user-dropdown-panel" id="userDropdown">
                        <div class="dropdown-user-header">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ Auth::user()->email }}</small>
                        </div>
                        <a href="{{ route('user.history') }}" class="dropdown-item-link">
                            <i class="bi bi-clock-history"></i> Riwayat Trip Saya
                        </a>
                        <hr style="margin: 6px 0; border: none; border-top: 1px solid var(--border-light);">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item-link" style="width: 100%; border: none; background: none; cursor: pointer; color: #dc2626;">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm d-none-mobile" style="padding: 10px 16px;">Masuk</a>
            @endauth

            <!-- CTA Reservasi Sekarang ↗ (Desktop) -->
            <a href="{{ route('booking.create') }}" class="btn btn-primary d-none-mobile" style="padding: 12px 20px;">
                Reservasi Sekarang &nbsp;↗
            </a>

            <!-- Mobile Reservasi Link (Figma Seluler Header) -->
            <a href="{{ route('booking.create') }}" class="mobile-header-reservasi">
                Reservasi
            </a>

            <!-- Mobile Hamburger Toggle -->
            <button class="mobile-nav-toggle" onclick="toggleMobileNav()" aria-label="Buka Menu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer Navigation -->
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="mobile-drawer-content">
            <div class="mobile-drawer-header">
                <div class="brand-logo">
                    <div class="logo-icon-box" style="width: 36px; height: 36px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="m8 3 4 8 5-5 5 15H2L8 3z"/>
                        </svg>
                    </div>
                    <div class="logo-brand-info">
                        <span class="logo-brand-title" style="font-size: 1.2rem;">YKS</span>
                        <span class="logo-brand-subtitle">Yuk Keliling Sentul</span>
                    </div>
                </div>
                <button onclick="toggleMobileNav()" style="background: none; border: none; font-size: 1.6rem; color: var(--primary); cursor: pointer;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <nav class="mobile-drawer-links">
                <a href="{{ route('home') }}" class="mobile-drawer-link" onclick="toggleMobileNav()"><i class="bi bi-house"></i> Beranda</a>
                <a href="{{ route('about') }}" class="mobile-drawer-link" onclick="toggleMobileNav()"><i class="bi bi-compass"></i> Tentang YKS</a>
                <a href="{{ route('packages.index') }}" class="mobile-drawer-link" onclick="toggleMobileNav()"><i class="bi bi-signpost-2"></i> Paket Wisata</a>
                <a href="{{ route('gallery') }}" class="mobile-drawer-link" onclick="toggleMobileNav()"><i class="bi bi-images"></i> Galeri & Testimoni</a>
                <a href="{{ route('info') }}" class="mobile-drawer-link" onclick="toggleMobileNav()"><i class="bi bi-info-circle"></i> Informasi Admin & Bank</a>
                <a href="{{ route('booking.track') }}" class="mobile-drawer-link" onclick="toggleMobileNav()"><i class="bi bi-search"></i> Lacak Status Booking</a>
                
                <hr style="margin: 16px 0; border: none; border-top: 1px solid var(--border);">
                
                @auth
                    <a href="{{ route('user.history') }}" class="mobile-drawer-link"><i class="bi bi-clock-history"></i> Riwayat Trip Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #dc2626; font-weight: 600; padding: 8px 0; display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 1rem;">
                            <i class="bi bi-box-arrow-right"></i> Keluar Akun
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-block" style="margin-bottom: 10px;">Masuk Akun</a>
                    <a href="{{ route('register') }}" class="btn btn-forest btn-block">Daftar Akun Baru</a>
                @endauth
            </nav>

            <div style="margin-top: 24px;">
                <a href="{{ route('booking.create') }}" class="btn btn-primary btn-block">
                    Reservasi Sekarang ↗
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Flash Alerts Notification -->
<div class="container" style="margin-top: 20px;">
    @if (session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('danger'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>{{ session('danger') }}</div>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i>
            <div>{{ session('info') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="display: block;">
            <strong>Mohon periksa data formulir:</strong>
            <ul style="margin-left: 20px; margin-top: 6px;">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<!-- Main Page Body Content -->
<main>
    @yield('content')
</main>

<!-- Footer (Kaki Halaman - Figma Deep Forest #1F3D2B) -->
<footer class="figma-footer">
    <div class="container">
        <div class="footer-top-grid">
            <!-- Brand & Tagline -->
            <div>
                <h3 class="footer-brand-title">YKS</h3>
                <span class="footer-brand-sub">Yuk Keliling Sentul</span>
                <p class="footer-brand-bio">
                    Jauh dari rutinitas. Dekat dengan alam. Jelajahi rute perbukitan, jalur tanah offroad 4x4, dan keindahan tersembunyi Sentul bersama tim kami.
                </p>
                <a href="https://instagram.com" target="_blank" class="footer-social-pill">
                    <i class="bi bi-instagram"></i> Instagram · @yks.sentul
                </a>
            </div>

            <!-- Navigation Links -->
            <div>
                <h4 class="footer-heading">Jelajahi YKS</h4>
                <ul class="footer-nav-list">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('about') }}">Tentang YKS</a></li>
                    <li><a href="{{ route('packages.index') }}">Katalog Paket Wisata</a></li>
                    <li><a href="{{ route('gallery') }}">Galeri & Testimoni</a></li>
                    <li><a href="{{ route('info') }}">Informasi Admin & Bank</a></li>
                    <li><a href="{{ route('booking.track') }}">Lacak Status Booking</a></li>
                </ul>
            </div>

            <!-- Meet Us & Contact -->
            <div class="footer-contact-info">
                <h4 class="footer-heading">Temui Kami</h4>
                <p>
                    <strong>Pos Jelajah YKS</strong><br>
                    Kawasan Sentul, Babakan Madang, Kabupaten Bogor, Jawa Barat 16810
                </p>
                <p>
                    <strong>Jam Layanan Admin:</strong><br>
                    Senin – Minggu · 08.00 – 17.00 WIB
                </p>
                <p>
                    <strong>WhatsApp Konsultasi:</strong><br>
                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}" target="_blank" style="color: #6ee7b7; font-weight: 700;">
                        <i class="bi bi-whatsapp"></i> +62 812-3456-7890
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom-bar">
            <span>&copy; 2026 Yuk Keliling Sentul. Hak cipta dilindungi undang-undang.</span>
            <span>Data operasional, ulasan & reservasi dikelola resmi oleh sistem YKS Sentul.</span>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp Action -->
<a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20tertarik%20tanya%20paket%20wisata%20Sentul" 
   class="floating-wa" 
   target="_blank" 
   aria-label="Chat WhatsApp Admin">
    <i class="bi bi-whatsapp" style="font-size: 1.25rem;"></i>
    <span class="floating-wa-text">Konsultasi Trip</span>
</a>

<!-- Interactive Scripts -->
<script>
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) dropdown.classList.toggle('show');
    }

    function toggleMobileNav() {
        const drawer = document.getElementById('mobileDrawer');
        if (drawer) drawer.classList.toggle('active');
    }

    window.onclick = function(event) {
        if (!event.target.closest('#userBtn') && !event.target.closest('#userDropdown')) {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    };
</script>
@yield('scripts')
</body>
</html>
