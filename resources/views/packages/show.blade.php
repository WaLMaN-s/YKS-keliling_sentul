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
            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Mulai dari</span>
            <span style="font-family: var(--font-heading); font-size: 2.2rem; font-weight: 900; color: var(--accent);">
                Rp {{ number_format($package->price, 0, ',', '.') }}
            </span>
            <span style="font-size: 0.85rem; color: var(--text-sub); display: block;">/ pax / unit jip</span>
        </div>
    </div>

    <!-- Gallery Image Preview -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 18px; margin-bottom: 48px;">
        <div style="border-radius: var(--radius-lg); overflow: hidden; height: 420px; border: 1px solid var(--border);">
            <img src="{{ $package->image ?: 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $package->name }}" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div style="display: grid; grid-template-rows: 1fr 1fr; gap: 18px;">
            <div style="border-radius: var(--radius-md); overflow: hidden; height: 201px; border: 1px solid var(--border);">
                <img src="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?auto=format&fit=crop&w=600&q=80" alt="Spot Alami Sentul" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="border-radius: var(--radius-md); overflow: hidden; height: 201px; border: 1px solid var(--border);">
                <img src="https://images.unsplash.com/photo-1510312305653-8ed496efae75?auto=format&fit=crop&w=600&q=80" alt="Panorama Alam" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </div>
</div>

<!-- Main Details & Booking Sidebar Layout -->
<section class="section-wrapper" style="padding-top: 0;">
    <div class="container">
        <div class="booking-layout-grid">
            <!-- Left Column: Details, Itinerary, Facilities -->
            <div style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px;">
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
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
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
