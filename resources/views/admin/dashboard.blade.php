@extends('layouts.admin', ['title' => 'Ringkasan'])

@section('content')
<!-- Header Bar (Figma: Administrasi / Ringkasan + Lihat laporan) -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Ringkasan</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">{{ date('l, d F Y') }} · Pantau kesiapan petualangan hari ini.</p>
    </div>

    <a href="{{ route('admin.reports.index') }}" class="btn-outline-yks">
        <i class="bi bi-bar-chart-line"></i> Lihat laporan
    </a>
</div>

<!-- 4 Kartu KPI Utama (Figma) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(215px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Reservasi Hari Ini</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $stats['reservasi_hari_ini'] }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">Pemesanan masuk · {{ date('d F') }}</p>
    </div>

    <div class="stat-card" style="border-top: 3px solid #F26B1D;">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #F26B1D; font-weight: 600;">Menunggu Verifikasi</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #F26B1D; margin-top: 6px;">{{ $stats['menunggu_verifikasi'] }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">Bukti terlama menunggu pemeriksaan</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Total Pendapatan Bulan Ini</span>
        <h3 style="font-size: 1.65rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">Rp{{ number_format($stats['pendapatan_lunas'], 0, ',', '.') }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">{{ $stats['lunas'] }} lunas · periode berjalan</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Slot Tersedia</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $stats['slot_tersedia'] }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">Minggu ini · seluruh paket</p>
    </div>
</div>

<!-- Baris 2: Reservasi per Minggu (Bar Chart) & Distribusi Status (Figma) -->
<div style="display: grid; grid-template-columns: 1.35fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- Grafik Reservasi per Minggu -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 18px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Reservasi per minggu</h3>
                <span style="font-size: 0.8rem; color: #6B756E;">Aktivitas pemesanan dalam tujuh hari</span>
            </div>
            <span style="font-size: 0.75rem; background: #F8F5EF; border: 1px solid #DFE4DC; padding: 4px 10px; border-radius: 6px; color: #1F3D2B; font-weight: 600;">
                Minggu ini ⌄
            </span>
        </div>

        <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 150px; gap: 12px; padding: 10px 6px 0; border-bottom: 1px solid #ECECE6;">
            @php $maxBar = collect($weeklyBars)->max('count') ?: 1; @endphp
            @foreach($weeklyBars as $bar)
                @php
                    $heightPct = round(($bar['count'] / $maxBar) * 100);
                    $isPeak = ($bar['count'] === $maxBar);
                @endphp
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                    <span style="font-size: 0.74rem; font-weight: 700; color: {{ $isPeak ? '#F26B1D' : '#1F3D2B' }};">{{ $bar['count'] }}</span>
                    <div style="width: 100%; max-width: 38px; height: {{ max(18, $heightPct) }}px; background: {{ $isPeak ? '#F26B1D' : '#1F3D2B' }}; border-radius: 6px 6px 0 0; transition: height 0.3s;"></div>
                </div>
            @endforeach
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 6px 0;">
            @foreach($weeklyBars as $bar)
                <div style="flex: 1; text-align: center; font-size: 0.74rem; color: #6B756E;">{{ $bar['label'] }}</div>
            @endforeach
        </div>

        <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid #F0F2EE; font-size: 0.78rem; color: #6B756E;">
            Puncak pemesanan: <strong style="color: #1F3D2B;">7 November · {{ $maxBar }} reservasi</strong>
        </div>
    </div>

    <!-- Distribusi Status -->
    <div class="stat-card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px;">
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 700;">Distribusi status</h3>
                    <span style="font-size: 0.8rem; color: #6B756E;">Total {{ $stats['total_reservasi'] }} reservasi tercatat</span>
                </div>
                <div style="text-align: right;">
                    <span style="font-size: 1.35rem; font-weight: 800; color: #1F3D2B;">{{ $stats['total_reservasi'] }}</span>
                    <span style="display: block; font-size: 0.7rem; color: #6B756E;">reservasi</span>
                </div>
            </div>

            <!-- Multi-segment Progress Bar -->
            @php
                $tot = max(1, $stats['total_reservasi']);
                $pLunas = round(($stats['lunas'] / $tot) * 100);
                $pVerif = round(($stats['menunggu_verifikasi'] / $tot) * 100);
                $pBelum = round(($stats['belum_bayar'] / $tot) * 100);
                $pBatal = max(0, 100 - $pLunas - $pVerif - $pBelum);
            @endphp
            <div style="display: flex; height: 12px; border-radius: 99px; overflow: hidden; gap: 2px; margin-bottom: 18px; background: #ECECE6;">
                <div style="width: {{ $pLunas }}%; background: #1F3D2B;" title="Lunas"></div>
                <div style="width: {{ $pVerif }}%; background: #F26B1D;" title="Menunggu Verifikasi"></div>
                <div style="width: {{ $pBelum }}%; background: #C1D1BE;" title="Belum Bayar"></div>
                <div style="width: {{ $pBatal }}%; background: #E57373;" title="Dibatalkan"></div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div style="padding: 10px 12px; background: #F8F5EF; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #1F3D2B;"></span> Lunas
                    </span>
                    <strong style="font-size: 0.9rem;">{{ $stats['lunas'] }}</strong>
                </div>
                <div style="padding: 10px 12px; background: #FEEFE6; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; display: flex; align-items: center; gap: 6px; color: #B2450A;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #F26B1D;"></span> Verifikasi
                    </span>
                    <strong style="font-size: 0.9rem; color: #F26B1D;">{{ $stats['menunggu_verifikasi'] }}</strong>
                </div>
                <div style="padding: 10px 12px; background: #F8F5EF; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #8E9E8B;"></span> Belum bayar
                    </span>
                    <strong style="font-size: 0.9rem;">{{ $stats['belum_bayar'] }}</strong>
                </div>
                <div style="padding: 10px 12px; background: #F8F5EF; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.8rem; display: flex; align-items: center; gap: 6px;">
                        <span style="width: 8px; height: 8px; border-radius: 50%; background: #E57373;"></span> Dibatalkan
                    </span>
                    <strong style="font-size: 0.9rem;">{{ $stats['dibatalkan'] }}</strong>
                </div>
            </div>
        </div>

        <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid #F0F2EE; font-size: 0.78rem; color: #1F3D2B; font-weight: 500;">
            ✓ {{ $stats['rasio_lunas'] }}% reservasi sudah lunas dan siap dijadwalkan.
        </div>
    </div>
</div>

<!-- Baris 3: Perlu Tindakan & Reservasi Mendatang (Figma) -->
<div style="display: grid; grid-template-columns: 1fr 1.35fr; gap: 20px;">
    <!-- Kolom Kiri: Perlu Tindakan (Antrean Bukti Pembayaran) -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Perlu Tindakan</h3>
                <span style="font-size: 0.8rem; color: #6B756E;">{{ count($actionRequired) }} bukti pembayaran menunggu pemeriksaan</span>
            </div>
            <a href="{{ route('admin.verifications.index') }}" style="font-size: 0.8rem; font-weight: 600; color: #F26B1D; text-decoration: none;">
                Buka verifikasi →
            </a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            @forelse($actionRequired as $item)
                <div style="padding: 12px 14px; border: 1px solid #DFE4DC; border-left: 3px solid #F26B1D; border-radius: 8px; background: #FCFCFA; display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                    <div>
                        <div style="font-weight: 700; font-size: 0.88rem; color: #1F3D2B;">
                            {{ $item->booking_code }} · {{ $item->customer_name }}
                        </div>
                        <div style="font-size: 0.76rem; color: #6B756E; margin-top: 2px;">
                            {{ $item->package_name }} · <strong style="color: #1F3D2B;">Rp{{ number_format($item->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                    <a href="{{ route('admin.verifications.index', ['select' => $item->booking_code]) }}"
                       style="padding: 6px 12px; border-radius: 6px; background: #FEEFE6; color: #F26B1D; font-size: 0.76rem; font-weight: 700; text-decoration: none; white-space: nowrap;">
                        Periksa →
                    </a>
                </div>
            @empty
                <div style="padding: 28px 16px; text-align: center; color: #6B756E; background: #F8F5EF; border-radius: 8px; font-size: 0.84rem;">
                    <i class="bi bi-check2-circle" style="font-size: 1.5rem; color: #1F3D2B; display: block; margin-bottom: 6px;"></i>
                    Semua bukti pembayaran sudah diperiksa!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kolom Kanan: Reservasi Mendatang -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Reservasi mendatang</h3>
                <span style="font-size: 0.8rem; color: #6B756E;">Keberangkatan terdekat yang tercatat</span>
            </div>
            <a href="{{ route('admin.reservations.index') }}" style="font-size: 0.8rem; font-weight: 600; color: #1F3D2B; text-decoration: none;">
                Semua reservasi →
            </a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.82rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                        <th style="padding: 8px;">ID pesanan / pelanggan</th>
                        <th style="padding: 8px;">Paket</th>
                        <th style="padding: 8px;">Jadwal (WIB)</th>
                        <th style="padding: 8px;">Peserta</th>
                        <th style="padding: 8px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingBookings as $b)
                        <tr style="border-bottom: 1px solid #ECECE6;">
                            <td style="padding: 10px 8px;">
                                <a href="{{ route('admin.reservations.index', ['select' => $b->booking_code]) }}" style="font-weight: 700; color: #1F3D2B; text-decoration: none; display: block;">
                                    {{ $b->booking_code }}
                                </a>
                                <span style="font-size: 0.75rem; color: #6B756E;">{{ $b->customer_name }}</span>
                            </td>
                            <td style="padding: 10px 8px; font-weight: 500;">{{ $b->package_name }}</td>
                            <td style="padding: 10px 8px;">
                                {{ date('d M Y', strtotime($b->trip_date)) }}<br>
                                <span style="font-size: 0.72rem; color: #6B756E;">08.00 WIB</span>
                            </td>
                            <td style="padding: 10px 8px;">{{ $b->participant_count }} orang</td>
                            <td style="padding: 10px 8px;">
                                <span style="display: inline-block; padding: 3px 9px; border-radius: 99px; font-size: 0.72rem; font-weight: 600;
                                    {{ $b->payment_status === 'Lunas' ? 'background: #EAF0E7; color: #1F3D2B;' : '' }}
                                    {{ $b->payment_status === 'Menunggu Verifikasi' ? 'background: #FEEFE6; color: #F26B1D;' : '' }}
                                    {{ $b->payment_status === 'Menunggu Pembayaran' ? 'background: #EDEEE9; color: #6B756E;' : '' }}
                                    {{ $b->payment_status === 'Dibatalkan' ? 'background: #FFEBEE; color: #C62828;' : '' }}
                                ">
                                    {{ $b->payment_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 24px; text-align: center; color: #6B756E;">Belum ada reservasi mendatang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
