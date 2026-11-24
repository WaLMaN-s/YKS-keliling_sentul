@extends('layouts.app', ['title' => 'Reservasi Jadwal & Data Pemesan'])

@section('content')
<!-- Page Breadcrumb & Header -->
<div class="container" style="padding-top: 36px;">
    <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px;">
        <a href="{{ route('home') }}" style="color: var(--text-sub);">Beranda</a> &nbsp;/&nbsp;
        <a href="{{ route('packages.index') }}" style="color: var(--text-sub);">Paket</a> &nbsp;/&nbsp;
        <span>Reservasi</span>
    </div>
    <div class="section-tag">
        ━ &nbsp; RESERVASI · PILIH JADWAL & DATA PEMESAN
    </div>
    <h1 class="section-title" style="font-size: 2.4rem; margin-bottom: 24px;">
        Pilih Jadwal & Lengkapi Data Pemesan
    </h1>

    <!-- 4-Step Progress Navigation (Figma ON GOING Pattern) -->
    <div class="reservation-steps-nav">
        <div class="step-indicator-item completed">
            <span class="step-badge"><i class="bi bi-check"></i></span>
            <span>Pilih Jadwal</span>
        </div>
        <div class="step-indicator-item active">
            <span class="step-badge">02</span>
            <span>Data Pemesan</span>
        </div>
        <div class="step-indicator-item">
            <span class="step-badge">03</span>
            <span>Pembayaran</span>
        </div>
        <div class="step-indicator-item">
            <span class="step-badge">04</span>
            <span>Konfirmasi</span>
        </div>
    </div>
</div>

<section class="section-wrapper" style="padding-top: 0;">
    <div class="container">
        <form method="POST" action="{{ route('booking.store') }}">
            @csrf

            <div class="booking-layout-grid">
                <!-- Left: Form Fields -->
                <div class="booking-form-card">
                    <!-- Section 0: Pilihan Paket -->
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label class="form-label">Pilihan Paket Jelajah <span class="text-accent">*</span></label>
                        <select name="package_id" id="packageSelect" onchange="onPackageChange(this)" class="form-control" style="font-weight: 700; color: var(--primary);">
                            @if(isset($allPackages))
                                @foreach ($allPackages as $p)
                                    <option value="{{ $p->id }}"
                                            data-price="{{ $p->price }}"
                                            data-name="{{ $p->name }}"
                                            data-duration="{{ $p->duration }}"
                                            data-unit="{{ $p->price_unit ?? 'orang' }}"
                                            data-motor-pribadi="{{ $p->is_motor_pribadi ? '1' : '0' }}"
                                            {{ $package->id == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} (Rp{{ number_format($p->price, 0, ',', '.') }} / {{ $p->price_unit ?? 'orang' }})
                                    </option>
                                @endforeach
                            @else
                                <option value="{{ $package->id }}"
                                        data-price="{{ $package->price }}"
                                        data-name="{{ $package->name }}"
                                        data-duration="{{ $package->duration }}"
                                        data-unit="{{ $package->price_unit ?? 'orang' }}"
                                        data-motor-pribadi="{{ $package->is_motor_pribadi ? '1' : '0' }}"
                                        selected>
                                    {{ $package->name }} (Rp{{ number_format($package->price, 0, ',', '.') }})
                                </option>
                            @endif
                        </select>
                        <small style="display: block; color: var(--text-muted); margin-top: 6px;">
                            *Ingin memesan <strong>Private Trip</strong> untuk rombongan khusus? <a href="https://wa.me/{{ env('WHATSAPP_NUMBER', '6281210842032') }}?text=Halo%20Admin%20YKS,%20saya%20ingin%20konsultasi%20Private%20Trip" target="_blank" style="color: var(--accent); font-weight: 700;">Chat Admin via WhatsApp ↗</a>
                        </small>
                    </div>

                    <!-- Add-on Opsional Khusus Bawa Motor Pribadi (Figma ON GOING #202:123 & #249:169) -->
                    <div id="motorPribadiAddonBox" style="display: {{ $package->is_motor_pribadi ? 'block' : 'none' }}; background: #EAF0E7; border: 1px solid #C1D1BE; border-radius: 12px; padding: 18px 20px; margin-bottom: 24px;">
                        <div style="font-weight: 700; font-size: 0.95rem; color: #1F3D2B; margin-bottom: 4px;">
                            Add-on Opsional · Bawa Motor Pribadi
                        </div>
                        <p style="font-size: 0.8rem; color: #667066; margin-bottom: 12px;">
                            Harga dasar Rp200.000. Pilih perlengkapan dan pendampingan tambahan sesuai kebutuhan (total semua add-on Rp550.000):
                        </p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(175px, 1fr)); gap: 10px;">
                            <label style="display: flex; align-items: center; justify-content: space-between; gap: 8px; background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 8px; padding: 10px 12px; cursor: pointer; font-size: 0.84rem;">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="addons[]" value="sepatu" data-addon-price="100000" class="addon-checkbox" onchange="updateSummary()">
                                    <strong>Sepatu</strong>
                                </span>
                                <span style="color: #1F3D2B; font-weight: 600;">+Rp100.000</span>
                            </label>
                            <label style="display: flex; align-items: center; justify-content: space-between; gap: 8px; background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 8px; padding: 10px 12px; cursor: pointer; font-size: 0.84rem;">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="addons[]" value="guide" data-addon-price="200000" class="addon-checkbox" onchange="updateSummary()">
                                    <strong>Guide</strong>
                                </span>
                                <span style="color: #1F3D2B; font-weight: 600;">+Rp200.000</span>
                            </label>
                            <label style="display: flex; align-items: center; justify-content: space-between; gap: 8px; background: #FFFFFF; border: 1px solid #DEDED4; border-radius: 8px; padding: 10px 12px; cursor: pointer; font-size: 0.84rem;">
                                <span style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="addons[]" value="protector" data-addon-price="250000" class="addon-checkbox" onchange="updateSummary()">
                                    <strong>Paket protector</strong>
                                </span>
                                <span style="color: #1F3D2B; font-weight: 600;">+Rp250.000</span>
                            </label>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 28px;">

                    <!-- Section 1: Jadwal Jelajah -->
                    <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 18px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-calendar-check" style="color: var(--accent);"></i>
                        1. Pilih Tanggal, Sesi & Jumlah
                    </h3>

                    <div class="resp-grid-2" style="margin-bottom: 18px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Tanggal Perjalanan <span class="text-accent">*</span></label>
                            <input type="date" name="trip_date" class="form-control" required
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('trip_date', date('Y-m-d', strtotime('+1 day'))) }}">
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Sesi Waktu (WIB) <span class="text-accent">*</span></label>
                            <select name="start_time" class="form-control">
                                <option value="08.00–12.00">Pagi · 08.00–12.00 WIB</option>
                                <option value="13.00–16.00">Siang · 13.00–16.00 WIB</option>
                                <option value="15.00–18.00">Sore · 15.00–18.00 WIB</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 28px;">
                        <label class="form-label" id="countFieldLabel">Jumlah Peserta / Unit Jeep <span class="text-accent">*</span></label>
                        <div style="display: flex; gap: 8px; max-width: 260px;">
                            <button type="button" onclick="adjustCount(-1)" class="btn btn-outline" style="padding: 0 16px; font-weight: 800;">-</button>
                            <input type="number" id="participantCount" name="participant_count" class="form-control" style="text-align: center; font-weight: 800;" min="1" max="100"
                                   value="{{ old('participant_count', 1) }}">
                            <button type="button" onclick="adjustCount(1)" class="btn btn-outline" style="padding: 0 16px; font-weight: 800;">+</button>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 28px;">

                    <!-- Section 2: Data Pemesan -->
                    <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 18px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-person-circle" style="color: var(--accent);"></i>
                        2. Identitas Pemesan
                    </h3>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap Pemesan <span class="text-accent">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required
                               placeholder="Nama lengkap sesuai KTP"
                               value="{{ old('customer_name') }}">
                    </div>

                    <div class="resp-grid-2" style="margin-bottom: 20px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Nomor WhatsApp Aktif <span class="text-accent">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" required
                                   placeholder="08xxxxxxxxxx"
                                   value="{{ old('customer_phone') }}">
                            <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 4px;">Untuk koordinasi titik kumpul & konfirmasi perjalanan</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Alamat Email <span class="text-accent">*</span></label>
                            <input type="email" name="customer_email" class="form-control" required
                                   placeholder="email@domain.com"
                                   value="{{ old('customer_email') }}">
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 28px;">

                    <!-- Section 3: Pembayaran & Catatan -->
                    <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 18px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-credit-card" style="color: var(--accent);"></i>
                        3. Metode Pembayaran & Catatan
                    </h3>

                    <div class="form-group">
                        <label class="form-label">Tujuan Rekening Transfer</label>
                        <select name="payment_method" class="form-control">
                            <option value="Transfer Bank BCA" {{ old('payment_method') === 'Transfer Bank BCA' ? 'selected' : '' }}>Bank BCA (PT Yuk Keliling Sentul)</option>
                            <option value="Transfer Bank Mandiri" {{ old('payment_method') === 'Transfer Bank Mandiri' ? 'selected' : '' }}>Bank Mandiri (PT Yuk Keliling Sentul)</option>
                            <option value="QRIS Digital" {{ old('payment_method') === 'QRIS Digital' ? 'selected' : '' }}>QRIS Real-time (BCA, Mandiri, GoPay, OVO, Dana)</option>
                        </select>
                    </div>

                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Catatan Tambahan / Kebutuhan Khusus (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Rombongan 4 orang, mohon disiapkan sepatu ukuran 42.">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Right: Sticky Order Summary -->
                <div>
                    <div class="booking-summary-sidebar">
                        <span class="section-tag" style="margin-bottom: 6px;">━ &nbsp; RINGKASAN RESERVASI</span>
                        <h4 id="summaryPackageName" style="font-size: 1.3rem; color: var(--primary); margin-bottom: 16px;">
                            {{ $package->name }}
                        </h4>

                        <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; gap: 14px; margin-bottom: 20px;">
                            <span><i class="bi bi-clock"></i> <span id="summaryDuration">{{ $package->duration }}</span></span>
                            <span><i class="bi bi-geo-alt"></i> Sentul</span>
                        </div>

                        <div style="background-color: var(--bg-body); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span style="color: var(--text-muted);">Harga Paket</span>
                                <strong id="summaryBasePrice">Rp{{ number_format($package->price, 0, ',', '.') }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span style="color: var(--text-muted);">Jumlah</span>
                                <strong id="summaryCount">1 {{ $package->price_unit ?? 'orang' }}</strong>
                            </div>
                            <div id="summaryAddonRow" style="display: none; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span style="color: var(--text-muted);">Add-on Motor Pribadi</span>
                                <strong id="summaryAddonVal">Rp0</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 14px;">
                                <span style="color: var(--text-muted);">Biaya Admin</span>
                                <span style="color: #1F3D2B; font-weight: 700;">Rp0</span>
                            </div>
                            <hr style="border: none; border-top: 1px dashed var(--border); margin-bottom: 14px;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-weight: 800; color: var(--primary);">Total Reservasi:</span>
                                <span id="summaryTotal" style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 900; color: var(--accent);">
                                    Rp{{ number_format($package->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 14px;">
                            Lanjut ke Pembayaran &nbsp;↗
                        </button>

                        <p style="text-align: center; font-size: 0.8rem; color: var(--text-muted); line-height: 1.5;">
                            <i class="bi bi-shield-check" style="color: var(--primary);"></i> Jadwal baru dikunci setelah pembayaran diperiksa dan reservasi dikonfirmasi admin.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    let basePrice = {{ $package->price }};
    let priceUnit = "{{ $package->price_unit ?? 'orang' }}";
    let isMotorPribadi = {{ $package->is_motor_pribadi ? 'true' : 'false' }};

    const inputCount = document.getElementById('participantCount');
    const summaryCount = document.getElementById('summaryCount');
    const summaryBasePrice = document.getElementById('summaryBasePrice');
    const summaryTotal = document.getElementById('summaryTotal');
    const summaryPackageName = document.getElementById('summaryPackageName');
    const summaryDuration = document.getElementById('summaryDuration');
    const addonBox = document.getElementById('motorPribadiAddonBox');
    const summaryAddonRow = document.getElementById('summaryAddonRow');
    const summaryAddonVal = document.getElementById('summaryAddonVal');

    function onPackageChange(sel) {
        const opt = sel.options[sel.selectedIndex];
        basePrice = parseInt(opt.getAttribute('data-price')) || 0;
        priceUnit = opt.getAttribute('data-unit') || 'orang';
        isMotorPribadi = opt.getAttribute('data-motor-pribadi') === '1';

        if (summaryPackageName) summaryPackageName.textContent = opt.getAttribute('data-name');
        if (summaryDuration) summaryDuration.textContent = opt.getAttribute('data-duration');
        if (summaryBasePrice) summaryBasePrice.textContent = 'Rp' + basePrice.toLocaleString('id-ID');

        if (addonBox) {
            addonBox.style.display = isMotorPribadi ? 'block' : 'none';
            if (!isMotorPribadi) {
                document.querySelectorAll('.addon-checkbox').forEach(cb => cb.checked = false);
            }
        }
        updateSummary();
    }

    function adjustCount(delta) {
        let val = parseInt(inputCount.value) || 1;
        val += delta;
        if (val < 1) val = 1;
        if (val > 100) val = 100;
        inputCount.value = val;
        updateSummary();
    }

    inputCount.addEventListener('input', updateSummary);

    function updateSummary() {
        let val = parseInt(inputCount.value) || 1;
        if (val < 1) val = 1;
        summaryCount.textContent = val + ' ' + priceUnit;

        let addonPerPerson = 0;
        if (isMotorPribadi) {
            document.querySelectorAll('.addon-checkbox:checked').forEach(cb => {
                addonPerPerson += parseInt(cb.getAttribute('data-addon-price')) || 0;
            });
        }

        let totalAddon = addonPerPerson * val;
        if (summaryAddonRow && summaryAddonVal) {
            summaryAddonRow.style.display = totalAddon > 0 ? 'flex' : 'none';
            summaryAddonVal.textContent = 'Rp' + totalAddon.toLocaleString('id-ID');
        }

        let total = (val * basePrice) + totalAddon;
        summaryTotal.textContent = 'Rp' + total.toLocaleString('id-ID');
    }
</script>
@endsection
