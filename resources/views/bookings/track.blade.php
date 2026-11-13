@extends('layouts.app', ['title' => 'Lacak Status Booking & Konfirmasi'])

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp;
        <span>Lacak Status Booking</span>
    </div>
    <div class="section-tag">
        ━ &nbsp; RESERVASI · LACAK STATUS & KONFIRMASI TANPA LOGIN
    </div>
    <h1 class="section-title" style="font-size: 2.4rem; margin-bottom: 24px;">
        Lacak Status Reservasi Anda
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
        <div class="step-indicator-item completed">
            <span class="step-badge"><i class="bi bi-check"></i></span>
            <span>Pembayaran</span>
        </div>
        <div class="step-indicator-item active">
            <span class="step-badge">04</span>
            <span>Konfirmasi</span>
        </div>
    </div>
</div>

<section class="section-wrapper" style="padding-top: 0;">
    <div class="container" style="max-width: 840px;">
        <!-- Search Box Tanpa Login -->
        <div class="resp-card-pad" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); margin-bottom: 28px; box-shadow: var(--shadow-sm);">
            <form method="GET" action="{{ route('booking.track') }}">
                <label class="form-label" style="font-size: 0.96rem; color: var(--primary);">
                    <i class="bi bi-search" style="color: var(--accent);"></i> Masukkan Kode Booking, Nomor WhatsApp, atau Email Pemesan
                </label>
                <div style="display: flex; gap: 12px; margin-top: 8px; flex-wrap: wrap;">
                    <input type="text" name="kode" class="form-control" style="flex: 1; min-width: 220px;"
                           placeholder="Contoh: YKS-261114-018 atau 081200000018"
                           value="{{ $kode ?? '' }}" required>
                    <button type="submit" class="btn btn-forest" style="padding: 12px 28px; white-space: nowrap;">
                        <i class="bi bi-search"></i> Cek Status
                    </button>
                </div>
                <small style="color: var(--text-muted); font-size: 0.8rem; display: block; margin-top: 8px;">
                    *Anda tidak perlu membuat akun atau login. Cukup masukkan Kode Booking atau Nomor WhatsApp yang digunakan saat reservasi.
                </small>
            </form>

            <!-- Quick Links: Recent Session Bookings or Sample Reference Codes -->
            <div style="margin-top: 16px; padding-top: 14px; border-top: 1px dashed var(--border-light); display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                @if(isset($recentBookings) && $recentBookings->isNotEmpty())
                    <span style="font-size: 0.78rem; font-weight: 700; color: var(--primary);">Pesanan Anda baru-baru ini:</span>
                    @foreach($recentBookings as $rb)
                        <a href="{{ route('booking.track', ['kode' => $rb->booking_code]) }}"
                           style="font-size: 0.78rem; font-weight: 700; padding: 4px 10px; border-radius: 99px; background: var(--bg-sage); color: var(--primary); border: 1px solid #C1D1BE;">
                            {{ $rb->booking_code }} ({{ $rb->payment_status }})
                        </a>
                    @endforeach
                @else
                    <span style="font-size: 0.78rem; color: var(--text-muted);">Contoh kode reservasi aktif:</span>
                    <a href="{{ route('booking.track', ['kode' => 'YKS-261114-018']) }}" style="font-size: 0.76rem; font-weight: 700; padding: 4px 10px; border-radius: 99px; background: var(--bg-sage); color: var(--primary);">YKS-261114-018</a>
                    <a href="{{ route('booking.track', ['kode' => 'YKS-261114-016']) }}" style="font-size: 0.76rem; font-weight: 700; padding: 4px 10px; border-radius: 99px; background: var(--bg-sage); color: var(--primary);">YKS-261114-016 (Lunas)</a>
                    <a href="{{ route('booking.track', ['kode' => '081200000018']) }}" style="font-size: 0.76rem; font-weight: 700; padding: 4px 10px; border-radius: 99px; background: var(--bg-body); color: var(--text-muted); border: 1px solid var(--border);">WA: 081200000018</a>
                @endif
            </div>
        </div>

        @if ($kode && !$booking)
            <div class="alert alert-danger" style="justify-content: center; text-align: center;">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Data reservasi untuk <strong>{{ $kode }}</strong> tidak ditemukan. Pastikan Kode Booking atau Nomor WhatsApp sudah sesuai.</span>
            </div>
        @endif

        <!-- Jika 1 Nomor WA memiliki lebih dari 1 riwayat booking -->
        @if (isset($matchedBookings) && $matchedBookings->count() > 1)
            <div style="background: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 24px;">
                <span style="font-size: 0.82rem; font-weight: 700; color: var(--primary); display: block; margin-bottom: 10px;">
                    Ditemukan {{ $matchedBookings->count() }} reservasi untuk "{{ $kode }}". Pilih salah satu:
                </span>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach ($matchedBookings as $mb)
                        <a href="{{ route('booking.track', ['kode' => $kode, 'pilih' => $mb->booking_code]) }}"
                           class="btn btn-sm {{ $booking && $booking->id === $mb->id ? 'btn-forest' : 'btn-outline' }}">
                            {{ $mb->booking_code }} · {{ \Carbon\Carbon::parse($mb->trip_date)->format('d M Y') }} ({{ $mb->payment_status }})
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($booking)
            <!-- Figma Banner Status Card (Rencana Tersusun / Terkonfirmasi) -->
            <div class="resp-card-pad" style="background-color: #EAF0E7; border: 1px solid #C1D1BE; border-radius: var(--radius-lg); margin-bottom: 24px;">
                <div style="width: 48px; height: 48px; border-radius: 50%; background: #FFFFFF; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; margin-bottom: 14px; box-shadow: var(--shadow-sm);">
                    @if($booking->payment_status === 'Lunas')
                        <i class="bi bi-patch-check-fill" style="color: #10b981;"></i>
                    @elseif($booking->payment_status === 'Menunggu Verifikasi')
                        <i class="bi bi-clock-history" style="color: var(--primary);"></i>
                    @else
                        <i class="bi bi-receipt" style="color: var(--accent);"></i>
                    @endif
                </div>

                <span style="display: inline-block; padding: 5px 12px; border-radius: 99px; font-size: 0.74rem; font-weight: 700; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 10px;
                      background-color: {{ $booking->payment_status === 'Lunas' ? '#D1FAE5' : ($booking->payment_status === 'Menunggu Verifikasi' ? '#F3E5D0' : '#FEE2E2') }};
                      color: {{ $booking->payment_status === 'Lunas' ? '#065F46' : ($booking->payment_status === 'Menunggu Verifikasi' ? '#8A4F17' : '#991B1B') }};">
                    STATUS RESERVASI · {{ strtoupper($booking->payment_status) }}
                </span>

                <h2 style="font-size: 1.85rem; color: var(--primary); margin-bottom: 8px;">
                    @if($booking->payment_status === 'Lunas')
                        Reservasi Terkonfirmasi. Siap Berangkat!
                    @elseif($booking->payment_status === 'Menunggu Verifikasi')
                        Rencana tersusun. Menunggu pemeriksaan.
                    @else
                        Menunggu penyelesaian bukti pembayaran.
                    @endif
                </h2>
                <p style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 20px; max-width: 640px;">
                    @if($booking->payment_status === 'Lunas')
                        Pembayaran Anda telah diverifikasi oleh Admin YKS. Tunjukkan Nomor Referensi di bawah ini kepada koordinator lapangan saat tiba di titik kumpul.
                    @elseif($booking->payment_status === 'Menunggu Verifikasi')
                        Bukti pembayaran Anda sudah diterima dan sedang dicocokkan oleh tim administrasi YKS.
                    @else
                        Silakan lakukan transfer sesuai total tagihan dan unggah foto struk/bukti transfer di bawah ini agar jadwal Anda segera dikunci.
                    @endif
                </p>

                <!-- Nomor Referensi Box + Copy Button (Figma Pattern) -->
                <div style="background: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                    <div>
                        <span style="font-size: 0.72rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 0.8px; display: block;">
                            NOMOR REFERENSI BOOKING
                        </span>
                        <strong id="bookingRefCode" style="font-family: var(--font-heading); font-size: 1.35rem; font-weight: 900; color: var(--primary); letter-spacing: 0.5px;">
                            {{ $booking->booking_code }}
                        </strong>
                    </div>
                    <button type="button" onclick="copyBookingCode('{{ $booking->booking_code }}', this)" class="btn btn-outline btn-sm" style="padding: 8px 14px;">
                        <i class="bi bi-copy"></i> Salin Kode
                    </button>
                </div>
            </div>

            <!-- Main Detail & Upload Card -->
            <div class="resp-card-pad" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); margin-bottom: 24px; box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 1.35rem; color: var(--primary); margin-bottom: 16px;">Rincian Reservasi & Jadwal</h3>

                <div class="resp-grid-2" style="background-color: var(--bg-body); padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border-light); margin-bottom: 24px;">
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Paket Terpilih:</span>
                        <strong style="color: var(--primary); font-size: 1rem;">
                            {{ $booking->package->name ?? 'Paket Wisata Sentul' }}
                        </strong>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Tanggal Jelajah:</span>
                        <strong style="color: var(--primary); font-size: 1rem;">
                            <i class="bi bi-calendar-check" style="color: var(--accent);"></i>
                            {{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d F Y') }} · 08.00 WIB
                        </strong>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Jumlah Peserta / Unit:</span>
                        <strong style="color: var(--text-dark);">{{ $booking->participant_count }} Orang / Unit</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Pemesan:</span>
                        <strong style="color: var(--text-dark);">{{ $booking->customer_name }} ({{ $booking->customer_phone }})</strong>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Total Tagihan:</span>
                        <strong style="font-family: var(--font-heading); font-size: 1.25rem; font-weight: 900; color: var(--accent);">
                            {{ $booking->formatted_total }}
                        </strong>
                    </div>
                    <div>
                        <span style="font-size: 0.78rem; color: var(--text-muted); display: block;">Metode Pembayaran:</span>
                        <strong style="color: var(--text-dark);">{{ $booking->payment_method }}</strong>
                    </div>
                </div>

                <!-- Meeting Point Info -->
                <div style="background-color: var(--bg-sage); border: 1px solid var(--bg-pill); border-radius: var(--radius-md); padding: 16px 20px; margin-bottom: 24px;">
                    <strong style="color: var(--primary); display: flex; align-items: center; gap: 8px; margin-bottom: 4px; font-size: 0.92rem;">
                        <i class="bi bi-geo-alt-fill" style="color: var(--accent);"></i> Titik Kumpul (Meeting Point):
                    </strong>
                    <p style="color: var(--text-dark); font-size: 0.9rem; margin: 0;">
                        {{ $booking->package->meeting_point ?? 'Basecamp Yuk Keliling Sentul, Hambalang / Babakan Madang, Kab. Bogor' }}
                    </p>
                </div>

                <!-- Bukti Transfer Status & Direct Upload Form -->
                <div style="margin-bottom: 24px;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary); display: block; margin-bottom: 8px;">Status Bukti Pembayaran:</span>
                    @if ($booking->payment_proof)
                        @php
                            $trackProofUrl = \App\Models\YksBooking::resolveProofUrl($booking->payment_proof);
                        @endphp
                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; color: #065F46; font-weight: 600; background-color: #EBF8F2; padding: 14px 18px; border-radius: var(--radius-md); border: 1px solid #A7E3CB;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="bi bi-check-circle-fill" style="font-size: 1.2rem; color: #10b981;"></i>
                                <span>Bukti transfer telah terunggah.</span>
                                @if($trackProofUrl)
                                    <a href="{{ $trackProofUrl }}" target="_blank" style="text-decoration: underline; color: #065F46; font-size: 0.82rem; margin-left: 6px;">
                                        Lihat Bukti ↗
                                    </a>
                                @endif
                            </div>
                            <a href="{{ route('payment.show', $booking->booking_code) }}" class="btn btn-outline btn-sm">
                                Ganti Bukti Transfer
                            </a>
                        </div>
                    @else
                        <div style="background-color: #FEF3C7; border: 1px solid #FCD34D; padding: 18px 20px; border-radius: var(--radius-md);">
                            <div style="color: #92400E; font-size: 0.9rem; font-weight: 600; margin-bottom: 12px;">
                                <i class="bi bi-exclamation-circle-fill"></i> Anda belum mengunggah bukti transfer pembayaran. Unggah langsung di bawah ini:
                            </div>
                            <form method="POST" action="{{ route('payment.upload', $booking->booking_code) }}" enctype="multipart/form-data" style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                                @csrf
                                <input type="file" name="payment_proof" class="form-control" accept="image/*" required style="flex: 1; min-width: 220px; background: #FFFFFF;">
                                <button type="submit" class="btn btn-primary btn-sm" style="padding: 11px 18px;">
                                    <i class="bi bi-upload"></i> Unggah Bukti Sekarang &nbsp;↗
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20konfirmasi%20status%20reservasi%20dengan%20Kode%20Booking:%20{{ urlencode($booking->booking_code) }}"
                       target="_blank"
                       class="btn btn-primary" style="flex: 1.2;">
                        <i class="bi bi-whatsapp"></i> Hubungi Admin via WA &nbsp;↗
                    </a>
                    <a href="{{ route('payment.show', $booking->booking_code) }}" class="btn btn-outline" style="flex: 1;">
                        <i class="bi bi-bank"></i> Lihat Nomor Rekening & Instruksi Bayar
                    </a>
                </div>
            </div>

            <!-- Figma "Setelah ini, apa?" Card -->
            <div class="resp-card-pad" style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg);">
                <h3 style="font-size: 1.35rem; color: var(--primary); margin-bottom: 18px;">Setelah ini, apa?</h3>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--bg-sage); color: var(--primary); font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">1</div>
                        <div>
                            <strong style="color: var(--primary); display: block; font-size: 0.96rem;">Admin memeriksa bukti</strong>
                            <span style="color: var(--text-muted); font-size: 0.88rem;">Admin mencocokkan nominal transfer, rekening tujuan, dan ketersediaan jadwal keberangkatan.</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--bg-sage); color: var(--primary); font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">2</div>
                        <div>
                            <strong style="color: var(--primary); display: block; font-size: 0.96rem;">Terima konfirmasi perjalanan</strong>
                            <span style="color: var(--text-muted); font-size: 0.88rem;">Jika sesuai, status di halaman ini berubah menjadi <strong>Lunas</strong> dan admin mengirim panduan titik temu via WhatsApp.</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--bg-sage); color: var(--primary); font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">3</div>
                        <div>
                            <strong style="color: var(--primary); display: block; font-size: 0.96rem;">Siapkan hari jelajahmu</strong>
                            <span style="color: var(--text-muted); font-size: 0.88rem;">Kenakan pakaian nyaman, bawa pakaian ganti, dan tiba di Basecamp YKS 30 menit sebelum sesi dimulai.</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
    function copyBookingCode(code, btn) {
        navigator.clipboard.writeText(code).then(() => {
            const oldHtml = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2"></i> Tersalin!';
            setTimeout(() => { btn.innerHTML = oldHtml; }, 2000);
        });
    }
</script>
@endsection
