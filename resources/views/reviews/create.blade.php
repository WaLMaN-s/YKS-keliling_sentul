@extends('layouts.app', ['title' => 'Tulis Ulasan'])

@section('content')
<!-- Pembuka Ulasan (Figma ON GOING #165:303 - Tulis ulasan · Desktop) -->
<div class="container" style="padding-top: 32px; padding-bottom: 28px;">
    <h1 style="font-family: var(--font-heading); font-size: 2.4rem; font-weight: 800; color: #1F3D2B; line-height: 1.2; letter-spacing: -0.8px; margin-bottom: 10px;">
        Bagaimana perjalananmu bersama YKS?
    </h1>
    <p style="font-size: 0.96rem; color: #667066; line-height: 1.6; margin: 0;">
        Bagikan momen, kesan, dan masukanmu. Ceritamu membantu kami menghadirkan perjalanan yang lebih baik.
    </p>
</div>

<!-- Formulir Ulasan (#165:328) -->
<section style="padding-bottom: 64px;">
    <div class="container">
        @if($errors->any())
            <div style="background: #FEF2F2; border: 1px solid #FCA5A5; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px; color: #991B1B; font-size: 0.88rem;">
                <strong>Mohon lengkapi isian berikut:</strong>
                <ul style="margin: 6px 0 0 18px;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('reviews.store') }}"
              style="background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 16px; padding: 32px; display: flex; flex-direction: column; gap: 28px;">
            @csrf

            <!-- Petunjuk Formulir (#165:330) -->
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                <span style="font-size: 1rem; font-weight: 700; color: #1F3D2B;">Tulis ulasanmu</span>
                <span style="font-size: 0.78rem; color: #667066;">
                    <span style="color: #F26B1D;">*</span> Wajib diisi
                </span>
            </div>

            <!-- 1. Tentang Kamu (#165:333) -->
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <h2 style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 700; color: #1F3D2B; margin: 0;">
                    Tentang kamu
                </h2>
                <div>
                    <label style="display: block; font-size: 0.88rem; font-weight: 600; color: #1F3D2B; margin-bottom: 8px;">
                        Nama penulis <span style="color: #F26B1D;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Nama yang ingin ditampilkan"
                           style="width: 100%; height: 50px; padding: 0 14px; border: 1px solid #DEDED4; border-radius: 10px; font-size: 0.9rem; color: #1F3D2B;">
                    <span style="display: block; font-size: 0.76rem; color: #667066; margin-top: 6px;">
                        Nama ini akan tampil bersama ulasanmu.
                    </span>
                </div>
            </div>

            <div style="height: 1px; background: #DEDED4;"></div>

            <!-- 2. Perjalanan yang Kamu Ikuti (#165:348) -->
            <div style="display: flex; flex-direction: column; gap: 18px;">
                <h2 style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 700; color: #1F3D2B; margin: 0;">
                    Perjalanan yang kamu ikuti
                </h2>

                <div>
                    <label style="display: block; font-size: 0.88rem; font-weight: 600; color: #1F3D2B; margin-bottom: 10px;">
                        Paket perjalanan <span style="color: #F26B1D;">*</span>
                    </label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                        @php
                            $reviewPackages = ['Jelajah Jeep', 'Trail', 'Bawa Motor Pribadi', 'Private Trip'];
                            $oldPkg = old('package_name', 'Jelajah Jeep');
                        @endphp
                        @foreach($reviewPackages as $rPkg)
                            <label class="pkg-radio-card" style="display: flex; align-items: center; gap: 10px; height: 50px; padding: 0 14px; border: 1px solid {{ $oldPkg === $rPkg ? '#1F3D2B' : '#DEDED4' }}; background: {{ $oldPkg === $rPkg ? '#EAF0E7' : '#FFFFFF' }}; border-radius: 10px; cursor: pointer; font-size: 0.86rem; color: #1F3D2B; font-weight: 500;">
                                <input type="radio" name="package_name" value="{{ $rPkg }}" {{ $oldPkg === $rPkg ? 'checked' : '' }}
                                       onchange="highlightPkgRadio(this)" style="accent-color: #1F3D2B; width: 17px; height: 17px;">
                                <span>{{ $rPkg }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div style="max-width: 382px;">
                    <label style="display: block; font-size: 0.88rem; font-weight: 600; color: #1F3D2B; margin-bottom: 8px;">
                        Tanggal perjalanan <span style="color: #F26B1D;">*</span>
                    </label>
                    <input type="date" name="trip_date" value="{{ old('trip_date', date('Y-m-d')) }}" required
                           max="{{ date('Y-m-d') }}"
                           style="width: 100%; height: 50px; padding: 0 14px; border: 1px solid #DEDED4; border-radius: 10px; font-size: 0.9rem; color: #1F3D2B;">
                </div>
            </div>

            <div style="height: 1px; background: #DEDED4;"></div>

            <!-- 3. Kesan dan Ceritamu (#165:372) -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <h2 style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 700; color: #1F3D2B; margin: 0;">
                    Kesan dan ceritamu
                </h2>

                <!-- Penilaian Bintang Interaktif (#165:375) -->
                <div>
                    <label style="display: block; font-size: 0.88rem; font-weight: 600; color: #1F3D2B; margin-bottom: 10px;">
                        Penilaian keseluruhan <span style="color: #F26B1D;">*</span>
                    </label>
                    <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 5) }}">
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        @for($star = 1; $star <= 5; $star++)
                            <button type="button" class="star-btn" data-val="{{ $star }}" onclick="setStarRating({{ $star }})"
                                    style="width: 44px; height: 44px; border-radius: 8px; border: 1px solid #DEDED4; background: #FCFBF8; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #F26B1D; cursor: pointer;">
                                ★
                            </button>
                        @endfor
                        <span id="ratingTextLabel" style="font-size: 0.84rem; color: #667066; margin-left: 6px;">
                            5 bintang · Sangat berkesan
                        </span>
                    </div>
                    <span style="display: block; font-size: 0.76rem; color: #667066; margin-top: 8px;">
                        1 = kurang berkesan · 5 = sangat berkesan
                    </span>
                </div>

                <!-- Ceritakan Pengalamanmu (#165:390) -->
                <div>
                    <label style="display: block; font-size: 0.88rem; font-weight: 600; color: #1F3D2B; margin-bottom: 8px;">
                        Ceritakan pengalamanmu <span style="color: #F26B1D;">*</span>
                    </label>
                    <textarea name="review" id="reviewTextarea" rows="5" maxlength="2000" required
                              oninput="updateCharCount(this)"
                              placeholder="Apa momen paling berkesan? Bagaimana jalur, pemandu, atau suasana perjalanannya? Kami juga senang mendengar saranmu."
                              style="width: 100%; padding: 14px; border: 1px solid #DEDED4; border-radius: 10px; font-size: 0.9rem; color: #1F3D2B; line-height: 1.65;">{{ old('review') }}</textarea>
                    <div style="display: flex; justify-content: space-between; font-size: 0.76rem; color: #667066; margin-top: 6px;">
                        <span id="charCounter">0 / 2.000</span>
                    </div>
                </div>
            </div>

            <div style="height: 1px; background: #DEDED4;"></div>

            <!-- 4. Persetujuan dan Pengiriman (#165:408) -->
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <label style="display: flex; align-items: flex-start; gap: 12px; cursor: pointer; font-size: 0.84rem; color: #1A1A1A; line-height: 1.6;">
                    <input type="checkbox" name="consent" value="1" required {{ old('consent') ? 'checked' : '' }}
                           style="width: 20px; height: 20px; margin-top: 2px; accent-color: #1F3D2B;">
                    <span>
                        Saya menyetujui nama, ulasan, dan foto yang saya unggah ditampilkan di situs YKS. Saya menulis berdasarkan pengalaman perjalanan saya sendiri. <span style="color: #F26B1D;">*</span>
                    </span>
                </label>

                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                    <a href="{{ route('gallery') }}"
                       style="display: inline-flex; align-items: center; gap: 10px; height: 50px; padding: 0 20px; background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; font-size: 0.9rem; font-weight: 700; color: #1F3D2B; text-decoration: none;">
                        <span>Kembali</span>
                        <i class="bi bi-arrow-left"></i>
                    </a>

                    <button type="submit"
                            style="display: inline-flex; align-items: center; gap: 10px; height: 50px; padding: 0 24px; background: #F26B1D; border: 1px solid #F26B1D; border-radius: 12px; font-size: 0.9rem; font-weight: 700; color: #1A1A1A; cursor: pointer;">
                        <span>Kirim ulasan</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>

                <p style="font-size: 0.76rem; color: #667066; margin: 0;">
                    Ulasan akan ditinjau oleh tim YKS sebelum ditampilkan. Emailmu tidak akan dipublikasikan.
                </p>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function highlightPkgRadio(radio) {
        document.querySelectorAll('.pkg-radio-card').forEach(lbl => {
            lbl.style.borderColor = '#DEDED4';
            lbl.style.background = '#FFFFFF';
        });
        if (radio.parentElement) {
            radio.parentElement.style.borderColor = '#1F3D2B';
            radio.parentElement.style.background = '#EAF0E7';
        }
    }

    function setStarRating(val) {
        document.getElementById('ratingInput').value = val;
        const descriptions = {
            1: '1 bintang · Kurang berkesan',
            2: '2 bintang · Cukup',
            3: '3 bintang · Baik',
            4: '4 bintang · Berkesan',
            5: '5 bintang · Sangat berkesan'
        };
        document.getElementById('ratingTextLabel').textContent = descriptions[val] || (val + ' bintang');
        document.querySelectorAll('.star-btn').forEach(btn => {
            const bVal = parseInt(btn.getAttribute('data-val'));
            if (bVal <= val) {
                btn.style.color = '#F26B1D';
                btn.style.borderColor = '#F26B1D';
                btn.style.background = '#FFF0E4';
            } else {
                btn.style.color = '#C5CCC6';
                btn.style.borderColor = '#DEDED4';
                btn.style.background = '#FCFBF8';
            }
        });
    }

    function updateCharCount(el) {
        const len = el.value.length;
        document.getElementById('charCounter').textContent = len.toLocaleString('id-ID') + ' / 2.000';
    }

    // Init default rating state
    setStarRating(parseInt(document.getElementById('ratingInput').value) || 5);
</script>
@endsection
