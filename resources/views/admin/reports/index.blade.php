@extends('layouts.admin', ['title' => 'Laporan'])

@section('content')
<!-- Header Bar (Figma: Administrasi / Laporan + Ekspor CSV & Unduh PDF) -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Laporan</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Ringkasan reservasi dan pendapatan untuk keputusan operasional.</p>
    </div>

    <div class="no-print" style="display: flex; gap: 10px;">
        <a href="{{ route('admin.reports.export_csv') }}" class="btn-outline-yks">
            <i class="bi bi-file-earmark-spreadsheet"></i> Ekspor CSV
        </a>
        <button onclick="window.print()" class="btn-primary-yks">
            <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
        </button>
    </div>
</div>

<!-- Card Filter Periode Laporan (Figma) -->
<div class="stat-card no-print" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
        <div>
            <h3 style="font-size: 1rem; font-weight: 700;">Periode laporan</h3>
            <span style="font-size: 0.78rem; color: #6B756E;">Berdasarkan tanggal pesanan dibuat · bukan tanggal keberangkatan</span>
        </div>
        <span style="font-size: 0.76rem; color: #6B756E;">
            Periode aktif: <strong style="color: #1F3D2B;">{{ $reportSummary['active_period'] }}</strong> · Diperbarui {{ date('d M Y, H.i') }} WIB
        </span>
    </div>

    <form action="{{ route('admin.reports.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end;">
        <div>
            <label style="font-size: 0.78rem; font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 4px;">Tanggal mulai</label>
            <input type="date" name="start_date" value="{{ $startDate }}" style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem;">
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 4px;">Tanggal akhir</label>
            <input type="date" name="end_date" value="{{ $endDate }}" style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem;">
        </div>
        <div>
            <label style="font-size: 0.78rem; font-weight: 600; color: #1F3D2B; display: block; margin-bottom: 4px;">Paket</label>
            <select name="package_id" style="width: 100%; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem; background: #FFF;">
                <option value="">Semua paket</option>
                @foreach($packages as $p)
                    <option value="{{ $p->id }}" {{ $packageId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-primary-yks" style="padding: 9px 18px;">Terapkan</button>
            @if($startDate || $endDate || $packageId)
                <a href="{{ route('admin.reports.index') }}" class="btn-outline-yks" style="padding: 9px 14px;">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- 4 Kartu KPI Laporan (Figma) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Total reservasi</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $reportSummary['total_bookings'] }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">{{ $reportSummary['active_period'] }}</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Pendapatan lunas</span>
        <h3 style="font-size: 1.65rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">Rp{{ number_format($reportSummary['total_income'], 0, ',', '.') }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">Tidak termasuk pesanan belum lunas</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Reservasi lunas</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $reportSummary['lunas_count'] }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">{{ $reportSummary['lunas_pct'] }}% dari total reservasi</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Menunggu / Dibatalkan</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #F26B1D; margin-top: 6px;">
            {{ $reportSummary['menunggu_total'] }} / {{ $reportSummary['batal_count'] }}
        </h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">
            {{ $reportSummary['verif_count'] }} verifikasi + {{ $reportSummary['belum_bayar_count'] }} belum bayar / {{ $reportSummary['batal_count'] }} batal
        </p>
    </div>
</div>

<!-- Baris 2: Tren Reservasi & Ringkasan Status (Figma) -->
<div style="display: grid; grid-template-columns: 1.35fr 1fr; gap: 20px; margin-bottom: 24px;">
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Tren reservasi</h3>
                <span style="font-size: 0.8rem; color: #6B756E;">Volume pemesanan harian · 3–9 November 2026</span>
            </div>
            <span style="font-size: 0.8rem; font-weight: 700; color: #1F3D2B;">34 reservasi</span>
        </div>

        <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 140px; gap: 12px; padding: 10px 6px 0; border-bottom: 1px solid #ECECE6;">
            @php $maxT = collect($trendBars)->max('count') ?: 1; @endphp
            @foreach($trendBars as $b)
                @php
                    $h = round(($b['count'] / $maxT) * 100);
                    $isPeak = ($b['count'] === $maxT);
                @endphp
                <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;">
                    <span style="font-size: 0.74rem; font-weight: 700; color: {{ $isPeak ? '#F26B1D' : '#1F3D2B' }};">{{ $b['count'] }}</span>
                    <div style="width: 100%; max-width: 36px; height: {{ max(18, $h) }}px; background: {{ $isPeak ? '#F26B1D' : '#1F3D2B' }}; border-radius: 6px 6px 0 0;"></div>
                </div>
            @endforeach
        </div>
        <div style="display: flex; justify-content: space-between; padding: 8px 6px 0;">
            @foreach($trendBars as $b)
                <div style="flex: 1; text-align: center; font-size: 0.74rem; color: #6B756E;">{{ $b['label'] }}</div>
            @endforeach
        </div>

        <div style="margin-top: 14px; font-size: 0.76rem; color: #6B756E;">
            Puncak pemesanan terjadi pada 7 November (7 reservasi dalam 1 hari).
        </div>
    </div>

    <div class="stat-card">
        <h3 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 4px;">Ringkasan status</h3>
        <span style="font-size: 0.8rem; color: #6B756E; display: block; margin-bottom: 16px;">{{ $reportSummary['active_period'] }} · {{ $reportSummary['total_bookings'] }} pesanan</span>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                    <span style="font-weight: 600;">Lunas</span>
                    <strong>{{ $reportSummary['lunas_count'] }} · {{ $reportSummary['lunas_pct'] }}%</strong>
                </div>
                <div style="height: 8px; background: #ECECE6; border-radius: 99px; overflow: hidden;">
                    <div style="width: {{ $reportSummary['lunas_pct'] }}%; height: 100%; background: #1F3D2B;"></div>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                    <span style="font-weight: 600; color: #F26B1D;">Menunggu verifikasi</span>
                    <strong>{{ $reportSummary['verif_count'] }} · {{ $reportSummary['verif_pct'] }}%</strong>
                </div>
                <div style="height: 8px; background: #ECECE6; border-radius: 99px; overflow: hidden;">
                    <div style="width: {{ $reportSummary['verif_pct'] }}%; height: 100%; background: #F26B1D;"></div>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                    <span style="font-weight: 600; color: #6B756E;">Menunggu pembayaran</span>
                    <strong>{{ $reportSummary['belum_bayar_count'] }} · {{ $reportSummary['belum_pct'] }}%</strong>
                </div>
                <div style="height: 8px; background: #ECECE6; border-radius: 99px; overflow: hidden;">
                    <div style="width: {{ $reportSummary['belum_pct'] }}%; height: 100%; background: #8E9E8B;"></div>
                </div>
            </div>

            <div>
                <div style="display: flex; justify-content: space-between; font-size: 0.82rem; margin-bottom: 4px;">
                    <span style="font-weight: 600; color: #C62828;">Dibatalkan</span>
                    <strong>{{ $reportSummary['batal_count'] }} · {{ $reportSummary['batal_pct'] }}%</strong>
                </div>
                <div style="height: 8px; background: #ECECE6; border-radius: 99px; overflow: hidden;">
                    <div style="width: {{ $reportSummary['batal_pct'] }}%; height: 100%; background: #E57373;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Kinerja Paket (Figma) -->
<div class="stat-card" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="font-size: 1.05rem; font-weight: 700;">Kinerja paket</h3>
            <span style="font-size: 0.8rem; color: #6B756E;">{{ $reportSummary['active_period'] }} · jumlah pesanan dan pendapatan yang sudah lunas</span>
        </div>

        <a href="{{ route('admin.reports.export_csv') }}" class="btn-outline-yks no-print" style="font-size: 0.78rem; padding: 6px 12px;">
            Unduh tabel ↓
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.84rem;">
            <thead>
                <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                    <th style="padding: 10px 8px;">Paket</th>
                    <th style="padding: 10px 8px;">Total reservasi</th>
                    <th style="padding: 10px 8px;">Lunas</th>
                    <th style="padding: 10px 8px;">Menunggu</th>
                    <th style="padding: 10px 8px;">Dibatalkan</th>
                    <th style="padding: 10px 8px; text-align: right;">Pendapatan lunas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($packagePerformance as $perf)
                    <tr style="border-bottom: 1px solid #ECECE6;">
                        <td style="padding: 12px 8px;">
                            <div style="font-weight: 700; color: #1F3D2B;">{{ $perf['name'] }}</div>
                            <div style="font-size: 0.74rem; color: #6B756E;">Rp{{ number_format($perf['price'], 0, ',', '.') }} / paket</div>
                        </td>
                        <td style="padding: 12px 8px; font-weight: 600;">{{ $perf['total'] }}</td>
                        <td style="padding: 12px 8px; color: #1F3D2B; font-weight: 600;">{{ $perf['lunas'] }}</td>
                        <td style="padding: 12px 8px; color: #F26B1D; font-weight: 600;">{{ $perf['menunggu'] }}</td>
                        <td style="padding: 12px 8px; color: #6B756E;">{{ $perf['dibatalkan'] }}</td>
                        <td style="padding: 12px 8px; text-align: right; font-weight: 700; color: #1F3D2B;">
                            Rp{{ number_format($perf['revenue'], 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #F8F5EF; font-weight: 800; color: #1F3D2B; border-top: 2px solid #1F3D2B;">
                    <td style="padding: 12px 8px;">Total periode</td>
                    <td style="padding: 12px 8px;">{{ $reportSummary['total_bookings'] }}</td>
                    <td style="padding: 12px 8px;">{{ $reportSummary['lunas_count'] }}</td>
                    <td style="padding: 12px 8px;">{{ $reportSummary['menunggu_total'] }}</td>
                    <td style="padding: 12px 8px;">{{ $reportSummary['batal_count'] }}</td>
                    <td style="padding: 12px 8px; text-align: right;">Rp{{ number_format($reportSummary['total_income'], 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="margin-top: 12px; font-size: 0.75rem; color: #6B756E;">
        Menunggu = verifikasi + belum bayar. Pendapatan dihitung dari pembayaran lunas tanpa pesanan batal.
    </div>
</div>

<!-- Baris 4: 2 Kartu Highlight (Paket Paling Diminati & Prioritas Tindak Lanjut - Figma) -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
    <div class="stat-card" style="background: #EAF0E7; border-color: #C1D1BE;">
        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #1F3D2B; font-weight: 700;">
            Paket paling diminati
        </span>
        <h3 style="font-size: 1.2rem; font-weight: 800; color: #1F3D2B; margin-top: 4px;">
            {{ $topPackage['name'] ?? 'Trekking Curug Leuwi Hejo' }}
        </h3>
        <p style="font-size: 0.82rem; color: #304F3B; margin-top: 4px;">
            Menyumbang {{ $topPackage['total'] ?? 0 }} pesanan dengan kontribusi pendapatan lunas sebesar Rp{{ number_format($topPackage['revenue'] ?? 0, 0, ',', '.') }} pada periode ini.
        </p>
    </div>

    <div class="stat-card" style="background: #FEEFE6; border-color: #FBD5BD;">
        <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #F26B1D; font-weight: 700;">
            Prioritas tindak lanjut
        </span>
        <h3 style="font-size: 1.2rem; font-weight: 800; color: #1F3D2B; margin-top: 4px;">
            {{ $reportSummary['verif_count'] }} bukti perlu diperiksa
        </h3>
        <p style="font-size: 0.82rem; color: #6B4F3A; margin-top: 4px;">
            Selesaikan antrean verifikasi sebelum menindaklanjuti {{ $reportSummary['belum_bayar_count'] }} pesanan yang belum membayar.
            <a href="{{ route('admin.verifications.index') }}" style="color: #F26B1D; font-weight: 700; margin-left: 6px;">Buka Verifikasi →</a>
        </p>
    </div>
</div>
@endsection
