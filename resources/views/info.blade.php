@extends('layouts.app', ['title' => 'Informasi Admin & Pembayaran'])

@section('content')
<style>
    .info-top-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 36px;
        align-items: start;
    }
    @media (max-width: 960px) {
        .info-top-grid {
            grid-template-columns: 1fr;
        }
    }
    .info-steps-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        align-items: stretch;
    }
    @media (max-width: 960px) {
        .info-steps-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 560px) {
        .info-steps-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Bagian Atas: INFORMASI ADMIN & PEMBAYARAN (Figma ON GOING #73:4887) -->
<section style="background: #F8F5EF; padding: 48px 0 72px;">
    <div class="container">
        <h1 style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: #6B4F3A; letter-spacing: 0.02em; margin-bottom: 32px;">
            INFORMASI ADMIN &amp; PEMBAYARAN
        </h1>

        <div class="info-top-grid">
            <!-- KOLOM KIRI: Kartu Putih Admin YKS -->
            <div style="background: #FFFFFF; border-radius: 16px; padding: 36px 32px; box-shadow: 0 2px 12px rgba(31, 61, 43, 0.04);">
                <div style="font-size: 0.72rem; font-weight: 700; color: #6B4F3A; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 10px;">
                    ADMIN YKS
                </div>

                <h2 style="font-family: var(--font-heading); font-size: 1.9rem; font-weight: 800; color: #1F3D2B; line-height: 1.2; margin-bottom: 24px;">
                    Bantu rencanakan jelajahmu.
                </h2>

                <!-- Jam Operasional -->
                <div style="margin-bottom: 22px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.92rem; font-weight: 700; color: #1F3D2B; margin-bottom: 8px;">
                        <i class="bi bi-clock" style="font-size: 1rem;"></i>
                        <span>Jam operasional</span>
                    </div>
                    <div style="font-size: 0.88rem; color: #4B554E; margin-bottom: 8px;">
                        Senin–Minggu · 08.00–19.00 WIB
                    </div>
                    <p style="font-size: 0.78rem; color: #718074; line-height: 1.6; margin: 0;">
                        Pesan di luar jam layanan dibalas pada jam operasional berikutnya. Jadwal tur mengikuti konfirmasi terpisah.
                    </p>
                </div>

                <div style="height: 1px; background: #E6E6DE; margin: 22px 0;"></div>

                <!-- Titik Pertemuan -->
                <div style="margin-bottom: 24px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 0.92rem; font-weight: 700; color: #1F3D2B; margin-bottom: 8px;">
                        <i class="bi bi-geo-alt" style="font-size: 1rem;"></i>
                        <span>Titik pertemuan</span>
                    </div>
                    <div style="font-size: 0.85rem; color: #4B554E; line-height: 1.55;">
                        Pos Jelajah YKS, Kawasan Sentul, Babakan Madang, Bogor, Jawa Barat.
                    </div>
                </div>

                <!-- Tombol Hubungi Admin -->
                <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20bertanya%20mengenai%20paket%20dan%20pembayaran."
                   target="_blank"
                   style="display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; height: 48px; border: 1px solid #DEDED4; border-radius: 10px; background: #FFFFFF; color: #1F3D2B; font-size: 0.88rem; font-weight: 700; text-decoration: none; margin-bottom: 20px; transition: background 0.15s ease;">
                    Hubungi Admin &nbsp;↗
                </a>

                <div style="font-size: 0.78rem; color: #718074;">
                    Instagram: @yuk_keliling_sentul
                </div>
            </div>

            <!-- KOLOM KANAN: Transfer Bank & Kartu Rekening -->
            <div style="padding-top: 4px;">
                <div style="font-size: 0.72rem; font-weight: 700; color: #6B4F3A; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 8px;">
                    TRANSFER BANK
                </div>

                <h2 style="font-family: var(--font-heading); font-size: 2rem; font-weight: 800; color: #1F3D2B; line-height: 1.2; margin-bottom: 12px;">
                    Cek dulu. Baru transfer.
                </h2>

                <p style="font-size: 0.92rem; color: #667066; line-height: 1.6; margin-bottom: 24px;">
                    Area rekening resmi ditampilkan seperti contoh berikut. Cocokkan bank, nomor, dan nama penerima dengan admin sebelum pembayaran.
                </p>

                <!-- Kartu Rekening Hijau Tua (#1F3D2B) -->
                <div style="background: #1F3D2B; border-radius: 14px; padding: 26px 28px; color: #FFFFFF; margin-bottom: 16px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 1rem; font-weight: 700; color: #FFFFFF; margin-bottom: 18px;">
                        <i class="bi bi-bank" style="font-size: 1.15rem;"></i>
                        <span>Bank Contoh</span>
                    </div>

                    <div style="font-size: 0.68rem; color: #BDCCBE; letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 6px;">
                        NOMOR REKENING CONTOH
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; flex-wrap: wrap;">
                        <span id="rekContohText" style="font-family: var(--font-heading); font-size: 1.65rem; font-weight: 800; letter-spacing: 1.5px; color: #FFFFFF;">
                            0000 0000 00
                        </span>

                        <button type="button" onclick="copyRekening('0000 0000 00', this)"
                                style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.28); border-radius: 8px; padding: 7px 14px; color: #FFFFFF; font-size: 0.78rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="bi bi-copy"></i> Salin
                        </button>
                    </div>

                    <div style="font-size: 0.82rem; color: #DFE7DF; padding-bottom: 14px; border-bottom: 1px solid rgba(255, 255, 255, 0.14);">
                        a.n. YKS ·
                    </div>
                </div>

                <!-- Kotak Peringatan Keamanan -->
                <div style="background: #F4E6D6; border-radius: 10px; padding: 14px 16px; display: flex; align-items: flex-start; gap: 10px; font-size: 0.78rem; color: #6B4F3A; line-height: 1.55;">
                    <i class="bi bi-info-circle" style="font-size: 0.9rem; flex-shrink: 0; margin-top: 1px;"></i>
                    <span>
                        Admin tidak akan meminta PIN, kata sandi, atau kode OTP. Abaikan permintaan transfer ke rekening pribadi yang tidak sesuai konfirmasi.
                    </span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bagian Bawah: ALUR PEMBAYARAN 4 Langkah (Figma ON GOING) -->
<section style="background: #EAF0E7; padding: 68px 0 80px;">
    <div class="container">
        <h2 style="font-family: var(--font-heading); font-size: 1.85rem; font-weight: 800; color: #6B4F3A; letter-spacing: 0.02em; margin-bottom: 28px;">
            ALUR PEMBAYARAN
        </h2>

        <div class="info-steps-grid">
            <!-- Step 01 -->
            <div style="background: #FFFFFF; border-radius: 16px; padding: 26px 24px;">
                <div style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: #F26B1D; margin-bottom: 10px;">
                    01
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 1.05rem; font-weight: 800; color: #1F3D2B; margin-bottom: 10px;">
                    Periksa ringkasan
                </h3>
                <p style="font-size: 0.82rem; color: #667066; line-height: 1.6; margin: 0;">
                    Periksa paket, jadwal, peserta, dan add-on terpilih. Motor pribadi Rp200.000 + add-on pilihan. Semua add-on: total Rp750.000.
                </p>
            </div>

            <!-- Step 02 -->
            <div style="background: #FFFFFF; border-radius: 16px; padding: 26px 24px;">
                <div style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: #F26B1D; margin-bottom: 10px;">
                    02
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 1.05rem; font-weight: 800; color: #1F3D2B; margin-bottom: 10px;">
                    Konfirmasi rekening
                </h3>
                <p style="font-size: 0.82rem; color: #667066; line-height: 1.6; margin: 0;">
                    Minta tujuan transfer aktif dan cocokkan nama penerima.
                </p>
            </div>

            <!-- Step 03 -->
            <div style="background: #FFFFFF; border-radius: 16px; padding: 26px 24px;">
                <div style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: #F26B1D; margin-bottom: 10px;">
                    03
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 1.05rem; font-weight: 800; color: #1F3D2B; margin-bottom: 10px;">
                    Transfer &amp; simpan bukti
                </h3>
                <p style="font-size: 0.82rem; color: #667066; line-height: 1.6; margin: 0;">
                    Bayar sesuai nominal, lalu simpan bukti transfer yang terbaca.
                </p>
            </div>

            <!-- Step 04 -->
            <div style="background: #FFFFFF; border-radius: 16px; padding: 26px 24px;">
                <div style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 800; color: #F26B1D; margin-bottom: 10px;">
                    04
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 1.05rem; font-weight: 800; color: #1F3D2B; margin-bottom: 10px;">
                    Unggah &amp; tunggu admin
                </h3>
                <p style="font-size: 0.82rem; color: #667066; line-height: 1.6; margin: 0;">
                    Kirim bukti melalui langkah Pembayaran. Admin memeriksa sebelum mengonfirmasi.
                </p>
            </div>
        </div>

        <!-- Banner Informasi Konfirmasi Bawah -->
        <div style="background: #F4E6D6; border-radius: 10px; padding: 14px 18px; margin-top: 24px; display: flex; align-items: center; gap: 10px; font-size: 0.78rem; color: #6B4F3A;">
            <i class="bi bi-info-circle" style="font-size: 0.9rem; flex-shrink: 0;"></i>
            <span>
                Pembayaran belum berarti reservasi otomatis dikonfirmasi. Tunggu pesan admin dan status "Dikonfirmasi" sebelum datang ke titik pertemuan.
            </span>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function copyRekening(text, btnElement) {
        navigator.clipboard.writeText(text).then(() => {
            const originalHTML = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="bi bi-check2"></i> Tersalin';
            setTimeout(() => {
                btnElement.innerHTML = originalHTML;
            }, 2000);
        });
    }
</script>
@endsection
