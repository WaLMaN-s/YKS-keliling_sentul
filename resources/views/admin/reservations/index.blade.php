@extends('layouts.admin', ['title' => 'Data Reservasi'])

@section('content')
<style>
    .res-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1200px) {
        .res-grid {
            grid-template-columns: 1fr;
        }
    }
    .status-tab {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 99px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        border: 1px solid #DFE4DC;
        background: #FFFFFF;
        color: #1F3D2B;
        transition: all 0.15s;
    }
    .status-tab:hover {
        background: #F8F5EF;
    }
    .status-tab.active {
        background: #1F3D2B;
        color: #FFFFFF;
        border-color: #1F3D2B;
    }

    /* Table Design */
    .res-table {
        width: 100%;
        min-width: 760px;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.83rem;
    }
    .res-table thead th {
        background: #F8F5EF;
        padding: 12px 10px;
        font-size: 0.73rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #6B756E;
        border-bottom: 2px solid #DFE4DC;
        white-space: nowrap;
        vertical-align: middle;
    }
    .res-table thead th:first-child {
        border-top-left-radius: 8px;
    }
    .res-table thead th:last-child {
        border-top-right-radius: 8px;
    }
    .res-table tbody td {
        padding: 12px 10px;
        border-bottom: 1px solid #ECECE6;
        vertical-align: middle;
        background: #FFFFFF;
        transition: background 0.15s ease;
    }
    .res-row {
        cursor: pointer;
    }
    .res-row:hover td {
        background: #FAF8F5 !important;
    }
    .res-row.selected td {
        background: #F4EFE6 !important;
    }
    .res-row.selected td:first-child {
        border-left: 3px solid #1F3D2B;
    }

    /* Badges & Elements */
    .res-code-badge {
        font-family: var(--font-head);
        font-weight: 800;
        color: #1F3D2B;
        font-size: 0.86rem;
        display: block;
        letter-spacing: 0.02em;
    }
    .res-cust-name {
        font-size: 0.78rem;
        font-weight: 600;
        color: #4B5563;
        display: block;
        margin-top: 2px;
    }
    .res-pkg-name {
        font-weight: 600;
        color: #1F3D2B;
        display: block;
        max-width: 140px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .res-pax-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 99px;
        background: #F8F5EF;
        border: 1px solid #DFE4DC;
        font-size: 0.74rem;
        font-weight: 600;
        color: #1F3D2B;
        white-space: nowrap;
    }
    .res-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 99px;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }
    .res-status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Action Button Group */
    .res-action-group {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
    }
    .btn-act-item {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        height: 30px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .btn-act-wa {
        width: 30px;
        background: #EAF0E7;
        border-color: #C1D1BE;
        color: #065F46;
        font-size: 0.85rem;
    }
    .btn-act-wa:hover {
        background: #25D366;
        border-color: #25D366;
        color: #FFFFFF;
        box-shadow: 0 2px 6px rgba(37, 211, 102, 0.3);
    }
    .btn-act-track {
        width: 30px;
        background: #FFFFFF;
        border-color: #DFE4DC;
        color: #6B756E;
        font-size: 0.78rem;
    }
    .btn-act-track:hover {
        background: #F8F5EF;
        color: #1F3D2B;
        border-color: #1F3D2B;
    }
    .btn-act-detail {
        padding: 0 10px;
        background: #FFFFFF;
        border-color: #DFE4DC;
        color: #1F3D2B;
    }
    .btn-act-detail:hover {
        background: #1F3D2B;
        border-color: #1F3D2B;
        color: #FFFFFF;
    }
    .btn-act-detail.selected {
        background: #1F3D2B;
        border-color: #1F3D2B;
        color: #FFFFFF;
        box-shadow: 0 2px 6px rgba(31, 61, 43, 0.25);
    }

    .timeline-item {
        position: relative;
        padding-left: 20px;
        padding-bottom: 12px;
        border-left: 2px solid #DFE4DC;
    }
    .timeline-item:last-child {
        border-left-color: transparent;
        padding-bottom: 0;
    }
    .timeline-dot {
        position: absolute;
        left: -6px;
        top: 2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #1F3D2B;
    }
    .res-detail-sticky {
        position: sticky;
        top: 20px;
    }
</style>

<!-- Header Bar (Figma: Administrasi / Data Reservasi) -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Data Reservasi</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Telusuri pesanan dan kelola status perjalanan pelanggan.</p>
    </div>

    <!-- Form Pencarian -->
    <form action="{{ route('admin.reservations.index') }}" method="GET" style="display: flex; gap: 8px;">
        @if($status)
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <div style="position: relative;">
            <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6B756E; font-size: 0.82rem;"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari ID pesanan atau nama pelanggan…"
                   style="padding: 8px 14px 8px 34px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.84rem; width: 260px; background: #FFF;">
        </div>
        <button type="submit" class="btn-primary-yks">Cari</button>
    </form>
</div>

<!-- Filter Tab Status dengan Jumlah (Figma: Semua, Lunas 28, Verifikasi 3, Belum bayar 7, Dibatalkan 2) -->
<div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px;">
    <a href="{{ route('admin.reservations.index', ['q' => $search]) }}" class="status-tab {{ !$status ? 'active' : '' }}">
        Semua status <span style="opacity: 0.75;">({{ $counts['all'] }})</span>
    </a>
    <a href="{{ route('admin.reservations.index', ['status' => 'Lunas', 'q' => $search]) }}" class="status-tab {{ $status === 'Lunas' ? 'active' : '' }}">
        Lunas <span>{{ $counts['lunas'] }}</span>
    </a>
    <a href="{{ route('admin.reservations.index', ['status' => 'Menunggu Verifikasi', 'q' => $search]) }}" class="status-tab {{ $status === 'Menunggu Verifikasi' ? 'active' : '' }}">
        Verifikasi <span>{{ $counts['verifikasi'] }}</span>
    </a>
    <a href="{{ route('admin.reservations.index', ['status' => 'Menunggu Pembayaran', 'q' => $search]) }}" class="status-tab {{ $status === 'Menunggu Pembayaran' ? 'active' : '' }}">
        Belum bayar <span>{{ $counts['belum_bayar'] }}</span>
    </a>
    <a href="{{ route('admin.reservations.index', ['status' => 'Dibatalkan', 'q' => $search]) }}" class="status-tab {{ $status === 'Dibatalkan' ? 'active' : '' }}">
        Dibatalkan <span>{{ $counts['dibatalkan'] }}</span>
    </a>
</div>

<div class="res-grid">
    <!-- KOLOM KIRI: Tabel Semua Reservasi -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Semua reservasi</h3>
                <span style="font-size: 0.78rem; color: #6B756E;">Menampilkan {{ $reservations->count() }} dari {{ $reservations->total() }} pesanan</span>
            </div>
        </div>

        <div style="overflow-x: auto; border-radius: 8px;">
            <table class="res-table">
                <thead>
                    <tr>
                        <th style="text-align: left; padding-left: 14px;">ID & Pelanggan</th>
                        <th style="text-align: left;">Paket Wisata</th>
                        <th style="text-align: left;">Keberangkatan</th>
                        <th style="text-align: center;">Peserta</th>
                        <th style="text-align: right;">Total Bayar</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center; padding-right: 14px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                        @php
                            $isSelected = ($selectedBooking && $selectedBooking->booking_code === $r->booking_code);
                            $waNum = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $r->customer_phone ?? ''));

                            $st = $r->payment_status;
                            $badgeBg = '#EDEEE9';
                            $badgeColor = '#6B756E';
                            $dotColor = '#8E9E8B';
                            if ($st === 'Lunas') {
                                $badgeBg = '#EAF0E7';
                                $badgeColor = '#065F46';
                                $dotColor = '#10B981';
                            } elseif ($st === 'Menunggu Verifikasi') {
                                $badgeBg = '#FEEFE6';
                                $badgeColor = '#9A3412';
                                $dotColor = '#F26B1D';
                            } elseif ($st === 'Dibatalkan') {
                                $badgeBg = '#FFEBEE';
                                $badgeColor = '#991B1B';
                                $dotColor = '#EF4444';
                            }
                        @endphp
                        <tr class="res-row {{ $isSelected ? 'selected' : '' }}" onclick="selectRes('{{ $r->booking_code }}', event)">
                            <td style="padding-left: 14px;">
                                <span class="res-code-badge">{{ $r->booking_code }}</span>
                                <span class="res-cust-name">{{ $r->customer_name }}</span>
                            </td>
                            <td>
                                <span class="res-pkg-name" title="{{ $r->package_name }}">{{ $r->package_name }}</span>
                            </td>
                            <td style="white-space: nowrap;">
                                <div style="font-weight: 600; color: #1F3D2B;">
                                    <i class="bi bi-calendar3" style="color: #F26B1D; margin-right: 4px; font-size: 0.8rem;"></i>
                                    {{ date('d M Y', strtotime($r->trip_date)) }}
                                </div>
                                <div style="font-size: 0.72rem; color: #6B756E; margin-left: 18px;">08.00 WIB</div>
                            </td>
                            <td style="text-align: center;">
                                <span class="res-pax-badge">
                                    <i class="bi bi-people-fill" style="color: #6B756E;"></i> {{ $r->participant_count }}
                                </span>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <strong style="font-weight: 800; color: #1F3D2B; font-size: 0.88rem;">
                                    Rp{{ number_format($r->total_price, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td style="text-align: center;">
                                <span class="res-status-badge" style="background: {{ $badgeBg }}; color: {{ $badgeColor }};">
                                    <span class="res-status-dot" style="background: {{ $dotColor }};"></span>
                                    {{ $st }}
                                </span>
                            </td>
                            <td style="text-align: center; padding-right: 14px;">
                                <div class="res-action-group">
                                    @if($waNum)
                                        <a href="https://wa.me/{{ $waNum }}?text=Halo%20Kak%20{{ urlencode($r->customer_name) }},%20kami%20dari%20Admin%20Yuk%20Keliling%20Sentul%20ingin%20mengonfirmasi%20reservasi%20dengan%20Kode:%20{{ $r->booking_code }}."
                                           target="_blank"
                                           class="btn-act-item btn-act-wa"
                                           title="Chat WhatsApp ({{ $r->customer_phone }})">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('booking.track', ['kode' => $r->booking_code]) }}"
                                       target="_blank"
                                       class="btn-act-item btn-act-track"
                                       title="Lihat Lacak Pesanan (Tampilan Pelanggan)">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                    <a href="{{ route('admin.reservations.index', ['select' => $r->booking_code, 'status' => $status, 'q' => $search, 'page' => request('page')]) }}#detail-panel"
                                       class="btn-act-item btn-act-detail {{ $isSelected ? 'selected' : '' }}"
                                       title="Buka Detail Reservasi">
                                        <i class="bi bi-card-text"></i>
                                        <span>Detail</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 36px 14px; text-align: center; color: #6B756E;">
                                <i class="bi bi-inbox" style="font-size: 1.8rem; display: block; margin-bottom: 6px; opacity: 0.5;"></i>
                                Tidak ada data reservasi yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <span style="font-size: 0.75rem; color: #6B756E;">Kolom tanggal menunjukkan jadwal keberangkatan wisata.</span>
            {{ $reservations->links() }}
        </div>
    </div>

    <!-- KOLOM KANAN: Panel Detail Reservasi (Figma) -->
    <div class="stat-card res-detail-sticky" id="detail-panel">
        @if($selectedBooking)
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid #ECECE6; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #6B756E; font-weight: 700;">
                        Detail reservasi
                    </span>
                    <div style="display: flex; align-items: center; gap: 8px; margin-top: 2px;">
                        <h3 style="font-size: 1.2rem; font-weight: 800; color: #1F3D2B;">{{ $selectedBooking->booking_code }}</h3>
                        <span style="display: inline-block; padding: 3px 9px; border-radius: 99px; font-size: 0.72rem; font-weight: 600;
                            {{ $selectedBooking->payment_status === 'Lunas' ? 'background: #EAF0E7; color: #1F3D2B;' : '' }}
                            {{ $selectedBooking->payment_status === 'Menunggu Verifikasi' ? 'background: #FEEFE6; color: #F26B1D;' : '' }}
                            {{ $selectedBooking->payment_status === 'Menunggu Pembayaran' ? 'background: #EDEEE9; color: #6B756E;' : '' }}
                            {{ $selectedBooking->payment_status === 'Dibatalkan' ? 'background: #FFEBEE; color: #C62828;' : '' }}
                        ">
                            {{ $selectedBooking->payment_status }}
                        </span>
                    </div>
                </div>

                <button type="button" onclick="window.print()" class="btn-outline-yks" style="font-size: 0.78rem; padding: 6px 12px;">
                    <i class="bi bi-printer"></i> Cetak ↗
                </button>
            </div>

            <!-- Profil Pemesan -->
            <div style="background: #F8F5EF; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px;">
                <div style="font-weight: 700; font-size: 0.95rem; color: #1F3D2B;">{{ $selectedBooking->customer_name }}</div>
                <div style="font-size: 0.8rem; color: #6B756E; margin-top: 2px;">
                    {{ $selectedBooking->customer_email }} · {{ $selectedBooking->customer_phone }}
                </div>
            </div>

            <!-- Rincian Perjalanan (Figma) -->
            <div style="display: grid; grid-template-columns: 125px 1fr; row-gap: 8px; font-size: 0.83rem; margin-bottom: 16px;">
                <span style="color: #6B756E;">Paket</span>
                <strong style="color: #1F3D2B;">{{ $selectedBooking->package_name }}</strong>

                <span style="color: #6B756E;">Keberangkatan</span>
                <strong>{{ date('l, d F Y', strtotime($selectedBooking->trip_date)) }}</strong>

                <span style="color: #6B756E;">Sesi</span>
                <span>08.00–13.00 WIB · {{ $selectedBooking->package_duration ?? '4–6 jam' }}</span>

                <span style="color: #6B756E;">Peserta</span>
                <span>
                    {{ $selectedBooking->participant_count }} orang
                    @if(!empty($selectedBooking->jenis_trip))
                        · <strong>{{ ucfirst($selectedBooking->jenis_trip) }} Trip</strong>
                    @endif
                    @if(!empty($selectedBooking->selected_units))
                        · Unit: <strong>{{ $selectedBooking->selected_units }}</strong>
                    @endif
                </span>

                @if(!empty($selectedBooking->selected_addons))
                    <span style="color: #6B756E;">Add-On</span>
                    <span style="color: #1F3D2B; font-weight: 600;">{{ $selectedBooking->selected_addons }} (+Rp{{ number_format($selectedBooking->subtotal_addons ?? 0, 0, ',', '.') }})</span>
                @endif

                <span style="color: #6B756E;">Titik kumpul</span>
                <span>{{ $selectedBooking->meeting_point ?? 'Pos Jelajah YKS, Sentul' }}</span>

                <span style="color: #6B756E;">Waktu kedatangan</span>
                <span>07.30 WIB untuk pengarahan</span>
            </div>

            @if($selectedBooking->notes)
                <div style="background: #FEEFE6; border-left: 3px solid #F26B1D; padding: 10px 12px; border-radius: 6px; font-size: 0.8rem; color: #1F3D2B; margin-bottom: 16px;">
                    <strong>Catatan pemesan:</strong> {{ $selectedBooking->notes }}
                </div>
            @endif

            <!-- Ringkasan Pembayaran (Figma) -->
            <div style="border-top: 1px solid #ECECE6; padding-top: 14px; margin-bottom: 16px;">
                <div style="font-weight: 700; font-size: 0.86rem; color: #1F3D2B; margin-bottom: 8px;">Ringkasan pembayaran</div>
                <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #6B756E; margin-bottom: 4px;">
                    <span>{{ $selectedBooking->participant_count }} × Rp{{ number_format($selectedBooking->package_price, 0, ',', '.') }}</span>
                    <span>Rp{{ number_format($selectedBooking->total_price, 0, ',', '.') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.82rem; color: #6B756E; margin-bottom: 8px;">
                    <span>Biaya admin</span>
                    <span>Rp0</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.95rem; font-weight: 800; color: #1F3D2B; padding-top: 6px; border-top: 1px dashed #DFE4DC;">
                    <span>Total</span>
                    <span>Rp{{ number_format($selectedBooking->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            @php
                $resProofUrl = \App\Models\YksBooking::resolveProofUrl($selectedBooking->payment_proof);
            @endphp
            <div style="border-top: 1px solid #ECECE6; padding-top: 14px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <div style="font-weight: 700; font-size: 0.86rem; color: #1F3D2B;">
                        <i class="bi bi-file-earmark-image"></i> Bukti Pembayaran
                    </div>
                    @if($resProofUrl)
                        <a href="{{ $resProofUrl }}" target="_blank" style="font-size: 0.75rem; font-weight: 600; color: #F26B1D; text-decoration: none;">
                            Buka Ukuran Penuh ↗
                        </a>
                    @endif
                </div>

                @if($resProofUrl)
                    <div style="border: 1px solid #DFE4DC; border-radius: 8px; padding: 6px; background: #FFFFFF; text-align: center; margin-bottom: 10px;">
                        <a href="{{ $resProofUrl }}" target="_blank" title="Klik untuk memperbesar di tab baru">
                            <img src="{{ $resProofUrl }}" alt="Bukti Transfer" style="max-width: 100%; max-height: 220px; object-fit: contain; border-radius: 6px; cursor: zoom-in;">
                        </a>
                    </div>
                @else
                    <div style="background: #F8F5EF; border: 1px dashed #DFE4DC; border-radius: 8px; padding: 12px; font-size: 0.78rem; color: #6B756E; text-align: center; margin-bottom: 10px;">
                        Belum ada berkas bukti transfer yang diunggah oleh pelanggan.
                    </div>
                @endif

                <!-- Form Upload / Ganti Bukti dari Admin -->
                <details style="font-size: 0.76rem; color: #6B756E;">
                    <summary style="cursor: pointer; color: #1F3D2B; font-weight: 600; margin-bottom: 6px;">
                        <i class="bi bi-upload"></i> {{ $resProofUrl ? 'Ganti / Unggah ulang bukti (dari WA)' : 'Unggah bukti transfer (dari WA)' }}
                    </summary>
                    <form action="{{ route('admin.reservations.upload_proof', $selectedBooking->booking_code) }}" method="POST" enctype="multipart/form-data" style="margin-top: 8px; display: flex; gap: 8px; flex-direction: column;">
                        @csrf
                        <input type="file" name="payment_proof" accept="image/*" required style="font-size: 0.75rem; padding: 6px; border: 1px solid #DFE4DC; border-radius: 6px; background: #FFF;">
                        <button type="submit" class="btn-outline-yks" style="padding: 6px 12px; font-size: 0.75rem; justify-content: center;">
                            Simpan Berkas Bukti
                        </button>
                    </form>
                </details>
            </div>

            <!-- Form Ubah Status & Tombol Periksa Bukti -->
            <div style="border-top: 1px solid #ECECE6; padding-top: 14px; margin-bottom: 16px;">
                <form action="{{ route('admin.reservations.update_status', $selectedBooking->booking_code) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status" style="flex: 1; padding: 8px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.82rem; color: #1F3D2B;">
                        @foreach(['Menunggu Pembayaran', 'Menunggu Verifikasi', 'Lunas', 'Dibatalkan'] as $st)
                            <option value="{{ $st }}" {{ $selectedBooking->payment_status === $st ? 'selected' : '' }}>
                                Status: {{ $st }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary-yks" style="padding: 8px 14px;">Simpan Status</button>
                </form>

                @if($selectedBooking->payment_status === 'Menunggu Verifikasi')
                    <a href="{{ route('admin.verifications.index', ['select' => $selectedBooking->booking_code]) }}"
                       class="btn-primary-yks" style="width: 100%; justify-content: center; margin-top: 10px; background: #F26B1D;">
                        <i class="bi bi-receipt-cutoff"></i> Periksa bukti pembayaran →
                    </a>
                @endif
            </div>

            <!-- Riwayat Status (Timeline Figma) -->
            <div style="border-top: 1px solid #ECECE6; padding-top: 14px;">
                <div style="font-weight: 700; font-size: 0.86rem; color: #1F3D2B; margin-bottom: 10px;">Riwayat status</div>
                <div style="font-size: 0.78rem;">
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: {{ $selectedBooking->payment_status === 'Lunas' ? '#1F3D2B' : '#F26B1D' }};"></div>
                        <div style="font-weight: 700; color: #1F3D2B;">{{ $selectedBooking->payment_status }}</div>
                        <div style="color: #6B756E;">Diperbarui {{ date('d M Y, H.i', strtotime($selectedBooking->updated_at)) }} WIB</div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-dot" style="background: #C1D1BE;"></div>
                        <div style="font-weight: 600; color: #1F3D2B;">Pesanan & Instruksi Pembayaran Dibuat</div>
                        <div style="color: #6B756E;">{{ date('d M Y, H.i', strtotime($selectedBooking->created_at)) }} WIB</div>
                    </div>
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 48px 16px; color: #6B756E;">
                Pilih salah satu reservasi di tabel kiri untuk melihat rincian lengkap.
            </div>
        @endif
    </div>
</div>

<script>
    function selectRes(code, event) {
        // Jangan picu pindah baris jika yang diklik adalah tombol/link di dalam kolom aksi
        if (event.target.closest('a') || event.target.closest('button') || event.target.closest('form')) {
            return;
        }
        window.location.href = "{{ route('admin.reservations.index') }}?select=" + encodeURIComponent(code) + "&status={{ $status }}&q={{ $search }}&page={{ request('page') }}#detail-panel";
    }
</script>
@endsection
