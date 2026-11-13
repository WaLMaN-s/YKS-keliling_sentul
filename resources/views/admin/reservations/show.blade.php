@extends('layouts.admin', ['title' => 'Detail Reservasi ' . $booking->booking_code])

@section('content')
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <a href="{{ route('admin.reservations.index') }}" style="color: #6B756E; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 8px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Reservasi
        </a>
        <h1 style="font-size: 1.5rem; font-weight: 700;">Reservasi #{{ $booking->booking_code }}</h1>
    </div>

    <div>
        <span style="display: inline-block; padding: 6px 14px; border-radius: 99px; font-size: 0.85rem; font-weight: 600; 
            {{ $booking->payment_status === 'Lunas' ? 'background: #EAF0E7; color: #1F3D2B;' : '' }}
            {{ $booking->payment_status === 'Menunggu Verifikasi' ? 'background: #FEEFE6; color: #F26B1D;' : '' }}
            {{ $booking->payment_status === 'Menunggu Pembayaran' ? 'background: #EDEEE9; color: #6B756E;' : '' }}
        ">
            {{ $booking->payment_status }}
        </span>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
    <!-- Rincian Data Reservasi -->
    <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid #DFE4DC; padding-bottom: 10px;">Informasi Pemesan & Trip</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; font-size: 0.9rem; margin-bottom: 20px;">
            <div>
                <span style="color: #6B756E; font-size: 0.8rem; display: block;">Nama Pemesan</span>
                <strong>{{ $booking->customer_name }}</strong>
            </div>
            <div>
                <span style="color: #6B756E; font-size: 0.8rem; display: block;">Nomor Telepon / WhatsApp</span>
                <strong>{{ $booking->customer_phone }}</strong>
            </div>
            <div>
                <span style="color: #6B756E; font-size: 0.8rem; display: block;">Email</span>
                <strong>{{ $booking->customer_email }}</strong>
            </div>
            <div>
                <span style="color: #6B756E; font-size: 0.8rem; display: block;">Tanggal Trip</span>
                <strong>{{ date('d F Y', strtotime($booking->trip_date)) }}</strong>
            </div>
            <div>
                <span style="color: #6B756E; font-size: 0.8rem; display: block;">Paket Pilihan</span>
                <strong>{{ $booking->package_name }}</strong>
            </div>
            <div>
                <span style="color: #6B756E; font-size: 0.8rem; display: block;">Jumlah Peserta</span>
                <strong>{{ $booking->participant_count }} Orang</strong>
            </div>
        </div>

        @if($booking->notes)
            <div style="background: #F8F5EF; padding: 14px; border-radius: 8px; font-size: 0.85rem; margin-top: 10px;">
                <strong style="display: block; margin-bottom: 4px; color: #1F3D2B;">Catatan / Permintaan Khusus:</strong>
                {{ $booking->notes }}
            </div>
        @endif
    </div>

    <!-- Rincian Pembayaran & Bukti -->
    <div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid #DFE4DC; padding-bottom: 10px;">Status Pembayaran</h3>

        <div style="margin-bottom: 16px;">
            <span style="color: #6B756E; font-size: 0.8rem; display: block;">Total Tagihan</span>
            <div style="font-size: 1.5rem; font-weight: 800; color: #1F3D2B;">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</div>
            <div style="font-size: 0.8rem; color: #6B756E; margin-top: 4px;">Metode: {{ $booking->payment_method }}</div>
        </div>

        @php
            $showProofUrl = \App\Models\YksBooking::resolveProofUrl($booking->payment_proof);
        @endphp
        @if($showProofUrl)
            <div style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="color: #6B756E; font-size: 0.8rem;">Bukti Transfer Pelanggan</span>
                    <a href="{{ $showProofUrl }}" target="_blank" style="font-size: 0.75rem; font-weight: 600; color: #F26B1D; text-decoration: none;">
                        Buka Tab Baru ↗
                    </a>
                </div>
                <a href="{{ $showProofUrl }}" target="_blank">
                    <img src="{{ $showProofUrl }}" alt="Bukti Transfer" style="width: 100%; max-height: 280px; object-fit: contain; border-radius: 8px; border: 1px solid #DFE4DC; background: #FFF;">
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
