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
        ━ &nbsp; RESERVASI · DATA PEMESAN & JADWAL
    </div>
    <h1 class="section-title" style="font-size: 2.4rem; margin-bottom: 24px;">
        Pilih Jadwal & Lengkapi Data
    </h1>

    <!-- 4-Step Progress Navigation (Figma Pattern) -->
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
        <form method="POST" action="{{ route('booking.store') }}" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn){ btn.disabled = true; btn.innerHTML = '<i class=\'bi bi-hourglass-split\'></i> Memproses Reservasi...'; }">
            @csrf

            <div class="booking-layout-grid">
                <!-- Left: Form Fields -->
                <div class="booking-form-card">
                    <!-- Section 0: Pilihan Paket & Jenis Perjalanan (SDD Proses 2) -->
                    <div class="resp-grid-2" style="margin-bottom: 24px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Pilihan Paket Jelajah <span class="text-accent">*</span></label>
                            <select name="package_id" id="packageSelect" onchange="onPackageChange(this)" class="form-control" style="font-weight: 700; color: var(--primary);">
                                @if(isset($allPackages))
                                    @foreach ($allPackages as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-name="{{ $p->name }}" data-duration="{{ $p->duration }}" {{ $package->id == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="{{ $package->id }}" data-price="{{ $package->price }}" data-name="{{ $package->name }}" data-duration="{{ $package->duration }}" selected>
                                        {{ $package->name }} (Rp {{ number_format($package->price, 0, ',', '.') }})
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Jenis Perjalanan (Trip) <span class="text-accent">*</span></label>
                            <select name="jenis_trip" class="form-control" style="font-weight: 600; color: var(--primary);">
                                <option value="private" {{ old('jenis_trip') === 'private' ? 'selected' : '' }}>Private Trip (Eksklusif Rombongan Sendiri)</option>
                                <option value="open" {{ old('jenis_trip') === 'open' ? 'selected' : '' }}>Open Trip (Gabungan Jadwal Tersedia)</option>
                            </select>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 28px;">

                    <!-- Section 1: Jadwal Jelajah & Unit -->
                    <h3 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 14px; display: flex; align-items: center; gap: 10px;">
                        <i class="bi bi-calendar-check" style="color: var(--accent);"></i>
                        1. Pilih Tanggal (Khusus Sabtu & Minggu), Peserta & Armada Unit
                    </h3>

                    <!-- Kalender Visual Weekend-Only (SDD UI-08) -->
                    <div class="form-group" style="margin-bottom: 22px;">
                        <label class="form-label">Pilih Tanggal Keberangkatan <span class="text-accent">*</span> <small style="font-weight: 500; color: var(--text-muted);">(Operasional wisata hanya tersedia setiap hari Sabtu & Minggu)</small></label>
                        <input type="hidden" name="trip_date" id="tripDateInput" value="{{ old('trip_date', $defaultTripDate) }}" required>

                        <div class="yks-calendar-box" id="bookingCalendarContainer">
                            <div class="yks-cal-header">
                                <button type="button" class="yks-cal-nav-btn" onclick="changeBookingMonth(-1)" aria-label="Bulan Sebelumnya">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span class="yks-cal-month-label" id="bookingCalMonthLabel">November 2026</span>
                                <button type="button" class="yks-cal-nav-btn" onclick="changeBookingMonth(1)" aria-label="Bulan Berikutnya">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>

                            <div class="yks-cal-legend">
                                <span><strong style="color: #1F3D2B;">●</strong> Tersedia (Sab/Min)</span>
                                <span><strong style="color: #D97706;">—</strong> Hampir penuh</span>
                                <span><strong style="color: #DC2626;">×</strong> Penuh / Ditutup</span>
                                <span style="color: #9CA3AF;">■ Sen–Jum tutup</span>
                            </div>

                            <div class="yks-cal-weekdays">
                                <div>Sen</div><div>Sel</div><div>Rab</div><div>Kam</div><div>Jum</div><div style="color: var(--primary);">Sab</div><div style="color: var(--primary);">Min</div>
                            </div>

                            <div class="yks-cal-grid" id="bookingCalGrid"></div>

                            <div class="yks-cal-selected-bar">
                                <i class="bi bi-calendar2-check-fill" style="color: var(--accent);"></i>
                                <span id="bookingSelectedText">Dipilih: -</span>
                            </div>
                        </div>
                        @error('trip_date')
                            <small style="color: #DC2626; font-size: 0.8rem; display: block; margin-top: 6px;">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom: 22px; max-width: 300px;">
                        <label class="form-label">Jumlah Peserta / Unit <span class="text-accent">*</span></label>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" onclick="adjustCount(-1)" class="btn btn-outline" style="padding: 0 16px; font-weight: 800;">-</button>
                            <input type="number" id="participantCount" name="participant_count" class="form-control" style="text-align: center; font-weight: 800;" min="1" max="100" 
                                   value="{{ old('participant_count', 1) }}">
                            <button type="button" onclick="adjustCount(1)" class="btn btn-outline" style="padding: 0 16px; font-weight: 800;">+</button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label style="display: inline-flex; align-items: center; gap: 10px; cursor: pointer; font-size: 0.9rem; color: var(--primary); font-weight: 600;">
                            <input type="checkbox" name="motor_pribadi" value="1" {{ old('motor_pribadi') ? 'checked' : '' }} style="accent-color: var(--primary); width: 17px; height: 17px;">
                            <span>Saya membawa unit motor trail pribadi (Opsi Trabas Motor Pribadi)</span>
                        </label>
                    </div>

                    <!-- Section 1B: Layanan Tambahan / Add-On (SDD FR-05 & Bab 6) -->
                    @if(isset($addons) && $addons->isNotEmpty())
                        <hr style="border: none; border-top: 1px solid var(--border-light); margin-bottom: 24px;">
                        <h3 style="font-size: 1.25rem; color: var(--primary); margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
                            <i class="bi bi-plus-circle-fill" style="color: var(--accent);"></i>
                            Layanan Tambahan (Add-On)
                        </h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">
                            Pilih perlengkapan atau pemandu tambahan jika diperlukan (otomatis dihitung ke total pembayaran):
                        </p>
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 28px;">
                            @foreach($addons as $addon)
                                <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px 16px; border: 1px solid var(--border); border-radius: var(--radius-md); background: #FFFFFF; flex-wrap: wrap;">
                                    <div>
                                        <strong style="color: var(--primary); font-size: 0.94rem; display: block;">{{ $addon->nama_addon }}</strong>
                                        <span style="color: var(--accent); font-weight: 700; font-size: 0.85rem;">Rp {{ number_format($addon->harga, 0, ',', '.') }} / item</span>
                                        @if($addon->keterangan)
                                            <small style="color: var(--text-muted); display: block; font-size: 0.78rem;">{{ $addon->keterangan }}</small>
                                        @endif
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span style="font-size: 0.78rem; color: var(--text-muted);">Jumlah:</span>
                                        <input type="number" name="addons[{{ $addon->id }}]"
                                               class="form-control addon-qty-input"
                                               data-addon-price="{{ $addon->harga }}"
                                               data-addon-name="{{ $addon->nama_addon }}"
                                               min="0" max="50" value="{{ old('addons.' . $addon->id, 0) }}"
                                               onchange="updateSummary()" oninput="updateSummary()"
                                               style="width: 76px; text-align: center; font-weight: 700; padding: 6px 8px;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

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
                               value="{{ old('customer_name', $user->name ?? '') }}">
                    </div>

                    <div class="resp-grid-2" style="margin-bottom: 20px;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Nomor WhatsApp Aktif <span class="text-accent">*</span></label>
                            <input type="tel" name="customer_phone" class="form-control" required 
                                   placeholder="08xxxxxxxxxx" 
                                   value="{{ old('customer_phone') }}">
                            <small style="color: var(--text-muted); font-size: 0.78rem; display: block; margin-top: 4px;">Untuk koordinasi titik kumpul & update cuaca</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Alamat Email <span class="text-accent">*</span></label>
                            <input type="email" name="customer_email" class="form-control" required 
                                   placeholder="email@domain.com" 
                                   value="{{ old('customer_email', $user->email ?? '') }}">
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
                        <label class="form-label">Catatan Tambahan / Permintaan Rombongan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Rombongan kantor 8 orang, mohon disiapkan 2 unit jip berdekatan, ada anak 8 tahun.">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Right: Sticky Order Summary -->
                <div>
                    <div class="booking-summary-sidebar">
                        <span class="section-tag" style="margin-bottom: 6px;">━ &nbsp; RINGKASAN TRIP</span>
                        <h4 style="font-size: 1.3rem; color: var(--primary); margin-bottom: 16px;">
                            {{ $package->name }}
                        </h4>

                        <div style="font-size: 0.85rem; color: var(--text-muted); display: flex; gap: 14px; margin-bottom: 20px;">
                            <span><i class="bi bi-clock"></i> {{ $package->duration }}</span>
                            <span><i class="bi bi-geo-alt"></i> Sentul</span>
                        </div>

                        <div style="background-color: var(--bg-body); border-radius: var(--radius-md); padding: 20px; margin-bottom: 24px;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span style="color: var(--text-muted);">Biaya Paket / pax</span>
                                <strong>Rp {{ number_format($package->price, 0, ',', '.') }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span style="color: var(--text-muted);">Jumlah Peserta</span>
                                <strong id="summaryCount">1 orang</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span style="color: var(--text-muted);">Subtotal Paket</span>
                                <strong id="summarySubtotalPackage">Rp {{ number_format($package->price, 0, ',', '.') }}</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 8px;">
                                <span style="color: var(--text-muted);">Subtotal Add-On</span>
                                <strong id="summarySubtotalAddons">Rp 0</strong>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem; margin-bottom: 14px;">
                                <span style="color: var(--text-muted);">Pemandu & Retribusi</span>
                                <span style="color: #10b981; font-weight: 700;">Termasuk</span>
                            </div>
                            <hr style="border: none; border-top: 1px dashed var(--border); margin-bottom: 14px;">
                            <div style="display: flex; justify-content: space-between; align-items: baseline;">
                                <span style="font-weight: 800; color: var(--primary);">Total Tagihan:</span>
                                <span id="summaryTotal" style="font-family: var(--font-heading); font-size: 1.5rem; font-weight: 900; color: var(--accent);">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-bottom: 14px;">
                            Lanjut ke Pembayaran &nbsp;↗
                        </button>

                        <p style="text-align: center; font-size: 0.8rem; color: var(--text-muted); line-height: 1.5;">
                            <i class="bi bi-shield-check" style="color: var(--primary);"></i> Pesanan Anda terjamin resmi. Notifikasi status akan dikirim otomatis.
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
    const inputCount = document.getElementById('participantCount');
    const summaryCount = document.getElementById('summaryCount');
    const summaryTotal = document.getElementById('summaryTotal');
    const packageSelect = document.getElementById('packageSelect');

    function onPackageChange(sel) {
        const opt = sel.options[sel.selectedIndex];
        basePrice = parseInt(opt.getAttribute('data-price')) || basePrice;
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
        summaryCount.textContent = val + ' orang';
        let subtotalPkg = val * basePrice;
        let subtotalAddon = 0;
        document.querySelectorAll('.addon-qty-input').forEach(function(inp) {
            let q = parseInt(inp.value) || 0;
            let p = parseInt(inp.getAttribute('data-addon-price')) || 0;
            if (q > 0) {
                subtotalAddon += (q * p);
            }
        });
        let total = subtotalPkg + subtotalAddon;
        const elSubPkg = document.getElementById('summarySubtotalPackage');
        const elSubAdd = document.getElementById('summarySubtotalAddons');
        if (elSubPkg) elSubPkg.textContent = 'Rp ' + subtotalPkg.toLocaleString('id-ID');
        if (elSubAdd) elSubAdd.textContent = 'Rp ' + subtotalAddon.toLocaleString('id-ID');
        summaryTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    }

    // === Weekend-Only Interactive Calendar (SDD UI-08) ===
    const calScheduleData = @json($scheduleData ?? ['daily_capacity' => 12, 'blocked_dates' => [], 'booked_counts' => []]);
    const calMinDate = "{{ date('Y-m-d') }}";
    const tripDateInput = document.getElementById('tripDateInput');
    const bookingCalGrid = document.getElementById('bookingCalGrid');
    const bookingCalMonthLabel = document.getElementById('bookingCalMonthLabel');
    const bookingSelectedText = document.getElementById('bookingSelectedText');

    const monthNamesID = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    const dayNamesID = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    let selectedDateStr = tripDateInput ? tripDateInput.value : "{{ $defaultTripDate }}";
    let initParts = selectedDateStr.split('-');
    let calCurrentYear = parseInt(initParts[0]) || {{ date('Y') }};
    let calCurrentMonth = (parseInt(initParts[1]) || {{ date('n') }}) - 1;

    function formatDateIndo(dateStr) {
        if (!dateStr) return '-';
        const [y, m, d] = dateStr.split('-').map(Number);
        const dt = new Date(y, m - 1, d);
        return `${dayNamesID[dt.getDay()]}, ${d} ${monthNamesID[m - 1]} ${y}`;
    }

    function changeBookingMonth(delta) {
        calCurrentMonth += delta;
        if (calCurrentMonth < 0) {
            calCurrentMonth = 11;
            calCurrentYear--;
        } else if (calCurrentMonth > 11) {
            calCurrentMonth = 0;
            calCurrentYear++;
        }
        renderBookingCalendar();
    }

    function selectBookingDate(dateStr) {
        selectedDateStr = dateStr;
        if (tripDateInput) tripDateInput.value = dateStr;
        if (bookingSelectedText) bookingSelectedText.textContent = 'Dipilih: ' + formatDateIndo(dateStr);
        renderBookingCalendar();
    }

    function renderBookingCalendar() {
        if (!bookingCalGrid) return;
        bookingCalMonthLabel.textContent = `${monthNamesID[calCurrentMonth]} ${calCurrentYear}`;
        bookingCalGrid.innerHTML = '';

        const firstDay = new Date(calCurrentYear, calCurrentMonth, 1);
        const daysInMonth = new Date(calCurrentYear, calCurrentMonth + 1, 0).getDate();
        // Convert JS Sunday=0..Saturday=6 to Monday=0..Sunday=6
        const startOffset = (firstDay.getDay() + 6) % 7;

        for (let i = 0; i < startOffset; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'yks-cal-day empty';
            bookingCalGrid.appendChild(emptyCell);
        }

        const dailyCap = calScheduleData.daily_capacity || 12;
        const blocked = calScheduleData.blocked_dates || {};
        const booked = calScheduleData.booked_counts || {};

        for (let day = 1; day <= daysInMonth; day++) {
            const mStr = String(calCurrentMonth + 1).padStart(2, '0');
            const dStr = String(day).padStart(2, '0');
            const dateStr = `${calCurrentYear}-${mStr}-${dStr}`;
            const dt = new Date(calCurrentYear, calCurrentMonth, day);
            const jsDay = dt.getDay(); // 0=Sun, 6=Sat
            const isWeekend = (jsDay === 0 || jsDay === 6);

            const btn = document.createElement('button');
            btn.type = 'button';

            if (!isWeekend) {
                btn.className = 'yks-cal-day weekday-closed';
                btn.disabled = true;
                btn.title = 'Tutup (Reservasi hanya hari Sabtu & Minggu)';
                btn.innerHTML = `<span>${day}</span><span class="yks-cal-dot">-</span>`;
            } else if (dateStr < calMinDate) {
                btn.className = 'yks-cal-day past-closed';
                btn.disabled = true;
                btn.title = 'Tanggal sudah lewat';
                btn.innerHTML = `<span>${day}</span><span class="yks-cal-dot"></span>`;
            } else if (blocked[dateStr] || (booked[dateStr] || 0) >= dailyCap) {
                btn.className = 'yks-cal-day full-closed';
                btn.disabled = true;
                btn.title = blocked[dateStr] ? `Ditutup: ${blocked[dateStr]}` : 'Kuota jadwal penuh';
                btn.innerHTML = `<span>${day}</span><span class="yks-cal-dot">×</span>`;
            } else {
                const used = booked[dateStr] || 0;
                const isAlmostFull = (dailyCap - used) <= 3;
                btn.className = 'yks-cal-day ' + (isAlmostFull ? 'almost-full' : 'available');
                if (dateStr === selectedDateStr) {
                    btn.classList.add('selected');
                }
                const dot = dateStr === selectedDateStr ? '✓' : (isAlmostFull ? '—' : '•');
                btn.title = `Tersedia (${dailyCap - used} slot tersisa)`;
                btn.innerHTML = `<span>${day}</span><span class="yks-cal-dot">${dot}</span>`;
                btn.onclick = () => selectBookingDate(dateStr);
            }

            bookingCalGrid.appendChild(btn);
        }

        if (bookingSelectedText) {
            bookingSelectedText.textContent = 'Dipilih: ' + formatDateIndo(selectedDateStr);
        }
    }

    document.addEventListener('DOMContentLoaded', renderBookingCalendar);
</script>
@endsection
