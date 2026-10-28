@extends('layouts.app', ['title' => 'Masuk Akun'])

@section('content')
<section class="section-wrapper" style="min-height: 75vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 440px;">
        <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 40px 32px; box-shadow: var(--shadow-md);">
            <div style="text-align: center; margin-bottom: 28px;">
                <div class="logo-icon-box" style="margin: 0 auto 16px; width: 48px; height: 48px;">
                    <i class="bi bi-person-fill" style="font-size: 1.5rem;"></i>
                </div>
                <h2 style="font-size: 1.6rem; color: var(--primary); margin-bottom: 6px;">Masuk Akun</h2>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Akses riwayat trip dan kelola tiket petualangan Anda.</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus value="{{ old('email') }}">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Masuk Sekarang &nbsp;↗
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border-light); font-size: 0.88rem; color: var(--text-muted);">
                Belum punya akun? <a href="{{ route('register') }}" style="color: var(--accent); font-weight: 700;">Daftar di sini</a>
            </div>
        </div>
    </div>
</section>
@endsection
