@extends('layouts.app', ['title' => 'Daftar Akun Baru'])

@section('content')
<section class="section" style="min-height: 75vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 460px;">
        <div style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 36px 30px; box-shadow: var(--shadow-md);">
            <div style="text-align: center; margin-bottom: 24px;">
                <div class="logo-icon" style="margin: 0 auto 12px; width: 48px; height: 48px;">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--dark); margin-bottom: 4px;">Daftar Akun Baru</h2>
                <p style="font-size: 0.88rem; color: var(--text-muted);">Simpan riwayat petualangan dan booking lebih cepat.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Rian Hidayat" required value="{{ old('name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required value="{{ old('email') }}">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ulangi Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">
                    <i class="bi bi-check-circle"></i> Buat Akun Sekarang
                </button>
            </form>

            <div style="text-align: center; margin-top: 24px; padding-top: 18px; border-top: 1px solid var(--border-light); font-size: 0.88rem; color: var(--text-muted);">
                Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700;">Masuk di sini</a>
            </div>
        </div>
    </div>
</section>
@endsection
