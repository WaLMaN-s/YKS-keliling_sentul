@extends('layouts.app', ['title' => 'Tulis Ulasan Perjalanan'])

@section('content')
<div class="container" style="padding-top: 36px; max-width: 820px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp;
        <a href="{{ route('gallery') }}" style="color: var(--text-sub);">Galeri & Testimoni</a> &nbsp;/&nbsp;
        <span>Tulis Ulasan</span>
    </div>

    <h1 class="section-title" style="font-size: 2.3rem; margin-bottom: 8px;">
        Bagaimana perjalananmu bersama YKS?
    </h1>
    <p class="section-desc" style="margin-bottom: 28px;">
        Bagikan momen, kesan, dan masukanmu. Ceritamu membantu kami menghadirkan perjalanan yang lebih baik.
    </p>
</div>

<section class="section-wrapper" style="padding-top: 0;">
    <div class="container" style="max-width: 820px;">
        <div class="resp-card-pad" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">
                <strong style="color: var(--primary); font-size: 1rem;">Tulis ulasanmu</strong>
                <span style="font-size: 0.78rem; color: var(--accent); font-weight: 600;">* Wajib diisi</span>
            </div>

            <form method="POST" action="{{ route('reviews.store') }}" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn){ btn.disabled = true; btn.innerHTML = '<i class=\'bi bi-hourglass-split\'></i> Mengirim Ulasan...'; }">
                @csrf

                <h4 style="color: var(--primary); font-size: 1.05rem; margin-bottom: 14px;">Tentang kamu</h4>
                <div class="resp-grid-2" style="gap: 16px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Nama penulis <span class="text-accent">*</span></label>
                        <input type="text" name="name" class="form-control" required
                               placeholder="Nama yang ingin ditampilkan"
                               value="{{ old('name') }}">
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Asal Kota (Opsional)</label>
                        <input type="text" name="city" class="form-control"
                               placeholder="Contoh: Jakarta Selatan, Bogor"
                               value="{{ old('city') }}">
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 24px;">

                <h4 style="color: var(--primary); font-size: 1.05rem; margin-bottom: 14px;">Perjalanan yang kamu ikuti</h4>
                <div class="resp-grid-2" style="gap: 16px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Paket perjalanan <span class="text-accent">*</span></label>
                        <select name="package_name" class="form-control" required>
                            <option value="">-- Pilih Paket Wisata --</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->name }}" {{ old('package_name') === $pkg->name ? 'selected' : '' }}>
                                    {{ $pkg->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Tanggal perjalanan <span class="text-accent">*</span></label>
                        <input type="date" name="trip_date" class="form-control" required
                               value="{{ old('trip_date', date('Y-m-d')) }}">
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 24px;">

                <h4 style="color: var(--primary); font-size: 1.05rem; margin-bottom: 14px;">Kesan dan ceritamu</h4>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Penilaian keseluruhan <span class="text-accent">*</span></label>
                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 5) }}">
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-btn" data-star="{{ $i }}"
                                    style="width: 44px; height: 44px; border-radius: 8px; border: 1px solid var(--border); background: #FFFFFF; font-size: 1.35rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.15s;">
                                <i class="bi bi-star-fill" style="color: #F59E0B;"></i>
                            </button>
                        @endfor
                        <span id="ratingLabel" style="font-size: 0.85rem; color: var(--text-muted); margin-left: 8px; font-weight: 600;">5 / 5 (Sangat Berkesan)</span>
                    </div>
                    <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 6px;">1 = kurang berkesan · 5 = sangat berkesan</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Ceritakan pengalamanmu <span class="text-accent">*</span></label>
                    <textarea name="review" rows="4" class="form-control" required maxlength="2000"
                              placeholder="Apa momen paling berkesan? Bagaimana jalur, pemandu, atau suasana perjalanannya? Kami juga senang mendengar saranmu.">{{ old('review') }}</textarea>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.86rem; color: var(--text-dark);">
                        <input type="checkbox" name="agree" value="1" required {{ old('agree') ? 'checked' : '' }} style="margin-top: 3px; accent-color: var(--primary); width: 16px; height: 16px;">
                        <span>Saya menyetujui nama dan ulasan yang saya kirim ditampilkan di situs Yuk Keliling Sentul. Saya menulis berdasarkan pengalaman perjalanan saya sendiri. <span class="text-accent">*</span></span>
                    </label>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <a href="{{ route('gallery') }}" class="btn btn-outline">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        Kirim ulasan &nbsp;↗
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const ratingLabels = {
        1: '1 / 5 (Kurang Berkesan)',
        2: '2 / 5 (Cukup)',
        3: '3 / 5 (Baik)',
        4: '4 / 5 (Memuaskan)',
        5: '5 / 5 (Sangat Berkesan)'
    };

    function setRating(val) {
        document.getElementById('ratingInput').value = val;
        document.getElementById('ratingLabel').textContent = ratingLabels[val] || '';
        document.querySelectorAll('.star-btn').forEach(btn => {
            const starVal = parseInt(btn.getAttribute('data-star'));
            const icon = btn.querySelector('i');
            if (starVal <= val) {
                icon.style.color = '#F59E0B';
                btn.style.borderColor = '#F59E0B';
                btn.style.backgroundColor = '#FFFBEB';
            } else {
                icon.style.color = '#CBD5E1';
                btn.style.borderColor = 'var(--border)';
                btn.style.backgroundColor = '#FFFFFF';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        setRating(parseInt(document.getElementById('ratingInput').value) || 5);
    });
</script>
@endsection
