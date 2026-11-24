@extends('layouts.app', ['title' => 'Tentang YKS'])

@section('content')
<!-- Pembuka Halaman (Figma ON GOING #73:6558 - Tentang YKS · Desktop) -->
<div class="container" style="padding-top: 36px;">
    <div class="section-tag" style="font-size: 1.1rem; margin-bottom: 14px;">
        <span style="color: #F26B1D;">━</span> &nbsp; TENTANG YKS
    </div>
    <h1 class="section-title" style="font-size: 3.2rem; max-width: 960px; margin-bottom: 16px; line-height: 1.15;">
        Kenal jalurnya. Dekat dengan ceritanya.
    </h1>
    <p class="section-desc" style="font-size: 1.2rem; max-width: 740px; margin-bottom: 36px; color: #667066;">
        Kami percaya petualangan terbaik bukan soal seberapa jauh pergi, tetapi seberapa utuh kita menikmati perjalanan.
    </p>

    <!-- Potret Perjalanan (#73:6578) -->
    <div class="about-hero-banner" style="height: 480px; border-radius: 16px; overflow: hidden; margin-bottom: 16px;">
        <img src="{{ asset('galeri/Untitled10.jpg') }}"
             alt="Potret Perjalanan Yuk Keliling Sentul"
             style="width: 100%; height: 100%; object-fit: cover;">
    </div>
</div>

<!-- Cerita YKS Section (Figma ON GOING #73:6581) -->
<section class="section-wrapper" style="padding-top: 48px; padding-bottom: 64px;">
    <div class="container">
        <div style="background: #FFFFFF; border: 1px solid var(--border); border-radius: 16px; padding: 48px; box-shadow: 0 8px 28px rgba(31,61,43,0.04);">
            <h2 style="font-size: 2rem; font-weight: 800; color: #1A1A1A; margin-bottom: 24px; line-height: 1.4;">
                Jelajahi Alam, Nikmati Petualangan Bersama Yuk Keliling Sentul!
            </h2>

            <div style="display: flex; flex-direction: column; gap: 18px; font-size: 1.1rem; color: #1A1A1A; line-height: 1.75; text-align: justify;">
                <p style="margin: 0;">
                    <strong>Yuk Keliling Sentul (YKS)</strong> merupakan penyedia jasa wisata petualangan yang berlokasi di kawasan Sentul, Bogor, Jawa Barat. Kami menghadirkan berbagai pilihan aktivitas wisata alam yang dapat dinikmati bersama keluarga, teman, maupun rombongan.
                </p>
                <p style="margin: 0;">
                    Melalui berbagai pilihan paket wisata, mulai dari petualangan menggunakan Jeep, motor trail, hingga paket bawa motor pribadi, kami mengajak Anda untuk menjelajahi keindahan alam Sentul dengan cara yang berbeda.
                </p>
                <p style="margin: 0;">
                    Dengan pilihan paket perjalanan <strong>Open Trip / Reguler</strong> maupun <strong>Private Trip</strong>, pengunjung dapat menyesuaikan pengalaman wisata berdasarkan kebutuhan dan keinginan masing-masing.
                </p>
                <p style="margin: 0;">
                    Kami berkomitmen untuk memberikan informasi layanan dan proses reservasi yang lebih mudah sehingga setiap pengunjung dapat merencanakan petualangannya dengan lebih praktis.
                </p>
                <p style="margin: 0; font-weight: 600; color: #1F3D2B;">
                    Yuk, temukan pengalaman petualangan seru dan jelajahi keindahan alam Sentul bersama kami!
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Ajakan Bertualang (Figma ON GOING #73:6651 - Banner Oranye #F26B1D) -->
<section style="background-color: #F26B1D; padding: 64px 0; position: relative; overflow: hidden;">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between; gap: 32px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 280px;">
            <h2 style="font-family: var(--font-heading); font-size: 2.6rem; font-weight: 800; color: #1F3D2B; line-height: 1.15; margin-bottom: 12px;">
                Akhir pekan, ke alam saja.
            </h2>
            <p style="font-size: 1.05rem; color: #1F3D2B; margin: 0; line-height: 1.65;">
                Pilih jalurmu. Sisanya, kita siapkan bersama.
            </p>
        </div>

        <div>
            <a href="{{ route('booking.create') }}"
               style="display: inline-flex; align-items: center; justify-content: center; min-height: 50px; padding: 14px 24px; background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; color: #1F3D2B; font-weight: 700; font-size: 0.92rem; text-decoration: none;">
                Reservasi Sekarang &nbsp;↗
            </a>
        </div>
    </div>
</section>
@endsection
