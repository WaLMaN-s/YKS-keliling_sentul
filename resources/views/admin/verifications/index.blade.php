@extends('layouts.admin', ['title' => 'Pembayaran', 'breadcrumb' => 'Pembayaran'])

@section('content')
<style>
    .pay-summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 960px) {
        .pay-summary-grid {
            grid-template-columns: 1fr;
        }
    }
    .pay-kpi-card {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .pay-kpi-card.dark {
        background: #1F3D2B;
        border-color: #1F3D2B;
        color: #FFFFFF;
    }
    .pay-tab {
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        background: #F4F6F1;
        color: #718074;
        transition: all 0.15s ease;
    }
    .pay-tab.active {
        background: #1F3D2B;
        color: #FFFFFF;
    }
    .pay-row {
        display: grid;
        grid-template-columns: 200px 180px 165px 145px 1fr 90px;
        gap: 12px;
        align-items: center;
        padding: 12px;
        min-height: 67px;
        border-bottom: 1px solid #E1E4DA;
    }
    .pill-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .pill-wait { background: #FFF0E4; color: #A54B10; }
    .pill-ok { background: #EAF0E7; color: #1F3D2B; }
    .pill-no { background: #FBEDEB; color: #AD4940; }
</style>

<!-- Identitas Halaman (Figma Frame 178:19878) -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px; margin-bottom: 6px;">Pembayaran</h1>
        <p style="font-size: 13px; color: #718074;">Periksa bukti transfer sebelum mengonfirmasi pembayaran pelanggan.</p>
    </div>

    <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; display: inline-flex; align-items: center; gap: 10px; font-size: 13px; color: #24382D;">
        <i class="bi bi-calendar3" style="color: #718074;"></i>
        <span>1–9 November 2026</span>
    </div>
</div>

<!-- 3 Kartu Ringkasan Bukti (Figma Frame 178:19885) -->
<div class="pay-summary-grid">
    <!-- Menunggu pemeriksaan -->
    <div class="pay-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Menunggu pemeriksaan</span>
            <i class="bi bi-file-earmark-search" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            {{ $summary['pending_count'] }} bukti
        </div>
        <div style="font-size: 11px; color: #718074;">
            Rp{{ number_format($summary['pending_sum'], 0, ',', '.') }} · Belum menjadi penerimaan
        </div>
    </div>

    <!-- Diterima (Dark Green Card) -->
    <div class="pay-kpi-card dark">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #BDCCBE;">
            <span>Diterima</span>
            <i class="bi bi-check-circle" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #FFFFFF;">
            {{ $summary['accepted_count'] }} bukti
        </div>
        <div style="font-size: 11px; color: #BDCCBE;">
            Rp{{ number_format($summary['accepted_sum'], 0, ',', '.') }} · Penerimaan terkonfirmasi
        </div>
    </div>

    <!-- Ditolak -->
    <div class="pay-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Ditolak</span>
            <i class="bi bi-x-circle" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            {{ $summary['rejected_count'] }} bukti
        </div>
        <div style="font-size: 11px; color: #718074;">
            Rp{{ number_format($summary['rejected_sum'], 0, ',', '.') }} · Perlu unggah ulang
        </div>
    </div>
</div>

<!-- Panel Antrean Bukti Transfer (Figma Frame 178:19904) -->
<div style="background: #FFFFFF; border: 1px solid #E1E4DA; border-radius: 12px; padding: 24px; display: flex; flex-direction: column; gap: 20px;">
    <div>
        <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
            Antrean bukti transfer
        </h3>
        <p style="font-size: 12px; color: #718074;">
            {{ $summary['all_count'] }} bukti diunggah · Prioritas: menunggu pemeriksaan
        </p>
    </div>

    <!-- Tab Status Pembayaran -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="{{ route('admin.verifications.index', ['tab' => 'all', 'q' => $search, 'package_id' => $packageFilter]) }}"
           class="pay-tab {{ $statusTab === 'all' ? 'active' : '' }}">
            Semua ({{ $summary['all_count'] }})
        </a>
        <a href="{{ route('admin.verifications.index', ['tab' => 'menunggu', 'q' => $search, 'package_id' => $packageFilter]) }}"
           class="pay-tab {{ $statusTab === 'menunggu' ? 'active' : '' }}">
            Menunggu pemeriksaan ({{ $summary['pending_count'] }})
        </a>
        <a href="{{ route('admin.verifications.index', ['tab' => 'diterima', 'q' => $search, 'package_id' => $packageFilter]) }}"
           class="pay-tab {{ $statusTab === 'diterima' ? 'active' : '' }}">
            Diterima ({{ $summary['accepted_count'] }})
        </a>
        <a href="{{ route('admin.verifications.index', ['tab' => 'ditolak', 'q' => $search, 'package_id' => $packageFilter]) }}"
           class="pay-tab {{ $statusTab === 'ditolak' ? 'active' : '' }}">
            Ditolak ({{ $summary['rejected_count'] }})
        </a>
    </div>

    <!-- Filter Pencarian Bukti -->
    <form action="{{ route('admin.verifications.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap;">
        <input type="hidden" name="tab" value="{{ $statusTab }}">
        <div style="position: relative;">
            <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #718074; font-size: 13px;"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nomor reservasi / pengirim..."
                   style="height: 42px; padding: 0 12px 0 34px; border: 1px solid #E1E4DA; border-radius: 8px; font-size: 13px; color: #24382D; width: 240px;">
        </div>

        <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: #24382D;">
            <i class="bi bi-calendar3" style="color: #718074;"></i>
            <span>Tanggal unggah: 1–9 Nov 2026</span>
        </div>

        <select name="package_id" onchange="this.form.submit()"
                style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 13px; color: #24382D; cursor: pointer;">
            <option value="">Semua paket</option>
            @foreach($packages as $p)
                <option value="{{ $p->id }}" {{ (string) $packageFilter === (string) $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
            @endforeach
        </select>

        @if($search || $packageFilter)
            <a href="{{ route('admin.verifications.index', ['tab' => $statusTab]) }}"
               style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; font-size: 12px; color: #718074; text-decoration: none; display: inline-flex; align-items: center;">
                Reset
            </a>
        @endif
    </form>

    <!-- Tabel Antrean Bukti Transfer -->
    <div style="overflow-x: auto;">
        <div style="min-width: 860px;">
            <div style="display: grid; grid-template-columns: 200px 180px 165px 145px 1fr 90px; gap: 12px; background: #F4F6F1; padding: 12px; border-radius: 6px; font-size: 11px; font-weight: 600; color: #718074;">
                <div>No. reservasi / pengirim</div>
                <div>Paket</div>
                <div>Tanggal unggah (WIB)</div>
                <div>Nominal transfer</div>
                <div>Status pemeriksaan</div>
                <div>Aksi</div>
            </div>

            @forelse($payments as $item)
                @php
                    $pkgName = $item->custom_package_name ?: ($item->catalog_package_name ?? 'Jelajah Jeep');
                    $isPending = ($item->payment_status === 'Menunggu Verifikasi');
                    $isAccepted = ($item->payment_status === 'Lunas');
                @endphp
                <div class="pay-row">
                    <div>
                        <div style="font-size: 12px; font-weight: 600; color: #24382D;">{{ $item->booking_code }}</div>
                        <div style="font-size: 11px; color: #718074; margin-top: 2px;">{{ $item->customer_name }} · Bank Pengirim</div>
                    </div>

                    <div style="font-size: 12px; color: #24382D;">
                        {{ $pkgName }}
                    </div>

                    <div style="font-size: 12px; color: #24382D;">
                        {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M · H.i') }}
                    </div>

                    <div style="font-size: 12px; color: #24382D; font-weight: 500;">
                        Rp{{ number_format($item->total_price, 0, ',', '.') }}
                    </div>

                    <div>
                        @if($isPending)
                            <span class="pill-status pill-wait">Menunggu pemeriksaan</span>
                        @elseif($isAccepted)
                            <span class="pill-status pill-ok">Diterima</span>
                        @else
                            <span class="pill-status pill-no">Ditolak</span>
                        @endif
                    </div>

                    <div>
                        <a href="{{ route('admin.verifications.show', $item->booking_code) }}"
                           style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                            {{ $isPending ? 'Tinjau' : 'Detail' }}
                        </a>
                    </div>
                </div>
            @empty
                <div style="padding: 36px; text-align: center; color: #718074; font-size: 13px;">
                    Tidak ada bukti pembayaran yang sesuai dengan filter.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Catatan Bawah Tabel (Figma Frame 178:20063) -->
    <div style="background: #F4F6F1; border-radius: 8px; padding: 14px 16px; display: flex; align-items: center; gap: 10px; font-size: 12px; color: #24382D;">
        <i class="bi bi-info-circle" style="color: #1F3D2B;"></i>
        <span>Pembayaran baru diakui sebagai penerimaan setelah admin memeriksa mutasi rekening dan menekan tombol Konfirmasi pembayaran.</span>
    </div>
</div>
@endsection
