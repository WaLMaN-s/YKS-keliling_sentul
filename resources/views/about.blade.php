@extends('layouts.app')

@section('content')
    <!-- Page Breadcrumb & Header -->
    <div class="container" style="padding-top: 36px;">
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
            <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; <span>Tentang YKS</span>
        </div>
        <div class="section-tag">
            ━ &nbsp; TENTANG YKS
        </div>
        <h1 class="section-title" style="font-size: 2.8rem; max-width: 800px; margin-bottom: 16px;">
            Sentul bukan sekadar tempat lewat.
        </h1>
        <p class="section-desc" style="font-size: 1.15rem; max-width: 720px; margin-bottom: 36px;">
            Kami percaya petualangan terbaik bukan soal seberapa jauh melangkah, tapi seberapa dalam kita merasakan setiap
            lekuk jalan, dinginnya air sungai, dan kehangatan kebersamaan.
        </p>

        <!-- Hero Image Potret Tim -->
        <div
            style="border-radius: var(--radius-lg); overflow: hidden; height: 440px; margin-bottom: 64px; border: 1px solid var(--border);">
            <img src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=1600&q=80"
                alt="Tim YKS di Jalur Sentul" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <!-- Cerita YKS Section -->
    <section class="section-wrapper section-white-bg">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1.4fr; gap: 64px; align-items: start;">
                <div>
                    <div class="section-tag">
                        ━ &nbsp; CERITA KAMI
                    </div>
                    <h2 style="font-size: 2.2rem; color: var(--primary); margin-bottom: 16px;">
                        Lahir dari kecintaan pada bukit dan tanah basah.
                    </h2>
                    <div
                        style="background-color: var(--bg-sage); padding: 20px; border-radius: var(--radius-md); border-left: 4px solid var(--primary); margin-top: 24px;">
                        <p style="font-size: 0.88rem; color: var(--primary); line-height: 1.6; margin: 0;">
                            <strong>Komitmen Lokal:</strong> Seluruh kru, driver, dan tour guide kami merupakan warga lokal
                            asli perbukitan Sentul, Babakan Madang, Bogor.
                        </p>
                    </div>
                </div>

                <div
                    style="display: flex; flex-direction: column; gap: 20px; font-size: 1.02rem; color: var(--text-dark); line-height: 1.75;">
                    <p>
                        <strong>Yuk Keliling Sentul (YKS)</strong> didirikan untuk menjembatani kejenuhan rutinitas kota
                        dengan keindahan bentang alam Sentul yang masih alami dan asri. Selama bertahun-tahun, banyak orang
                        hanya mengenal Sentul dari restoran atau jalan aspal utamanya.
                    </p>
                    <p>
                        Padahal, hanya beberapa kilometer menanjak ke atas bukit, tersembunyi jalur tanah merah cadas,
                        aliran air sungai jernih dari mata air pegunungan, serta curug-curug tersembunyi yang menyimpan
                        kesegaran murni.
                    </p>
                    <p>
                        Bagi kami, jip 4x4 maupun tongkat trekking hanyalah sarana. Yang ingin kami bangun adalah momen di
                        mana Anda bisa tertawa lepas saat roda mencipratkan lumpur, merasakan sejuknya terpaan angin bukit,
                        dan pulang dengan energi yang baru.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Nilai Perjalanan (Cara Kami Menjelajah - Figma Deep Forest Background) -->
    <section class="section-wrapper section-forest-bg">
        <div class="container">
            <div class="section-tag tag-white" style="margin-bottom: 12px;">
                ━ &nbsp; CARA KAMI MENJELAJAH
            </div>
            <h2 style="font-size: 2.4rem; color: #FFFFFF; margin-bottom: 40px;">
                Seru, tertata, dan tetap peduli.
            </h2>

            <div class="workflow-grid">
                <div
                    style="background-color: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: var(--radius-lg); padding: 32px 24px;">
                    <span
                        style="font-family: var(--font-heading); font-size: 2rem; font-weight: 900; color: #CBD9C8; display: block; margin-bottom: 12px;">01</span>
                    <h3 style="font-size: 1.25rem; color: #FFFFFF; margin-bottom: 10px;">Hormati Alam</h3>
                    <p style="font-size: 0.9rem; color: #E3E9E0; line-height: 1.6;">
                        Tetap berada di jalur aman, wajib membawa kembali sampah plastik, dan tidak merusak vegetasi maupun
                        ekosistem aliran sungai.
                    </p>
                </div>

                <div
                    style="background-color: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: var(--radius-lg); padding: 32px 24px;">
                    <span
                        style="font-family: var(--font-heading); font-size: 2rem; font-weight: 900; color: #CBD9C8; display: block; margin-bottom: 12px;">02</span>
                    <h3 style="font-size: 1.25rem; color: #FFFFFF; margin-bottom: 10px;">Jelajah Bersama</h3>
                    <p style="font-size: 0.9rem; color: #E3E9E0; line-height: 1.6;">
                        Pemandu dan peserta saling mendengarkan ritme langkah. Tidak ada yang ditinggal di belakang;
                        kenyamanan keluarga dan anak diutamakan.
                    </p>
                </div>

                <div
                    style="background-color: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: var(--radius-lg); padding: 32px 24px;">
                    <span
                        style="font-family: var(--font-heading); font-size: 2rem; font-weight: 900; color: #CBD9C8; display: block; margin-bottom: 12px;">03</span>
                    <h3 style="font-size: 1.25rem; color: #FFFFFF; margin-bottom: 10px;">Cerita yang Bertahan</h3>
                    <p style="font-size: 0.9rem; color: #E3E9E0; line-height: 1.6;">
                        Bukan sekadar foto selfie di titik tujuan, melainkan obrolan hangat, rasa percaya diri melewati
                        rintangan, dan kenangan bersama rekan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tim Pendamping Section -->
    <section class="section-wrapper">
        <div class="container">
            <div class="section-tag">
                ━ &nbsp; TIM PENDAMPING
            </div>
            <h2 class="section-title">Orang-orang di balik kemudi dan jalur.</h2>
            <p class="section-desc" style="margin-bottom: 40px;">
                Setiap pemandu kami dibekali pengetahuan navigasi, standar pertolongan pertama, dan keramahan khas Bogor.
            </p>

            <div class="why-yks-grid">
                <div class="why-yks-box">
                    <div
                        style="width: 52px; height: 52px; background-color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-size: 1.4rem;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h3 class="why-box-title">Driver & Navigator 4x4</h3>
                    <p class="why-box-desc">
                        Pengemudi berpengalaman yang menguasai teknik winching, sudut kemiringan bukit, serta kontrol roda
                        di atas bebatuan licin.
                    </p>
                </div>

                <div class="why-yks-box">
                    <div
                        style="width: 52px; height: 52px; background-color: var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-size: 1.4rem;">
                        <i class="bi bi-person-walking"></i>
                    </div>
                    <h3 class="why-box-title">Pemandu Trekking Lokal</h3>
                    <p class="why-box-desc">
                        Membimbing setiap langkah di pematang sawah dan jalur curug, siap memegang tangan peserta saat
                        menyeberangi arus sungai.
                    </p>
                </div>

                <div class="why-yks-box">
                    <div
                        style="width: 52px; height: 52px; background-color: var(--primary); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-size: 1.4rem;">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h3 class="why-box-title">Safety & Medical First</h3>
                    <p class="why-box-desc">
                        Membawa kotak P3K darurat standar outdoor, oksigen portable saat dibutuhkan, serta koordinasi cepat
                        dengan fasilitas medis terdekat.
                    </p>
                </div>

                <div class="why-yks-box">
                    <div
                        style="width: 52px; height: 52px; background-color: var(--accent); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #FFFFFF; font-size: 1.4rem;">
                        <i class="bi bi-camera"></i>
                    </div>
                    <h3 class="why-box-title">Kreator Dokumentasi</h3>
                    <p class="why-box-desc">
                        Siap mengambil foto dan video aksi terbaik Anda dengan sudut pandang estetik untuk feed dan kenangan
                        abadi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Komitmen Keselamatan -->
    <section class="section-wrapper section-white-bg">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 48px; align-items: center;">
                <div>
                    <div class="section-tag">
                        ━ &nbsp; SEBELUM GAS, PASTIKAN SIAP
                    </div>
                    <h2 class="section-title">Rasa aman dimulai sebelum berangkat.</h2>
                    <p class="section-desc" style="margin-bottom: 28px;">
                        Kami tidak berkompromi dengan keselamatan rombongan. Setiap sesi diawali dengan briefing singkat dan
                        pemeriksaan fisik armada.
                    </p>

                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <div style="display: flex; align-items: flex-start; gap: 14px;">
                            <i class="bi bi-check2-circle"
                                style="color: var(--accent); font-size: 1.4rem; flex-shrink: 0;"></i>
                            <div>
                                <strong style="color: var(--primary);">Pemeriksaan Kondisi Kendaraan & Rem</strong>
                                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 2px;">Cek tekanan ban,
                                    sistem pengereman, oli, dan gardan 4x4 setiap pagi.</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; gap: 14px;">
                            <i class="bi bi-check2-circle"
                                style="color: var(--accent); font-size: 1.4rem; flex-shrink: 0;"></i>
                            <div>
                                <strong style="color: var(--primary);">Briefing Posisi Duduk & Jalur Bebatuan</strong>
                                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 2px;">Penjelasan titik
                                    tumpu tangan dan aturan saat melintasi turunan curam.</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; gap: 14px;">
                            <i class="bi bi-check2-circle"
                                style="color: var(--accent); font-size: 1.4rem; flex-shrink: 0;"></i>
                            <div>
                                <strong style="color: var(--primary);">Pemandu Mendampingi Penuh di Jalur</strong>
                                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 2px;">Tidak ada peserta
                                    yang berjalan sendirian tanpa arahan kru lapangan.</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: flex-start; gap: 14px;">
                            <i class="bi bi-check2-circle"
                                style="color: var(--accent); font-size: 1.4rem; flex-shrink: 0;"></i>
                            <div>
                                <strong style="color: var(--primary);">Pemantauan Cuaca & Debit Sungai Real-time</strong>
                                <p style="font-size: 0.88rem; color: var(--text-muted); margin-top: 2px;">Koordinasi dengan
                                    pos pantau curug sebelum rombongan turun ke air.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    style="border-radius: var(--radius-lg); overflow: hidden; height: 380px; border: 1px solid var(--border);">
                    <img src="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?auto=format&fit=crop&w=800&q=80"
                        alt="Pengarahan Keselamatan YKS" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom CTA Banner -->
    <section class="section-wrapper">
        <div class="container">
            <div class="cta-adventure-banner">
                <div>
                    <h2 class="cta-banner-title">Pilih petualanganmu akhir pekan ini.</h2>
                    <p class="cta-banner-desc">
                        Rombongan keluarga, sahabat, maupun kantor — tim YKS siap memberikan pengalaman berkesan di Sentul.
                    </p>
                </div>
                <a href="{{ route('booking.create') }}" class="btn btn-forest btn-lg"
                    style="background-color: #1F3D2B; color: #FFFFFF; border: 1px solid rgba(255,255,255,0.2);">
                    Reservasi Sekarang &nbsp;↗
                </a>
            </div>
        </div>
    </section>
@endsection
