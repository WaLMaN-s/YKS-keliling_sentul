@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Hero Section (Figma 684px - Petualangan Dimulai) -->
    <section class="hero-figma">
        <div class="hero-figma-inner">
            <div class="section-tag tag-orange">
                ━ &nbsp; SENTUL, JAWA BARAT · JELAJAH TANPA BIASA
            </div>
            <h1 class="hero-main-title">
                Sentul dari sisi yang jarang terlihat.
            </h1>
            <p class="hero-lead-desc">
                Bukan sekadar keliling. Kami mengajakmu menembus rute perbukitan, jalur tanah, dan sungai tersembunyi dengan pemandu berpengalaman.
            </p>
            <div class="hero-action-row">
                <a href="{{ route('booking.create') }}" class="btn btn-primary btn-lg">
                    Pilih Jadwal Sekarang &nbsp;↗
                </a>
                <a href="{{ route('packages.index') }}" class="btn btn-outline-white btn-lg">
                    Jelajahi Paket &nbsp;↗
                </a>
            </div>
            <div class="hero-meta-bottom">
                <span><i class="bi bi-geo-alt"></i> Sentul · Bogor · Jawa Barat</span>
                <span>01 — 03 Sesi Harian</span>
            </div>
        </div>
    </section>

    <!-- Three Highlights Below Hero Banner -->
    <div class="hero-highlights-bar">
        <div class="highlight-box">
            <h3 class="highlight-box-title">Jalur alam pilihan</h3>
            <p class="highlight-box-desc">Hutan, sungai, dan perbukitan Sentul dengan pemandangan alami yang terjaga.</p>
        </div>
        <div class="highlight-box">
            <h3 class="highlight-box-title">Satu jip, banyak cerita</h3>
            <p class="highlight-box-desc">Hingga 4 peserta per jip 4x4 untuk pengalaman yang akrab dan aman.</p>
        </div>
        <div class="highlight-box">
            <h3 class="highlight-box-title">Berangkat lebih tenang</h3>
            <p class="highlight-box-desc">Pengarahan keselamatan, briefing rute, dan pendampingan penuh sebelum perjalanan.</p>
        </div>
    </div>
</div>

<!-- Paket Unggulan Section -->
<section class="section-wrapper" id="paket">
    <div class="container">
        <div class="section-header-row">
            <div>
                <div class="section-tag">
                    ━ &nbsp; PILIH CARA JELAJAHMU
                </div>
                <h2 class="section-title">Paket populer untuk setiap gaya jelajah.</h2>
                <p class="section-desc">
                    Dari jelajah pertama sampai tantangan berikutnya. Pemandu lokal kami siap menemani setiap langkah dan putaran roda.
                </p>
            </div>
            <a href="{{ route('packages.index') }}" class="btn btn-outline">
                Lihat Semua Paket &nbsp;↗
            </a>
        </div>

        <!-- Tour Cards Grid -->
        <div class="packages-grid">
            @foreach ($packages as $pkg)
                <div class="package-card">
                    <div class="package-card-media">
                        <img src="{{ $pkg->image ?: 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $pkg->name }}" loading="lazy">
                        <span class="package-badge-tag">
                            @if ($loop->first)
                                ★ Paling Diminati
                            @elseif ($pkg->difficulty === 'Mudah')
                                Santai & Keluarga
                            @elseif ($pkg->difficulty === 'Sedang')
                                Jalur Favorit
                            @else
                                Tantangan Ekstrem
                            @endif
                        </span>
                    </div>
                    <div class="package-card-content">
                        <span class="package-category-chip">{{ $pkg->category->name ?? 'Wisata Sentul' }}</span>
                        <h3 class="package-name">{{ $pkg->name }}</h3>
                        <p class="package-desc">{{ Str::limit($pkg->description, 110) }}</p>
                        
                        <div class="package-meta-row">
                            <span class="package-meta-item"><i class="bi bi-clock"></i> {{ $pkg->duration }}</span>
                            <span class="package-meta-item"><i class="bi bi-speedometer2"></i> {{ $pkg->difficulty }}</span>
                            <span class="package-meta-item"><i class="bi bi-geo-alt"></i> Sentul</span>
                        </div>

                        <div class="package-bottom-row">
                            <div class="package-price-wrap">
                                <span class="package-price-label">Mulai dari</span>
                                <span class="package-price-val">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('packages.show', $pkg->slug) }}" class="btn btn-forest btn-sm">
                                Detail &nbsp;↗
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Kenapa YKS Section (Figma: Mengapa Keliling Bersama Kami) -->
<section class="section-wrapper section-sage-bg" id="kenapa-yks">
    <div class="container">
        <div class="section-header-row" style="margin-bottom: 36px;">
            <div>
                <div class="section-tag">
                    ━ &nbsp; MENGAPA KELILING BERSAMA KAMI
                </div>
                <h2 class="section-title">Pengalaman Sentul yang lebih dekat dan terarah.</h2>
                <p class="section-desc">
                    Komitmen kami adalah kenyamanan, keselamatan, dan keaslian suasana alam yang dirasakan langsung oleh setiap peserta.
                </p>
            </div>
        </div>

        <div class="why-yks-grid">
            <div class="why-yks-box">
                <span class="why-num">01</span>
                <h3 class="why-box-title">Tim Lokal Asli Sentul</h3>
                <p class="why-box-desc">
                    Dipandu oleh warga asli Sentul yang memahami setiap rute rahasia, kondisi cuaca, debit sungai, dan spot terindah yang aman.
                </p>
            </div>

            <div class="why-yks-box">
                <span class="why-num">02</span>
                <h3 class="why-box-title">Jalur Eksklusif & Aman</h3>
                <p class="why-box-desc">
                    Rute dirancang secara berkala untuk memadukan keseruan alam liar dan faktor keamanan tinggi bagi semua tingkat kemahiran.
                </p>
            </div>

            <div class="why-yks-box">
                <span class="why-num">03</span>
                <h3 class="why-box-title">Unit Jip & Alat Terawat</h3>
                <p class="why-box-desc">
                    Armada 4x4 Land Rover diperiksa secara berkala sebelum setiap sesi. Dilengkapi trekking pole, jas hujan, dan kotak P3K lengkap.
                </p>
            </div>

            <div class="why-yks-box">
                <span class="why-num">04</span>
                <h3 class="why-box-title">Dokumentasi Seru</h3>
                <p class="why-box-desc">
                    Pemandu kami siap mengabadikan foto dan video aksi terbaik Anda di tengah lintasan sungai dan puncak perbukitan.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Cara Reservasi (Figma: Dari Rencana Jadi Cerita) -->
<section class="section-wrapper" id="cara-reservasi">
    <div class="container">
        <div class="section-header-row" style="margin-bottom: 36px;">
            <div>
                <div class="section-tag">
                    ━ &nbsp; DARI RENCANA JADI CERITA
                </div>
                <h2 class="section-title">Empat langkah mudah menuju petualangan.</h2>
                <p class="section-desc">
                    Proses pemesanan transparan tanpa perantara. Pilih paket, tentukan waktu luang Anda, dan kami siapkan segalanya.
                </p>
            </div>
        </div>

        <div class="workflow-grid">
            <div class="step-card">
                <span class="step-number-tag">01</span>
                <h3 class="step-card-title">Pilih Paket</h3>
                <p class="step-card-desc">
                    Pilih petualangan yang cocok: jelajah santai trekking curug, uji adrenalin offroad 4x4, gowes sepeda, atau sunset camping.
                </p>
            </div>

            <div class="step-card">
                <span class="step-number-tag">02</span>
                <h3 class="step-card-title">Pilih Jadwal & Peserta</h3>
                <p class="step-card-desc">
                    Tentukan tanggal jelajah dan jam sesi (Pagi 08.00 atau Siang 13.00), serta jumlah jip atau peserta rombongan.
                </p>
            </div>

            <div class="step-card">
                <span class="step-number-tag">03</span>
                <h3 class="step-card-title">Bayar & Kirim Bukti</h3>
                <p class="step-card-desc">
                    Transfer ke rekening resmi YKS (BCA, Mandiri, BRI, atau QRIS) lalu unggah foto struk transfer Anda dengan praktis.
                </p>
            </div>

            <div class="step-card">
                <span class="step-number-tag">04</span>
                <h3 class="step-card-title">Konfirmasi & Siap Berangkat</h3>
                <p class="step-card-desc">
                    Terima e-tiket dan panduan titik temu (Meeting Point). Tim pemandu siap menyambut Anda langsung di lokasi.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Cuplikan Galeri (Figma: Di Balik Percikan Lumpur) -->
<section class="section-wrapper section-white-bg" id="galeri">
    <div class="container">
        <div class="section-header-row">
            <div>
                <div class="section-tag">
                    ━ &nbsp; DI BALIK PERCIKAN LUMPUR
                </div>
                <h2 class="section-title">Momen nyata dari jalur Sentul.</h2>
                <p class="section-desc">
                    Bukan sekadar foto brosur. Ini adalah potret keseruan riil para penjelajah yang merasakan serunya alam Sentul.
                </p>
            </div>
            <a href="{{ route('gallery') }}" class="btn btn-outline">
                Buka Galeri &nbsp;↗
            </a>
        </div>

        <div class="gallery-mosaic">
            <div class="gallery-mosaic-item">
                <img src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=600&q=80" alt="Offroad 4x4 Sentul">
                <span class="gallery-mosaic-caption">Aksi 4x4 di Jalur Cisadon</span>
            </div>
            <div class="gallery-mosaic-item">
                <img src="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?auto=format&fit=crop&w=600&q=80" alt="Trekking Curug Sentul">
                <span class="gallery-mosaic-caption">Segarnya Air Curug Leuwi Hejo</span>
            </div>
            <div class="gallery-mosaic-item">
                <img src="https://images.unsplash.com/photo-1510312305653-8ed496efae75?auto=format&fit=crop&w=600&q=80" alt="Camping Bukit Paniisan">
                <span class="gallery-mosaic-caption">Senja di Puncak Bukit Paniisan</span>
            </div>
            <div class="gallery-mosaic-item">
                <img src="https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&w=600&q=80" alt="Gowes Sentul">
                <span class="gallery-mosaic-caption">Gowes Pagi di Jalur Rainbow Hills</span>
            </div>
        </div>
    </div>
</section>

<!-- Cerita Peserta (Figma: Cerita Selepas Jelajah) -->
<section class="section-wrapper" id="testimoni">
    <div class="container">
        <div class="section-header-row">
            <div>
                <div class="section-tag">
                    ━ &nbsp; CERITA SELEPAS JELAJAH
                </div>
                <h2 class="section-title">Kata mereka yang sudah mencoba.</h2>
                <p class="section-desc">
                    Cerita jujur dari rombongan teman kantor, keluarga, dan para petualang yang telah kembali dari lintasan Sentul.
                </p>
            </div>
            <a href="{{ route('gallery') }}" class="btn btn-outline">
                Lihat Semua Ulasan &nbsp;↗
            </a>
        </div>

        <div class="testimonials-row">
            @foreach ($testimonials as $t)
                <div class="testi-card">
                    <div>
                        <div class="testi-stars">
                            @for ($i = 0; $i < $t->rating; $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                        </div>
                        <p class="testi-text">"{{ $t->review }}"</p>
                    </div>
                    <div class="testi-author-info">
                        <span class="testi-author-name">{{ $t->name }}</span>
                        <span class="testi-author-meta">{{ $t->city }} · Paket {{ $t->package_name }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Tanya Sebelum Jelajah (Figma: Biar Makin Siap - FAQ) -->
<section class="section-wrapper section-white-bg" id="faq">
    <div class="container">
        <div class="faq-figma-grid">
            <div>
                <div class="section-tag">
                    ━ &nbsp; BIAR MAKIN SIAP
                </div>
                <h2 class="section-title">Pertanyaan yang sering ditanyakan.</h2>
                <p class="section-desc">
                    Admin kami siap membantu memilih perjalanan yang paling tepat dengan kondisi fisik dan rencana rombongan Anda.
                </p>
                <div style="margin-top: 28px;">
                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20mau%20konsultasi%20trip" target="_blank" class="btn btn-forest">
                        <i class="bi bi-whatsapp"></i> Tanya Langsung via WA &nbsp;↗
                    </a>
                </div>
            </div>

            <div class="faq-accordion-list">
                <div class="faq-box active" onclick="toggleFaq(this)">
                    <div class="faq-box-header">
                        <span class="faq-question">Apakah pemula atau anak-anak bisa ikut?</span>
                        <span class="faq-symbol">−</span>
                    </div>
                    <div class="faq-answer">
                        Bisa! Kami memiliki rute trekking pemula seperti <em>Curug Putri Kencana</em> atau <em>Leuwi Hejo</em> dengan jalur landai yang aman untuk usia 6 tahun ke atas. Pemandu kami siap membantu setiap pijakan.
                    </div>
                </div>

                <div class="faq-box" onclick="toggleFaq(this)">
                    <div class="faq-box-header">
                        <span class="faq-question">Bagaimana jika cuaca hujan?</span>
                        <span class="faq-symbol">+</span>
                    </div>
                    <div class="faq-answer">
                        Keseruan offroad dan alam justru semakin terasa saat hujan gerimis! Namun jika terjadi hujan lebat atau debit air sungai meluap tinggi, demi keselamatan rute akan disesuaikan atau dijadwalkan ulang tanpa biaya penalti.
                    </div>
                </div>

                <div class="faq-box" onclick="toggleFaq(this)">
                    <div class="faq-box-header">
                        <span class="faq-question">Apa saja yang perlu dibawa dari rumah?</span>
                        <span class="faq-symbol">+</span>
                    </div>
                    <div class="faq-answer">
                        Disarankan membawa pakaian ganti lengkap, alas kaki outdoor (sandal gunung atau sepatu trekking yang tidak licin), handuk kecil, kantong plastik untuk pakaian basah, serta botol minum pribadi.
                    </div>
                </div>

                <div class="faq-box" onclick="toggleFaq(this)">
                    <div class="faq-box-header">
                        <span class="faq-question">Di mana titik kumpul dan jam keberangkatan?</span>
                        <span class="faq-symbol">+</span>
                    </div>
                    <div class="faq-answer">
                        Titik kumpul berada di Pos Jelajah YKS kawasan Babakan Madang, Sentul (akses tol mudah). Sesi keberangkatan terbagi 2: Sesi Pagi pukul 08.00 WIB dan Sesi Siang pukul 13.00 WIB.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ajakan Bertualang (Bottom Banner CTA) -->
<section class="section-wrapper">
    <div class="container">
        <div class="cta-adventure-banner">
            <div>
                <h2 class="cta-banner-title">Akhir pekan, ke alam saja.</h2>
                <p class="cta-banner-desc">
                    Pilih jalurmu. Sisanya, kita siapkan bersama. Kuota pemandu dan jip akhir pekan terbatas setiap pekannya!
                </p>
            </div>
            <a href="{{ route('booking.create') }}" class="btn btn-forest btn-lg" style="background-color: #1F3D2B; color: #FFFFFF; border: 1px solid rgba(255,255,255,0.2);">
                Reservasi Sekarang &nbsp;↗
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function toggleFaq(element) {
        const isActive = element.classList.contains('active');
        document.querySelectorAll('.faq-box').forEach(box => {
            box.classList.remove('active');
            const sym = box.querySelector('.faq-symbol');
            if (sym) sym.textContent = '+';
        });

        if (!isActive) {
            element.classList.add('active');
            const sym = element.querySelector('.faq-symbol');
            if (sym) sym.textContent = '−';
        }
    }
</script>
@endsection
