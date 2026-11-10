@extends('layouts.admin', ['title' => 'Verifikasi Pembayaran'])

@section('content')
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 6px;">Antrean Verifikasi Pembayaran</h1>
    <p style="color: #6B756E; font-size: 0.9rem;">Periksa bukti transfer dan cocokkan mutasi bank sebelum mengonfirmasi jadwal reservasi.</p>
</div>

<div style="background: #FFFFFF; border: 1px solid var(--admin-border); border-radius: 12px; padding: 24px;">
    @if(count($pendingPayments) === 0)
        <div style="text-align: center; padding: 48px; color: #6B756E;">
            <i class="bi bi-check2-circle" style="font-size: 3rem; color: #1F3D2B; display: block; margin-bottom: 12px;"></i>
            <h3>Semua Bukti Telah Diperiksa!</h3>
            <p style="font-size: 0.875rem; margin-top: 6px;">Tidak ada bukti pembayaran yang menunggu verifikasi saat ini.</p>
        </div>
    @else
        <div style="display: flex; flex-direction: column; gap: 20px;">
            @foreach($pendingPayments as $item)
                <div style="border: 1px solid #DFE4DC; border-radius: 10px; padding: 20px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
                    <div>
                        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                            <span style="font-weight: 700; font-size: 1.05rem;">{{ $item->booking_code }}</span>
                            <span style="background: #FEEFE6; color: #F26B1D; font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 99px;">Menunggu Verifikasi</span>
                        </div>
                        <div style="font-size: 0.85rem; color: #6B756E; line-height: 1.6;">
                            <div><strong>Pelanggan:</strong> {{ $item->customer_name }} ({{ $item->customer_phone }})</div>
                            <div><strong>Paket:</strong> {{ $item->package_name }} · {{ $item->participant_count }} Peserta</div>
                            <div><strong>Tanggal Trip:</strong> {{ date('d M Y', strtotime($item->trip_date)) }}</div>
                            <div><strong>Nominal Ditagihkan:</strong> <span style="font-weight: 700; color: #1F3D2B;">Rp{{ number_format($item->total_price, 0, ',', '.') }}</span></div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px;">
                        @if($item->payment_proof)
                            <a href="{{ asset('storage/' . $item->payment_proof) }}" target="_blank" class="btn-primary-yks" style="background: #304F3B;">
                                <i class="bi bi-image"></i> Lihat Bukti Transfer
                            </a>
                        @endif

                        <form action="{{ route('admin.verifications.approve', $item->booking_code) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary-yks" style="background: #1F3D2B;">
                                <i class="bi bi-check-lg"></i> Setujui Pembayaran
                            </button>
                        </form>

                        <form action="{{ route('admin.verifications.reject', $item->booking_code) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak bukti ini?')">
                            @csrf
                            <button type="submit" style="background: #FFF; border: 1px solid #E57373; color: #D32F2F; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; cursor: pointer;">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
