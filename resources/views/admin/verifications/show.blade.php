@extends('layouts.admin', ['title' => 'Pemeriksaan Bukti Transfer', 'breadcrumb' => 'Pemeriksaan Bukti Transfer'])

@section('content')
<style>
    .inspect-grid {
        display: grid;
        grid-template-columns: 1fr 530px;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1180px) {
        .inspect-grid {
            grid-template-columns: 1fr;
        }
    }
    .inspect-card {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .i-kv {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        font-size: 13px;
        line-height: 1.5;
    }
    .i-kv span:first-child {
        color: #718074;
    }
    .i-kv span:last-child {
        color: #24382D;
        font-weight: 500;
        text-align: right;
    }
    .chk-inspect {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 12px;
        color: #24382D;
        cursor: pointer;
        line-height: 1.5;
    }
    .chk-inspect input[type="checkbox"] {
        width: 17px;
        height: 17px;
        margin-top: 2px;
        accent-color: #1F3D2B;
        cursor: pointer;
        flex-shrink: 0;
    }
    .btn-confirm-pay {
        width: 100%;
        height: 44px;
        border-radius: 8px;
        border: none;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.16s ease;
    }
    .btn-confirm-pay:disabled {
        background: #EAF0E7;
        color: #718074;
        cursor: not-allowed;
    }
    .btn-confirm-pay:not(:disabled) {
        background: #1F3D2B;
        color: #FFFFFF;
        cursor: pointer;
    }
    .btn-confirm-pay:not(:disabled):hover {
        background: #162D1F;
    }
</style>

@php
    $pkgName = $booking->custom_package_name ?: ($booking->catalog_package_name ?? 'Jelajah Jeep');
    $timeSlot = $booking->start_time ?: '08.00–12.00';
    $unitCount = $booking->vehicle_count ?: 1;
    $proofUrl = \App\Models\YksBooking::resolveProofUrl($booking->payment_proof);
    $isPending = ($booking->payment_status === 'Menunggu Verifikasi');
    $isAccepted = ($booking->payment_status === 'Lunas');
    $isRejected = ($booking->payment_status === 'Ditolak');
@endphp

<!-- Identitas Halaman (Figma Frame 178:20131) -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 20px; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px; margin-bottom: 6px;">
            Pemeriksaan Bukti Transfer
        </h1>
        <p style="font-size: 13px; color: #718074;">
            {{ $booking->booking_code }} · {{ $booking->customer_name }} · Diunggah {{ \Carbon\Carbon::parse($booking->updated_at)->translatedFormat('d F Y, H.i') }} WIB
        </p>
    </div>

    <a href="{{ route('admin.verifications.index') }}"
       style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-arrow-left"></i> Kembali ke pembayaran
    </a>
</div>

<!-- Banner Informasi Penting (Figma Frame 178:20138) -->
@if($isPending)
    <div style="background: #FFF0E4; border-radius: 8px; padding: 14px 16px; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 24px;">
        <i class="bi bi-info-circle" style="color: #A54B10; font-size: 15px;"></i>
        <span style="font-size: 12px; color: #A54B10; line-height: 1.5;">
            Menunggu pemeriksaan — bukti ini belum dinyatakan sah dan belum termasuk penerimaan. Cocokkan dengan mutasi rekening sebelum mengambil keputusan.
        </span>
    </div>
@elseif($isAccepted)
    <div style="background: #EAF0E7; border-radius: 8px; padding: 14px 16px; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 24px;">
        <i class="bi bi-check-circle-fill" style="color: #1F3D2B; font-size: 15px;"></i>
        <span style="font-size: 12px; color: #1F3D2B; line-height: 1.5;">
            Diterima — bukti transfer ini telah dikonfirmasi oleh admin dan tercatat sebagai penerimaan sah.
        </span>
    </div>
@else
    <div style="background: #FBEDEB; border-radius: 8px; padding: 14px 16px; display: flex; align-items: flex-start; gap: 10px; margin-bottom: 24px;">
        <i class="bi bi-x-circle-fill" style="color: #AD4940; font-size: 15px;"></i>
        <span style="font-size: 12px; color: #AD4940; line-height: 1.5;">
            Ditolak — bukti transfer ini telah ditolak dan menunggu unggah ulang dari pelanggan.
        </span>
    </div>
@endif

@if($errors->any())
    <div style="background: #FBEDEB; border: 1px solid #E5B4AE; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; color: #AD4940;">
        @foreach($errors->all() as $err)
            <div>• {{ $err }}</div>
        @endforeach
    </div>
@endif

<div class="inspect-grid">
    <!-- KOLOM KIRI: Pratinjau Bukti Transfer & Data Pembayaran (Figma Frame 178:20142) -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Kartu 1: Pratinjau Bukti Transfer -->
        <div class="inspect-card">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B;">
                    Pratinjau bukti transfer
                </h3>
                <span style="background: #FFF0E4; color: #A54B10; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                    {{ $proofUrl ? 'Berkas Pelanggan' : 'Berkas contoh' }}
                </span>
            </div>

            <!-- Area Pratinjau (Figma Frame 178:20149) -->
            <div style="background: #F4F6F1; border-radius: 8px; padding: 24px;">
                @if($proofUrl)
                    <div style="background: #FFFFFF; border: 1px solid #E1E4DA; border-radius: 8px; padding: 16px; text-align: center;">
                        <a href="{{ $proofUrl }}" target="_blank" title="Klik untuk memperbesar di tab baru">
                            <img src="{{ $proofUrl }}" alt="Bukti Transfer {{ $booking->booking_code }}"
                                 style="max-width: 100%; max-height: 360px; object-fit: contain; border-radius: 6px; margin-bottom: 12px;">
                        </a>
                    </div>
                @endif

                <!-- Dokumen Ilustratif Sesuai Figma Frame 178:20150 -->
                <div style="background: #FFFFFF; border: 1px solid #E1E4DA; border-radius: 8px; padding: 28px; display: flex; flex-direction: column; gap: 18px; {{ $proofUrl ? 'margin-top: 16px;' : '' }}">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; font-weight: 600; color: #24382D;">Bank Pengirim</span>
                        <i class="bi bi-bank" style="font-size: 20px; color: #1F3D2B;"></i>
                    </div>

                    <div>
                        <span style="background: #FFF0E4; color: #A54B10; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                            ILUSTRASI · BUKAN BUKTI SAH
                        </span>
                    </div>

                    <div>
                        <div style="font-family: 'Montserrat', sans-serif; font-size: 29px; font-weight: 700; color: #1F3D2B;">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 11px; color: #718074; margin-top: 4px;">
                            {{ \Carbon\Carbon::parse($booking->updated_at)->translatedFormat('d F Y · H.i') }} WIB
                        </div>
                    </div>

                    <div style="height: 1px; background: #E1E4DA;"></div>

                    <div class="i-kv">
                        <span>Pengirim</span>
                        <span>{{ $booking->customer_name }}</span>
                    </div>
                    <div class="i-kv">
                        <span>Bank tujuan</span>
                        <span>Bank Contoh</span>
                    </div>
                    <div class="i-kv">
                        <span>Nomor tujuan</span>
                        <span>0000 0000 00</span>
                    </div>
                    <div class="i-kv">
                        <span>Penerima</span>
                        <span>YKS</span>
                    </div>
                    <div class="i-kv">
                        <span>Referensi simulasi</span>
                        <span>CONTOH-{{ str_replace('YKS-', '', $booking->booking_code) }}</span>
                    </div>

                    <div style="height: 1px; background: #E1E4DA;"></div>

                    <p style="font-size: 10px; color: #718074; line-height: 1.5;">
                        Dokumen ilustratif untuk desain. Bukan transaksi bank dan tidak membuktikan pembayaran.
                    </p>
                </div>
            </div>

            <!-- Informasi Berkas -->
            <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <i class="bi bi-file-earmark-image" style="font-size: 20px; color: #1F3D2B;"></i>
                    <div>
                        <div style="font-size: 12px; font-weight: 600; color: #24382D;">
                            {{ $booking->payment_proof ? basename($booking->payment_proof) : 'bukti-transfer-contoh.jpg' }}
                        </div>
                        <div style="font-size: 11px; color: #718074;">JPG · 320 KB · Berkas ilustrasi</div>
                    </div>
                </div>
                @if($proofUrl)
                    <a href="{{ $proofUrl }}" target="_blank" style="color: #1F3D2B; font-size: 16px;" title="Buka ukuran penuh">
                        <i class="bi bi-arrows-angle-expand"></i>
                    </a>
                @endif
            </div>
        </div>

        <!-- Kartu 2: Data Pembayaran (Figma Frame 178:20185) -->
        <div class="inspect-card">
            <div>
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                    Data pembayaran
                </h3>
                <p style="font-size: 12px; color: #718074;">Data dari formulir pelanggan, perlu diverifikasi</p>
            </div>

            <div class="i-kv">
                <span>Nama pengirim</span>
                <span>{{ $booking->customer_name }}</span>
            </div>
            <div class="i-kv">
                <span>Bank pengirim</span>
                <span>Bank Pengirim</span>
            </div>
            <div class="i-kv">
                <span>Tanggal transfer</span>
                <span>{{ \Carbon\Carbon::parse($booking->updated_at)->translatedFormat('d M Y, H.i') }} WIB</span>
            </div>
            <div class="i-kv">
                <span>Nominal ditagih</span>
                <span>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="i-kv">
                <span>Nominal ditransfer (klaim)</span>
                <span>Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div class="i-kv">
                <span>Selisih nominal</span>
                <span>Rp0 · {{ $isAccepted ? 'Terverifikasi' : 'Belum diverifikasi' }}</span>
            </div>

            <div style="height: 1px; background: #E1E4DA;"></div>

            <div class="i-kv">
                <span>Rekening tujuan contoh</span>
                <span>Bank Contoh · 0000 0000 00</span>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Reservasi Terkait, Checklist Pemeriksaan, Keputusan Pembayaran (Figma Frame 178:20212) -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Kartu 1: Reservasi Terkait (Figma Frame 178:20213) -->
        <div class="inspect-card">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B;">
                    Reservasi terkait
                </h3>
                <a href="{{ route('admin.reservations.index', ['select' => $booking->booking_code]) }}"
                   style="font-size: 12px; font-weight: 600; color: #24382D; text-decoration: none;">
                    {{ $booking->booking_code }} ↗
                </a>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <span style="background: {{ $isPending ? '#FFF0E4' : ($isAccepted ? '#EAF0E7' : '#FBEDEB') }}; color: {{ $isPending ? '#A54B10' : ($isAccepted ? '#1F3D2B' : '#AD4940') }}; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                    {{ $isPending ? 'Menunggu pemeriksaan' : ($isAccepted ? 'Diterima' : 'Ditolak') }}
                </span>
                <span style="background: #F4F6F1; color: #1F3D2B; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                    {{ $booking->reservation_status ?: 'Menunggu verifikasi' }}
                </span>
            </div>

            <div class="i-kv">
                <span>Pelanggan / paket</span>
                <span>{{ $booking->customer_name }} / {{ $pkgName }}</span>
            </div>
            <div class="i-kv">
                <span>Tanggal perjalanan</span>
                <span>{{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('l, d F Y') }}</span>
            </div>
            <div class="i-kv">
                <span>Waktu perjalanan</span>
                <span>{{ $timeSlot }} WIB · {{ $booking->package_duration ?? '4 jam' }}</span>
            </div>
            <div class="i-kv">
                <span>Peserta / jeep</span>
                <span>{{ $booking->participant_count }} peserta / {{ $unitCount }} jeep</span>
            </div>
            <div class="i-kv">
                <span>Kapasitas per jeep</span>
                <span>Maksimal 4 peserta</span>
            </div>
            <div class="i-kv">
                <span>Harga / biaya admin</span>
                <span>Rp{{ number_format($booking->total_price, 0, ',', '.') }} / Rp0</span>
            </div>
        </div>

        <!-- Kartu 2: Checklist Pemeriksaan (Figma Frame 178:20241) -->
        <div class="inspect-card">
            <div>
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                    Checklist pemeriksaan
                </h3>
                <p id="checklistProgressText" style="font-size: 12px; color: #718074;">
                    @if($isAccepted)
                        Pemeriksaan selesai · 4 dari 4 selesai (Terkunci)
                    @elseif($isRejected)
                        Bukti ditolak · Menunggu unggahan bukti baru dari pelanggan
                    @else
                        Belum diperiksa · 0 dari 4 selesai
                    @endif
                </p>
            </div>

            <label class="chk-inspect" style="{{ !$isPending ? 'opacity: 0.75; cursor: default;' : '' }}">
                <input type="checkbox" class="inspect-chk" onchange="updateInspectChecklist()" {{ $isAccepted ? 'checked' : '' }} {{ !$isPending ? 'disabled' : '' }}>
                <span>Bukti terbaca; nama, tanggal, dan nominal terlihat.</span>
            </label>

            <label class="chk-inspect" style="{{ !$isPending ? 'opacity: 0.75; cursor: default;' : '' }}">
                <input type="checkbox" class="inspect-chk" onchange="updateInspectChecklist()" {{ $isAccepted ? 'checked' : '' }} {{ !$isPending ? 'disabled' : '' }}>
                <span>Rekening tujuan sesuai rekening YKS.</span>
            </label>

            <label class="chk-inspect" style="{{ !$isPending ? 'opacity: 0.75; cursor: default;' : '' }}">
                <input type="checkbox" class="inspect-chk" onchange="updateInspectChecklist()" {{ $isAccepted ? 'checked' : '' }} {{ !$isPending ? 'disabled' : '' }}>
                <span>Dana Rp{{ number_format($booking->total_price, 0, ',', '.') }} tercatat pada mutasi rekening.</span>
            </label>

            <label class="chk-inspect" style="{{ !$isPending ? 'opacity: 0.75; cursor: default;' : '' }}">
                <input type="checkbox" class="inspect-chk" onchange="updateInspectChecklist()" {{ $isAccepted ? 'checked' : '' }} {{ !$isPending ? 'disabled' : '' }}>
                <span>Transfer belum dipakai pada reservasi lain.</span>
            </label>
        </div>

        <!-- Kartu 3: Keputusan Pembayaran (Figma Frame 178:20258) -->
        <div class="inspect-card">
            <div>
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                    Keputusan pembayaran
                </h3>
                <p style="font-size: 12px; color: #718074;">
                    {{ $isPending ? 'Pilih tindakan setelah pemeriksaan selesai' : 'Keputusan telah ditetapkan untuk bukti transfer ini' }}
                </p>
            </div>

            @if($isAccepted)
                <!-- Tampilan Terkunci Saat Pembayaran Sudah Dikonfirmasi (Diterima) -->
                <div style="background: #EAF0E7; border: 1px solid #C1D1BE; border-radius: 10px; padding: 18px; display: flex; flex-direction: column; gap: 8px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #1F3D2B;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Pembayaran Telah Dikonfirmasi (Diterima)</span>
                    </div>
                    <p style="font-size: 12px; color: #24382D; line-height: 1.55; margin: 0;">
                        Keputusan pemeriksaan sudah selesai dan bersifat final. Status pembayaran telah tercatat sebagai <strong>Diterima</strong> dan status reservasi menjadi <strong>Terkonfirmasi</strong>. Bukti yang sudah disahkan tidak dapat ditolak kembali.
                    </p>
                </div>
            @elseif($isRejected)
                <!-- Tampilan Terkunci Saat Bukti Transfer Sudah Ditolak -->
                @php
                    $savedReason = 'Bukti transfer tidak sesuai dengan mutasi rekening.';
                    if (!empty($booking->notes) && preg_match_all('/\[Ditolak Admin:\s*(.*?)\]/s', $booking->notes, $m)) {
                        $savedReason = trim($m[1][count($m[1]) - 1]);
                    } elseif (!empty($booking->admin_notes)) {
                        $savedReason = $booking->admin_notes;
                    }
                @endphp
                <div style="background: #FBEDEB; border: 1px solid #E5B4AE; border-radius: 10px; padding: 18px; display: flex; flex-direction: column; gap: 10px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: #AD4940;">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Bukti Transfer Telah Ditolak</span>
                    </div>
                    <div style="background: #FFFFFF; border: 1px solid #E5B4AE; border-radius: 8px; padding: 12px; font-size: 12px; color: #24382D;">
                        <strong>Alasan penolakan yang dikirim ke pelanggan:</strong><br>
                        “{{ $savedReason }}”
                    </div>
                    <p style="font-size: 11px; color: #718074; line-height: 1.5; margin: 0;">
                        Status reservasi dikembalikan ke <strong>Menunggu pembayaran</strong>. Panel pemeriksaan akan aktif kembali secara otomatis setelah pelanggan mengunggah bukti transfer baru.
                    </p>
                </div>
            @else
                <!-- Tampilan Interaktif Saat Status Menunggu Pemeriksaan -->
                <div style="background: #EAF0E7; border-radius: 8px; padding: 14px; display: flex; align-items: flex-start; gap: 10px;">
                    <i class="bi bi-info-circle" style="color: #1F3D2B; font-size: 15px;"></i>
                    <span style="font-size: 12px; color: #1F3D2B; line-height: 1.5;">
                        Konfirmasi: pembayaran menjadi Diterima dan reservasi menjadi Terkonfirmasi. Nominal baru dihitung sebagai penerimaan setelah tindakan ini.
                    </span>
                </div>

                <p style="font-size: 11px; color: #718074;">
                    Konfirmasi hanya tersedia jika seluruh checklist selesai.
                </p>

                <!-- Form Konfirmasi Pembayaran -->
                <form action="{{ route('admin.verifications.approve', $booking->booking_code) }}" method="POST">
                    @csrf
                    <button type="submit" id="confirmPayBtn" class="btn-confirm-pay" disabled>
                        <i id="confirmPayIcon" class="bi bi-lock"></i>
                        <span>Konfirmasi pembayaran</span>
                    </button>
                </form>

                <div style="height: 1px; background: #E1E4DA;"></div>

                <!-- Form Tolak Bukti Transfer -->
                <form action="{{ route('admin.verifications.reject', $booking->booking_code) }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                    @csrf
                    <div>
                        <label style="font-size: 12px; font-weight: 600; color: #24382D; display: block; margin-bottom: 7px;">
                            Alasan penolakan *
                        </label>
                        <textarea name="rejection_reason" rows="3" required
                                  placeholder="Jelaskan ketidaksesuaian bukti transfer..."
                                  style="width: 100%; min-height: 90px; padding: 12px; border: 1px solid #E1E4DA; border-radius: 8px; font-family: inherit; font-size: 13px; color: #24382D;">{{ old('rejection_reason') }}</textarea>
                    </div>

                    <p style="font-size: 11px; color: #AD4940;">
                        Wajib diisi saat menolak. Alasan akan ditampilkan kepada pelanggan.
                    </p>

                    <p style="font-size: 11px; color: #718074; line-height: 1.5;">
                        Tolak: pembayaran menjadi Ditolak, reservasi kembali Menunggu pembayaran, dan penahanan slot dilepas. Pelanggan perlu memilih slot tersedia dan mengunggah ulang.
                    </p>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit"
                                style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #AD4940; background: #FFFFFF; color: #AD4940; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                            <i class="bi bi-x-lg"></i> Tolak bukti transfer
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
    function updateInspectChecklist() {
        const all = document.querySelectorAll('.inspect-chk');
        const checked = document.querySelectorAll('.inspect-chk:checked');
        const prog = document.getElementById('checklistProgressText');
        const btn = document.getElementById('confirmPayBtn');
        const icon = document.getElementById('confirmPayIcon');

        if (prog) {
            if (checked.length === all.length) {
                prog.textContent = `Pemeriksaan selesai · ${checked.length} dari ${all.length} selesai`;
            } else {
                prog.textContent = `Sedang diperiksa · ${checked.length} dari ${all.length} selesai`;
            }
        }

        if (btn) {
            const unlocked = (checked.length === all.length);
            btn.disabled = !unlocked;
            if (icon) {
                icon.className = unlocked ? 'bi bi-check2-circle' : 'bi bi-lock';
            }
        }
    }
</script>
@endsection
