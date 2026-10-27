@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-tag">
            <i class="bi bi-compass"></i> Official Local Guide Sentul, Bogor
        </div>
        <h1 class="hero-title">
            Jelajahi Pesona Alam & Curug<br>Tersembunyi di <span class="highlight" style="color: #6ee7b7;">Sentul</span>
        </h1>
        <p class="hero-desc">
            Nikmati segarnya air toska pegunungan, pematang sawah hijau, hingga tantangan offroad 4x4 bersama tim pemandu lokal berlisensi dan berpengalaman. Aman, seru, dan ramah keluarga!
        </p>
        <div class="hero-buttons">
            <a href="#paket" class="btn btn-primary btn-lg">
                <i class="bi bi-tree"></i> Lihat Pilihan Paket
            </a>
            <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20mau%20konsultasi%20trip%20ke%20Sentul" target="_blank" class="btn btn-outline btn-lg" style="color:#fff; border-color: rgba(255,255,255,0.4);">
                <i class="bi bi-whatsapp"></i> Konsultasi Gratis
            </a>
        </div>

        <!-- Key Stats -->
        <div class="hero-stats">
            <div class="stat-item">
                <h3>1.500+</h3>
                <p>Peserta Terlayani</p>
            </div>
            <div class="stat-item">
                <h3>15+</h3>
                <p>Curug & Spot Eksotis</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Safety & Local Guide</p>
            </div>
            <div class="stat-item">
                <h3>5.0 ★</h3>
                <p>Kepuasan Wisatawan</p>
            </div>
        </div>
    </div>
</section>

<!-- Paket Wisata Section -->
<section class="section" id="paket">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Eksplorasi Sentul</span>
            <h2 class="section-title">Pilihan Paket Wisata Favorit</h2>
            <p class="section-desc">Pilih petualangan terbaik Anda mulai dari trekking curug santai pemula, rute sepeda tanjakan, hingga uji adrenalin offroad 4x4.</p>
        </div>

        <!-- Category Filter -->
        <div class="filter-pills">
            <button class="filter-pill active" onclick="filterCategory('all', this)">Semua Kategori</button>
            @foreach ($categories as $cat)
                <button class="filter-pill" onclick="filterCategory('{{ $cat->slug }}', this)">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>

        <!-- Tour Cards Grid -->
        <div class="cards-grid">
            @foreach ($packages as $pkg)
                <div class="tour-card tour-card-item" data-category="{{ $pkg->category->slug ?? '' }}">
                    <div class="tour-card-img">
                        <img src="{{ $pkg->image ?: 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $pkg->name }}" loading="lazy">
                        <div class="card-badge-top">
                            @if ($pkg->difficulty === 'Mudah')
                                <span class="badge badge-easy"><i class="bi bi-shield-check"></i> Mudah / Pemula</span>
                            @elseif ($pkg->difficulty === 'Sedang')
                                <span class="badge badge-medium"><i class="bi bi-lightning-charge"></i> Sedang</span>
                            @else
                                <span class="badge badge-hard"><i class="bi bi-fire"></i> Menantang</span>
                            @endif
                        </div>
                        <div class="card-price-tag">
                            Rp {{ number_format($pkg->price, 0, ',', '.') }} <small>/ pax</small>
                        </div>
                    </div>
                    <div class="tour-card-body">
                        <h3 class="card-title">{{ $pkg->name }}</h3>
                        <p class="card-desc">{{ $pkg->description }}</p>
                        
                        <div class="card-meta">
                            <span class="card-meta-item"><i class="bi bi-clock-history"></i> {{ $pkg->duration }}</span>
                            <span class="card-meta-item"><i class="bi bi-signpost-2"></i> {{ $pkg->distance }}</span>
                            <span class="card-meta-item"><i class="bi bi-geo-alt"></i> Sentul</span>
                        </div>

                        <div class="card-action">
                            <a href="{{ route('packages.show', $pkg->slug) }}" class="btn btn-outline btn-block">
                                <i class="bi bi-info-circle"></i> Detail
                            </a>
                            <a href="{{ route('booking.create', ['paket_id' => $pkg->id]) }}" class="btn btn-primary btn-block">
                                <i class="bi bi-calendar-check"></i> Booking
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Keunggulan Section -->
<section class="section section-bg" id="keunggulan">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Kenapa Memilih YKS</span>
            <h2 class="section-title">Standar Wisata Terbaik di Sentul</h2>
            <p class="section-desc">Kenyamanan dan keselamatan petualangan Anda adalah komitmen utama tim Yuk Keliling Sentul.</p>
        </div>

        <div class="features-grid">
            <div class="feature-box">
                <div class="feature-icon"><i class="bi bi-person-badge-fill"></i></div>
                <h3 class="feature-title">Local Guide Berpengalaman</h3>
                <p class="feature-text">Dipandu warga asli Sentul yang menguasai setiap jalur pintas, kondisi sungai, dan spot terindah yang jarang diketahui publik.</p>
            </div>

            <div class="feature-box">
                <div class="feature-icon"><i class="bi bi-shield-shaded"></i></div>
                <h3 class="feature-title">Peralatan & Safety P3K</h3>
                <p class="feature-text">Gratis peminjaman tongkat trekking (trekking pole), jas hujan darurat, pelampung anak, dan perlengkapan P3K medis lapangan.</p>
            </div>

            <div class="feature-box">
                <div class="feature-icon"><i class="bi bi-camera-fill"></i></div>
                <h3 class="feature-title">Dokumentasi Foto Keren</h3>
                <p class="feature-text">Guide kami siap membantu mengambil momen foto terbaik dan estetik untuk kebutuhan media sosial atau kenangan keluarga Anda.</p>
            </div>

            <div class="feature-box">
                <div class="feature-icon"><i class="bi bi-cash-coin"></i></div>
                <h3 class="feature-title">Harga Jujur & Transparan</h3>
                <p class="feature-text">Semua tiket masuk curug, retribusi, air mineral, dan biaya guide sudah termasuk dalam satu paket tanpa pungli tersembunyi.</p>
            </div>
        </div>
    </div>
</section>

<!-- Cara Pemesanan -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Alur Mudah</span>
            <h2 class="section-title">3 Langkah Mudah Berpetualang</h2>
            <p class="section-desc">Pesan trip liburan akhir pekan Anda dalam hitungan menit.</p>
        </div>

        <div class="features-grid">
            <div class="feature-box text-center" style="text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 20px;"><i class="bi bi-1-circle-fill"></i></div>
                <h3 class="feature-title">Pilih Paket Wisata</h3>
                <p class="feature-text">Tentukan destinasi curug, rute offroad, atau gowes yang sesuai dengan tingkat fisik dan waktu Anda.</p>
            </div>

            <div class="feature-box text-center" style="text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 20px;"><i class="bi bi-2-circle-fill"></i></div>
                <h3 class="feature-title">Isi Form & Bayar</h3>
                <p class="feature-text">Masukkan tanggal keberangkatan dan jumlah peserta. Bayar mudah via Transfer Bank atau QRIS resmi kami.</p>
            </div>

            <div class="feature-box text-center" style="text-align: center;">
                <div class="feature-icon" style="margin: 0 auto 20px;"><i class="bi bi-3-circle-fill"></i></div>
                <h3 class="feature-title">Kumpul & Berangkat!</h3>
                <p class="feature-text">Tim guide kami akan menyambut Anda di meeting point Sentul. Siap bersenang-senang menyatu dengan alam!</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimoni Pelanggan -->
<section class="section section-bg">
    <div class="container">
        <div class="section-header">
            <span class="section-subtitle">Cerita Wisatawan</span>
            <h2 class="section-title">Apa Kata Mereka Tentang YKS</h2>
            <p class="section-desc">Ulasan jujur dari teman-teman yang telah menjelajahi Sentul bersama kami.</p>
        </div>

        <div class="features-grid">
            @foreach ($testimonials as $t)
                <div class="feature-box" style="border-left: 4px solid var(--primary);">
                    <div style="color: #f59e0b; margin-bottom: 12px; font-size: 1.1rem;">
                        @for ($i=0; $i<$t->rating; $i++) <i class="bi bi-star-fill"></i> @endfor
                    </div>
                    <p style="font-style: italic; margin-bottom: 16px; color: var(--text-main);">"{{ $t->review }}"</p>
                    <div>
                        <strong style="display:block; color: var(--dark);">{{ $t->name }}</strong>
                        <small style="color: var(--text-muted);">{{ $t->city }} &bull; Paket {{ $t->package_name }}</small>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section" id="faq">
    <div class="container" style="max-width: 800px;">
        <div class="section-header">
            <span class="section-subtitle">FAQ</span>
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
            <p class="section-desc">Informasi seputar persiapan dan teknis trekking di Sentul.</p>
        </div>

        <div class="faq-accordion" style="display: flex; flex-direction: column; gap: 14px;">
            <div class="feature-box faq-item active" style="padding: 20px 24px; cursor: pointer;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--dark); display: flex; justify-content: space-between;">
                    <span>Apakah aman untuk anak-anak dan lansia?</span>
                    <i class="bi bi-chevron-down"></i>
                </h4>
                <p style="margin-top: 10px; color: var(--text-muted); font-size: 0.92rem;">
                    Sangat aman! Kami memiliki paket khusus kategori <strong>Mudah</strong> seperti <em>Curug Putri Kencana</em> atau <em>Leuwi Hejo</em> dengan jalur yang landai, sungai dangkal, dan guide khusus pendamping keluarga.
                </p>
            </div>

            <div class="feature-box faq-item" style="padding: 20px 24px; cursor: pointer;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--dark); display: flex; justify-content: space-between;">
                    <span>Pakaian dan sepatu apa yang disarankan?</span>
                    <i class="bi bi-chevron-down"></i>
                </h4>
                <p style="margin-top: 10px; color: var(--text-muted); font-size: 0.92rem;">
                    Gunakan pakaian olahraga yang cepat kering (dry-fit) dan sandal gunung atau sepatu trekking yang tidak licin di bebatuan basah. Bawa juga pakaian ganti lengkap dan kantong plastik untuk pakaian basah.
                </p>
            </div>

            <div class="feature-box faq-item" style="padding: 20px 24px; cursor: pointer;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--dark); display: flex; justify-content: space-between;">
                    <span>Di mana titik kumpul (meeting point) nya?</span>
                    <i class="bi bi-chevron-down"></i>
                </h4>
                <p style="margin-top: 10px; color: var(--text-muted); font-size: 0.92rem;">
                    Meeting point tergantung paket yang Anda pilih, umumnya di Rest Area Leuwi Hejo atau Babakan Madang Sentul. Kami akan mengirimkan share location WhatsApp yang akurat setelah booking terkonfirmasi.
                </p>
            </div>

            <div class="feature-box faq-item" style="padding: 20px 24px; cursor: pointer;">
                <h4 style="font-size: 1.05rem; font-weight: 700; color: var(--dark); display: flex; justify-content: space-between;">
                    <span>Bagaimana jika terjadi cuaca hujan lebat?</span>
                    <i class="bi bi-chevron-down"></i>
                </h4>
                <p style="margin-top: 10px; color: var(--text-muted); font-size: 0.92rem;">
                    Keselamatan adalah prioritas nomor satu. Jika terjadi luapan air sungai sebelum trip, Anda dapat me-reschedule (menjadwalkan ulang) tanggal keberangkatan tanpa biaya penalti.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section style="background: linear-gradient(135deg, var(--dark), #065f46); color: #fff; padding: 60px 0; text-align: center;">
    <div class="container">
        <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 16px;">Siap Menikmati Liburan Segar di Sentul?</h2>
        <p style="color: #cbd5e1; max-width: 600px; margin: 0 auto 28px; font-size: 1.05rem;">
            Ajak keluarga, teman kantor, atau komunitasmu. Booking tanggal sekarang sebelum kuota guide penuh di akhir pekan!
        </p>
        <div style="display: flex; justify-content: center; gap: 14px; flex-wrap: wrap;">
            <a href="{{ route('packages.index') }}" class="btn btn-primary btn-lg">Pilih Paket Sekarang</a>
            <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}" target="_blank" class="btn btn-accent btn-lg"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a>
        </div>
    </div>
</section>
@endsection
