@extends('layouts.app')

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container pkg-detail-top">
    <div class="pkg-breadcrumb">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; <span>Katalog Paket</span>
    </div>
    <div class="section-tag">
        ━ &nbsp; PILIHAN JELAJAH SENTUL
    </div>
    <h1 class="section-title" style="max-width: 820px; margin-bottom: 10px;">
        Pricelist Resmi Trabas Trail & Wisata Jeep Sentul.
    </h1>
    <p class="section-desc" style="max-width: 660px; margin-bottom: 22px;">
        Pilih paket sewa trail Hambalang, bawa motor pribadi, atau wisata Jeep 4x4 &amp; Trekking Curug Sentul.
    </p>

    <!-- Search & Quick Filter Pills -->
    <div class="pkg-filter-card">
        <form method="GET" action="{{ route('packages.index') }}" class="pkg-filter-form">
            <div style="flex-grow: 1; position: relative; min-width: 200px;">
                <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama curug, offroad, atau aktivitas..." 
                       class="form-control" style="background-color: var(--bg-body); padding-left: 42px;">
                <i class="bi bi-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
            </div>
            @if(request('kategori'))
                <input type="hidden" name="kategori" value="{{ request('kategori') }}">
            @endif
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-forest" style="padding: 11px 22px;">Cari Paket</button>
                @if(request('cari') || request('kategori'))
                    <a href="{{ route('packages.index') }}" class="btn btn-outline" style="padding: 11px 18px;">Reset</a>
                @endif
            </div>
        </form>

        <div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
            <span style="font-size: 0.78rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; margin-right: 4px;">Kategori:</span>
            <a href="{{ route('packages.index', request()->except('kategori')) }}" 
               class="btn btn-sm {{ !request('kategori') ? 'btn-forest' : 'btn-outline' }}">
                Semua Paket
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('packages.index', array_merge(request()->all(), ['kategori' => $cat->slug])) }}" 
                   class="btn btn-sm {{ request('kategori') === $cat->slug ? 'btn-forest' : 'btn-outline' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Tour Cards Grid Section -->
<section class="section-wrapper" style="padding-top: 0;">
    <div class="container">
        @if ($packages->isEmpty())
            <div style="text-align: center; padding: 64px 20px; background: #FFFFFF; border-radius: var(--radius-lg); border: 1px dashed var(--border);">
                <i class="bi bi-compass text-muted" style="font-size: 52px; color: var(--text-sub);"></i>
                <h3 style="margin-top: 16px; color: var(--primary);">Tidak ada paket yang sesuai kriteria</h3>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Silakan atur ulang kata kunci pencarian Anda atau hubungi admin kami.</p>
                <a href="{{ route('packages.index') }}" class="btn btn-forest">Lihat Semua Paket</a>
            </div>
        @else
            <div class="packages-grid">
                @foreach ($packages as $pkg)
                    <div class="package-card">
                        <div class="package-card-media">
                            <img src="{{ $pkg->image ?: 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $pkg->name }}" loading="lazy">
                            <span class="package-badge-tag">
                                @if ($pkg->difficulty === 'Mudah')
                                    Santai & Pemula
                                @elseif ($pkg->difficulty === 'Sedang')
                                    Menengah Favorit
                                @else
                                    Tantangan Ekstrem
                                @endif
                            </span>
                        </div>
                        <div class="package-card-content">
                            <span class="package-category-chip">{{ $pkg->category->name ?? 'Wisata Alam' }}</span>
                            <h3 class="package-name">{{ $pkg->name }}</h3>
                            <p class="package-desc">{{ Str::limit($pkg->description, 72) }}</p>
                            
                            <div class="package-meta-row">
                                <span class="package-meta-item"><i class="bi bi-clock"></i> {{ $pkg->duration }}</span>
                                <span class="package-meta-item"><i class="bi bi-signpost-2"></i> {{ $pkg->distance }}</span>
                                <span class="package-meta-item pkg-meta-loc"><i class="bi bi-geo-alt"></i> Sentul</span>
                            </div>

                            <div class="package-bottom-row">
                                <div class="package-price-wrap">
                                    <span class="package-price-label">{{ str_contains(strtolower($pkg->name), 'jeep') ? 'Harga / Unit (3-4 Org)' : 'Harga / Orang' }}</span>
                                    <span class="package-price-val">Rp {{ number_format($pkg->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="package-card-actions">
                                    <a href="{{ route('packages.show', $pkg->slug) }}" class="btn btn-outline btn-sm">
                                        Detail
                                    </a>
                                    <a href="{{ route('booking.create', ['paket_id' => $pkg->id]) }}" class="btn btn-primary btn-sm">
                                        Pesan &nbsp;↗
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Bantuan Memilih Section (Figma: Bantuan Memilih) -->
        <div class="pkg-help-banner">
            <div>
                <span class="section-tag" style="margin-bottom: 6px;">━ &nbsp; KONSULTASI RUTE</span>
                <h3 class="pkg-section-heading" style="margin-bottom: 6px;">Masih ragu memilih rute yang pas?</h3>
                <p style="font-size: 0.92rem; color: var(--text-muted); max-width: 620px;">
                    Sampaikan usia rombongan, kondisi fisik, atau preferensi kendaraan Anda. Admin kami akan menyarankan opsi terbaik.
                </p>
            </div>
            <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20bisa%20bantu%20rekomendasi%20paket%20yang%20cocok%20untuk%20rombongan%20saya" target="_blank" class="btn btn-forest">
                <i class="bi bi-whatsapp"></i> Chat WhatsApp &nbsp;↗
            </a>
        </div>
    </div>
</section>
@endsection
