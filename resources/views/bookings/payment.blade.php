@extends('layouts.app', ['title' => 'Instruksi Pembayaran'])

@section('content')
<div style="background: var(--dark); color: #fff; padding: 40px 0; text-align: center;">
    <div class="container">
        <span class="badge badge-warning" style="margin-bottom: 8px;">Langkah Terakhir</span>
        <h1 style="font-size: 2.1rem; font-weight: 800; margin-bottom: 8px;">Selesaikan Pembayaran Trip</h1>
        <p style="color: #cbd5e1;">Kode Booking Anda: <strong style="color: #fde047;">{{ $booking->booking_code }}</strong></p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 860px;">
        <div style="display: grid; grid-template-columns: 1.3fr 1fr; gap: 30px;">
            <!-- Left: Rekening & Instruksi -->
            <div>
                <div style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 28px; box-shadow: var(--shadow-sm); margin-bottom: 24px;">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--dark); margin-bottom: 16px;">
                        Pilihan Metode Pembayaran
                    </h3>

                    <!-- Option 1: BCA -->
                    <div style="border: 1px solid var(--border); border-radius: var(--radius-md); padding: 18px; margin-bottom: 14px; background: #fafafa;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: #1e3a8a; font-size: 1.05rem;"><i class="bi bi-bank"></i> Bank BCA</strong>
                            <span class="badge" style="background: #dbeafe; color: #1e40af;">Transfer Bank</span>
                        </div>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Nomor Rekening:</p>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <code style="font-size: 1.2rem; font-weight: 800; color: var(--dark); background: #fff; padding: 4px 10px; border-radius: 6px; border: 1px solid var(--border);">
                                8720-1928-33
                            </code>
                            <button type="button" class="btn btn-outline btn-sm" onclick="navigator.clipboard.writeText('8720192833'); alert('Nomor rekening BCA disalin!');">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                        </div>
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">a.n. YUK KELILING SENTUL</small>
                    </div>

                    <!-- Option 2: Mandiri -->
                    <div style="border: 1px solid var(--border); border-radius: var(--radius-md); padding: 18px; margin-bottom: 14px; background: #fafafa;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <strong style="color: #92400e; font-size: 1.05rem;"><i class="bi bi-bank2"></i> Bank Mandiri</strong>
                            <span class="badge" style="background: #fef3c7; color: #92400e;">Transfer Bank</span>
                        </div>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 6px;">Nomor Rekening:</p>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <code style="font-size: 1.2rem; font-weight: 800; color: var(--dark); background: #fff; padding: 4px 10px; border-radius: 6px; border: 1px solid var(--border);">
                                133-00-9876543-1
                            </code>
                            <button type="button" class="btn btn-outline btn-sm" onclick="navigator.clipboard.writeText('1330098765431'); alert('Nomor rekening Mandiri disalin!');">
                                <i class="bi bi-clipboard"></i> Salin
                            </button>
                        </div>
                        <small style="color: var(--text-muted); display: block; margin-top: 6px;">a.n. YUK KELILING SENTUL</small>
                    </div>

                    <!-- Option 3: QRIS -->
                    <div style="border: 1px solid var(--border); border-radius: var(--radius-md); padding: 18px; background: #fdfefe; text-align: center;">
                        <strong style="display: block; font-size: 1rem; color: var(--dark); margin-bottom: 8px;">
                            <i class="bi bi-qr-code-scan text-primary"></i> Scan QRIS Digital
                        </strong>
                        <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 12px;">Mendukung BCA Mobile, GoPay, OVO, ShopeePay, Dana, & LinkAja</p>
                        <div style="display: inline-block; padding: 10px; background: #fff; border: 2px dashed #cbd5e1; border-radius: 12px;">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode('YKS-SENTUL-PAYMENT-' . $booking->booking_code . '-TOTAL-' . $booking->total_price) }}" alt="QRIS YKS" style="width: 170px; height: 170px;">
                        </div>
                        <small style="display: block; color: var(--text-muted); margin-top: 6px;">NMID: ID102026889912 YUK KELILING SENTUL</small>
                    </div>
                </div>

                <!-- Form Upload Bukti -->
                <div style="background: #fff; border-radius: var(--radius-lg); border: 1px solid var(--border); padding: 28px; box-shadow: var(--shadow-sm);">
                    <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--dark); margin-bottom: 12px;">
                        <i class="bi bi-upload text-primary"></i> Upload Bukti Pembayaran
                    </h3>
                    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 18px;">
                        Setelah transfer, silakan upload struk / screenshot m-banking Anda di sini.
                    </p>

                    <form method="POST" action="{{ route('payment.upload', $booking->booking_code) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Pilih Foto / Screenshot Bukti Transfer <span class="text-danger">*</span></label>
                            <input type="file" name="payment_proof" id="buktiBayarInput" class="form-control" accept="image/*" required>
                            <small class="text-muted">Format: JPG, PNG, WEBP (Maks. 5 MB)</small>
                            <div id="previewBox"></div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-2">
                            <i class="bi bi-check-circle"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div>
                <div class="booking-card" style="position: sticky; top: 96px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border-light);">
                        <strong style="color: var(--dark);">Status Booking</strong>
                        <span class="badge {{ $booking->payment_status === 'Lunas' ? 'badge-success' : ($booking->payment_status === 'Menunggu Verifikasi' ? 'badge-warning' : 'badge-pending') }}">
                            {{ $booking->payment_status }}
                        </span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <span style="font-size: 0.82rem; color: var(--text-muted);">Paket Dipilih:</span>
                        <h4 style="font-size: 1.05rem; font-weight: 800; color: var(--primary); margin: 2px 0 6px;">
                            {{ $booking->package->name ?? 'Paket Wisata Sentul' }}
                        </h4>
                        <p style="font-size: 0.85rem; color: var(--text-muted);"><i class="bi bi-calendar"></i> Tanggal Trip: <strong>{{ \Carbon\Carbon::parse($booking->trip_date)->translatedFormat('d F Y') }}</strong></p>
                        <p style="font-size: 0.85rem; color: var(--text-muted);"><i class="bi bi-people"></i> Peserta: <strong>{{ $booking->participant_count }} Orang</strong></p>
                        <p style="font-size: 0.85rem; color: var(--text-muted);"><i class="bi bi-person"></i> Pemesan: <strong>{{ $booking->customer_name }}</strong></p>
                        <p style="font-size: 0.85rem; color: var(--text-muted);"><i class="bi bi-whatsapp"></i> WA: <strong>{{ $booking->customer_phone }}</strong></p>
                    </div>

                    <div style="background: var(--bg-body); border-radius: var(--radius-md); padding: 16px; margin-bottom: 20px;">
                        <span style="font-size: 0.85rem; color: var(--text-muted); display: block;">Total yang Harus Ditransfer:</span>
                        <div style="font-size: 1.7rem; font-weight: 800; color: var(--primary); margin: 4px 0;">
                            {{ $booking->formatted_total }}
                        </div>
                        <small style="color: #059669; font-weight: 600;"><i class="bi bi-check2-circle"></i> Nominal Pas Tanpa Kode Unik</small>
                    </div>

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281234567890') }}?text=Halo%20Admin%20YKS,%20saya%20sudah%20buat%20booking%20kode:%20{{ urlencode($booking->booking_code) }}" 
                       target="_blank" 
                       class="btn btn-outline btn-block">
                        <i class="bi bi-whatsapp text-primary"></i> Konfirmasi ke WA Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
