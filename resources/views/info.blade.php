@extends('layouts.app')

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; <span>Informasi Admin & Pembayaran</span>
    </div>
    <div class="section-tag">
        ━ &nbsp; INFORMASI ADMIN & PEMBAYARAN
    </div>
    <h1 class="section-title" style="font-size: 2.8rem; max-width: 850px; margin-bottom: 16px;">
        Pusat Layanan & Rekening Resmi YKS.
    </h1>
    <p class="section-desc" style="font-size: 1.15rem; max-width: 720px; margin-bottom: 32px;">
        Temukan jam layanan customer service, panduan titik kumpul, dan nomor rekening resmi yang terverifikasi aman untuk transaksi Anda.
    </p>

    <!-- Official Notice Warning Box -->
    <div style="background-color: #FEF3C7; border: 1px solid #FCD34D; border-radius: var(--radius-md); padding: 18px 24px; display: flex; align-items: flex-start; gap: 14px; margin-bottom: 48px;">
        <i class="bi bi-shield-lock-fill" style="color: #D97706; font-size: 1.5rem; flex-shrink: 0;"></i>
        <div>
            <strong style="color: #92400E; font-size: 0.95rem;">Perhatian Keamanan Transaksi:</strong>
            <p style="color: #B45309; font-size: 0.88rem; margin-top: 4px; line-height: 1.5;">
                Pastikan Anda hanya melakukan pembayaran ke rekening bank resmi yang tercantum di halaman ini. Kami tidak bertanggung jawab atas pembayaran ke rekening pribadi perorangan selain atas nama resmi pengelola YKS.
            </p>
        </div>
    </div>
</div>

<!-- Main Two Column Layout (Layanan Admin & Rekening Bank) -->
<section class="section-wrapper section-white-bg" style="padding-top: 48px;">
    <div class="container">
        <div style="display: grid; grid-template-columns: 1fr 1.25fr; gap: 48px; align-items: start;">
            <!-- Kolom Kiri: Layanan Admin YKS -->
            <div style="background-color: var(--bg-body); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 36px;">
                <div class="section-tag">
                    ━ &nbsp; ADMIN LAYANAN YKS
                </div>
                <h2 style="font-size: 1.8rem; color: var(--primary); margin-bottom: 24px;">
                    Bantu rencanakan jelajahmu.
                </h2>

                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <div>
                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 0.8px; display: block; margin-bottom: 4px;">
                            Jam Operasional Admin:
                        </span>
                        <strong style="font-size: 1.1rem; color: var(--primary);">Senin – Minggu · 08.00 – 17.00 WIB</strong>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                            Pesan masuk di luar jam layanan tetap akan dicatat dan direspon cepat pada pagi hari berikutnya.
                        </p>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--border-light);">

                    <div>
                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 0.8px; display: block; margin-bottom: 4px;">
                            Titik Pertemuan (Meeting Point):
                        </span>
                        <strong style="font-size: 1.1rem; color: var(--primary);">Pos Jelajah YKS Sentul</strong>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                            Jl. Babakan Madang, Sentul Selatan, Kabupaten Bogor, Jawa Barat 16810 (Dekat Gerbang Tol Sentul Selatan).
                        </p>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--border-light);">

                    <div>
                        <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-sub); text-transform: uppercase; letter-spacing: 0.8px; display: block; margin-bottom: 4px;">
                            Kontak Cepat:
                        </span>
                        <p style="font-size: 0.95rem; color: var(--text-dark); margin-top: 4px;">
                            WhatsApp: <strong>+62 812-3456-7890</strong><br>
                            Email: <strong>info@kelilingsentul.id</strong>
                        </p>
                    </div>

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20tanya%20info%20pembayaran" target="_blank" class="btn btn-primary btn-block" style="margin-top: 12px;">
                        <i class="bi bi-whatsapp"></i> Hubungi Admin WhatsApp &nbsp;↗
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan: Rekening Bank Resmi -->
            <div>
                <div class="section-tag">
                    ━ &nbsp; TRANSFER BANK RESMI
                </div>
                <h2 style="font-size: 1.8rem; color: var(--primary); margin-bottom: 12px;">
                    Cek dulu. Baru transfer.
                </h2>
                <p style="font-size: 0.92rem; color: var(--text-muted); margin-bottom: 28px;">
                    Silakan gunakan salah satu rekening resmi kami di bawah ini untuk penyelesaian tagihan booking trip Anda:
                </p>

                <!-- Rekening Cards -->
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- BCA Card -->
                    <div style="background-color: #1F3D2B; color: #FFFFFF; border-radius: var(--radius-lg); padding: 28px; position: relative;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span style="font-weight: 800; font-size: 1.3rem; letter-spacing: 1px; color: #FFFFFF;">BANK BCA</span>
                            <span style="background-color: rgba(255, 255, 255, 0.15); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Utama</span>
                        </div>
                        <span style="font-size: 0.75rem; color: #CBD9C8; letter-spacing: 1px; text-transform: uppercase;">Nomor Rekening Resmi</span>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 4px; margin-bottom: 12px;">
                            <span id="rekBca" style="font-family: var(--font-heading); font-size: 1.7rem; font-weight: 800; letter-spacing: 2px; color: #FFFFFF;">8450-1234-5678</span>
                            <button onclick="copyToClipboard('845012345678', this)" class="btn btn-sm" style="background-color: rgba(255, 255, 255, 0.2); color: #fff; border: 1px solid rgba(255, 255, 255, 0.3);">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                        </div>
                        <div style="font-size: 0.9rem; color: #E3E9E0;">
                            Atas Nama: <strong>PT YUK KELILING SENTUL</strong>
                        </div>
                    </div>

                    <!-- Mandiri Card -->
                    <div style="background-color: #FFFFFF; border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 28px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span style="font-weight: 800; font-size: 1.3rem; color: var(--primary); letter-spacing: 1px;">BANK MANDIRI</span>
                            <span style="background-color: var(--bg-sage); color: var(--primary); padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700;">Alternatif</span>
                        </div>
                        <span style="font-size: 0.75rem; color: var(--text-sub); letter-spacing: 1px; text-transform: uppercase;">Nomor Rekening Resmi</span>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 4px; margin-bottom: 12px;">
                            <span id="rekMandiri" style="font-family: var(--font-heading); font-size: 1.7rem; font-weight: 800; letter-spacing: 2px; color: var(--primary);">1330-0987-6543-2</span>
                            <button onclick="copyToClipboard('1330098765432', this)" class="btn btn-sm btn-outline">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                        </div>
                        <div style="font-size: 0.9rem; color: var(--text-dark);">
                            Atas Nama: <strong>PT YUK KELILING SENTUL</strong>
                        </div>
                    </div>

                    <!-- QRIS Support Card -->
                    <div style="background-color: var(--bg-sage); border: 1px solid var(--bg-pill); border-radius: var(--radius-lg); padding: 24px; display: flex; align-items: center; gap: 20px;">
                        <div style="width: 54px; height: 54px; background-color: var(--primary); color: #fff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; flex-shrink: 0;">
                            <i class="bi bi-qr-code"></i>
                        </div>
                        <div>
                            <strong style="color: var(--primary); font-size: 1.05rem;">Dukungan QRIS Real-time</strong>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 2px;">
                                Terima pembayaran instan melalui GoPay, OVO, Dana, ShopeePay, LinkAja, dan seluruh aplikasi Mobile Banking.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Panduan Pembayaran 3 Langkah -->
<section class="section-wrapper">
    <div class="container">
        <div class="section-tag">
            ━ &nbsp; ALUR PEMBAYARAN MUDAH
        </div>
        <h2 class="section-title">3 Langkah Menyelesaikan Reservasi Anda</h2>
        <p class="section-desc" style="margin-bottom: 40px;">
            Ikuti panduan berikut agar pesanan Anda dapat segera diverifikasi oleh tim administrasi YKS.
        </p>

        <div class="workflow-grid" style="grid-template-columns: repeat(3, 1fr);">
            <div class="step-card">
                <span class="step-number-tag">01</span>
                <h3 class="step-card-title">Transfer Sesuai Tagihan</h3>
                <p class="step-card-desc">
                    Lakukan transfer melalui ATM, m-banking, atau internet banking sesuai total nominal yang tertera pada halaman booking.
                </p>
            </div>

            <div class="step-card">
                <span class="step-number-tag">02</span>
                <h3 class="step-card-title">Simpan & Unggah Bukti</h3>
                <p class="step-card-desc">
                    Ambil tangkapan layar (screenshot) bukti transfer sukses, lalu unggah melalui menu <a href="{{ route('booking.track') }}" style="color: var(--accent); font-weight: 700;">Lacak Trip</a> dengan memasukkan kode booking.
                </p>
            </div>

            <div class="step-card">
                <span class="step-number-tag">03</span>
                <h3 class="step-card-title">Verifikasi & E-Tiket</h3>
                <p class="step-card-desc">
                    Dalam maksimal 15–30 menit (jam kerja), admin akan memvalidasi bukti Anda dan menerbitkan status Terkonfirmasi beserta share location guide.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Tanya Jawab Pembayaran -->
<section class="section-wrapper section-white-bg">
    <div class="container">
        <div class="section-tag">
            ━ &nbsp; TANYA JAWAB PEMBAYARAN
        </div>
        <h2 class="section-title" style="margin-bottom: 32px;">Pertanyaan seputar konfirmasi transfer</h2>

        <div style="display: flex; flex-direction: column; gap: 16px; max-width: 880px;">
            <div class="faq-box active">
                <div class="faq-box-header">
                    <span class="faq-question">Berapa lama batas waktu pembayaran setelah booking?</span>
                </div>
                <div class="faq-answer" style="display: block;">
                    Batas waktu pembayaran adalah <strong>2 x 24 jam</strong> setelah formulir booking disubmit, atau maksimal H-1 sebelum keberangkatan untuk booking mendadak. Lewat dari batas waktu, slot tanggal akan dibuka kembali untuk peserta lain.
                </div>
            </div>

            <div class="faq-box active">
                <div class="faq-box-header">
                    <span class="faq-question">Apakah bisa membayar DP (Uang Muka) terlebih dahulu?</span>
                </div>
                <div class="faq-answer" style="display: block;">
                    Bisa! Khusus untuk rombongan kantor, komunitas, atau booking lebih dari 2 unit jip offroad, Anda dapat membayar DP minimal 50% dan melunasi sisanya di meeting point Sentul. Silakan konfirmasi ke WhatsApp admin setelah booking.
                </div>
            </div>

            <div class="faq-box active">
                <div class="faq-box-header">
                    <span class="faq-question">Bagaimana jika salah transfer atau nominal berlebih?</span>
                </div>
                <div class="faq-answer" style="display: block;">
                    Jangan khawatir. Hubungi langsung admin kami di WhatsApp <strong>+62 812-3456-7890</strong> dengan melampirkan bukti mutasi transfer. Tim keuangan kami akan melakukan penyesuaian atau pengembalian lebih bayar dalam waktu 1x24 jam.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bottom CTA Banner -->
<section class="section-wrapper">
    <div class="container">
        <div class="cta-adventure-banner">
            <div>
                <h2 class="cta-banner-title">Sudah siap untuk petualangan seru?</h2>
                <p class="cta-banner-desc">
                    Tentukan tanggal dan paket favorit Anda sekarang sebelum slot akhir pekan penuh!
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
    function copyToClipboard(text, btnElement) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHTML = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="bi bi-check2"></i> Disalin!';
            setTimeout(() => {
                btnElement.innerHTML = originalHTML;
            }, 2000);
        });
    }
</script>
@endsection
