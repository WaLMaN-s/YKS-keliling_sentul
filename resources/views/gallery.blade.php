@extends('layouts.app')

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; <span>Galeri & Testimoni</span>
    </div>
    <div class="section-tag">
        ━ &nbsp; DOKUMENTASI & CERITA PENJELAJAH
    </div>
    <h1 class="section-title" style="font-size: 2.8rem; max-width: 800px; margin-bottom: 16px;">
        Setiap percikan lumpur punya cerita.
    </h1>
    <p class="section-desc" style="font-size: 1.15rem; max-width: 720px; margin-bottom: 36px;">
        Kumpulan momen nyata dari para wisatawan, komunitas, dan rombongan keluarga yang telah menembus jalur perbukitan serta sungai Sentul bersama kami.
    </p>

    <!-- Filter Buttons -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 32px;">
        <button class="btn btn-forest btn-sm" onclick="filterGallery('all', this)">Semua Momen</button>
        <button class="btn btn-outline btn-sm" onclick="filterGallery('offroad', this)">Offroad 4x4</button>
        <button class="btn btn-outline btn-sm" onclick="filterGallery('curug', this)">Trekking Curug</button>
        <button class="btn btn-outline btn-sm" onclick="filterGallery('gowes', this)">Gowes Sepeda</button>
        <button class="btn btn-outline btn-sm" onclick="filterGallery('camping', this)">Sunset & Camping</button>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-mosaic" style="grid-template-columns: repeat(3, 1fr); gap: 24px;">
        <div class="gallery-mosaic-item gallery-item-filter" data-cat="offroad" style="height: 300px;">
            <img src="https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80" alt="Jip 4x4 di Jalur Sentul">
            <span class="gallery-mosaic-caption">Aksi Menembus Jalur Tanah Merah Cisadon</span>
        </div>
        <div class="gallery-mosaic-item gallery-item-filter" data-cat="curug" style="height: 300px;">
            <img src="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?auto=format&fit=crop&w=800&q=80" alt="Curug Leuwi Hejo">
            <span class="gallery-mosaic-caption">Segarnya Kolam Alami Leuwi Hejo</span>
        </div>
        <div class="gallery-mosaic-item gallery-item-filter" data-cat="camping" style="height: 300px;">
            <img src="https://images.unsplash.com/photo-1510312305653-8ed496efae75?auto=format&fit=crop&w=800&q=80" alt="Sunset Camping">
            <span class="gallery-mosaic-caption">Matahari Terbenam di Bukit Paniisan</span>
        </div>
        <div class="gallery-mosaic-item gallery-item-filter" data-cat="gowes" style="height: 300px;">
            <img src="https://images.unsplash.com/photo-1473496169904-658ba7c44d8a?auto=format&fit=crop&w=800&q=80" alt="Gowes Sentul">
            <span class="gallery-mosaic-caption">Rombongan Gowes KM 0 Sentul</span>
        </div>
        <div class="gallery-mosaic-item gallery-item-filter" data-cat="curug" style="height: 300px;">
            <img src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=800&q=80" alt="Curug Hordeng">
            <span class="gallery-mosaic-caption">Trekking Pematang Sawah & Curug Kembar</span>
        </div>
        <div class="gallery-mosaic-item gallery-item-filter" data-cat="offroad" style="height: 300px;">
            <img src="https://images.unsplash.com/photo-1506015391300-4802dc74de2e?auto=format&fit=crop&w=800&q=80" alt="Lintasan Sungai 4x4">
            <span class="gallery-mosaic-caption">Cipratan Air saat Menyeberang Sungai Sentul</span>
        </div>
    </div>
</div>

<!-- Testimoni Lengkap Section -->
<section class="section-wrapper section-sage-bg" style="margin-top: 64px;">
    <div class="container">
        <div class="section-tag">
            ━ &nbsp; ULASAN LENGKAP PENJELAJAH
        </div>
        <h2 class="section-title">Apa kata mereka tentang pengalaman bersama YKS?</h2>
        <p class="section-desc" style="margin-bottom: 40px;">
            Ulasan jujur tanpa rekayasa dari wisatawan yang telah membuktikan profesionalisme pemandu dan keseruan rute kami.
        </p>

        <div class="testimonials-row" style="grid-template-columns: repeat(3, 1fr);">
            @forelse ($testimonials as $t)
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
            @empty
                <p>Belum ada ulasan yang ditampilkan.</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Bottom CTA Banner -->
<section class="section-wrapper">
    <div class="container">
        <div class="cta-adventure-banner">
            <div>
                <h2 class="cta-banner-title">Abadikan ceritamu di Sentul selanjutnya.</h2>
                <p class="cta-banner-desc">
                    Pilih paket yang paling Anda suka dan siapkan diri merasakan sensasi bertualang di alam terbuka Sentul.
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
    function filterGallery(category, btnElement) {
        document.querySelectorAll('.gallery-item-filter').forEach(item => {
            if (category === 'all' || item.getAttribute('data-cat') === category) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        const buttons = btnElement.parentElement.querySelectorAll('button');
        buttons.forEach(b => {
            b.classList.remove('btn-forest');
            b.classList.add('btn-outline');
        });
        btnElement.classList.remove('btn-outline');
        btnElement.classList.add('btn-forest');
    }
</script>
@endsection
