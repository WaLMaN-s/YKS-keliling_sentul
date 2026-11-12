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
        Kumpulan dokumentasi asli dari para penjelajah, komunitas motor trail, dan rombongan keluarga yang telah menembus jalur Hambalang serta sungai Sentul bersama Yuk Keliling Sentul.
    </p>

    <!-- Filter Buttons -->
    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 32px;">
        <button class="btn btn-forest btn-sm" onclick="filterGallery('all', this)">Semua Dokumentasi (11)</button>
        <button class="btn btn-outline btn-sm" onclick="filterGallery('trail', this)">Trabas Motor Trail</button>
        <button class="btn btn-outline btn-sm" onclick="filterGallery('jeep', this)">Wisata Jeep 4x4</button>
        <button class="btn btn-outline btn-sm" onclick="filterGallery('sungai', this)">Jalur Sungai & Curug</button>
    </div>

    <!-- Gallery Grid (11 Foto Asli dari public/galeri) -->
    <div class="gallery-mosaic" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail sungai" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled.jpg') }}', 'Spot Curug & Unit Trail Yuk Keliling Sentul Hambalang')">
            <img src="{{ asset('galeri/Untitled.jpg') }}" alt="Spot Curug & Unit Trail Yuk Keliling Sentul" loading="lazy">
            <span class="gallery-mosaic-caption">Spot Curug & Unit Trail YKS Hambalang</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="jeep" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled3.jpg') }}', 'Konvoi Wisata Jeep 4x4 Menembus Jalur Lumpur Sentul')">
            <img src="{{ asset('galeri/Untitled3.jpg') }}" alt="Konvoi Wisata Jeep 4x4 Sentul" loading="lazy">
            <span class="gallery-mosaic-caption">Konvoi Wisata Jeep 4x4 Jalur Hutan Sentul</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail sungai" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled9.jpg') }}', 'Aksi Trabas Motor Trail Menerobos Aliran Sungai Sentul')">
            <img src="{{ asset('galeri/Untitled9.jpg') }}" alt="Trabas Motor Trail Sungai Sentul" loading="lazy">
            <span class="gallery-mosaic-caption">Aksi Trabas Menerobos Aliran Sungai Sentul</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="jeep sungai" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled8.jpg') }}', 'Keseruan Rombongan Wisata Jeep 4x4 di Sungai Berbatu')">
            <img src="{{ asset('galeri/Untitled8.jpg') }}" alt="Wisata Jeep 4x4 di Sungai Sentul" loading="lazy">
            <span class="gallery-mosaic-caption">Keseruan Wisata Jeep 4x4 di Sungai Sentul</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/7.jpg') }}', 'Rombongan Riders di Puncak Jalur Tanjakan Hambalang')">
            <img src="{{ asset('galeri/7.jpg') }}" alt="Rombongan Riders di Jalur Hambalang" loading="lazy">
            <span class="gallery-mosaic-caption">Rombongan Riders di Tanjakan Bukit Hambalang</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled6.jpg') }}', 'Panorama Perbukitan Hambalang Bersama Unit KLX')">
            <img src="{{ asset('galeri/Untitled6.jpg') }}" alt="Panorama Perbukitan Hambalang" loading="lazy">
            <span class="gallery-mosaic-caption">Panorama Perbukitan Hambalang dari Ketinggian</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="jeep" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled4.jpg') }}', 'Serunya Wisata Jeep 4x4 Saat Cuaca Sejuk di Puncak Bukit')">
            <img src="{{ asset('galeri/Untitled4.jpg') }}" alt="Wisata Jeep 4x4 Kuning Puncak Bukit" loading="lazy">
            <span class="gallery-mosaic-caption">Serunya Jeep 4x4 di Puncak Bukit Sentul</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled5.jpg') }}', 'Perlengkapan Safety Lengkap di Jalur Hutan Pinus Sentul')">
            <img src="{{ asset('galeri/Untitled5.jpg') }}" alt="Riders di Hutan Pinus Sentul" loading="lazy">
            <span class="gallery-mosaic-caption">Jalur Hutan Pinus dengan Gear Safety Lengkap</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled10.jpg') }}', 'Komunitas & Rombongan Trabas Bersama Leader YKS')">
            <img src="{{ asset('galeri/Untitled10.jpg') }}" alt="Rombongan Trabas Bersama Leader YKS" loading="lazy">
            <span class="gallery-mosaic-caption">Rombongan Trabas Bersama Leader YKS</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled2.jpg') }}', 'Menikmati Lanskap Pegunungan Sentul dari Atas Motor Trail')">
            <img src="{{ asset('galeri/Untitled2.jpg') }}" alt="Lanskap Pegunungan Sentul" loading="lazy">
            <span class="gallery-mosaic-caption">Lanskap Pegunungan Sentul & Hambalang</span>
        </div>

        <div class="gallery-mosaic-item gallery-item-filter" data-cat="trail" style="height: 340px; cursor: pointer;" onclick="openLightbox('{{ asset('galeri/Untitled1.jpg') }}', 'Lady Riders Siap Menaklukkan Rute Trabas Pemula')">
            <img src="{{ asset('galeri/Untitled1.jpg') }}" alt="Lady Riders Trabas Sentul" loading="lazy">
            <span class="gallery-mosaic-caption">Lady Riders di Jalur Trabas Pemula YKS</span>
        </div>
    </div>
</div>

<!-- Lightbox Modal Preview -->
<div id="galleryLightbox" onclick="closeLightbox(event)" style="display: none; position: fixed; inset: 0; background: rgba(14, 33, 24, 0.88); backdrop-filter: blur(6px); z-index: 4000; align-items: center; justify-content: center; padding: 20px;">
    <div style="position: relative; max-width: 900px; width: 100%; text-align: center;">
        <button type="button" onclick="closeLightbox(event, true)" style="position: absolute; top: -44px; right: 0; background: #FFFFFF; border: none; width: 38px; height: 38px; border-radius: 50%; font-size: 1.1rem; color: var(--primary); cursor: pointer; display: inline-flex; align-items: center; justify-content: center;">
            <i class="bi bi-x-lg"></i>
        </button>
        <img id="lightboxImg" src="" alt="Preview Dokumentasi YKS" style="max-height: 80vh; max-width: 100%; width: auto; margin: 0 auto; border-radius: 14px; box-shadow: 0 20px 50px rgba(0,0,0,0.5);">
        <p id="lightboxCaption" style="color: #FFFFFF; font-weight: 600; font-size: 1rem; margin-top: 14px;"></p>
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

        <div class="testimonials-row">
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
                        <span class="testi-author-meta">{{ $t->city }} · {{ $t->package_name }}</span>
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
                    Pilih paket trabas trail atau wisata jeep 4x4 favoritmu dan rasakan langsung keseruannya akhir pekan ini.
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
            const cats = (item.getAttribute('data-cat') || '').split(' ');
            if (category === 'all' || cats.includes(category)) {
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

    function openLightbox(src, caption) {
        const modal = document.getElementById('galleryLightbox');
        const img = document.getElementById('lightboxImg');
        const cap = document.getElementById('lightboxCaption');
        if (!modal || !img) return;
        img.src = src;
        if (cap) cap.textContent = caption || '';
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(event, force = false) {
        if (force || event.target.id === 'galleryLightbox') {
            const modal = document.getElementById('galleryLightbox');
            if (modal) modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
</script>
@endsection
