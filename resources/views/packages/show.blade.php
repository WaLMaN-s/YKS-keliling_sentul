@extends('layouts.app', ['title' => $package->name])

@section('content')
@php
    $includes = array_filter(explode("\n", str_replace("\r", "", $package->includes)));
    $excludes = array_filter(explode("\n", str_replace("\r", "", $package->excludes)));
    $itinerary_steps = array_filter(explode("\n", str_replace("\r", "", $package->itinerary)));
@endphp

<!-- Package Header Banner -->
<div class="package-hero">
    <div class="container">
        <a href="{{ route('packages.index') }}" style="color: #cbd5e1; font-size: 0.9rem; margin-bottom: 12px; display: inline-flex; align-items: center; gap: 6px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Paket
        </a>
        <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
            <span class="badge badge-medium" style="background: rgba(255,255,255,0.15); color: #fef08a;">
                {{ $package->category->name ?? 'Wisata' }}
            </span>
            @if ($package->difficulty === 'Mudah')
                <span class="badge badge-easy"><i class="bi bi-shield-check"></i> Mudah / Pemula</span>
            @elseif ($package->difficulty === 'Sedang')
                <span class="badge badge-medium"><i class="bi bi-lightning-charge"></i> Sedang</span>
            @else
                <span class="badge badge-hard"><i class="bi bi-fire"></i> Menantang</span>
            @endif
        </div>
        <h1 style="font-size: 2.3rem; font-weight: 800;">{{ $package->name }}</h1>
    </div>
</div>

<div class="container section">
    <div class="package-grid">
        <!-- Main Content -->
        <div class="package-main">
            <!-- Cover Image -->
            <div style="border-radius: var(--radius-md); overflow: hidden; margin-bottom: 28px; max-height: 420px;">
                <img src="{{ $package->image ?: 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $package->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Quick Specs -->
            <div class="card-meta" style="font-size: 0.95rem; padding: 16px 0; margin-bottom: 24px;">
                <span class="card-meta-item"><i class="bi bi-clock-history"></i> Durasi: <strong>{{ $package->duration }}</strong></span>
                <span class="card-meta-item"><i class="bi bi-signpost-2"></i> Jarak Trekking: <strong>{{ $package->distance }}</strong></span>
                <span class="card-meta-item"><i class="bi bi-geo-alt"></i> Lokasi: <strong>Sentul, Bogor</strong></span>
            </div>

            <!-- Description -->
            <h3 style="font-size: 1.3rem; font-weight: 800; margin-bottom: 12px; color: var(--dark);">Deskripsi Trip</h3>
            <p style="color: var(--text-muted); line-height: 1.8; margin-bottom: 30px; font-size: 1rem;">
                {!! nl2br(e($package->description)) !!}
            </p>

            <!-- Meeting Point -->
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius-md); padding: 18px 20px; margin-bottom: 32px; display: flex; align-items: flex-start; gap: 14px;">
                <i class="bi bi-pin-map-fill text-primary" style="font-size: 24px;"></i>
                <div>
                    <strong style="color: #166534; font-size: 1.05rem; display: block; margin-bottom: 4px;">Titik Kumpul (Meeting Point)</strong>
                    <span style="color: #14532d; font-size: 0.95rem;">{{ $package->meeting_point }}</span>
                    <small style="display: block; color: #15803d; margin-top: 4px;">*Share location GPS akurat akan dikirimkan oleh admin via WhatsApp setelah booking.</small>
                </div>
            </div>

            <!-- Itinerary Timeline -->
            <h3 style="font-size: 1.3rem; font-weight: 800; margin-bottom: 16px; color: var(--dark);">Jadwal & Rencana Perjalanan (Itinerary)</h3>
            <div class="itinerary-list">
                @foreach ($itinerary_steps as $step)
                    @php
                        $parts = explode('-', $step, 2);
                        $waktu = trim($parts[0]);
                        $aktivitas = isset($parts[1]) ? trim($parts[1]) : '';
                    @endphp
                    <div class="itinerary-item">
                        <strong style="color: var(--primary); font-size: 0.95rem;">{{ $waktu }}</strong>
                        <p style="color: var(--text-main); font-size: 0.92rem; margin-top: 2px;">{{ $aktivitas }}</p>
                    </div>
                @endforeach
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border); margin: 32px 0;">

            <!-- Facilities Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: #15803d; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i class="bi bi-check-circle-fill"></i> Fasilitas Termasuk (Include)
                    </h4>
                    <ul class="facility-list facility-include">
                        @foreach ($includes as $inc)
                            <li><i class="bi bi-check-lg"></i> {{ $inc }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: #b91c1c; margin-bottom: 12px; display: center; gap: 8px;">
                        <i class="bi bi-x-circle-fill"></i> Tidak Termasuk (Exclude)
                    </h4>
                    <ul class="facility-list facility-exclude">
                        @foreach ($excludes as $exc)
                            <li><i class="bi bi-x-lg"></i> {{ $exc }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sidebar Sticky Booking Box -->
        <div class="package-sidebar">
            <div class="booking-card">
                <div class="price-header">
                    <span style="font-size: 0.82rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 0.05em;">Harga Paket</span>
                    <div class="price-val">Rp {{ number_format($package->price, 0, ',', '.') }} <span style="font-size: 0.9rem; font-weight: 500; color: var(--text-muted);">/ orang</span></div>
                    <small style="color: var(--text-muted); display: block; margin-top: 4px;">Minimal booking 1 orang &bull; Bisa grup/rombongan</small>
                </div>

                <div style="margin-bottom: 20px;">
                    <ul style="list-style: none; font-size: 0.88rem; color: var(--text-main);">
                        <li style="margin-bottom: 8px;"><i class="bi bi-check2 text-primary"></i> Konfirmasi Booking Instan</li>
                        <li style="margin-bottom: 8px;"><i class="bi bi-check2 text-primary"></i> Gratis Tongkat Trekking</li>
                        <li style="margin-bottom: 8px;"><i class="bi bi-check2 text-primary"></i> Garansi Pemandu Lokal Resmi</li>
                    </ul>
                </div>

                <a href="{{ route('booking.create', ['paket_id' => $package->id]) }}" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 10px;">
                    <i class="bi bi-calendar-check"></i> Pesan Sekarang
                </a>

                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20tertarik%20tanya%20paket%20wisata%20{{ urlencode($package->name) }}" 
                   target="_blank" 
                   class="btn btn-outline btn-block">
                    <i class="bi bi-whatsapp text-primary"></i> Konsultasi via WA
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
