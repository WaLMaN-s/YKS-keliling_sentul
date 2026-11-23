@extends('layouts.app', ['title' => $package->name])

@section('content')
@php
    $includes = array_filter(explode("\n", str_replace("\r", "", $package->includes)));
    $excludes = array_filter(explode("\n", str_replace("\r", "", $package->excludes)));
    $itinerary_steps = array_filter(explode("\n", str_replace("\r", "", $package->itinerary)));
@endphp

<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; 
        <a href="{{ route('packages.index') }}" style="color: var(--text-sub);">Katalog Paket</a> &nbsp;/&nbsp; 
        <span>{{ $package->name }}</span>
    </div>
    
    <div class="section-tag">
        ━ &nbsp; DETAIL JELAJAH · {{ strtoupper($package->category->name ?? 'SENTUL') }}
    </div>

    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 24px; flex-wrap: wrap; margin-bottom: 24px;">
        <div>
            <h1 class="section-title" style="font-size: 2.6rem; margin-bottom: 8px;">{{ $package->name }}</h1>
            <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; font-size: 0.9rem; color: var(--text-muted);">
                <span class="package-badge-tag" style="position: static; display: inline-block;">
                    {{ $package->difficulty }}
                </span>
                <span><i class="bi bi-clock"></i> {{ $package->duration }}</span>
                <span><i class="bi bi-signpost-2"></i> {{ $package->distance }}</span>
                <span><i class="bi bi-geo-alt"></i> Sentul, Babakan Madang</span>
            </div>
        </div>

        <div style="text-align: right;">
            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Harga Paket Resmi</span>
            <span style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--accent);">
                Rp {{ number_format($package->price, 0, ',', '.') }}
            </span>
            <span style="font-size: 0.85rem; color: var(--text-sub); display: block;">{{ str_contains(strtolower($package->name), 'jeep') ? '/ unit Jeep (3 s/d 4 Orang)' : '/ orang' }}</span>
        </div>
    </div>

    <!-- Gallery Image Preview -->
    <div class="pkg-hero-gallery">
        <div class="pkg-hero-main-img">
            <img src="{{ $package->image ?: '/uploads/packages/pricelist-trabas-trail.jpg' }}" alt="{{ $package->name }}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div class="pkg-hero-side-grid">
            <div class="pkg-hero-side-img">
                <img src="/uploads/packages/pricelist-trabas-trail.jpg" alt="Pricelist Sewa Motor Trail Hambalang" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div class="pkg-hero-side-img">
                <img src="/uploads/packages/wisata-jeep-4x4-sentul.jpg" alt="Edisi Wisata Jeep 4x4 Sentul" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</div>

<!-- Main Details & Booking Sidebar Layout -->
<section class="section-wrapper" style="padding-top: 0;">
    <div class="container">
        <div class="booking-layout-grid">
            <!-- Left Column: Details, Itinerary, Facilities -->
            <div class="resp-card-pad" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg);">
                <!-- Description -->
                <h3 style="font-size: 1.4rem; color: var(--primary); margin-bottom: 14px;">Gambaran Perjalanan</h3>
                <p style="font-size: 1rem; color: var(--text-dark); line-height: 1.8; margin-bottom: 36px;">
                    {!! nl2br(e($package->description)) !!}
                </p>

                <!-- Meeting Point Box -->
                <div style="background-color: var(--bg-sage); border: 1px solid var(--bg-pill); border-radius: var(--radius-md); padding: 22px 24px; margin-bottom: 40px; display: flex; align-items: flex-start; gap: 16px;">
                    <i class="bi bi-geo-alt-fill" style="color: var(--primary); font-size: 1.8rem; flex-shrink: 0;"></i>
                    <div>
                        <strong style="color: var(--primary); font-size: 1.05rem; display: block; margin-bottom: 4px;">Titik Kumpul (Meeting Point):</strong>
                        <span style="color: var(--text-dark); font-size: 0.95rem;">{{ $package->meeting_point }}</span>
                        <small style="display: block; color: var(--text-muted); margin-top: 6px;">*Petunjuk rute GPS dan kontak koordinator lapangan dikirimkan via WhatsApp setelah pesanan diverifikasi.</small>
                    </div>
                </div>

                <!-- Itinerary Timeline -->
                <h3 style="font-size: 1.4rem; color: var(--primary); margin-bottom: 20px;">Rencana Sesi (Itinerary)</h3>
                <div style="display: flex; flex-direction: column; gap: 18px; margin-bottom: 40px; padding-left: 8px;">
                    @foreach ($itinerary_steps as $idx => $step)
                        @php
                            $parts = explode('-', $step, 2);
                            $waktu = trim($parts[0]);
                            $aktivitas = isset($parts[1]) ? trim($parts[1]) : '';
                        @endphp
                        <div style="display: flex; gap: 16px; align-items: flex-start;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background-color: var(--bg-sage); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0;">
                                {{ $idx + 1 }}
                            </div>
                            <div style="padding-top: 4px;">
                                <strong style="font-family: var(--font-heading); color: var(--primary); font-size: 0.95rem;">{{ $waktu }}</strong>
                                <p style="font-size: 0.92rem; color: var(--text-muted); margin-top: 2px;">{{ $aktivitas }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 36px;">

                <!-- Facilities Grid -->
                <div class="resp-grid-2" style="gap: 32px;">
                    <div>
                        <h4 style="font-size: 1.1rem; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-check-circle-fill" style="color: #10b981;"></i> Fasilitas Termasuk
                        </h4>
                        <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 0.92rem; color: var(--text-dark);">
                            @foreach ($includes as $inc)
                                <li style="display: flex; align-items: flex-start; gap: 10px;">
                                    <i class="bi bi-check2" style="color: #10b981; font-weight: 800;"></i>
                                    <span>{{ $inc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div>
                        <h4 style="font-size: 1.1rem; color: var(--primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-x-circle-fill" style="color: #ef4444;"></i> Tidak Termasuk
                        </h4>
                        <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px; font-size: 0.92rem; color: var(--text-muted);">
                            @foreach ($excludes as $exc)
                                <li style="display: flex; align-items: flex-start; gap: 10px;">
                                    <i class="bi bi-dash" style="color: #ef4444; font-weight: 800;"></i>
                                    <span>{{ $exc }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- SDD UI-04 (FR-05, FR-12, FR-13, FR-14): Layanan Tambahan (Add-On), Fasilitas Basecamp & Ketentuan Reservasi -->
                @if(isset($addons) && $addons->isNotEmpty())
                    <hr style="border: none; border-top: 1px solid var(--border-light); margin: 36px 0;">
                    <h3 style="font-size: 1.35rem; color: var(--primary); margin-bottom: 14px;">
                        <i class="bi bi-plus-circle" style="color: var(--accent);"></i> Layanan Tambahan (Add-On) Tersedia
                    </h3>
                    <div class="resp-grid-2" style="gap: 14px; margin-bottom: 12px;">
                        @foreach($addons as $addon)
                            <div style="padding: 14px 16px; border: 1px solid var(--border); border-radius: var(--radius-md); background: var(--bg-body); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <strong style="color: var(--primary); font-size: 0.92rem; display: block;">{{ $addon->nama_addon }}</strong>
                                    <small style="color: var(--text-muted); font-size: 0.78rem;">{{ $addon->keterangan }}</small>
                                </div>
                                <span style="font-weight: 800; color: var(--accent); white-space: nowrap; margin-left: 10px;">
                                    Rp {{ number_format($addon->harga, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <hr style="border: none; border-top: 1px solid var(--border-light); margin: 36px 0;">

                <!-- Fasilitas Basecamp Gunung Pancar / Sentul (FR-14) -->
                <h3 style="font-size: 1.35rem; color: var(--primary); margin-bottom: 14px;">
                    <i class="bi bi-house-heart-fill" style="color: var(--accent);"></i> Fasilitas Basecamp YKS (Gunung Pancar & Sentul)
                </h3>
                <div class="resp-grid-2" style="gap: 12px; margin-bottom: 36px;">
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
                        <div style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: var(--bg-sage); border-radius: var(--radius-md); font-size: 0.88rem; color: var(--primary); font-weight: 600;">
                            <i class="bi bi-check-circle-fill" style="color: var(--primary);"></i>
                            <span>{{ $fac }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Ketentuan Perubahan Jadwal, Pembatalan, Kerusakan Unit & Cuaca (FR-12 & FR-13) -->
                <h3 style="font-size: 1.35rem; color: var(--primary); margin-bottom: 14px;">
                    <i class="bi bi-shield-exclamation" style="color: var(--accent);"></i> Ketentuan Reservasi, Unit & Kondisi Cuaca
                </h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    @if(isset($terms) && $terms->isNotEmpty())
                        @foreach($terms as $term)
                            <div style="padding: 14px 18px; border: 1px solid var(--border); border-left: 4px solid var(--primary); border-radius: var(--radius-md); background: #FFFFFF;">
                                <strong style="color: var(--primary); font-size: 0.94rem; display: block; margin-bottom: 4px;">{{ $term->judul }}</strong>
                                <p style="color: var(--text-muted); font-size: 0.86rem; margin: 0; line-height: 1.6;">{{ $term->isi }}</p>
                            </div>
                        @endforeach
                    @else
                        <div style="padding: 14px 18px; border: 1px solid var(--border); border-left: 4px solid var(--primary); border-radius: var(--radius-md); background: #FFFFFF;">
                            <strong style="color: var(--primary); font-size: 0.94rem; display: block; margin-bottom: 4px;">Perubahan Jadwal (Maksimal H-2)</strong>
                            <p style="color: var(--text-muted); font-size: 0.86rem; margin: 0;">Perubahan jadwal (reschedule) dapat dilakukan mandiri maksimal H-2 sebelum keberangkatan selama kuota tanggal baru masih tersedia.</p>
                        </div>
                        <div style="padding: 14px 18px; border: 1px solid var(--border); border-left: 4px solid #DC2626; border-radius: var(--radius-md); background: #FFFFFF;">
                            <strong style="color: #991B1B; font-size: 0.94rem; display: block; margin-bottom: 4px;">Ketentuan Pembatalan & Kerusakan Unit</strong>
                            <p style="color: var(--text-muted); font-size: 0.86rem; margin: 0;">Pembatalan pada hari pelaksanaan (Hari-H) menyebabkan pembayaran hangus. Kerusakan unit akibat kelalaian sengaja di luar instruksi pemandu menjadi tanggung jawab penyewa.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Sticky Booking Widget -->
            <div>
                <div class="booking-summary-sidebar">
                    <span class="section-tag" style="margin-bottom: 6px;">━ &nbsp; RESERVASI JADWAL</span>
                    <h3 style="font-size: 1.4rem; color: var(--primary); margin-bottom: 18px;">Pesan Paket Ini</h3>

                    <div style="background-color: var(--bg-body); border-radius: var(--radius-md); padding: 18px; margin-bottom: 24px;">
                        <span style="font-size: 0.8rem; color: var(--text-muted);">Harga Resmi:</span>
                        <div style="font-family: var(--font-heading); font-size: 2rem; font-weight: 900; color: var(--accent); margin: 4px 0;">
                            Rp {{ number_format($package->price, 0, ',', '.') }}
                        </div>
                        <small style="color: var(--text-muted);">Sudah termasuk tiket retribusi & asuransi lapangan.</small>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 24px; font-size: 0.88rem; color: var(--text-dark);">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-shield-check" style="color: var(--accent);"></i>
                            <span>Pemandu Lokal Berlisensi Resmi</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-arrow-repeat" style="color: var(--accent);"></i>
                            <span>Garansi Reschedule saat Cuaca Buruk</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-chat-dots" style="color: var(--accent);"></i>
                            <span>Koordinasi Instan via WhatsApp</span>
                        </div>
                    </div>

                    <a href="{{ route('booking.create', ['paket_id' => $package->id]) }}" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 12px;">
                        Lanjut ke Reservasi &nbsp;↗
                    </a>

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20tertarik%20tanya%20paket%20{{ urlencode($package->name) }}" 
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
