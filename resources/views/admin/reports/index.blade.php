@extends('layouts.admin', ['title' => 'Laporan'])

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Laporan Reservasi & Pendapatan</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Ringkasan reservasi dan pendapatan untuk keputusan operasional wisata YKS.</p>
    </div>

    <!-- Export Action Buttons (Figma: Ekspor CSV & Unduh PDF) -->
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.reports.export_csv') }}" class="btn-primary-yks" style="background: #304F3B;">
            <i class="bi bi-file-earmark-spreadsheet"></i> Ekspor CSV
        </a>
        <button onclick="window.print()" class="btn-primary-yks" style="background: #1F3D2B;">
            <i class="bi bi-file-earmark-pdf"></i> Unduh PDF
        </button>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Periode Aktif Laporan</span>
        <h3 style="font-size: 1.3rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $reportSummary['active_period'] }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Berdasarkan tanggal pesanan dibuat</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Total Reservasi Selesai</span>
        <h3 style="font-size: 1.8rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $reportSummary['total_bookings'] }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Tingkat konfirmasi tinggi</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Puncak Pemesanan</span>
        <h3 style="font-size: 1.3rem; font-weight: 800; color: #F26B1D; margin-top: 6px;">{{ $reportSummary['peak_date'] }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">{{ $reportSummary['peak_count'] }} reservasi dalam 1 hari</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Total Pendapatan Lunas</span>
        <h3 style="font-size: 1.6rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">Rp{{ number_format($reportSummary['total_income'], 0, ',', '.') }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Dana masuk rekening resmi</p>
    </div>
</div>
@endsection
