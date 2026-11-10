@extends('layouts.app', ['title' => 'Daftar Akun Baru'])

@section('content')
<section class="section-wrapper" style="min-height: 75vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 480px;">
        <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 40px 32px; box-shadow: var(--shadow-md);">
            <div style="text-align: center; margin-bottom: 28px;">
                <div class="logo-icon-box" style="margin: 0 auto 16px; width: 48px; height: 48px;">
                    <i class="bi bi-person-plus-fill" style="font-size: 1.5rem;"></i>
                </div>
                <h2 style="font-size: 1.6rem; color: var(--primary); margin-bottom: 6px;">Daftar Akun Baru</h2>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Simpan riwayat trip dan nikmati booking lebih cepat.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="text-accent">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Nama lengkap Anda" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Email <span class="text-accent">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required value="{{ old('email') }}">
                </div>

                <div class="resp-grid-2" style="gap: 14px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Password <span class="text-accent">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Ulangi Password <span class="text-accent">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Buat Akun Sekarang &nbsp;↗
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-light); font-size: 0.88rem; color: var(--text-muted);">
                Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 700;">Masuk di sini</a>
            </div>
        </div>
    </div>
</section>
@endsection
