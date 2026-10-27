<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(isset($title) ? $title . ' — ' . config('app.name', 'YKS - Keliling Sentul') : config('app.name', 'YKS - Keliling Sentul') . ' — Jasa Wisata, Trekking Curug & Tour Guide Sentul Bogor'); ?></title>
    <meta name="description" content="Paket Wisata & Trekking Curug Sentul, Offroad 4x4, Gowes, dan Camping bersama Tour Guide Lokal Berpengalaman. Jelajahi keindahan alam Sentul, Bogor.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Style -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>?v=<?php echo e(time()); ?>">
</head>
<body>

<!-- Top Info Bar -->
<div class="top-bar">
    <div class="container top-bar-inner">
        <div class="top-bar-left">
            <span><i class="bi bi-geo-alt-fill text-accent"></i> Sentul, Babakan Madang, Kab. Bogor</span>
            <span class="d-none-sm"><i class="bi bi-clock-fill text-accent"></i> Buka Setiap Hari (06.00 - 20.00 WIB)</span>
        </div>
        <div class="top-bar-right">
            <a href="https://wa.me/<?php echo e(env('WHATSAPP_NUMBER', '6281234567890')); ?>?text=Halo%20YKS,%20saya%20mau%20tanya%20paket%20wisata%20Sentul" target="_blank" class="top-wa-btn">
                <i class="bi bi-whatsapp"></i> Chat WhatsApp: +62 812-3456-7890
            </a>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<header class="main-header">
    <div class="container header-inner">
        <a href="<?php echo e(route('home')); ?>" class="brand-logo">
            <div class="logo-icon">
                <i class="bi bi-tree-fill"></i>
            </div>
            <div class="logo-text">
                <span class="brand-title">YKS <span class="highlight">Sentul</span></span>
                <span class="brand-sub">Yuk Keliling Sentul</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <nav class="nav-links">
            <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Beranda</a>
            <a href="<?php echo e(route('packages.index')); ?>" class="<?php echo e(request()->routeIs('packages.*') ? 'active' : ''); ?>">Paket Wisata</a>
            <a href="<?php echo e(route('home')); ?>#keunggulan">Keunggulan</a>
            <a href="<?php echo e(route('home')); ?>#faq">FAQ & Info</a>
            <a href="<?php echo e(route('booking.track')); ?>" class="<?php echo e(request()->routeIs('booking.track') ? 'active' : ''); ?>">
                <i class="bi bi-search"></i> Cek Pesanan
            </a>
        </nav>

        <!-- User / Action Buttons -->
        <div class="header-actions">
            <?php if(auth()->guard()->check()): ?>
                <div class="user-dropdown">
                    <button class="user-btn" id="userBtn" onclick="toggleUserDropdown()">
                        <div class="avatar-circle">
                            <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                        </div>
                        <span class="d-none-sm"><?php echo e(explode(' ', Auth::user()->name)[0]); ?></span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu" id="userDropdown">
                        <div class="dropdown-header">
                            <strong><?php echo e(Auth::user()->name); ?></strong>
                            <small><?php echo e(Auth::user()->email); ?></small>
                        </div>
                        <a href="<?php echo e(route('user.history')); ?>" class="dropdown-item"><i class="bi bi-receipt"></i> Riwayat Pesanan</a>
                        <hr>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item text-danger" style="width: 100%; border: none; background: none; cursor: pointer; text-align: left;">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="btn btn-outline btn-sm">Masuk</a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-sm d-none-sm">Daftar</a>
            <?php endif; ?>

            <!-- Mobile Toggle -->
            <button class="mobile-toggle" id="mobileToggle" onclick="toggleMobileNav()" aria-label="Toggle Navigation">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Drawer -->
    <div class="mobile-drawer" id="mobileDrawer">
        <div class="mobile-drawer-inner">
            <div class="drawer-header">
                <span class="brand-title">Menu Navigasi</span>
                <button class="close-drawer" onclick="toggleMobileNav()"><i class="bi bi-x-lg"></i></button>
            </div>
            <nav class="drawer-links">
                <a href="<?php echo e(route('home')); ?>" onclick="toggleMobileNav()"><i class="bi bi-house"></i> Beranda</a>
                <a href="<?php echo e(route('packages.index')); ?>" onclick="toggleMobileNav()"><i class="bi bi-compass"></i> Paket Wisata</a>
                <a href="<?php echo e(route('booking.track')); ?>" onclick="toggleMobileNav()"><i class="bi bi-search"></i> Lacak Booking</a>
                <a href="<?php echo e(route('home')); ?>#keunggulan" onclick="toggleMobileNav()"><i class="bi bi-star"></i> Keunggulan Kami</a>
                <a href="<?php echo e(route('home')); ?>#faq" onclick="toggleMobileNav()"><i class="bi bi-question-circle"></i> Tanya Jawab (FAQ)</a>
                <hr>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('user.history')); ?>"><i class="bi bi-receipt"></i> Riwayat Trip Saya</a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-danger" style="background: none; border: none; font-weight: 600; padding: 12px 0; cursor: pointer; display: flex; align-items: center; gap: 10px; width: 100%;">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline btn-block mb-2">Masuk Akun</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-block">Daftar Baru</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

<main class="page-content">
    <div class="container" style="margin-top: 20px;">
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('danger')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <span><?php echo e(session('danger')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('info')): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill"></i>
                <span><?php echo e(session('info')); ?></span>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger" style="display: block;">
                <strong>Perhatian:</strong>
                <ul style="margin-left: 20px; margin-top: 6px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($err); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <?php echo $__env->yieldContent('content'); ?>
</main>

<!-- Footer -->
<footer class="main-footer">
    <div class="container footer-grid">
        <div class="footer-col brand-col">
            <div class="footer-brand">
                <i class="bi bi-tree-fill text-accent"></i>
                <span>YKS <span class="highlight">Sentul</span></span>
            </div>
            <p class="footer-desc">
                Partner petualangan terpercaya Anda di Sentul, Bogor. Kami menyediakan jasa pemandu lokal (Local Tour Guide) berlisensi, trekking curug, offroad 4x4, gowes sepeda, dan family gathering dengan standar keamanan terbaik.
            </p>
            <div class="social-links">
                <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                <a href="https://wa.me/<?php echo e(env('WHATSAPP_NUMBER', '6281234567890')); ?>" target="_blank" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4 class="footer-heading">Kategori Wisata</h4>
            <ul class="footer-links">
                <li><a href="<?php echo e(route('packages.index', ['kategori' => 'trekking-curug'])); ?>">Trekking Curug Sentul</a></li>
                <li><a href="<?php echo e(route('packages.index', ['kategori' => 'offroad-4x4'])); ?>">Fun Offroad 4x4 Land Rover</a></li>
                <li><a href="<?php echo e(route('packages.index', ['kategori' => 'cycling-gowes'])); ?>">Gowes & Cycling Guide</a></li>
                <li><a href="<?php echo e(route('packages.index', ['kategori' => 'camping-outbound'])); ?>">Camping & Sunset Camp</a></li>
                <li><a href="<?php echo e(route('packages.index')); ?>">Semua Pilihan Paket</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4 class="footer-heading">Bantuan & Pelanggan</h4>
            <ul class="footer-links">
                <li><a href="<?php echo e(route('booking.track')); ?>">Lacak Status Booking</a></li>
                <li><a href="<?php echo e(route('home')); ?>#faq">Pertanyaan Umum (FAQ)</a></li>
                <li><a href="<?php echo e(route('login')); ?>">Masuk Akun Pengguna</a></li>
                <li><a href="<?php echo e(route('register')); ?>">Pendaftaran Member Baru</a></li>
            </ul>
        </div>

        <div class="footer-col contact-col">
            <h4 class="footer-heading">Basecamp & Kontak</h4>
            <p><i class="bi bi-geo-alt-fill text-accent"></i> Jl. Babakan Madang, Sentul Selatan, Kabupaten Bogor, Jawa Barat 16810</p>
            <p><i class="bi bi-whatsapp text-accent"></i> +62 812-3456-7890</p>
            <p><i class="bi bi-envelope-fill text-accent"></i> info@kelilingsentul.id</p>
            <div class="payment-badges mt-2">
                <span class="pay-tag">BCA</span>
                <span class="pay-tag">Mandiri</span>
                <span class="pay-tag">QRIS</span>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <p>&copy; <?php echo e(date('Y')); ?> <strong><?php echo e(config('app.name', 'YKS - Keliling Sentul')); ?></strong>. Jelajahi Alam Sentul Bersama Kami.</p>
            <p class="designer-text">Built with Laravel 12 &bull; Role User Platform</p>
        </div>
    </div>
</footer>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/<?php echo e(env('WHATSAPP_NUMBER', '6281234567890')); ?>?text=Halo%20Admin%20YKS%20Keliling%20Sentul,%20saya%20tertarik%20tanya%20paket%20wisata" 
   class="floating-wa" 
   target="_blank" 
   aria-label="Konsultasi via WhatsApp">
    <i class="bi bi-whatsapp"></i>
    <span class="floating-wa-text">Tanya Paket via WA</span>
</a>

<!-- JavaScript -->
<script src="<?php echo e(asset('assets/js/main.js')); ?>?v=<?php echo e(time()); ?>"></script>
</body>
</html>
<?php /**PATH /home/man/WEB/YKS-keliling_sentul/resources/views/layouts/app.blade.php ENDPATH**/ ?>