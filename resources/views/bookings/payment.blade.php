@extends('layouts.app', ['title' => 'Instruksi Pembayaran · ' . $booking->booking_code])

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; 
        <a href="{{ route('booking.create') }}" style="color: var(--text-sub);">Reservasi</a> &nbsp;/&nbsp; 
        <span>Pembayaran</span>
    </div>
    <div class="section-tag">
        ━ &nbsp; RESERVASI · PEMBAYARAN & UNGGAH BUKTI
    </div>
    <h1 class="section-title" style="font-size: 2.4rem; margin-bottom: 24px;">
        Selesaikan Pembayaran Trip Anda
    </h1>

    <!-- 4-Step Progress Navigation -->
    <div class="reservation-steps-nav">
        <div class="step-indicator-item completed">
            <span class="step-badge"><i class="bi bi-check"></i></span>
            <span>Pilih Jadwal</span>
        </div>
        <div class="step-indicator-item completed">
            <span class="step-badge"><i class="bi bi-check"></i></span>
            <span>Data Pemesan</span>
        </div>
        <div class="step-indicator-item active">
            <span class="step-badge">03</span>
            <span>Pembayaran</span>
        </div>
        <div class="step-indicator-item">
            <span class="step-badge">04</span>
            <span>Konfirmasi</span>
        </div>
    </div>
</div>

<section class="section-wrapper" style="padding-top: 0;">
    <div class="container">
        <div class="booking-layout-grid">
            <!-- Left Column: Bank Accounts & Upload Bukti -->
            <div>
                <!-- Transfer Bank Card -->
                <div class="resp-card-pad" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); margin-bottom: 32px;">
                    <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 8px;">
                        Tujuan Transfer Resmi YKS
                    </h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 24px;">
                        Silakan transfer sesuai nominal tagihan ke salah satu rekening bank resmi kami:
                    </p>

                    <!-- BCA Card -->
                    <div style="background-color: var(--primary); color: #FFFFFF; border-radius: var(--radius-md); padding: 24px; margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <strong style="font-size: 1.2rem; letter-spacing: 1px; color: #FFFFFF;">BANK BCA</strong>
                            <span style="background-color: rgba(255,255,255,0.15); font-size: 0.75rem; padding: 4px 10px; border-radius: 20px;">Prioritas</span>
                        </div>
                        <span style="font-size: 0.75rem; color: #CBD9C8; text-transform: uppercase;">Nomor Rekening:</span>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin: 4px 0 10px;">
                            <span style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 800; letter-spacing: 2px;">8450-1234-5678</span>
                            <button type="button" onclick="copyRek('845012345678', this)" class="btn btn-sm" style="background-color: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.3);">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                        </div>
                        <small style="color: #E3E9E0;">a.n. PT YUK KELILING SENTUL</small>
                    </div>

                    <!-- Mandiri Card -->
                    <div style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 22px; margin-bottom: 18px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="font-size: 1.15rem; color: var(--primary);">BANK MANDIRI</strong>
                            <span style="background-color: var(--bg-sage); font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; color: var(--primary);">Alternatif</span>
                        </div>
                        <span style="font-size: 0.75rem; color: var(--text-sub); text-transform: uppercase;">Nomor Rekening:</span>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin: 4px 0 10px;">
                            <span style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: var(--primary); letter-spacing: 2px;">1330-0987-6543-2</span>
                            <button type="button" onclick="copyRek('1330098765432', this)" class="btn btn-sm btn-outline">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                        </div>
                        <small style="color: var(--text-dark);">a.n. PT YUK KELILING SENTUL</small>
                    </div>

                    <!-- QRIS Card -->
                    <div style="background-color: var(--bg-sage); border: 1px solid var(--bg-pill); border-radius: var(--radius-md); padding: 20px; display: flex; align-items: center; gap: 20px;">
                        <div style="width: 52px; height: 52px; background-color: var(--primary); color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                            <i class="bi bi-qr-code"></i>
                        </div>
                        <div>
                            <strong style="color: var(--primary); font-size: 0.98rem; display: block;">QRIS Semua E-Wallet & M-Banking</strong>
                            <small style="color: var(--text-muted);">BCA Mobile, Livin Mandiri, GoPay, OVO, Dana, ShopeePay</small>
                        </div>
                    </div>
                </div>

                <!-- Upload Bukti Form -->
                <div class="resp-card-pad" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg);">
                    <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-upload" style="color: var(--accent);"></i> Unggah Bukti Transfer
                    </h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 24px;">
                        Foto struk ATM atau tangkapan layar (screenshot) mutasi rekening Anda.
                    </p>

                    <form method="POST" action="{{ route('payment.upload', $booking->booking_code) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Berkas Bukti Transfer <span class="text-accent">*</span></label>
                            <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                            <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 6px;">Format file: JPG, PNG, atau WEBP (Maksimal 5 MB)</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top: 16px;">
                            Kirim Bukti Pembayaran &nbsp;↗
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Booking Summary & Details -->
            <div>
                <div class="booking-summary-sidebar">
                    <span class="section-tag" style="margin-bottom: 6px;">━ &nbsp; DETAIL PESANAN</span>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                        <span style="font-size: 0.85rem; color: var(--text-muted);">Kode Booking:</span>
                        <code style="background-color: var(--bg-sage); padding: 4px 10px; border-radius: 6px; font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                            {{ $booking->booking_code }}
                        </code>
                    </div>

                    <div style="border-top: 1px solid var(--border-light); padding-top: 16px; margin-bottom: 20px;">
                        <h4 style="font-size: 1.15rem; color: var(--primary); margin-bottom: 6px;">
                            {{ $booking->package->name ?? 'Paket Wisata Sentul' }}
                        </h4>
                        <div style="display: flex; flex-direction: column; gap: 8px; font-size: 0.88rem; color: var(--text-muted); margin-top: 12px;">
                            <div><i class="bi bi-calendar-event"></i> Tanggal Trip: <strong>{{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d F Y') }}</strong></div>
                            <div><i class="bi bi-people"></i> Jumlah Peserta: <strong>{{ $booking->participant_count }} Orang</strong></div>
                            <div><i class="bi bi-person"></i> Atas Nama: <strong>{{ $booking->customer_name }}</strong></div>
                            <div><i class="bi bi-whatsapp"></i> No. WA: <strong>{{ $booking->customer_phone }}</strong></div>
                        </div>
                    </div>

                    <div style="background-color: var(--bg-body); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px;">
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">Total Pembayaran:</span>
                        <div style="font-family: var(--font-heading); font-size: 1.8rem; font-weight: 900; color: var(--accent); margin: 4px 0;">
                            {{ $booking->formatted_total }}
                        </div>
                        <small style="color: #10b981; font-weight: 600;"><i class="bi bi-check2-circle"></i> Status: {{ $booking->payment_status }}</small>
                    </div>

                    <a href="{{ route('booking.track', ['kode' => $booking->booking_code]) }}"
                       class="btn btn-forest btn-block" style="margin-bottom: 10px;">
                        <i class="bi bi-search"></i> Lacak Status Booking Ini &nbsp;↗
                    </a>

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20sudah%20melakukan%20booking%20dengan%20kode%20{{ $booking->booking_code }}" 
                       target="_blank" 
                       class="btn btn-outline btn-block">
                        <i class="bi bi-whatsapp"></i> Konfirmasi ke WA Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function copyRek(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const old = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2"></i> Disalin!';
            setTimeout(() => { btn.innerHTML = old; }, 2000);
        });
    }
</script>
@endsection
