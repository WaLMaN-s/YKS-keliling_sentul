@extends('layouts.admin', ['title' => 'Verifikasi Pembayaran'])

@section('content')
<style>
    .verif-grid {
        display: grid;
        grid-template-columns: 1fr 1.25fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1050px) {
        .verif-grid {
            grid-template-columns: 1fr;
        }
    }
    .queue-item {
        padding: 14px 16px;
        border: 1px solid #ECECE6;
        border-radius: 10px;
        background: #FFFFFF;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: inherit;
        transition: all 0.16s ease;
    }
    .queue-item:hover {
        border-color: #C1D1BE;
        background: #FCFCFA;
    }
    .queue-item.active {
        border: 1.5px solid #1F3D2B;
        border-left: 4px solid #F26B1D;
        background: #F8F5EF;
    }
</style>

<!-- Header Bar (Figma: Administrasi / Verifikasi Pembayaran + Badge Menunggu verifikasi) -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Verifikasi Pembayaran</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Periksa bukti transfer dan cocokkan dengan tagihan reservasi.</p>
    </div>

    <span style="background: #FEEFE6; color: #F26B1D; border: 1px solid #FBD5BD; padding: 6px 14px; border-radius: 99px; font-size: 0.82rem; font-weight: 700;">
        Menunggu verifikasi ({{ count($allPending) }})
    </span>
</div>

<div class="verif-grid">
    <!-- KOLOM KIRI: Antrean Pembayaran -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Antrean pembayaran</h3>
                <span style="font-size: 0.78rem; color: #6B756E;">{{ count($pendingPayments) }} bukti · terlama lebih dahulu</span>
            </div>

            <form action="{{ route('admin.verifications.index') }}" method="GET" style="display: flex; gap: 6px;">
                <div style="position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6B756E; font-size: 0.78rem;"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari ID / nama…"
                           style="padding: 6px 10px 6px 28px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.8rem; width: 170px;">
                </div>
            </form>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            @forelse($pendingPayments as $item)
                @php
                    $isCurrent = ($selectedItem && $selectedItem->booking_code === $item->booking_code);
                @endphp
                <a href="{{ route('admin.verifications.index', ['select' => $item->booking_code, 'q' => $search]) }}"
                   class="queue-item {{ $isCurrent ? 'active' : '' }}">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                            <span style="font-weight: 700; font-size: 0.92rem; color: #1F3D2B;">{{ $item->customer_name }}</span>
                            <span style="background: #FEEFE6; color: #F26B1D; font-size: 0.7rem; font-weight: 600; padding: 2px 7px; border-radius: 99px;">
                                {{ \Carbon\Carbon::parse($item->updated_at)->diffForHumans() }}
                            </span>
                        </div>
                        <div style="font-size: 0.8rem; color: #6B756E;">
                            <strong>{{ $item->booking_code }}</strong> · {{ $item->package_name }}
                        </div>
                        <div style="font-size: 0.86rem; font-weight: 700; color: #1F3D2B; margin-top: 4px;">
                            Rp{{ number_format($item->total_price, 0, ',', '.') }}
                            <span style="font-weight: 400; font-size: 0.74rem; color: #6B756E;">
                                · Dikirim {{ date('d M · H.i', strtotime($item->updated_at)) }} WIB
                            </span>
                        </div>
                    </div>

                    <div style="font-size: 0.78rem; font-weight: 700; color: {{ $isCurrent ? '#F26B1D' : '#1F3D2B' }}; white-space: nowrap;">
                        {{ $isCurrent ? 'Sedang diperiksa →' : 'Periksa →' }}
                    </div>
                </a>
            @empty
                <div style="text-align: center; padding: 40px 20px; color: #6B756E;">
                    <i class="bi bi-check2-circle" style="font-size: 2.5rem; color: #1F3D2B; display: block; margin-bottom: 10px;"></i>
                    <strong style="color: #1F3D2B; display: block;">Antrean Verifikasi Kosong</strong>
                    <span style="font-size: 0.82rem;">Seluruh bukti pembayaran telah selesai diperiksa.</span>
                </div>
            @endforelse
        </div>
    </div>

    <!-- KOLOM KANAN: Pemeriksaan Bukti Pembayaran (Figma) -->
    <div class="stat-card">
        @if($selectedItem)
            <!-- Header Pemeriksaan -->
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid #ECECE6; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #6B756E; font-weight: 700;">
                        Pemeriksaan bukti pembayaran
                    </span>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #1F3D2B; margin-top: 2px;">
                        {{ $selectedItem->booking_code }} · {{ $selectedItem->customer_name }}
                    </h3>
                    <span style="font-size: 0.76rem; color: #6B756E;">
                        Dikirim {{ date('d M Y · H.i', strtotime($selectedItem->updated_at)) }} WIB
                    </span>
                </div>

                <a href="{{ route('admin.reservations.index', ['select' => $selectedItem->booking_code]) }}" class="btn-outline-yks" style="font-size: 0.78rem; padding: 6px 12px;">
                    Buka reservasi ↗
                </a>
            </div>

            <!-- Preview Gambar Bukti dengan Kontrol Zoom (− 100% +) -->
            @php
                $verifProofUrl = \App\Models\YksBooking::resolveProofUrl($selectedItem->payment_proof);
            @endphp
            <div style="background: #F8F5EF; border: 1px solid #DFE4DC; border-radius: 10px; padding: 14px; margin-bottom: 18px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; flex-wrap: wrap; gap: 8px;">
                    <span style="font-size: 0.78rem; font-weight: 600; color: #1F3D2B;">
                        <i class="bi bi-file-earmark-image"></i>
                        {{ $selectedItem->payment_proof ? basename($selectedItem->payment_proof) : 'bukti-transfer-' . strtolower($selectedItem->booking_code) . '.jpg' }}
                    </span>

                    <div style="display: inline-flex; align-items: center; gap: 8px;">
                        @if($verifProofUrl)
                            <a href="{{ $verifProofUrl }}" target="_blank" style="font-size: 0.75rem; font-weight: 600; color: #F26B1D; text-decoration: none; padding: 3px 8px; border-radius: 6px; background: #FFF; border: 1px solid #DFE4DC;">
                                <i class="bi bi-box-arrow-up-right"></i> Buka Asli ↗
                            </a>
                        @endif

                        <div style="display: inline-flex; align-items: center; gap: 6px; background: #FFFFFF; border: 1px solid #DFE4DC; border-radius: 6px; padding: 2px 8px;">
                            <button type="button" onclick="adjustProofZoom(-20)" style="border: none; background: none; cursor: pointer; font-weight: 700; color: #1F3D2B; padding: 2px 4px;">−</button>
                            <span id="zoomLabel" style="font-size: 0.75rem; font-weight: 600; min-width: 38px; text-align: center;">100%</span>
                            <button type="button" onclick="adjustProofZoom(20)" style="border: none; background: none; cursor: pointer; font-weight: 700; color: #1F3D2B; padding: 2px 4px;">+</button>
                        </div>
                    </div>
                </div>

                <div style="min-height: 260px; max-height: 380px; overflow: auto; background: #FFFFFF; border: 1px solid #ECECE6; border-radius: 8px; display: flex; align-items: center; justify-content: center; padding: 14px;">
                    @if($verifProofUrl)
                        <a href="{{ $verifProofUrl }}" target="_blank" title="Klik untuk membuka ukuran penuh di tab baru">
                            <img id="proofImg" src="{{ $verifProofUrl }}" alt="Bukti Transfer"
                                 style="max-width: 100%; max-height: 340px; object-fit: contain; transition: transform 0.2s; cursor: zoom-in; border-radius: 6px;">
                        </a>
                    @else
                        <!-- Ilustrasi Struk Transfer sesuai Figma jika bukti contoh -->
                        <div id="proofImg" style="border: 1px dashed #C1D1BE; border-radius: 8px; padding: 24px; background: #FCFCFA; text-align: center; width: 340px; transition: transform 0.2s;">
                            <div style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: #6B756E; font-weight: 700;">BUKTI TRANSFER BANK</div>
                            <div style="font-size: 1.35rem; font-weight: 800; color: #1F3D2B; margin: 8px 0;">Rp{{ number_format($selectedItem->total_price, 0, ',', '.') }}</div>
                            <div style="font-size: 0.75rem; color: #6B756E;">Metode: {{ $selectedItem->payment_method }}</div>
                            <div style="font-size: 0.74rem; color: #1F3D2B; margin-top: 6px; font-weight: 600;">Ref: {{ $selectedItem->booking_code }}</div>
                        </div>
                    @endif
                </div>

                <!-- Admin Upload/Update Bukti -->
                <details style="margin-top: 10px; font-size: 0.76rem; color: #6B756E;">
                    <summary style="cursor: pointer; color: #1F3D2B; font-weight: 600;">
                        <i class="bi bi-upload"></i> {{ $verifProofUrl ? 'Ganti berkas bukti (dari WhatsApp)' : 'Unggah berkas bukti (dari WhatsApp)' }}
                    </summary>
                    <form action="{{ route('admin.verifications.upload_proof', $selectedItem->booking_code) }}" method="POST" enctype="multipart/form-data" style="margin-top: 8px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                        @csrf
                        <input type="file" name="payment_proof" accept="image/*" required style="font-size: 0.75rem; padding: 5px 8px; border: 1px solid #DFE4DC; border-radius: 6px; background: #FFF; flex: 1;">
                        <button type="submit" class="btn-outline-yks" style="padding: 5px 12px; font-size: 0.75rem;">
                            Unggah
                        </button>
                    </form>
                </details>
            </div>

            <!-- Rincian Pesanan & Perbandingan Tagihan (Figma) -->
            <div style="display: grid; grid-template-columns: 1.15fr 1fr; gap: 16px; margin-bottom: 18px;">
                <div style="background: #F8F5EF; border-radius: 10px; padding: 14px; font-size: 0.82rem;">
                    <div style="font-weight: 700; color: #1F3D2B; margin-bottom: 10px;">Rincian pesanan</div>
                    <div style="display: grid; grid-template-columns: 110px 1fr; row-gap: 6px;">
                        <span style="color: #6B756E;">Nama pelanggan</span>
                        <strong style="color: #1F3D2B;">{{ $selectedItem->customer_name }}</strong>

                        <span style="color: #6B756E;">Kontak</span>
                        <span>{{ $selectedItem->customer_phone }}</span>

                        <span style="color: #6B756E;">Paket</span>
                        <strong style="color: #1F3D2B;">{{ $selectedItem->package_name }}</strong>

                        <span style="color: #6B756E;">Jadwal</span>
                        <span>{{ date('d M Y', strtotime($selectedItem->trip_date)) }} · 08.00 WIB</span>

                        <span style="color: #6B756E;">Jumlah</span>
                        <span>{{ $selectedItem->participant_count }} peserta × Rp{{ number_format($selectedItem->package_price, 0, ',', '.') }}</span>

                        <span style="color: #6B756E;">Metode</span>
                        <span>{{ $selectedItem->payment_method }}</span>
                    </div>
                </div>

                <div style="background: #EAF0E7; border: 1px solid #C1D1BE; border-radius: 10px; padding: 14px; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <span style="font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.06em; color: #1F3D2B; font-weight: 700;">
                            TAGIHAN YANG DIHARAPKAN
                        </span>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #1F3D2B; margin-top: 4px;">
                            Rp{{ number_format($selectedItem->total_price, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 0.78rem; color: #304F3B; margin-top: 4px;">
                            Nominal pada bukti: <strong>Rp{{ number_format($selectedItem->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>

                    <div style="margin-top: 12px;">
                        <span style="display: inline-block; background: #1F3D2B; color: #FFFFFF; font-size: 0.72rem; font-weight: 600; padding: 4px 10px; border-radius: 99px;">
                            ✓ Nominal sesuai · selisih Rp0
                        </span>
                    </div>
                </div>
            </div>

            <div style="font-size: 0.76rem; color: #6B756E; margin-bottom: 16px; background: #FCFCFA; padding: 8px 12px; border-radius: 6px; border: 1px solid #ECECE6;">
                ⓘ Kecocokan nominal belum berarti pembayaran terkonfirmasi. Periksa mutasi rekening resmi sebelum menyetujui.
            </div>

            <!-- Keputusan Pemeriksaan (Setujui / Tolak dengan Alasan) -->
            <div style="border-top: 1px solid #ECECE6; padding-top: 16px;">
                <div style="font-weight: 700; font-size: 0.9rem; color: #1F3D2B; margin-bottom: 10px;">Keputusan pemeriksaan</div>

                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <!-- Form Tolak dengan Alasan -->
                    <form action="{{ route('admin.verifications.reject', $selectedItem->booking_code) }}" method="POST"
                          onsubmit="return confirm('Tolak bukti pembayaran ini dan minta pelanggan mengirim ulang?')"
                          style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        @csrf
                        <input type="text" name="rejection_reason" placeholder="Tulis alasan penolakan jika bukti tidak sesuai…"
                               style="flex: 1; min-width: 220px; padding: 9px 12px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem;">
                        <button type="submit" style="background: #FFFFFF; border: 1px solid #E57373; color: #D32F2F; padding: 9px 16px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; cursor: pointer;">
                            × Tolak Bukti
                        </button>
                    </form>

                    <!-- Form Setujui Pembayaran -->
                    <form action="{{ route('admin.verifications.approve', $selectedItem->booking_code) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary-yks" style="width: 100%; justify-content: center; padding: 11px 20px; font-size: 0.9rem;">
                            <i class="bi bi-check2-circle"></i> Setujui Pembayaran & Konfirmasi Jadwal (Lunas)
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 60px 20px; color: #6B756E;">
                <i class="bi bi-receipt" style="font-size: 2.5rem; color: #C1D1BE; display: block; margin-bottom: 10px;"></i>
                Pilih salah satu item pada antrean di sebelah kiri untuk memeriksa bukti pembayaran.
            </div>
        @endif
    </div>
</div>

<script>
    let currentZoom = 100;
    function adjustProofZoom(delta) {
        currentZoom = Math.min(200, Math.max(60, currentZoom + delta));
        const lbl = document.getElementById('zoomLabel');
        const img = document.getElementById('proofImg');
        if (lbl) lbl.textContent = currentZoom + '%';
        if (img) img.style.transform = `scale(${currentZoom / 100})`;
    }
</script>
@endsection
