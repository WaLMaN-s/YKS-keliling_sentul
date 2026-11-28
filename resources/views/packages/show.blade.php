@extends('layouts.app', ['title' => $package->name])

@section('content')
@php
    $includes = array_filter(explode("\n", str_replace("\r", "", $package->includes)));
    $excludes = array_filter(explode("\n", str_replace("\r", "", $package->excludes)));
    $itinerary_steps = array_filter(explode("\n", str_replace("\r", "", $package->itinerary)));
@endphp

<!-- Page Breadcrumb & Header -->
<div class="container pkg-detail-top">
    <div class="pkg-breadcrumb">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; 
        <a href="{{ route('packages.index') }}" style="color: var(--text-sub);">Katalog Paket</a> &nbsp;/&nbsp; 
        <span>{{ $package->name }}</span>
    </div>
    
    <div class="section-tag">
        ━ &nbsp; DETAIL JELAJAH · {{ strtoupper($package->category->name ?? 'SENTUL') }}
    </div>

    <div class="pkg-detail-header">
        <div class="pkg-detail-title-col">
            <h1 class="section-title" style="margin-bottom: 10px;">{{ $package->name }}</h1>
            <div class="pkg-detail-meta">
                <span class="package-badge-tag" style="position: static; display: inline-block;">
                    {{ $package->difficulty }}
                </span>
                <span><i class="bi bi-clock"></i> {{ $package->duration }}</span>
                <span><i class="bi bi-signpost-2"></i> {{ $package->distance }}</span>
                <span><i class="bi bi-geo-alt"></i> Sentul, Babakan Madang</span>
            </div>
        </div>

        <div class="pkg-detail-price-box">
            <span class="pkg-price-label">Harga Paket Resmi</span>
            <span class="pkg-detail-price">
                Rp {{ number_format($package->price, 0, ',', '.') }}
            </span>
            <span class="pkg-price-unit">{{ str_contains(strtolower($package->name), 'jeep') ? '/ unit Jeep (3 s/d 4 Orang)' : '/ orang' }}</span>
        </div>
    </div>

    <!-- Gallery Image Preview -->
    <div class="pkg-hero-gallery">
        <div class="pkg-hero-main-img">
            <img src="{{ $package->image ?: '/galeri/Untitled.jpg' }}" alt="{{ $package->name }}">
        </div>
        <div class="pkg-hero-side-grid">
            <div class="pkg-hero-side-img">
                <img src="/galeri/Untitled5.jpg" alt="Aksi Jalur Hutan Pinus Sentul">
            </div>
            <div class="pkg-hero-side-img">
                <img src="/galeri/Untitled3.jpg" alt="Wisata Jeep 4x4 Sentul">
            </div>
        </div>
    </div>
</div>

<!-- Main Details & Booking Sidebar Layout -->
<section class="section-wrapper pkg-detail-body-section" style="padding-top: 0;">
    <div class="container">
        <div class="booking-layout-grid">
            <!-- Left Column: Details, Itinerary, Facilities -->
            <div class="resp-card-pad pkg-detail-card" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg);">
                <!-- Description -->
                <h3 class="pkg-section-heading">Gambaran Perjalanan</h3>
                <p class="pkg-desc-text">
                    {!! nl2br(e($package->description)) !!}
                </p>

                <!-- Meeting Point Box -->
                <div class="pkg-meeting-box">
                    <i class="bi bi-geo-alt-fill pkg-meeting-icon"></i>
                    <div>
                        <strong class="pkg-meeting-title">Titik Kumpul (Meeting Point):</strong>
                        <span class="pkg-meeting-addr">{{ $package->meeting_point }}</span>
                        <small class="pkg-meeting-note">*Petunjuk rute GPS dan kontak koordinator lapangan dikirimkan via WhatsApp setelah pesanan diverifikasi.</small>
                    </div>
                </div>

                <!-- Itinerary Timeline -->
                <h3 class="pkg-section-heading">Rencana Sesi (Itinerary)</h3>
                <div class="pkg-itinerary-list">
                    @foreach ($itinerary_steps as $idx => $step)
                        @php
                            $parts = explode('-', $step, 2);
                            $waktu = trim($parts[0]);
                            $aktivitas = isset($parts[1]) ? trim($parts[1]) : '';
                        @endphp
                        <div class="pkg-itinerary-item">
                            <div class="pkg-itinerary-num">
                                {{ $idx + 1 }}
                            </div>
                            <div style="padding-top: 2px;">
                                <strong class="pkg-itinerary-time">{{ $waktu }}</strong>
                                <p class="pkg-itinerary-desc">{{ $aktivitas }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr class="pkg-divider">

                <!-- Facilities Grid -->
                <div class="resp-grid-2 pkg-facilities-grid">
                    <div>
                        <h4 class="pkg-sub-heading">
                            <i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Fasilitas Termasuk
                        </h4>
                        <ul class="pkg-check-list">
                            @foreach ($includes as $inc)
                                <li>
                                    <i class="bi bi-check2" style="color: #10b981; font-weight: 800; flex-shrink: 0;"></i>
                                    <span>{{ $inc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 class="pkg-sub-heading">
                            <i class="bi bi-x-circle-fill" style="color: #ef4444;"></i> Tidak Termasuk
                        </h4>
                        <ul class="pkg-check-list pkg-check-list-muted">
                            @foreach ($excludes as $exc)
                                <li>
                                    <i class="bi bi-dash" style="color: #ef4444; font-weight: 800; flex-shrink: 0;"></i>
                                    <span>{{ $exc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- SDD UI-04 (FR-05, FR-12, FR-13, FR-14): Layanan Tambahan (Add-On), Fasilitas Basecamp & Ketentuan Reservasi -->
                @if(isset($addons) && $addons->isNotEmpty())
                    <hr class="pkg-divider">
                    <h3 class="pkg-section-heading">
                        <i class="bi bi-plus-circle" style="color: var(--accent);"></i> Layanan Tambahan (Add-On) Tersedia
                    </h3>
                    <div class="resp-grid-2 pkg-addon-grid">
                        @foreach($addons as $addon)
                            <div class="pkg-addon-item">
                                <div class="pkg-addon-info">
                                    <strong>{{ $addon->nama_addon }}</strong>
                                    <small>{{ $addon->keterangan }}</small>
                                </div>
                                <span class="pkg-addon-price">
                                    Rp {{ number_format($addon->harga, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <hr class="pkg-divider">

                <!-- Fasilitas Basecamp Gunung Pancar / Sentul (FR-14) -->
                <h3 class="pkg-section-heading">
                    <i class="bi bi-house-heart-fill" style="color: var(--accent);"></i> Fasilitas Basecamp YKS (Gunung Pancar & Sentul)
                </h3>
                <div class="resp-grid-2 pkg-basecamp-grid">
                    @php
                        $facilitiesList = !empty($basecampFacilities) ? $basecampFacilities : [
                            'Area Parkir Luas & Aman (Mobil & Motor)',
                            'Kamar Mandi & Ruang Bilas Bersih',
                            'Mushola & Tempat Wudhu',
                            'Gazebo Istirahat & Warung Kopi',
                            'Ruang Fitting Gear (Jersey, Helm, Sepatu Boot)',
                            'Bengkel & Tim Mekanik Siaga'
                        ];
                    @endphp
                    @foreach($facilitiesList as $fac)
                        <div class="pkg-facility-chip">
                            <i class="bi bi-check-circle-fill" style="color: var(--primary); flex-shrink: 0;"></i>
                            <span>{{ $fac }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Ketentuan Perubahan Jadwal, Pembatalan, Kerusakan Unit & Cuaca (FR-12 & FR-13) -->
                <h3 class="pkg-section-heading">
                    <i class="bi bi-shield-exclamation" style="color: var(--accent);"></i> Ketentuan Reservasi, Unit & Kondisi Cuaca
                </h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @if(isset($terms) && $terms->isNotEmpty())
                        @foreach($terms as $term)
                            <div class="pkg-term-card">
                                <strong>{{ $term->judul }}</strong>
                                <p>{{ $term->isi }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="pkg-term-card">
                            <strong>Perubahan Jadwal (Maksimal H-2)</strong>
                            <p>Perubahan jadwal (reschedule) dapat dilakukan mandiri maksimal H-2 sebelum keberangkatan selama kuota tanggal baru masih tersedia.</p>
                        </div>
                        <div class="pkg-term-card pkg-term-danger">
                            <strong>Ketentuan Pembatalan & Kerusakan Unit</strong>
                            <p>Pembatalan pada hari pelaksanaan (Hari-H) menyebabkan pembayaran hangus. Kerusakan unit akibat kelalaian sengaja di luar instruksi pemandu menjadi tanggung jawab penyewa.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Sticky Booking Widget -->
            <div>
                <div class="booking-summary-sidebar">
                    <span class="section-tag" style="margin-bottom: 6px;">━ &nbsp; RESERVASI JADWAL</span>
                    <h3 class="pkg-section-heading" style="margin-bottom: 16px;">Pesan Paket Ini</h3>

                    <div class="pkg-sidebar-price-box">
                        <span class="pkg-price-label">Harga Resmi:</span>
                        <div class="pkg-sidebar-price">
                            Rp {{ number_format($package->price, 0, ',', '.') }}
                        </div>
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Sudah termasuk tiket retribusi & asuransi lapangan.</small>
                    </div>

                    <div class="pkg-sidebar-perks">
                        <div>
                            <i class="bi bi-shield-check" style="color: var(--accent); flex-shrink: 0;"></i>
                            <span>Pemandu Lokal Berlisensi Resmi</span>
                        </div>
                        <div>
                            <i class="bi bi-arrow-repeat" style="color: var(--accent); flex-shrink: 0;"></i>
                            <span>Garansi Reschedule saat Cuaca Buruk</span>
                        </div>
                        <div>
                            <i class="bi bi-chat-dots" style="color: var(--accent); flex-shrink: 0;"></i>
                            <span>Koordinasi Instan via WhatsApp</span>
                        </div>
                    </div>

                    <a href="{{ route('booking.create', ['paket_id' => $package->id]) }}" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 12px;">
                        Lanjut ke Reservasi &nbsp;↗
                    </a>

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20tertarik%20tanya%20paket%20{{ urlencode($package->name) }}" 
                       target="_blank" 
                       class="btn btn-outline btn-block">
                        <i class="bi bi-whatsapp"></i> Tanya Admin via WA
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
