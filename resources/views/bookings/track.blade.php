@extends('layouts.app', ['title' => 'Lacak Status Booking & E-Tiket'])

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; 
        <span>Lacak Trip</span>
    </div>
    <div class="section-tag">
        ━ &nbsp; RESERVASI · STATUS JELAJAH & E-TIKET
    </div>
    <h1 class="section-title" style="font-size: 2.4rem; margin-bottom: 24px;">
        Lacak Status & Tiket Petualangan
    </h1>

    <!-- 4-Step Progress Navigation -->
    <div class="reservation-steps-nav">
        <div class="step-indicator-item completed">
            <span class="step-badge"><i class="bi bi-check"></i></span>
            <span>01 Pilih Paket</span>
        </div>
        <div class="step-indicator-item completed">
            <span class="step-badge"><i class="bi bi-check"></i></span>
            <span>02 Jadwal & Data</span>
        </div>
        <div class="step-indicator-item completed">
            <span class="step-badge"><i class="bi bi-check"></i></span>
            <span>03 Pembayaran</span>
        </div>
        <div class="step-indicator-item active">
            <span class="step-badge">04</span>
            <span>04 Konfirmasi</span>
        </div>
    </div>
</div>

<section class="section-wrapper" style="padding-top: 0;">
    <div class="container" style="max-width: 820px;">
        <!-- Search Box -->
        <div style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 28px; margin-bottom: 36px;">
            <form method="GET" action="{{ route('booking.track') }}">
                <label class="form-label" style="font-size: 0.95rem;">Masukkan Kode Booking atau Nomor WhatsApp</label>
                <div style="display: flex; gap: 12px; margin-top: 8px;">
                    <input type="text" name="kode" class="form-control" 
                           placeholder="Contoh: YKS-20261028-XXXX atau 08123456789" 
                           value="{{ $kode ?? '' }}" required>
                    <button type="submit" class="btn btn-forest" style="padding: 12px 28px; white-space: nowrap;">
                        <i class="bi bi-search"></i> Cek Status
                    </button>
                </div>
                <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 8px;">
                    *Kode booking didapatkan saat pertama kali membuat reservasi di website.
                </small>
            </form>
        </div>

        @if ($kode && !$booking)
            <div class="alert alert-danger" style="justify-content: center; text-align: center;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Data pesanan untuk <strong>{{ $kode }}</strong> tidak ditemukan. Pastikan nomor atau kode booking sudah benar.</span>
            </div>
        @endif

        @if ($booking)
            <div style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); overflow: hidden; box-shadow: var(--shadow-md);">
                <!-- Header Card -->
                <div style="background-color: var(--primary); color: #FFFFFF; padding: 24px 32px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span style="font-size: 0.75rem; color: #CBD9C8; text-transform: uppercase; letter-spacing: 1px; font-weight: 700;">Nomor Tiket / Kode Booking:</span>
                        <h2 style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 900; color: #FFFFFF; margin: 2px 0 0;">
                            {{ $booking->booking_code }}
                        </h2>
                    </div>
                    <div>
                        <span style="display: inline-block; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 0.82rem; 
                              background-color: {{ $booking->payment_status === 'Lunas' ? '#10b981' : ($booking->payment_status === 'Menunggu Verifikasi' ? '#f59e0b' : '#ef4444') }}; color: #FFFFFF;">
                            {{ $booking->payment_status }}
                        </span>
                    </div>
                </div>

                <!-- Body Card -->
                <div style="padding: 36px 32px;">
                    <div style="margin-bottom: 24px;">
                        <span class="section-tag" style="margin-bottom: 4px;">━ &nbsp; PAKET TERPILIH</span>
                        <h3 style="font-size: 1.5rem; color: var(--primary); margin: 0 0 6px;">
                            {{ $booking->package->name ?? 'Paket Wisata Sentul' }}
                        </h3>
                    </div>

                    <!-- Details Grid -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; background-color: var(--bg-body); padding: 24px; border-radius: var(--radius-md); border: 1px solid var(--border-light); margin-bottom: 28px;">
                        <div>
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Tanggal Jelajah:</span>
                            <strong style="color: var(--primary); font-size: 1.05rem;">
                                <i class="bi bi-calendar-check" style="color: var(--accent);"></i> {{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d F Y') }}
                            </strong>
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Jumlah Peserta:</span>
                            <strong style="color: var(--primary); font-size: 1.05rem;">
                                <i class="bi bi-people" style="color: var(--accent);"></i> {{ $booking->participant_count }} Orang
                            </strong>
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Nama Pemesan:</span>
                            <strong style="color: var(--text-dark);">{{ $booking->customer_name }}</strong>
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">No. WhatsApp:</span>
                            <strong style="color: var(--text-dark);">{{ $booking->customer_phone }}</strong>
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Total Tagihan:</span>
                            <strong style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 900; color: var(--accent);">{{ $booking->formatted_total }}</strong>
                        </div>
                        <div>
                            <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Metode Pembayaran:</span>
                            <strong style="color: var(--text-dark);">{{ $booking->payment_method }}</strong>
                        </div>
                    </div>

                    <!-- Meeting Point Info -->
                    <div style="background-color: var(--bg-sage); border: 1px solid var(--bg-pill); border-radius: var(--radius-md); padding: 18px 22px; margin-bottom: 28px;">
                        <strong style="color: var(--primary); display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <i class="bi bi-geo-alt-fill" style="color: var(--accent);"></i> Titik Kumpul (Meeting Point):
                        </strong>
                        <p style="color: var(--text-dark); font-size: 0.92rem; margin: 0;">
                            {{ $booking->package->meeting_point ?? 'Pos Jelajah YKS Sentul, Babakan Madang, Kab. Bogor' }}
                        </p>
                    </div>

                    <!-- Bukti Transfer Status -->
                    <div style="margin-bottom: 32px;">
                        <span style="font-size: 0.85rem; color: var(--text-muted); display: block; margin-bottom: 6px;">Bukti Transfer:</span>
                        @if ($booking->payment_proof)
                            <div style="display: flex; align-items: center; gap: 10px; color: #10b981; font-weight: 700; background-color: #EBF8F2; padding: 12px 18px; border-radius: var(--radius-md); border: 1px solid #A7E3CB;">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Bukti transfer sudah terunggah ({{ $booking->payment_proof }}). Sedang diproses tim keuangan.</span>
                            </div>
                        @else
                            <div style="display: flex; justify-content: space-between; align-items: center; background-color: #FEF3C7; border: 1px solid #FCD34D; padding: 14px 20px; border-radius: var(--radius-md); flex-wrap: wrap; gap: 12px;">
                                <span style="color: #92400E; font-size: 0.9rem;">
                                    <i class="bi bi-exclamation-circle-fill"></i> Anda belum mengunggah bukti transfer pembayaran.
                                </span>
                                <a href="{{ route('payment.show', $booking->booking_code) }}" class="btn btn-primary btn-sm">
                                    Unggah Bukti Sekarang &nbsp;↗
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20konfirmasi%20trip%20untuk%20Kode%20Booking:%20{{ urlencode($booking->booking_code) }}" 
                           target="_blank" 
                           class="btn btn-primary" style="flex: 1.2;">
                            <i class="bi bi-whatsapp"></i> Hubungi Guide / Admin WA
                        </a>
                        <a href="{{ route('payment.show', $booking->booking_code) }}" class="btn btn-outline" style="flex: 1;">
                            <i class="bi bi-credit-card"></i> Buka Instruksi Bayar
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
