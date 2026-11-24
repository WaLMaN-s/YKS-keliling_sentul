@extends('layouts.app', ['title' => 'Galeri & Testimoni'])

@section('content')
<!-- Pembuka Halaman (Figma ON GOING #73:5137 - Galeri & Testimoni · Desktop) -->
<div class="container" style="padding-top: 32px;">
    <div style="font-size: 0.8rem; color: #667066; margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: #667066; text-decoration: none;">Beranda</a> &nbsp;/&nbsp; <span>Galeri & Testimoni</span>
    </div>
    <h1 style="font-size: 2rem; font-weight: 700; color: #6B4F3A; margin-bottom: 20px;">
        GALERI & TESTIMONI
    </h1>

    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; color: #667066; margin-bottom: 24px;">
        <i class="bi bi-arrows-angle-expand"></i>
        <span>Pilih ikon perbesar untuk melihat foto.</span>
    </div>

    <!-- Susunan Foto Bertingkat 3 Kolom (#73:5173) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; align-items: start; margin-bottom: 36px;">
        <!-- Kolom 1 -->
        <div style="display: flex; flex-direction: column; gap: 28px;">
            <div onclick="openLightbox('{{ asset('galeri/Untitled9.jpg') }}', 'Lintas Sungai · Percikan di lintasan sungai')" style="cursor: pointer;">
                <div style="height: 300px; border-radius: 16px; overflow: hidden; position: relative;">
                    <img src="{{ asset('galeri/Untitled9.jpg') }}" alt="Percikan di lintasan sungai" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 12px; right: 12px; width: 36px; height: 36px; border-radius: 10px; background: #F8F5EF; display: flex; align-items: center; justify-content: center; color: #1F3D2B;">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.7rem; font-weight: 600; color: #6B4F3A;">Lintas Sungai</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #1F3D2B;">Percikan di lintasan sungai</div>
                </div>
            </div>

            <div onclick="openLightbox('{{ asset('galeri/Untitled6.jpg') }}', 'Jelajah Senja · Menunggu senja di bukit')" style="cursor: pointer;">
                <div style="height: 420px; border-radius: 16px; overflow: hidden; position: relative;">
                    <img src="{{ asset('galeri/Untitled6.jpg') }}" alt="Menunggu senja di bukit" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 12px; right: 12px; width: 36px; height: 36px; border-radius: 10px; background: #F8F5EF; display: flex; align-items: center; justify-content: center; color: #1F3D2B;">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.7rem; font-weight: 600; color: #6B4F3A;">Jelajah Senja</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #1F3D2B;">Menunggu senja di bukit</div>
                </div>
            </div>
        </div>

        <!-- Kolom 2 -->
        <div style="display: flex; flex-direction: column; gap: 28px;">
            <div onclick="openLightbox('{{ asset('galeri/Untitled10.jpg') }}', 'Momen peserta · Jeda yang jadi cerita')" style="cursor: pointer;">
                <div style="height: 420px; border-radius: 16px; overflow: hidden; position: relative;">
                    <img src="{{ asset('galeri/Untitled10.jpg') }}" alt="Jeda yang jadi cerita" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 12px; right: 12px; width: 36px; height: 36px; border-radius: 10px; background: #F8F5EF; display: flex; align-items: center; justify-content: center; color: #1F3D2B;">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.7rem; font-weight: 600; color: #6B4F3A;">Momen peserta</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #1F3D2B;">Jeda yang jadi cerita</div>
                </div>
            </div>

            <div onclick="openLightbox('{{ asset('galeri/Untitled5.jpg') }}', 'Di balik perjalanan · Siap sebelum berangkat')" style="cursor: pointer;">
                <div style="height: 300px; border-radius: 16px; overflow: hidden; position: relative;">
                    <img src="{{ asset('galeri/Untitled5.jpg') }}" alt="Siap sebelum berangkat" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 12px; right: 12px; width: 36px; height: 36px; border-radius: 10px; background: #F8F5EF; display: flex; align-items: center; justify-content: center; color: #1F3D2B;">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.7rem; font-weight: 600; color: #6B4F3A;">Di balik perjalanan</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #1F3D2B;">Siap sebelum berangkat</div>
                </div>
            </div>
        </div>

        <!-- Kolom 3 -->
        <div style="display: flex; flex-direction: column; gap: 28px;">
            <div onclick="openLightbox('{{ asset('galeri/Untitled3.jpg') }}', 'Jelajah Rimba · Menembus hijau rimba')" style="cursor: pointer;">
                <div style="height: 300px; border-radius: 16px; overflow: hidden; position: relative;">
                    <img src="{{ asset('galeri/Untitled3.jpg') }}" alt="Menembus hijau rimba" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 12px; right: 12px; width: 36px; height: 36px; border-radius: 10px; background: #F8F5EF; display: flex; align-items: center; justify-content: center; color: #1F3D2B;">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.7rem; font-weight: 600; color: #6B4F3A;">Jelajah Rimba</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #1F3D2B;">Menembus hijau rimba</div>
                </div>
            </div>

            <div onclick="openLightbox('{{ asset('galeri/Untitled8.jpg') }}', 'Jelajah Rimba · Jejak petualangan hari ini')" style="cursor: pointer;">
                <div style="height: 420px; border-radius: 16px; overflow: hidden; position: relative;">
                    <img src="{{ asset('galeri/Untitled8.jpg') }}" alt="Jejak petualangan hari ini" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; bottom: 12px; right: 12px; width: 36px; height: 36px; border-radius: 10px; background: #F8F5EF; display: flex; align-items: center; justify-content: center; color: #1F3D2B;">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <div style="font-size: 0.7rem; font-weight: 600; color: #6B4F3A;">Jelajah Rimba</div>
                    <div style="font-size: 1rem; font-weight: 600; color: #1F3D2B;">Jejak petualangan hari ini</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Foto Tambahan yang Dapat Dimuat -->
    <div id="extraGalleryGrid" style="display: none; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 36px;">
        <div onclick="openLightbox('{{ asset('galeri/Untitled.jpg') }}', 'Trabas Trail · Spot Curug Sentul')" style="cursor: pointer;">
            <div style="height: 280px; border-radius: 16px; overflow: hidden;">
                <img src="{{ asset('galeri/Untitled.jpg') }}" alt="Spot Curug" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="margin-top: 8px; font-weight: 600; color: #1F3D2B;">Spot Curug & Unit Trail</div>
        </div>
        <div onclick="openLightbox('{{ asset('galeri/7.jpg') }}', 'Tanjakan Bukit Hambalang')" style="cursor: pointer;">
            <div style="height: 280px; border-radius: 16px; overflow: hidden;">
                <img src="{{ asset('galeri/7.jpg') }}" alt="Tanjakan Bukit Hambalang" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="margin-top: 8px; font-weight: 600; color: #1F3D2B;">Rombongan Riders di Bukit Hambalang</div>
        </div>
        <div onclick="openLightbox('{{ asset('galeri/Untitled4.jpg') }}', 'Jeep 4x4 di Puncak Bukit')" style="cursor: pointer;">
            <div style="height: 280px; border-radius: 16px; overflow: hidden;">
                <img src="{{ asset('galeri/Untitled4.jpg') }}" alt="Jeep 4x4 di Puncak Bukit" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="margin-top: 8px; font-weight: 600; color: #1F3D2B;">Wisata Jeep 4x4 Puncak Bukit</div>
        </div>
    </div>

    <!-- Tombol Muat Lebih Banyak (#73:5232) -->
    <div style="display: flex; justify-content: center; margin-bottom: 64px;">
        <button type="button" id="loadMoreBtn" onclick="toggleMorePhotos()"
                style="background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; min-height: 50px; padding: 14px 24px; font-size: 0.9rem; font-weight: 700; color: #1F3D2B; cursor: pointer;">
            Muat Lebih Banyak &nbsp;+
        </button>
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

<!-- Ulasan Perjalanan Section (Figma ON GOING #73:5235) -->
<section style="background: #EAF0E7; padding: 80px 0;" id="testimoni">
    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; flex-wrap: wrap; margin-bottom: 32px;">
            <div style="flex: 1; min-width: 280px;">
                <h2 style="font-family: var(--font-heading); font-size: 2.75rem; font-weight: 800; color: #1F3D2B; line-height: 1.15; margin-bottom: 12px;">
                    Yang tinggal setelah perjalanan.
                </h2>
                <p style="font-size: 1rem; color: #667066; margin: 0;">
                    Ulasan berikut adalah cerita pengalaman peserta bersama Yuk Keliling Sentul.
                </p>
            </div>

            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                <!-- Filter Penilaian (#73:5241) -->
                <form method="GET" action="{{ route('gallery') }}#testimoni">
                    <div style="background: #FFFFFF; border-radius: 12px; padding: 10px 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-star-fill" style="color: #F26B1D;"></i>
                        <select name="penilaian" onchange="this.form.submit()" style="border: none; background: transparent; font-size: 0.85rem; color: #1F3D2B; font-weight: 600; outline: none; cursor: pointer;">
                            <option value="">Semua penilaian</option>
                            <option value="5" {{ ($ratingFilter ?? '') == '5' ? 'selected' : '' }}>5 Bintang (★★★★★)</option>
                            <option value="4" {{ ($ratingFilter ?? '') == '4' ? 'selected' : '' }}>4 Bintang (★★★★)</option>
                            <option value="3" {{ ($ratingFilter ?? '') == '3' ? 'selected' : '' }}>3 Bintang (★★★)</option>
                            <option value="2" {{ ($ratingFilter ?? '') == '2' ? 'selected' : '' }}>2 Bintang (★★)</option>
                            <option value="1" {{ ($ratingFilter ?? '') == '1' ? 'selected' : '' }}>1 Bintang (★)</option>
                            <option value="terendah" {{ ($ratingFilter ?? '') == 'terendah' ? 'selected' : '' }}>Urutkan: Bintang Terendah</option>
                            <option value="tertinggi" {{ ($ratingFilter ?? '') == 'tertinggi' ? 'selected' : '' }}>Urutkan: Bintang Tertinggi</option>
                        </select>
                    </div>
                </form>

                <!-- Tombol ke Halaman Tulis Ulasan (#165:303) -->
                <a href="{{ route('reviews.create') }}"
                   style="display: inline-flex; align-items: center; gap: 8px; background: #F26B1D; color: #1A1A1A; font-weight: 700; font-size: 0.88rem; padding: 13px 20px; border-radius: 12px; text-decoration: none;">
                    Tulis ulasanmu &nbsp;↗
                </a>
            </div>
        </div>

        <!-- Daftar Testimoni (#73:5247) -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
            @forelse ($testimonials as $t)
                <div style="background: #FFFFFF; border-radius: 16px; padding: 28px; display: flex; flex-direction: column; justify-content: space-between; gap: 20px;">
                    <div>
                        <div style="color: #F26B1D; font-size: 1.1rem; letter-spacing: 2px; margin-bottom: 14px;">
                            @for ($i = 0; $i < $t->rating; $i++)★@endfor
                        </div>
                        <p style="font-size: 1rem; color: #1A1A1A; line-height: 1.65; margin: 0;">
                            {{ $t->review }}
                        </p>
                    </div>

                    <div>
                        <div style="height: 1px; background: #DEDED4; margin-bottom: 14px;"></div>
                        <div style="font-size: 0.85rem; font-weight: 700; color: #1F3D2B;">{{ $t->name }}</div>
                        <div style="font-size: 0.74rem; color: #667066; margin-top: 2px;">
                            {{ $t->package_name }} · {{ $t->city ?: 'November 2026' }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; background: #FFFFFF; border-radius: 16px; padding: 36px; text-align: center; color: #667066;">
                    Belum ada ulasan pada kategori penilaian ini.
                </div>
            @endforelse
        </div>

        <!-- Navigasi Ulasan (#73:5263) -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; gap: 8px;">
                <a href="#testimoni" style="background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; padding: 12px 20px; font-size: 0.88rem; font-weight: 700; color: #1F3D2B; text-decoration: none;">
                    Sebelum &nbsp;←
                </a>
                <a href="#testimoni" style="background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 12px; padding: 12px 20px; font-size: 0.88rem; font-weight: 700; color: #1F3D2B; text-decoration: none;">
                    Berikut &nbsp;→
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function toggleMorePhotos() {
        const grid = document.getElementById('extraGalleryGrid');
        const btn = document.getElementById('loadMoreBtn');
        if (!grid) return;
        if (grid.style.display === 'none' || grid.style.display === '') {
            grid.style.display = 'grid';
            if (btn) btn.textContent = 'Sembunyikan Foto Tambahan −';
        } else {
            grid.style.display = 'none';
            if (btn) btn.textContent = 'Muat Lebih Banyak +';
        }
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
