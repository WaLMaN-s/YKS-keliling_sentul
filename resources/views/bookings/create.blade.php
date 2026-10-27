@extends('layouts.app', ['title' => 'Formulir Pemesanan Trip'])

@section('content')
<div style="background: var(--dark); color: #fff; padding: 40px 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 2.1rem; font-weight: 800; margin-bottom: 8px;">Formulir Pemesanan Trip</h1>
        <p style="color: #cbd5e1;">Lengkapi data di bawah ini untuk mengamankan slot tour guide Anda.</p>
    </div>
</div>

<section class="section">
    <div class="container" style="max-width: 960px;">
        <form method="POST" action="{{ route('booking.store') }}">
            @csrf
            <input type="hidden" name="package_id" value="{{ $package->id }}">

            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 32px;">
                <!-- Left: Form Input -->
                <div style="background: #fff; padding: 32px; border-radius: var(--radius-lg); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 20px; color: var(--dark); display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-person-fill text-primary"></i> Data Pemesan
                    </h3>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap Pemesan <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required 
                               placeholder="Contoh: Budi Pratama" 
                               value="{{ old('customer_name', $user->name ?? '') }}">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp Aktif <span class="text-danger">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" required 
                                   placeholder="08123456789" 
                                   value="{{ old('customer_phone') }}">
                            <small class="text-muted">Untuk koordinasi GPS titik kumpul</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="customer_email" class="form-control" required 
                                   placeholder="nama@email.com" 
                                   value="{{ old('customer_email', $user->email ?? '') }}">
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid var(--border-light); margin: 24px 0;">

                    <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 20px; color: var(--dark); display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-calendar-event text-primary"></i> Rencana Petualangan
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Tanggal Keberangkatan Trip <span class="text-danger">*</span></label>
                            <input type="date" name="trip_date" class="form-control" required 
                                   min="{{ date('Y-m-d') }}" 
                                   value="{{ old('trip_date', date('Y-m-d', strtotime('+1 day'))) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Jumlah Peserta (Orang) <span class="text-danger">*</span></label>
                            <div style="display: flex; gap: 8px;">
                                <button type="button" id="btnMinus" class="btn btn-outline" style="padding: 0 16px;">-</button>
                                <input type="number" id="jumlahPeserta" name="participant_count" class="form-control" style="text-align: center; font-weight: 700;" min="1" max="100" 
                                       value="{{ old('participant_count', 1) }}">
                                <button type="button" id="btnPlus" class="btn btn-outline" style="padding: 0 16px;">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Metode Pembayaran Pilihan</label>
                        <select name="payment_method" class="form-control">
                            <option value="Transfer Bank BCA" {{ old('payment_method') === 'Transfer Bank BCA' ? 'selected' : '' }}>Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri" {{ old('payment_method') === 'Transfer Bank Mandiri' ? 'selected' : '' }}>Transfer Bank Mandiri</option>
                            <option value="QRIS Digital" {{ old('payment_method') === 'QRIS Digital' ? 'selected' : '' }}>QRIS (GoPay, OVO, Dana, ShopeePay, BCA)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Tambahan / Permintaan Khusus (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Ada anak usia 7 tahun, butuh pelampung tambahan, atau request start jam 07.00 pagi.">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Right: Summary Card -->
                <div>
                    <div class="booking-card" style="position: sticky; top: 96px;">
                        <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--dark); margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 12px;">
                            Ringkasan Paket
                        </h4>

                        <div style="margin-bottom: 16px;">
                            <strong style="color: var(--primary); font-size: 0.95rem; display: block; margin-bottom: 4px;">{{ $package->name }}</strong>
                            <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; gap: 12px;">
                                <span><i class="bi bi-clock"></i> {{ $package->duration }}</span>
                                <span><i class="bi bi-signpost"></i> {{ $package->distance }}</span>
                            </div>
                        </div>

                        <div style="background: var(--bg-body); border-radius: var(--radius-md); padding: 16px; margin-bottom: 20px;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span class="text-muted">Harga / Orang</span>
                                <span id="hargaPerOrang" data-harga="{{ $package->price }}"><strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 12px;">
                                <span class="text-muted">Biaya Panduan & P3K</span>
                                <span class="text-success"><strong>Gratis (Include)</strong></span>
                            </div>
                            <div style="border-top: 1px dashed var(--border); padding-top: 12px; display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-weight: 700; color: var(--dark);">Total Estimasi:</span>
                                <span id="totalHargaDisplay" style="font-size: 1.4rem; font-weight: 800; color: var(--primary);">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 12px;">
                            <i class="bi bi-lock-fill"></i> Lanjut ke Pembayaran
                        </button>

                        <p style="text-align: center; font-size: 0.78rem; color: var(--text-muted);">
                            <i class="bi bi-shield-check text-success"></i> Slot booking dijamin aman. Admin akan menghubungi via WhatsApp setelah bukti transfer terunggah.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
