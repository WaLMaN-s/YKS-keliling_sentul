@extends('layouts.admin', ['title' => 'Ringkasan'])

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Ringkasan Operasional</h1>
    <p style="color: #6B756E; font-size: 0.9rem;">Ringkasan aktivitas reservasi dan pendapatan Yuk Keliling Sentul.</p>
</div>

<!-- Grid Statistik (Figma Specification) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Total Reservasi</span>
        <h3 style="font-size: 1.8rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $stats['total_reservasi'] }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">{{ $stats['rasio_lunas'] }} reservasi sudah lunas</p>
    </div>

    <div class="stat-card" style="border-top: 3px solid #F26B1D;">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #F26B1D; font-weight: 600;">Menunggu Verifikasi</span>
        <h3 style="font-size: 1.8rem; font-weight: 800; color: #F26B1D; margin-top: 6px;">{{ $stats['menunggu_verifikasi'] }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Bukti pembayaran baru masuk</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Lunas & Siap Jalan</span>
        <h3 style="font-size: 1.8rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $stats['lunas'] }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Siap dijadwalkan bersama tim guide</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Estimasi Pendapatan</span>
        <h3 style="font-size: 1.6rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">Rp{{ number_format($stats['pendapatan_kotor'], 0, ',', '.') }}</h3>
        <p style="font-size: 0.75rem; color: #6B756E; margin-top: 4px;">Periode aktif bulan berjalan</p>
    </div>
</div>

<!-- Antrean Verifikasi Cepat & Reservasi Terbaru -->
<div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 1.1rem; font-weight: 700;">Reservasi Terbaru</h3>
        <a href="{{ route('admin.reservations.index') }}" class="btn-primary-yks">Lihat Semua</a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                    <th style="padding: 10px;">Kode</th>
                    <th style="padding: 10px;">Pelanggan</th>
                    <th style="padding: 10px;">Paket</th>
                    <th style="padding: 10px;">Tgl Trip</th>
                    <th style="padding: 10px;">Total</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $b)
                    <tr style="border-bottom: 1px solid #ECECE6;">
                        <td style="padding: 12px 10px; font-weight: 600;">{{ $b->booking_code }}</td>
                        <td style="padding: 12px 10px;">{{ $b->customer_name }}</td>
                        <td style="padding: 12px 10px;">{{ $b->package_name }}</td>
                        <td style="padding: 12px 10px;">{{ date('d M Y', strtotime($b->trip_date)) }}</td>
                        <td style="padding: 12px 10px; font-weight: 600;">Rp{{ number_format($b->total_price, 0, ',', '.') }}</td>
                        <td style="padding: 12px 10px;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; 
                                {{ $b->payment_status === 'Lunas' ? 'background: #EAF0E7; color: #1F3D2B;' : '' }}
                                {{ $b->payment_status === 'Menunggu Verifikasi' ? 'background: #FEEFE6; color: #F26B1D;' : '' }}
                                {{ $b->payment_status === 'Menunggu Pembayaran' ? 'background: #EDEEE9; color: #6B756E;' : '' }}
                            ">
                                {{ $b->payment_status }}
                            </span>
                        </td>
                        <td style="padding: 12px 10px; text-align: right;">
                            <a href="{{ route('admin.reservations.show', $b->booking_code) }}" style="color: #1F3D2B; text-decoration: underline; font-size: 0.8rem;">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 24px; text-align: center; color: #6B756E;">Belum ada reservasi masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
