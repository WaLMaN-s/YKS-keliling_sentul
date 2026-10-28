@extends('layouts.app', ['title' => 'Riwayat Trip Saya'])

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp; 
        <span>Riwayat Trip Saya</span>
    </div>
    
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; margin-bottom: 32px;">
        <div>
            <div class="section-tag">
                ━ &nbsp; RIWAYAT PESANAN
            </div>
            <h1 class="section-title" style="font-size: 2.4rem; margin: 0;">
                Halo, {{ explode(' ', $user->name)[0] }}!
            </h1>
            <p class="section-desc" style="margin-top: 6px;">Berikut daftar seluruh reservasi dan tiket petualangan Anda bersama YKS.</p>
        </div>

        <a href="{{ route('packages.index') }}" class="btn btn-primary">
            + &nbsp; Pesan Trip Baru
        </a>
    </div>
</div>

<section class="section-wrapper" style="padding-top: 0;">
    <div class="container" style="max-width: 960px;">
        @if ($bookings->isEmpty())
            <div style="text-align: center; padding: 64px 20px; background-color: #FFFFFF; border-radius: var(--radius-lg); border: 1px dashed var(--border);">
                <i class="bi bi-compass" style="font-size: 52px; color: var(--text-sub);"></i>
                <h3 style="margin-top: 16px; color: var(--primary);">Belum Ada Riwayat Trip</h3>
                <p style="color: var(--text-muted); margin-bottom: 24px;">Anda belum pernah memesan trip petualangan Sentul bersama kami.</p>
                <a href="{{ route('packages.index') }}" class="btn btn-forest btn-lg">Pilih Paket Wisata Pertama Anda &nbsp;↗</a>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 20px;">
                @foreach ($bookings as $ord)
                    <div style="background-color: #FFFFFF; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; transition: all 0.2s ease;">
                        <div>
                            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                                <code style="font-size: 0.92rem; font-weight: 800; color: var(--primary); background-color: var(--bg-sage); padding: 3px 10px; border-radius: 6px;">
                                    {{ $ord->booking_code }}
                                </code>
                                <span style="font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 20px; 
                                      background-color: {{ $ord->payment_status === 'Lunas' ? '#10b981' : ($ord->payment_status === 'Menunggu Verifikasi' ? '#f59e0b' : '#ef4444') }}; color: #FFFFFF;">
                                    {{ $ord->payment_status }}
                                </span>
                            </div>
                            <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 8px;">
                                {{ $ord->package->name ?? 'Paket Wisata Sentul' }}
                            </h3>
                            <div style="display: flex; gap: 20px; font-size: 0.88rem; color: var(--text-muted); flex-wrap: wrap;">
                                <span><i class="bi bi-calendar-event"></i> Jadwal: <strong>{{ \Carbon\Carbon::parse($ord->trip_date)->translatedFormat('d F Y') }}</strong></span>
                                <span><i class="bi bi-people"></i> <strong>{{ $ord->participant_count }} Peserta</strong></span>
                                <span><i class="bi bi-wallet2"></i> Total: <strong style="color: var(--accent);">{{ $ord->formatted_total }}</strong></span>
                            </div>
                        </div>

                        <div style="display: flex; gap: 10px; align-items: center;">
                            @if ($ord->payment_status === 'Menunggu Pembayaran')
                                <a href="{{ route('payment.show', $ord->booking_code) }}" class="btn btn-primary btn-sm">
                                    Bayar / Upload Bukti &nbsp;↗
                                </a>
                            @endif
                            <a href="{{ route('booking.track', ['kode' => $ord->booking_code]) }}" class="btn btn-outline btn-sm">
                                Detail Tiket
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
