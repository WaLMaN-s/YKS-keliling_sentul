@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<!-- Identitas Halaman (Figma ON GOING #178:18549) -->
<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
    <h1 style="font-family: var(--font-head); font-size: 1.75rem; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px;">
        Dashboard
    </h1>

    <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 8px; min-height: 42px; padding: 0 14px; display: inline-flex; align-items: center; gap: 10px; font-size: 0.8rem; color: #24382D;">
        <i class="bi bi-calendar3" style="color: #718074;"></i>
        <span>1–9 Nov 2026</span>
    </div>
</div>

<!-- 4 Kartu Ringkasan Periode (#178:18556) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
    <div class="stat-card" style="display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; color: #718074;">
            <span>Total reservasi</span>
            <i class="bi bi-ticket-perforated" style="font-size: 1rem;"></i>
        </div>
        <div style="font-family: var(--font-head); font-size: 1.65rem; font-weight: 700; color: #1F3D2B;">
            {{ $stats['total_reservasi'] }}
        </div>
        <div style="font-size: 0.7rem; color: #718074;">
            Dibuat pada 1–9 November 2026
        </div>
    </div>

    <div class="stat-card" style="background: #1F3D2B; border-color: #1F3D2B; color: #FFFFFF; display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; color: #BDCCBE;">
            <span>Penerimaan terkonfirmasi</span>
            <i class="bi bi-wallet2" style="font-size: 1rem; color: #BDCCBE;"></i>
        </div>
        <div style="font-family: var(--font-head); font-size: 1.65rem; font-weight: 700; color: #FFFFFF;">
            Rp{{ number_format($stats['diterima_nominal'], 0, ',', '.') }}
        </div>
        <div style="font-size: 0.7rem; color: #BDCCBE;">
            {{ $stats['diterima_count'] }} pembayaran diterima · 1–9 Nov
        </div>
    </div>

    <div class="stat-card" style="display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; color: #718074;">
            <span>Perlu pemeriksaan</span>
            <i class="bi bi-file-earmark-medical" style="font-size: 1rem;"></i>
        </div>
        <div style="font-family: var(--font-head); font-size: 1.65rem; font-weight: 700; color: #1F3D2B;">
            {{ $stats['perlu_pemeriksaan_count'] }} bukti
        </div>
        <div style="font-size: 0.7rem; color: #718074;">
            Total nominal Rp{{ number_format($stats['perlu_pemeriksaan_nominal'], 0, ',', '.') }}
        </div>
    </div>

    <div class="stat-card" style="display: flex; flex-direction: column; gap: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; color: #718074;">
            <span>Reservasi terkonfirmasi</span>
            <i class="bi bi-patch-check" style="font-size: 1rem;"></i>
        </div>
        <div style="font-family: var(--font-head); font-size: 1.65rem; font-weight: 700; color: #1F3D2B;">
            {{ $stats['terkonfirmasi_count'] }}
        </div>
        <div style="font-size: 0.7rem; color: #718074;">
            {{ $stats['selesai_count'] }} selesai · {{ $stats['masih_menunggu_count'] }} masih menunggu
        </div>
    </div>
</div>

<!-- Baris 2: Perlu Ditinjau & Status Pembayaran (#178:18581) -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <!-- Panel Kiri: Perlu Ditinjau (#178:18614) -->
    <div class="stat-card" style="display: flex; flex-direction: column; gap: 18px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h3 style="font-family: var(--font-head); font-size: 1.05rem; font-weight: 700; color: #1F3D2B;">
                    Perlu ditinjau
                </h3>
                <span style="font-size: 0.76rem; color: #718074;">Bukti masuk, belum dinyatakan sah</span>
            </div>
            <span style="background: #FFF0E4; color: #A54B10; font-size: 0.7rem; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                {{ $stats['perlu_pemeriksaan_count'] }} bukti
            </span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            @forelse($actionRequired as $item)
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                    <div>
                        <div style="font-size: 0.82rem; font-weight: 600; color: #24382D;">
                            {{ $item->customer_name }} · Rp{{ number_format($item->total_price, 0, ',', '.') }}
                        </div>
                        <div style="font-size: 0.7rem; color: #718074; margin-top: 2px;">
                            {{ $item->booking_code }} · {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('j M, H.i') }} WIB
                        </div>
                    </div>
                    <a href="{{ route('admin.verifications.show', $item->booking_code) }}" class="btn-outline-yks" style="min-height: 38px; padding: 6px 14px; font-size: 0.78rem;">
                        Tinjau
                    </a>
                </div>
            @empty
                <div style="font-size: 0.8rem; color: #718074; padding: 16px 0;">
                    Seluruh bukti transfer telah diperiksa.
                </div>
            @endforelse
        </div>

        <a href="{{ route('admin.verifications.index') }}" style="font-size: 0.72rem; color: #A54B10; text-decoration: none; font-weight: 600; margin-top: auto;">
            Buka semua pembayaran →
        </a>
    </div>

    <!-- Panel Kanan: Status Pembayaran (#184:2) -->
    <div class="stat-card" style="display: flex; flex-direction: column; gap: 16px;">
        <div>
            <h3 style="font-family: var(--font-head); font-size: 1.05rem; font-weight: 700; color: #1F3D2B;">
                Status pembayaran
            </h3>
            <span style="font-size: 0.76rem; color: #718074;">{{ $stats['total_reservasi'] }} reservasi pada periode terpilih</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px; font-size: 0.82rem;">
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Diterima · {{ $stats['diterima_count'] }}</span>
                <span style="font-weight: 500; color: #24382D;">Rp{{ number_format($stats['diterima_nominal'], 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Menunggu pemeriksaan · {{ $stats['perlu_pemeriksaan_count'] }}</span>
                <span style="font-weight: 500; color: #24382D;">Rp{{ number_format($stats['perlu_pemeriksaan_nominal'], 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Ditolak · {{ $stats['ditolak_count'] }}</span>
                <span style="font-weight: 500; color: #24382D;">Rp{{ number_format($stats['ditolak_nominal'], 0, ',', '.') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Belum bayar · {{ $stats['belum_bayar_count'] }}</span>
                <span style="font-weight: 500; color: #24382D;">Rp{{ number_format($stats['belum_bayar_nominal'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Baris 3: Keberangkatan Terdekat (#178:18634) -->
<div class="stat-card" style="display: flex; flex-direction: column; gap: 18px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="font-family: var(--font-head); font-size: 1.05rem; font-weight: 700; color: #1F3D2B;">
                Keberangkatan terdekat
            </h3>
            <span style="font-size: 0.76rem; color: #718074;">Reservasi terkonfirmasi · Waktu perjalanan dalam WIB</span>
        </div>
        <a href="{{ route('admin.schedules.index') }}" style="font-size: 0.78rem; font-weight: 600; color: #24382D; text-decoration: none;">
            Lihat jadwal →
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
            <thead>
                <tr style="background: #F4F6F1; color: #718074; text-align: left;">
                    <th style="padding: 10px 12px; border-radius: 6px 0 0 6px; font-weight: 600;">Tanggal & waktu</th>
                    <th style="padding: 10px 12px; font-weight: 600;">Paket</th>
                    <th style="padding: 10px 12px; font-weight: 600;">Peserta / jeep</th>
                    <th style="padding: 10px 12px; border-radius: 0 6px 6px 0; font-weight: 600;">Pemesan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upcomingConfirmed as $up)
                    <tr style="border-bottom: 1px solid var(--admin-border);">
                        <td style="padding: 14px 12px; color: #24382D;">
                            {{ \Carbon\Carbon::parse($up->trip_date)->translatedFormat('j M') }} · {{ $up->start_time ?? '08.00–12.00' }}
                        </td>
                        <td style="padding: 14px 12px; color: #24382D;">{{ $up->package_name }}</td>
                        <td style="padding: 14px 12px; color: #24382D;">{{ $up->participant_count }} peserta · {{ $up->vehicle_count ?? 1 }} jeep</td>
                        <td style="padding: 14px 12px; color: #24382D;">{{ $up->customer_name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 20px 12px; color: #718074; text-align: center;">Belum ada keberangkatan terkonfirmasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <span style="font-size: 0.7rem; color: #718074;">
        Reservasi yang masih menunggu pemeriksaan belum masuk daftar keberangkatan terkonfirmasi.
    </span>
</div>

<!-- Baris 4: Reservasi Terbaru (#178:18684) -->
<div class="stat-card" style="display: flex; flex-direction: column; gap: 18px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div>
            <h3 style="font-family: var(--font-head); font-size: 1.05rem; font-weight: 700; color: #1F3D2B;">
                Reservasi terbaru
            </h3>
            <span style="font-size: 0.76rem; color: #718074;">Diurutkan berdasarkan tanggal dibuat</span>
        </div>
        <a href="{{ route('admin.reservations.index') }}" class="btn-outline-yks">
            <i class="bi bi-arrow-up-right"></i> Semua reservasi
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
            <thead>
                <tr style="background: #F4F6F1; color: #718074; text-align: left;">
                    <th style="padding: 10px 12px; border-radius: 6px 0 0 6px; font-weight: 600;">No. reservasi / pelanggan</th>
                    <th style="padding: 10px 12px; font-weight: 600;">Paket & jadwal</th>
                    <th style="padding: 10px 12px; font-weight: 600;">Total</th>
                    <th style="padding: 10px 12px; font-weight: 600;">Pembayaran</th>
                    <th style="padding: 10px 12px; border-radius: 0 6px 6px 0; font-weight: 600;">Reservasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentBookings as $rb)
                    @php
                        $isRejected = $rb->payment_status === 'Menunggu Pembayaran' && str_contains((string) $rb->notes, '[Ditolak Admin:');
                        if ($rb->payment_status === 'Lunas') {
                            $payLabel = 'Diterima';
                            $payStyle = 'background: #EAF0E7; color: #1F3D2B;';
                        } elseif ($rb->payment_status === 'Menunggu Verifikasi') {
                            $payLabel = 'Menunggu pemeriksaan';
                            $payStyle = 'background: #FFF0E4; color: #A54B10;';
                        } elseif ($isRejected) {
                            $payLabel = 'Ditolak';
                            $payStyle = 'background: #FBEDEB; color: #AD4940;';
                        } else {
                            $payLabel = 'Belum bayar';
                            $payStyle = 'background: #F4F6F1; color: #1F3D2B;';
                        }
                        $resLabel = $rb->reservation_status ?: ($rb->payment_status === 'Lunas' ? 'Terkonfirmasi' : ($rb->payment_status === 'Menunggu Verifikasi' ? 'Menunggu verifikasi' : 'Menunggu pembayaran'));
                    @endphp
                    <tr style="border-bottom: 1px solid var(--admin-border);">
                        <td style="padding: 12px;">
                            <a href="{{ route('admin.reservations.index', ['select' => $rb->booking_code]) }}" style="font-weight: 600; color: #24382D; text-decoration: none; display: block;">
                                {{ $rb->booking_code }}
                            </a>
                            <span style="font-size: 0.7rem; color: #718074;">{{ $rb->customer_name }} · {{ \Carbon\Carbon::parse($rb->created_at)->translatedFormat('j M Y') }}</span>
                        </td>
                        <td style="padding: 12px;">
                            <div style="color: #24382D;">{{ $rb->package_name }}</div>
                            <div style="font-size: 0.7rem; color: #718074;">{{ \Carbon\Carbon::parse($rb->trip_date)->translatedFormat('j M Y') }} · {{ $rb->start_time ?? '08.00–12.00' }}</div>
                        </td>
                        <td style="padding: 12px; color: #24382D;">
                            Rp{{ number_format($rb->total_price, 0, ',', '.') }}
                        </td>
                        <td style="padding: 12px;">
                            <span style="display: inline-block; padding: 5px 10px; border-radius: 100px; font-size: 0.7rem; font-weight: 600; {{ $payStyle }}">
                                {{ $payLabel }}
                            </span>
                        </td>
                        <td style="padding: 12px; font-size: 0.74rem; color: #24382D;">
                            {{ $resLabel }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
