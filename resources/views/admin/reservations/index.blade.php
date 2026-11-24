@extends('layouts.admin', ['title' => 'Reservasi', 'breadcrumb' => 'Reservasi'])

@section('content')
<style>
    .res-bottom-grid {
        display: grid;
        grid-template-columns: 1fr 430px;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1150px) {
        .res-bottom-grid {
            grid-template-columns: 1fr;
        }
    }
    .panel-box {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 24px;
    }
    .filter-ctrl {
        height: 42px;
        padding: 0 12px;
        border: 1px solid #E1E4DA;
        border-radius: 8px;
        background: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        color: #24382D;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .res-table-row {
        display: grid;
        grid-template-columns: 175px 220px 110px 130px 190px 1fr;
        gap: 12px;
        align-items: center;
        padding: 12px;
        min-height: 67px;
        border-bottom: 1px solid #E1E4DA;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s ease;
    }
    .res-table-row:hover {
        background: #F9FAF7;
    }
    .res-table-row.selected {
        background: #EAF0E7;
    }
    .kv-line {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        font-size: 13px;
        line-height: 1.5;
    }
    .kv-line span:first-child {
        color: #718074;
    }
    .kv-line span:last-child {
        color: #24382D;
        font-weight: 500;
        text-align: right;
    }
    .pill-badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }
    .pill-orange { background: #FFF0E4; color: #A54B10; }
    .pill-green { background: #EAF0E7; color: #1F3D2B; }
    .pill-red { background: #FBEDEB; color: #AD4940; }
    .pill-neutral { background: #F4F6F1; color: #1F3D2B; }
</style>

<!-- Identitas Halaman (Figma Frame 178:19537) -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px; margin-bottom: 6px;">Reservasi</h1>
        <p style="font-size: 13px; color: #718074;">Kelola perjalanan pelanggan dengan status reservasi dan pembayaran yang terpisah.</p>
    </div>

    <a href="{{ route('admin.reservations.create') }}"
       style="background: #F26B1D; color: #1F3D2B; height: 42px; padding: 0 18px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
        <span style="font-size: 16px; font-weight: 700;">+</span> Tambah Reservasi
    </a>
</div>

<!-- Filter Reservasi (Figma Frame 178:19544) -->
<form action="{{ route('admin.reservations.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px;">
    <div style="position: relative;">
        <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #718074; font-size: 13px;"></i>
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nomor / nama..."
               class="filter-ctrl" style="padding-left: 34px; width: 210px;">
    </div>

    <div class="filter-ctrl" style="color: #24382D;">
        <i class="bi bi-calendar3" style="color: #718074;"></i>
        <span>Dibuat: 1–9 Nov 2026</span>
    </div>

    <select name="package_id" onchange="this.form.submit()" class="filter-ctrl" style="cursor: pointer;">
        <option value="">Semua paket</option>
        @foreach($packages as $pkg)
            <option value="{{ $pkg->id }}" {{ (string) $packageFilter === (string) $pkg->id ? 'selected' : '' }}>
                {{ $pkg->name }}
            </option>
        @endforeach
    </select>

    <select name="reservation_status" onchange="this.form.submit()" class="filter-ctrl" style="cursor: pointer;">
        <option value="">Status reservasi</option>
        @foreach(['Menunggu pembayaran', 'Menunggu verifikasi', 'Terkonfirmasi', 'Selesai', 'Dibatalkan'] as $rs)
            <option value="{{ $rs }}" {{ $resStatusFilter === $rs ? 'selected' : '' }}>{{ $rs }}</option>
        @endforeach
    </select>

    <select name="payment_status" onchange="this.form.submit()" class="filter-ctrl" style="cursor: pointer;">
        <option value="">Status pembayaran</option>
        @foreach(['Menunggu Verifikasi' => 'Menunggu pemeriksaan', 'Lunas' => 'Diterima', 'Ditolak' => 'Ditolak', 'Menunggu Pembayaran' => 'Belum bayar'] as $val => $lbl)
            <option value="{{ $val }}" {{ $payStatusFilter === $val ? 'selected' : '' }}>{{ $lbl }}</option>
        @endforeach
    </select>

    @if($search || $packageFilter || $resStatusFilter || $payStatusFilter)
        <a href="{{ route('admin.reservations.index') }}" class="filter-ctrl" style="text-decoration: none; color: #718074;">
            Reset
        </a>
    @endif
</form>

<!-- Panel Tabel Daftar Reservasi (Figma Frame 178:19560) -->
<div class="panel-box" style="margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap;">
        <div>
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                Daftar reservasi
            </h3>
            <p style="font-size: 12px; color: #718074;">
                {{ $reservations->total() }} reservasi · Pilih baris untuk melihat detail
            </p>
        </div>

        <span style="background: #F4F6F1; color: #1F3D2B; font-size: 11px; font-weight: 600; padding: 5px 11px; border-radius: 100px;">
            {{ $counts['terkonfirmasi'] }} terkonfirmasi · {{ $counts['selesai'] }} selesai · {{ $counts['menunggu'] }} menunggu
        </span>
    </div>

    <div style="overflow-x: auto;">
        <div style="min-width: 920px;">
            <!-- Kepala Tabel -->
            <div style="display: grid; grid-template-columns: 175px 220px 110px 130px 190px 1fr; gap: 12px; background: #F4F6F1; padding: 12px; border-radius: 6px; font-size: 11px; font-weight: 600; color: #718074;">
                <div>No. reservasi / pemesan</div>
                <div>Paket &amp; jadwal WIB</div>
                <div>Peserta / jeep</div>
                <div>Total</div>
                <div>Pembayaran</div>
                <div>Reservasi</div>
            </div>

            <!-- Baris Data -->
            @forelse($reservations as $r)
                @php
                    $isSelected = ($selectedBooking && $selectedBooking->booking_code === $r->booking_code);
                    $pkgName = $r->custom_package_name ?: ($r->catalog_package_name ?? 'Jelajah Jeep');
                    $timeSlot = $r->start_time ?: '08.00–12.00';
                    $unitCount = $r->vehicle_count ?: 1;

                    // Map Payment Status Label & Style sesuai Figma ON GOING
                    $payLabel = $r->payment_status;
                    $payClass = 'pill-neutral';
                    if (in_array($r->payment_status, ['Menunggu Verifikasi', 'Menunggu pemeriksaan'])) {
                        $payLabel = 'Menunggu pemeriksaan';
                        $payClass = 'pill-orange';
                    } elseif (in_array($r->payment_status, ['Lunas', 'Diterima'])) {
                        $payLabel = 'Diterima';
                        $payClass = 'pill-green';
                    } elseif ($r->payment_status === 'Ditolak') {
                        $payLabel = 'Ditolak';
                        $payClass = 'pill-red';
                    } elseif (in_array($r->payment_status, ['Menunggu Pembayaran', 'Belum bayar'])) {
                        $payLabel = 'Belum bayar';
                        $payClass = 'pill-neutral';
                    }

                    // Map Reservation Status Label & Style
                    $resLabel = $r->reservation_status ?: 'Menunggu verifikasi';
                    $resClass = 'pill-neutral';
                    if (in_array($resLabel, ['Terkonfirmasi', 'Selesai'])) {
                        $resClass = 'pill-green';
                    } elseif ($resLabel === 'Dibatalkan') {
                        $resClass = 'pill-red';
                    }
                @endphp
                <a href="{{ route('admin.reservations.index', array_merge(request()->query(), ['select' => $r->booking_code])) }}#detail-panel"
                   class="res-table-row {{ $isSelected ? 'selected' : '' }}">
                    <div>
                        <div style="font-size: 12px; font-weight: 600; color: #24382D;">{{ $r->booking_code }}</div>
                        <div style="font-size: 11px; color: #718074; margin-top: 2px;">
                            {{ $r->customer_name }}
                            @if(($r->booking_source ?? 'Website') === 'Non-Website')
                                <span style="font-size: 10px; color: #A54B10;">({{ $r->booking_channel ?? 'WA' }})</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 12px; color: #24382D;">{{ $pkgName }}</div>
                        <div style="font-size: 10px; color: #718074; margin-top: 2px;">
                            {{ \Carbon\Carbon::parse($r->trip_date)->translatedFormat('d M Y') }} · {{ $timeSlot }}
                        </div>
                    </div>

                    <div style="font-size: 12px; color: #24382D;">
                        {{ $r->participant_count }} peserta / {{ $unitCount }}
                    </div>

                    <div style="font-size: 12px; color: #24382D; font-weight: 500;">
                        Rp{{ number_format($r->total_price, 0, ',', '.') }}
                    </div>

                    <div>
                        <span class="pill-badge {{ $payClass }}">{{ $payLabel }}</span>
                    </div>

                    <div>
                        <span class="pill-badge {{ $resClass }}">{{ $resLabel }}</span>
                    </div>
                </a>
            @empty
                <div style="padding: 36px; text-align: center; color: #718074; font-size: 13px;">
                    Tidak ada data reservasi yang cocok dengan filter.
                </div>
            @endforelse
        </div>
    </div>

    <div style="margin-top: 16px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <span style="font-size: 11px; color: #718074;">
            {{ $reservations->total() }} reservasi · Nama paket dan tarif lama mengikuti saat reservasi dibuat.
        </span>
        {{ $reservations->links() }}
    </div>
</div>

<!-- Bagian Bawah 2 Kolom: Detail Reservasi & Perbarui Status Reservasi (Figma Frame 178:19728) -->
@if($selectedBooking)
    @php
        $selPkgName = $selectedBooking->custom_package_name ?: ($selectedBooking->catalog_package_name ?? 'Jelajah Jeep');
        $selTimeSlot = $selectedBooking->start_time ?: '08.00–12.00';
        $selUnitCount = $selectedBooking->vehicle_count ?: 1;
        $selBasePrice = $selectedBooking->package_agreed_price ?: ($selectedBooking->catalog_package_price ?: $selectedBooking->total_price);
        $selAddonPrice = $selectedBooking->addon_price ?? 0;
        $selAddonText = $selectedBooking->addon_notes ?: ($selAddonPrice > 0 ? 'Add-on · Rp' . number_format($selAddonPrice, 0, ',', '.') : 'Tidak dipilih · Rp0');

        $selPayLabel = $selectedBooking->payment_status;
        $selPayClass = 'pill-neutral';
        if (in_array($selectedBooking->payment_status, ['Menunggu Verifikasi', 'Menunggu pemeriksaan'])) {
            $selPayLabel = 'Menunggu pemeriksaan';
            $selPayClass = 'pill-orange';
        } elseif (in_array($selectedBooking->payment_status, ['Lunas', 'Diterima'])) {
            $selPayLabel = 'Diterima';
            $selPayClass = 'pill-green';
        } elseif ($selectedBooking->payment_status === 'Ditolak') {
            $selPayLabel = 'Ditolak';
            $selPayClass = 'pill-red';
        } else {
            $selPayLabel = 'Belum bayar';
            $selPayClass = 'pill-neutral';
        }

        $selResLabel = $selectedBooking->reservation_status ?: 'Menunggu verifikasi';
        $selResClass = in_array($selResLabel, ['Terkonfirmasi', 'Selesai']) ? 'pill-green' : ($selResLabel === 'Dibatalkan' ? 'pill-red' : 'pill-orange');
    @endphp

    <div class="res-bottom-grid" id="detail-panel">
        <!-- KOLOM KIRI: Detail YKS-XXXXX (Figma Frame 178:19729) -->
        <div class="panel-box" style="display: flex; flex-direction: column; gap: 18px;">
            <div>
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                    Detail {{ $selectedBooking->booking_code }}
                </h3>
                <p style="font-size: 12px; color: #718074;">
                    Dibuat {{ \Carbon\Carbon::parse($selectedBooking->created_at)->translatedFormat('d F Y, H.i') }} WIB · {{ $selectedBooking->customer_name }}
                    @if(($selectedBooking->booking_source ?? 'Website') === 'Non-Website')
                        · Kanal {{ $selectedBooking->booking_channel ?? 'WhatsApp' }}
                    @endif
                </p>
            </div>

            <!-- Status Terpisah: RESERVASI & PEMBAYARAN -->
            <div style="display: flex; gap: 24px; flex-wrap: wrap;">
                <div>
                    <div style="font-size: 11px; color: #718074; margin-bottom: 6px;">RESERVASI</div>
                    <span class="pill-badge {{ $selResClass }}">{{ $selResLabel }}</span>
                </div>
                <div>
                    <div style="font-size: 11px; color: #718074; margin-bottom: 6px;">PEMBAYARAN</div>
                    <span class="pill-badge {{ $selPayClass }}">{{ $selPayLabel }}</span>
                </div>
            </div>

            <div style="height: 1px; background: #E1E4DA;"></div>

            <!-- Rincian Data -->
            <div class="kv-line">
                <span>Paket</span>
                <span>{{ $selPkgName }}</span>
            </div>
            <div class="kv-line">
                <span>Durasi / kapasitas</span>
                <span>{{ $selectedBooking->package_duration ?? '4 jam' }} · Maks. 4 peserta/jeep</span>
            </div>
            <div class="kv-line">
                <span>Jadwal perjalanan</span>
                <span>{{ \Carbon\Carbon::parse($selectedBooking->trip_date)->translatedFormat('l, d F Y') }}</span>
            </div>
            <div class="kv-line">
                <span>Waktu</span>
                <span>{{ $selTimeSlot }} WIB</span>
            </div>
            <div class="kv-line">
                <span>Peserta / jumlah jeep</span>
                <span>{{ $selectedBooking->participant_count }} peserta / {{ $selUnitCount }} jeep</span>
            </div>
            <div class="kv-line">
                <span>Pelanggan</span>
                <span>{{ $selectedBooking->customer_name }} · {{ $selectedBooking->customer_email }}</span>
            </div>
            @if(!empty($selectedBooking->group_name))
                <div class="kv-line">
                    <span>Rombongan</span>
                    <span>{{ $selectedBooking->group_name }}</span>
                </div>
            @endif
            <div class="kv-line">
                <span>Harga paket</span>
                <span>1 × Rp{{ number_format($selBasePrice, 0, ',', '.') }}</span>
            </div>
            <div class="kv-line">
                <span>Add-on</span>
                <span>{{ $selAddonText }}</span>
            </div>
            <div class="kv-line">
                <span>Biaya admin</span>
                <span>Rp0</span>
            </div>

            <div style="height: 1px; background: #E1E4DA;"></div>

            <div class="kv-line" style="font-weight: 700;">
                <span style="color: #718074; font-weight: 400;">Total reservasi</span>
                <span style="font-size: 14px; font-weight: 700; color: #24382D;">Rp{{ number_format($selectedBooking->total_price, 0, ',', '.') }}</span>
            </div>

            <!-- Aksi Pembayaran (Figma Frame 178:19772) -->
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; padding-top: 4px; flex-wrap: wrap;">
                <span style="font-size: 12px; color: #718074;">
                    Bukti diterima {{ \Carbon\Carbon::parse($selectedBooking->updated_at)->translatedFormat('d M, H.i') }} WIB
                </span>

                <a href="{{ route('admin.verifications.show', $selectedBooking->booking_code) }}"
                   style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-file-earmark-check"></i> Periksa bukti transfer
                </a>
            </div>
        </div>

        <!-- KOLOM KANAN: Perbarui Status Reservasi & Riwayat Status (Figma Frame 178:19777) -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- Panel 1: Perbarui Status Reservasi -->
            <form action="{{ route('admin.reservations.update_status', $selectedBooking->booking_code) }}" method="POST" class="panel-box" style="display: flex; flex-direction: column; gap: 18px;">
                @csrf
                @method('PATCH')

                <div>
                    <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                        Perbarui status reservasi
                    </h3>
                    <p style="font-size: 12px; color: #718074;">Perubahan dicatat dalam riwayat status</p>
                </div>

                <div style="display: flex; flex-direction: column; gap: 7px;">
                    <label style="font-size: 12px; font-weight: 600; color: #24382D;">Status baru *</label>
                    <select name="reservation_status" required
                            style="height: 44px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 13px; color: #24382D;">
                        @foreach(['Menunggu pembayaran', 'Menunggu verifikasi', 'Terkonfirmasi', 'Selesai', 'Dibatalkan'] as $st)
                            <option value="{{ $st }}" {{ $selResLabel === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>

                <p style="font-size: 11px; color: #718074; line-height: 1.5;">
                    Alur: Menunggu pembayaran → Menunggu verifikasi → Terkonfirmasi → Selesai. Pembatalan memerlukan alasan.
                </p>

                <div style="display: flex; flex-direction: column; gap: 7px;">
                    <label style="font-size: 12px; font-weight: 600; color: #24382D;">Catatan perubahan</label>
                    <textarea name="admin_notes" rows="3" placeholder="Tulis alasan atau catatan operasional..."
                              style="min-height: 90px; padding: 12px; border: 1px solid #E1E4DA; border-radius: 8px; font-family: inherit; font-size: 13px; color: #24382D;">{{ $selectedBooking->admin_notes }}</textarea>
                </div>

                <div style="background: #EAF0E7; border-radius: 8px; padding: 14px; display: flex; gap: 10px; align-items: flex-start;">
                    <i class="bi bi-info-circle" style="color: #1F3D2B; font-size: 15px;"></i>
                    <span style="font-size: 12px; color: #1F3D2B; line-height: 1.5;">
                        Status Terkonfirmasi hanya tersedia setelah pembayaran diterima. Mengubah status reservasi tidak mengesahkan pembayaran.
                    </span>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit"
                            style="height: 42px; padding: 0 16px; border-radius: 8px; border: none; background: #F26B1D; color: #1F3D2B; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="bi bi-floppy"></i> Simpan status
                    </button>
                </div>
            </form>

            <!-- Panel 2: Riwayat Status (Figma Frame 178:19800) -->
            <div class="panel-box" style="display: flex; flex-direction: column; gap: 16px;">
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B;">
                    Riwayat status
                </h3>

                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <i class="bi bi-record-circle" style="color: #1F3D2B; font-size: 14px; margin-top: 2px;"></i>
                    <div>
                        <div style="font-size: 12px; font-weight: 600; color: #24382D;">{{ $selResLabel }} · Bukti diunggah</div>
                        <div style="font-size: 11px; color: #718074; margin-top: 2px;">
                            {{ \Carbon\Carbon::parse($selectedBooking->updated_at)->translatedFormat('d M Y, H.i') }} WIB · {{ $selectedBooking->customer_name }}
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; align-items: flex-start;">
                    <i class="bi bi-record-circle" style="color: #718074; font-size: 14px; margin-top: 2px;"></i>
                    <div>
                        <div style="font-size: 12px; font-weight: 600; color: #24382D;">Reservasi dibuat · Menunggu pembayaran</div>
                        <div style="font-size: 11px; color: #718074; margin-top: 2px;">
                            {{ \Carbon\Carbon::parse($selectedBooking->created_at)->translatedFormat('d M Y, H.i') }} WIB · Sistem
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection
