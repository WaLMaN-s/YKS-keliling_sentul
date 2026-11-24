@extends('layouts.admin', ['title' => 'Laporan', 'breadcrumb' => 'Laporan'])

@section('content')
<style>
    .rep-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1080px) {
        .rep-kpi-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    .rep-kpi-card {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .rep-kpi-card.dark {
        background: #1F3D2B;
        border-color: #1F3D2B;
        color: #FFFFFF;
    }
    .rep-mid-grid {
        display: grid;
        grid-template-columns: 1fr 430px;
        gap: 24px;
        margin-bottom: 24px;
        align-items: start;
    }
    @media (max-width: 1120px) {
        .rep-mid-grid {
            grid-template-columns: 1fr;
        }
    }
    .rep-panel {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
</style>

<!-- Identitas Halaman (Figma Frame 178:20599) -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px; margin-bottom: 6px;">Laporan</h1>
        <p style="font-size: 13px; color: #718074;">Ringkasan reservasi dan pembayaran untuk evaluasi operasional YKS.</p>
    </div>

    <div class="no-print" style="display: flex; gap: 10px;">
        <a href="{{ route('admin.reports.export_csv') }}"
           style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-download"></i> Ekspor CSV
        </a>
        <button type="button" onclick="window.print()"
                style="height: 42px; padding: 0 16px; border-radius: 8px; border: none; background: #F26B1D; color: #1F3D2B; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-download"></i> Unduh PDF
        </button>
    </div>
</div>

<!-- Filter Laporan (Figma Frame 178:20610) -->
<div class="rep-panel no-print" style="margin-bottom: 24px; gap: 14px;">
    <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
        <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-calendar3" style="color: #718074; font-size: 13px;"></i>
            <input type="date" name="start_date" value="{{ $startDate ?: '2026-11-01' }}"
                   style="border: none; font-family: inherit; font-size: 13px; color: #24382D; background: transparent;">
        </div>

        <span style="font-size: 12px; color: #718074;">sampai</span>

        <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
            <i class="bi bi-calendar3" style="color: #718074; font-size: 13px;"></i>
            <input type="date" name="end_date" value="{{ $endDate ?: '2026-11-09' }}"
                   style="border: none; font-family: inherit; font-size: 13px; color: #24382D; background: transparent;">
        </div>

        <select name="package_id" style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 13px; color: #24382D;">
            <option value="">Semua paket</option>
            @foreach($packages as $p)
                <option value="{{ $p->id }}" {{ (string) $packageId === (string) $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>

        <select name="status" style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 13px; color: #24382D;">
            <option value="">Semua status</option>
            @foreach(['Menunggu pembayaran', 'Menunggu verifikasi', 'Terkonfirmasi', 'Selesai'] as $st)
                <option value="{{ $st }}" {{ $statusFilter === $st ? 'selected' : '' }}>{{ $st }}</option>
            @endforeach
        </select>

        <button type="submit"
                style="height: 42px; padding: 0 16px; border-radius: 8px; border: none; background: #1F3D2B; color: #FFFFFF; font-size: 13px; font-weight: 600; cursor: pointer;">
            Terapkan filter
        </button>
    </form>

    <p style="font-size: 11px; color: #718074;">
        Reservasi dihitung menurut tanggal dibuat; penerimaan menurut tanggal konfirmasi pembayaran. Rentang inklusif, WIB.
    </p>
</div>

<!-- 4 Kartu Ringkasan Laporan (Figma Frame 178:20628) -->
<div class="rep-kpi-grid">
    <div class="rep-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Total reservasi</span>
            <i class="bi bi-ticket-perforated" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            {{ $reportSummary['total_bookings'] }}
        </div>
        <div style="font-size: 11px; color: #718074;">
            Nilai reservasi Rp{{ number_format($reportSummary['total_value'], 0, ',', '.') }}
        </div>
    </div>

    <div class="rep-kpi-card dark">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #BDCCBE;">
            <span>Penerimaan terkonfirmasi</span>
            <i class="bi bi-wallet2" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #FFFFFF;">
            Rp{{ number_format($reportSummary['accepted_sum'], 0, ',', '.') }}
        </div>
        <div style="font-size: 11px; color: #BDCCBE;">
            Hanya {{ $reportSummary['accepted_count'] }} pembayaran diterima
        </div>
    </div>

    <div class="rep-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Menunggu pemeriksaan</span>
            <i class="bi bi-file-earmark-search" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            Rp{{ number_format($reportSummary['pending_sum'], 0, ',', '.') }}
        </div>
        <div style="font-size: 11px; color: #718074;">
            {{ $reportSummary['pending_count'] }} pembayaran · Bukan penerimaan
        </div>
    </div>

    <div class="rep-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Pembayaran ditolak</span>
            <i class="bi bi-x-circle" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            Rp{{ number_format($reportSummary['rejected_sum'], 0, ',', '.') }}
        </div>
        <div style="font-size: 11px; color: #718074;">
            {{ $reportSummary['rejected_count'] }} pembayaran · Bukan penerimaan
        </div>
    </div>
</div>

<!-- Tren Selama Periode & Rincian Status (Figma Frame 178:20653) -->
<div class="rep-mid-grid">
    <!-- Kiri: Tren Selama Periode -->
    <div class="rep-panel">
        <div>
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                Tren selama periode
            </h3>
            <p style="font-size: 12px; color: #718074;">1–9 November 2026 · Data contoh</p>
        </div>

        <div style="display: flex; gap: 20px; font-size: 11px;">
            <span style="color: #1F3D2B;">● Reservasi dibuat</span>
            <span style="color: #A54B10;">● Penerimaan terkonfirmasi</span>
        </div>

        <!-- Bar Chart Kelompok Periode (1-3 Nov, 4-6 Nov, 7-9 Nov) -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; height: 150px; align-items: end; padding: 0 16px;">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <div style="display: flex; gap: 10px; align-items: flex-end; height: 116px;">
                    <div style="width: 50px; height: 54px; background: #1F3D2B; border-radius: 5px 5px 0 0; color: #FFF; font-size: 11px; display: flex; justify-content: center; padding-top: 6px;">2</div>
                    <div style="width: 76px; height: 50px; background: #FFF0E4; border-radius: 5px 5px 0 0; color: #A54B10; font-size: 10px; display: flex; justify-content: center; padding-top: 6px;">Rp1,3 jt</div>
                </div>
                <span style="font-size: 11px; color: #718074;">1–3 Nov</span>
            </div>

            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <div style="display: flex; gap: 10px; align-items: flex-end; height: 116px;">
                    <div style="width: 50px; height: 54px; background: #1F3D2B; border-radius: 5px 5px 0 0; color: #FFF; font-size: 11px; display: flex; justify-content: center; padding-top: 6px;">2</div>
                    <div style="width: 76px; height: 46px; background: #FFF0E4; border-radius: 5px 5px 0 0; color: #A54B10; font-size: 10px; display: flex; justify-content: center; padding-top: 6px;">Rp1,2 jt</div>
                </div>
                <span style="font-size: 11px; color: #718074;">4–6 Nov</span>
            </div>

            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                <div style="display: flex; gap: 10px; align-items: flex-end; height: 116px;">
                    <div style="width: 50px; height: 108px; background: #1F3D2B; border-radius: 5px 5px 0 0; color: #FFF; font-size: 11px; display: flex; justify-content: center; padding-top: 6px;">4</div>
                    <div style="width: 76px; height: 110px; background: #FFF0E4; border-radius: 5px 5px 0 0; color: #A54B10; font-size: 10px; display: flex; justify-content: center; padding-top: 6px;">Rp2,9 jt</div>
                </div>
                <span style="font-size: 11px; color: #718074;">7–9 Nov</span>
            </div>
        </div>

        <p style="font-size: 10px; color: #718074;">
            Jumlah reservasi dan nilai rupiah memakai skala terpisah. Berdasarkan tanggal dibuat / dikonfirmasi.
        </p>

        <p style="font-size: 11px; color: #718074;">
            Total {{ $reportSummary['total_bookings'] }} reservasi dibuat · Rp{{ number_format($reportSummary['accepted_sum'], 0, ',', '.') }} diterima
        </p>
    </div>

    <!-- Kanan: Rincian Status (Figma Frame 178:20687) -->
    <div class="rep-panel">
        <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B;">
            Rincian status
        </h3>

        <div style="display: flex; flex-direction: column; gap: 12px; font-size: 13px;">
            <div style="font-size: 11px; font-weight: 600; color: #24382D;">RESERVASI</div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Menunggu pembayaran</span>
                <span style="font-weight: 500; color: #24382D;">{{ $statusCounts['menunggu_pembayaran'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Menunggu verifikasi</span>
                <span style="font-weight: 500; color: #24382D;">{{ $statusCounts['menunggu_verifikasi'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Terkonfirmasi</span>
                <span style="font-weight: 500; color: #24382D;">{{ $statusCounts['terkonfirmasi'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #718074;">Selesai</span>
                <span style="font-weight: 500; color: #24382D;">{{ $statusCounts['selesai'] }}</span>
            </div>
        </div>

        <div style="height: 1px; background: #E1E4DA;"></div>

        <div style="display: flex; justify-content: space-between; font-size: 13px;">
            <span style="color: #718074;">Belum bayar · {{ $statusCounts['unpaid_count'] }} reservasi</span>
            <span style="font-weight: 500; color: #24382D;">Rp{{ number_format($statusCounts['unpaid_sum'], 0, ',', '.') }}</span>
        </div>

        <p style="font-size: 10px; color: #718074; line-height: 1.5;">
            Tidak ada pembatalan pada periode ini. Status pembayaran ditolak tidak berarti reservasi dibatalkan.
        </p>
    </div>
</div>

<!-- Tabel Rekap per Paket (Figma Frame 178:20711) -->
<div class="rep-panel">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap;">
        <div>
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                Rekap per paket
            </h3>
            <p style="font-size: 12px; color: #718074;">
                Semua reservasi dalam periode terpilih · Nominal dalam rupiah
            </p>
        </div>
        <span style="background: #FFF0E4; color: #A54B10; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
            Data contoh
        </span>
    </div>

    <div style="overflow-x: auto;">
        <div style="min-width: 860px;">
            <div style="display: grid; grid-template-columns: 180px 80px 150px 150px 150px 135px 1fr; gap: 12px; background: #F4F6F1; padding: 12px; border-radius: 6px; font-size: 11px; font-weight: 600; color: #718074;">
                <div>Paket</div>
                <div>Reservasi</div>
                <div>Nilai reservasi</div>
                <div>Diterima</div>
                <div>Menunggu periksa</div>
                <div>Ditolak</div>
                <div>Belum bayar</div>
            </div>

            @foreach($packageRecap as $rec)
                <div style="display: grid; grid-template-columns: 180px 80px 150px 150px 150px 135px 1fr; gap: 12px; padding: 14px 12px; border-bottom: 1px solid #E1E4DA; font-size: 12px; color: #24382D; align-items: center;">
                    <div style="font-weight: 500;">{{ $rec['name'] }}</div>
                    <div>{{ $rec['count'] }}</div>
                    <div>Rp{{ number_format($rec['total_value'], 0, ',', '.') }}</div>
                    <div>Rp{{ number_format($rec['accepted_value'], 0, ',', '.') }}</div>
                    <div>Rp{{ number_format($rec['pending_value'], 0, ',', '.') }}</div>
                    <div>Rp{{ number_format($rec['rejected_value'], 0, ',', '.') }}</div>
                    <div>Rp{{ number_format($rec['unpaid_value'], 0, ',', '.') }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
