<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Ruang Administrasi — Yuk Keliling Sentul (YKS)</title>

    <!-- Google Fonts: Inter & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --forest: #1F3D2B;
            --forest-dark: #142A1D;
            --forest-soft: #304F3B;
            --orange: #F26B1D;
            --orange-hover: #D95B12;
            --cream: #F8F5EF;
            --sage: #EAF0E7;
            --border: #DFE4DC;
            --text-muted: #5F6C63;
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
            background-color: var(--cream);
            color: var(--forest);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-shell {
            width: 100%;
            max-width: 960px;
            background: #FFFFFF;
            border: 1px solid var(--border);
            border-radius: 22px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            box-shadow: 0 24px 60px rgba(20, 42, 29, 0.12);
        }

        /* Left Branding Panel */
        .login-brand-panel {
            background: linear-gradient(155deg, var(--forest) 0%, var(--forest-dark) 100%);
            color: #FFFFFF;
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-badge-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 36px;
        }

        .brand-logo-box {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background-color: var(--forest-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--orange);
            font-family: var(--font-head);
            font-weight: 900;
            font-size: 1.1rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .brand-name {
            font-family: var(--font-head);
            font-size: 1.2rem;
            font-weight: 800;
            display: block;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #C1D1BE;
            display: block;
            margin-top: 3px;
        }

        .brand-tag {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--orange);
            margin-bottom: 12px;
            display: block;
        }

        .brand-headline {
            font-family: var(--font-head);
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.22;
            margin-bottom: 16px;
        }

        .brand-desc {
            font-size: 0.92rem;
            color: #D5E0D2;
            line-height: 1.65;
            margin-bottom: 28px;
        }

        .feature-pills {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.84rem;
            color: #EAF0E7;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 10px 14px;
            border-radius: 10px;
        }

        .feature-pill i {
            color: var(--orange);
            font-size: 1rem;
        }

        .brand-footer-note {
            margin-top: 36px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.78rem;
            color: #B4C6B0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Right Form Panel */
        .login-form-panel {
            padding: 48px 42px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-eyebrow {
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .form-title {
            font-family: var(--font-head);
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--forest);
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 24px;
        }

        .alert-box {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 600;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-error {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
        }

        .alert-warning {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            color: #92400E;
        }

        .alert-success {
            background: var(--sage);
            border: 1px solid #C1D1BE;
            color: var(--forest);
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--forest);
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 0.92rem;
            color: var(--forest);
            background: var(--cream);
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--forest);
            background: #FFFFFF;
            box-shadow: 0 0 0 3px rgba(31, 61, 43, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 1rem;
        }

        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1rem;
            padding: 4px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            font-size: 0.83rem;
            color: var(--text-muted);
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-login {
            width: 100%;
            padding: 13px 20px;
            border: none;
            border-radius: 10px;
            background: var(--orange);
            color: #FFFFFF;
            font-family: var(--font-main);
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .btn-login:hover {
            background: var(--orange-hover);
        }

        .demo-credentials {
            margin-top: 20px;
            padding: 12px 14px;
            background: var(--sage);
            border: 1px dashed #B5C7B1;
            border-radius: 10px;
            font-size: 0.78rem;
            color: var(--forest);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-fill-demo {
            background: #FFFFFF;
            border: 1px solid var(--border);
            color: var(--forest);
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-fill-demo:hover {
            background: var(--forest);
            color: #FFFFFF;
        }

        .back-public-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 22px;
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            align-self: center;
        }

        .back-public-link:hover {
            color: var(--forest);
        }

        @media (max-width: 820px) {
            body {
                padding: 14px;
            }
            .login-shell {
                grid-template-columns: 1fr;
            }
            .login-brand-panel {
                padding: 28px 24px;
            }
            .brand-headline {
                font-size: 1.5rem;
            }
            .feature-pills, .brand-footer-note {
                display: none;
            }
            .login-form-panel {
                padding: 28px 24px;
            }
        }
    </style>
</head>
<body>

<div class="login-shell">
    <!-- Left Branding Column -->
    <div class="login-brand-panel">
        <div>
            <div class="brand-badge-row">
                <div class="brand-logo-box">YKS.</div>
                <div>
                    <span class="brand-name">Yuk Keliling Sentul</span>
                    <span class="brand-sub">Ruang Administrasi</span>
                </div>
            </div>

            <span class="brand-tag">━ &nbsp; PORTAL PENGELOLA RESMI</span>
            <h1 class="brand-headline">Kendalikan jadwal, paket, dan verifikasi trip dalam satu layar.</h1>
            <p class="brand-desc">
                Panel operasional internal untuk tim administrasi Yuk Keliling Sentul dalam memantau kuota harian, memvalidasi bukti pembayaran pelanggan, dan mengelola katalog paket.
            </p>

            <div class="feature-pills">
                <div class="feature-pill">
                    <i class="bi bi-check2-circle"></i>
                    <span>Kelola 5 Paket Resmi Trabas Trail & Wisata Jeep 4x4</span>
                </div>
                <div class="feature-pill">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>Verifikasi Bukti Transfer & Status Reservasi Real-time</span>
                </div>
                <div class="feature-pill">
                    <i class="bi bi-calendar3"></i>
                    <span>Pengaturan Slot Harian, Kalender & Laporan Pendapatan</span>
                </div>
            </div>
        </div>

        <div class="brand-footer-note">
            <span>&copy; 2026 Yuk Keliling Sentul</span>
            <span>Akses Khusus Internal</span>
        </div>
    </div>

    <!-- Right Login Form Column -->
    <div class="login-form-panel">
        <div class="form-eyebrow">━ &nbsp; AUTENTIKASI ADMIN</div>
        <h2 class="form-title">Masuk ke Panel Admin</h2>
        <p class="form-subtitle">Masukkan email dan kata sandi pengelola YKS untuk melanjutkan.</p>

        @if ($errors->any())
            <div class="alert-box alert-error">
                <i class="bi bi-exclamation-octagon-fill"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert-box alert-warning">
                <i class="bi bi-shield-lock-fill"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="alert-box alert-success">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="emailInput">Email Pengelola</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" id="emailInput" name="email" class="form-input"
                           placeholder="admin@yks.id"
                           value="{{ old('email', 'admin@yks.id') }}" required autofocus>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="passwordInput">Kata Sandi</label>
                <div class="input-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" id="passwordInput" name="password" class="form-input"
                           placeholder="Masukkan kata sandi admin" required>
                    <button type="button" class="toggle-pw" onclick="togglePassword()" aria-label="Tampilkan Kata Sandi">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember" checked>
                    <span>Ingat sesi saya</span>
                </label>
                <span style="font-size: 0.78rem;">Role: Pengelola Utama</span>
            </div>

            <button type="submit" class="btn-login">
                <span>Masuk ke Ruang Administrasi</span>
                <i class="bi bi-arrow-up-right"></i>
            </button>
        </form>

        <!-- Quick Demo Auto-fill Box -->
        <div class="demo-credentials">
            <div>
                <strong style="display: block;">Akun Admin Default:</strong>
                <span>Email: <code>admin@yks.id</code> &nbsp;|&nbsp; Sandi: <code>admin123</code></span>
            </div>
            <button type="button" class="btn-fill-demo" onclick="fillDemoAdmin()">Isi Otomatis</button>
        </div>

        <a href="{{ route('home') }}" class="back-public-link">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda Pelanggan
        </a>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    function fillDemoAdmin() {
        document.getElementById('emailInput').value = 'admin@yks.id';
        document.getElementById('passwordInput').value = 'admin123';
    }
</script>
</body>
</html>
