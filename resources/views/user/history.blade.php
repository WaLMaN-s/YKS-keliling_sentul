@extends('layouts.app', ['title' => 'Riwayat Trip Saya'])

@section('content')
<div style="background: var(--dark); color: #fff; padding: 40px 0;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 4px;">Riwayat Pesanan Trip</h1>
            <p style="color: #cbd5e1;">Halo, <strong>{{ $user->name }}</strong>! Berikut daftar petualangan Sentul Anda.</p>
        </div>
        <a href="{{ route('packages.index') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Pesan Trip Baru
        </a>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 900px;">
        @if ($bookings->isEmpty())
            <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: var(--radius-lg); border: 1px dashed var(--border);">
                <i class="bi bi-compass text-muted" style="font-size: 48px;"></i>
                <h3 style="margin-top: 14px; color: var(--dark);">Belum Ada Riwayat Trip</h3>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Anda belum pernah memesan paket petualangan di Sentul bersama kami.</p>
                <a href="{{ route('packages.index') }}" class="btn btn-primary btn-lg">Pilih Paket Wisata Pertama Anda</a>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach ($bookings as $ord)
                    <div style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 24px; box-shadow: var(--shadow-sm); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 18px;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                                <code style="font-size: 0.9rem; font-weight: 800; color: var(--dark); background: var(--bg-body); padding: 2px 8px; border-radius: 6px; border: 1px solid var(--border);">
                                    {{ $ord->booking_code }}
                                </code>
                                <span class="badge {{ $ord->payment_status === 'Lunas' ? 'badge-success' : ($ord->payment_status === 'Menunggu Verifikasi' ? 'badge-warning' : 'badge-pending') }}">
                                    {{ $ord->payment_status }}
                                </span>
                            </div>
                            <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--primary); margin-bottom: 6px;">
                                {{ $ord->package->name ?? 'Paket Wisata Sentul' }}
                            </h3>
                            <div style="display: flex; gap: 18px; font-size: 0.88rem; color: var(--text-muted); flex-wrap: wrap;">
                                <span><i class="bi bi-calendar text-primary"></i> Keberangkatan: <strong>{{ \Carbon\Carbon::parse($ord->trip_date)->translatedFormat('d F Y') }}</strong></span>
                                <span><i class="bi bi-people text-primary"></i> <strong>{{ $ord->participant_count }} Peserta</strong></span>
                                <span><i class="bi bi-wallet2 text-primary"></i> Total: <strong>{{ $ord->formatted_total }}</strong></span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px; align-items: center;">
                            @if ($ord->payment_status === 'Menunggu Pembayaran')
                                <a href="{{ route('payment.show', $ord->booking_code) }}" class="btn btn-accent btn-sm">
                                    <i class="bi bi-upload"></i> Bayar / Upload Struk
                                </a>
                            @endif
                            <a href="{{ route('booking.track', ['kode' => $ord->booking_code]) }}" class="btn btn-outline btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
