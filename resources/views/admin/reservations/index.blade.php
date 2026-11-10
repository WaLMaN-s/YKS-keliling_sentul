@extends('layouts.admin', ['title' => 'Data Reservasi'])

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Data Reservasi</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Kelola seluruh riwayat pemesanan trip petualangan YKS.</p>
    </div>

    <!-- Filter Status -->
    <div style="display: flex; gap: 8px;">
        <a href="{{ route('admin.reservations.index') }}" class="btn-primary-yks" style="{{ !$status ? '' : 'background: #FFFFFF; color: #1F3D2B; border: 1px solid #DFE4DC;' }}">Semua</a>
        <a href="{{ route('admin.reservations.index', ['status' => 'Lunas']) }}" class="btn-primary-yks" style="{{ $status === 'Lunas' ? '' : 'background: #FFFFFF; color: #1F3D2B; border: 1px solid #DFE4DC;' }}">Lunas</a>
        <a href="{{ route('admin.reservations.index', ['status' => 'Menunggu Verifikasi']) }}" class="btn-primary-yks" style="{{ $status === 'Menunggu Verifikasi' ? '' : 'background: #FFFFFF; color: #1F3D2B; border: 1px solid #DFE4DC;' }}">Verifikasi</a>
        <a href="{{ route('admin.reservations.index', ['status' => 'Menunggu Pembayaran']) }}" class="btn-primary-yks" style="{{ $status === 'Menunggu Pembayaran' ? '' : 'background: #FFFFFF; color: #1F3D2B; border: 1px solid #DFE4DC;' }}">Belum Bayar</a>
    </div>
</div>

<div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                    <th style="padding: 12px 10px;">Kode</th>
                    <th style="padding: 12px 10px;">Pelanggan</th>
                    <th style="padding: 12px 10px;">Kontak</th>
                    <th style="padding: 12px 10px;">Paket</th>
                    <th style="padding: 12px 10px;">Tgl Trip</th>
                    <th style="padding: 12px 10px;">Total</th>
                    <th style="padding: 12px 10px;">Status</th>
                    <th style="padding: 12px 10px; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $r)
                    <tr style="border-bottom: 1px solid #ECECE6;">
                        <td style="padding: 12px 10px; font-weight: 600;">{{ $r->booking_code }}</td>
                        <td style="padding: 12px 10px;">{{ $r->customer_name }}</td>
                        <td style="padding: 12px 10px; color: #6B756E;">{{ $r->customer_phone }}</td>
                        <td style="padding: 12px 10px;">{{ $r->package_name }}</td>
                        <td style="padding: 12px 10px;">{{ date('d M Y', strtotime($r->trip_date)) }}</td>
                        <td style="padding: 12px 10px; font-weight: 600;">Rp{{ number_format($r->total_price, 0, ',', '.') }}</td>
                        <td style="padding: 12px 10px;">
                            <span style="display: inline-block; padding: 4px 10px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; 
                                {{ $r->payment_status === 'Lunas' ? 'background: #EAF0E7; color: #1F3D2B;' : '' }}
                                {{ $r->payment_status === 'Menunggu Verifikasi' ? 'background: #FEEFE6; color: #F26B1D;' : '' }}
                                {{ $r->payment_status === 'Menunggu Pembayaran' ? 'background: #EDEEE9; color: #6B756E;' : '' }}
                            ">
                                {{ $r->payment_status }}
                            </span>
                        </td>
                        <td style="padding: 12px 10px; text-align: right;">
                            <a href="{{ route('admin.reservations.show', $r->booking_code) }}" style="color: #1F3D2B; text-decoration: underline; font-size: 0.8rem;">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="padding: 32px; text-align: center; color: #6B756E;">Tidak ada data reservasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $reservations->links() }}
    </div>
</div>
@endsection
