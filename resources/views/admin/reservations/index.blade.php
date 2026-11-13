@extends('layouts.admin', ['title' => 'Data Reservasi'])

@section('content')
<style>
    .res-grid {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1100px) {
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
    .res-row {
        cursor: pointer;
        transition: background 0.15s;
    }
    .res-row:hover {
        background: #FCFCFA;
    }
    .res-row.selected {
        background: #F8F5EF;
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

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.83rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                        <th style="padding: 10px 8px;">ID / Pelanggan</th>
                        <th style="padding: 10px 8px;">Paket</th>
                        <th style="padding: 10px 8px;">Keberangkatan</th>
                        <th style="padding: 10px 8px;">Peserta</th>
                        <th style="padding: 10px 8px;">Total</th>
                        <th style="padding: 10px 8px;">Status</th>
                        <th style="padding: 10px 8px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $r)
                        @php
                            $isSelected = ($selectedBooking && $selectedBooking->booking_code === $r->booking_code);
                        @endphp
                        <tr class="res-row {{ $isSelected ? 'selected' : '' }}" style="border-bottom: 1px solid #ECECE6;">
                            <td style="padding: 11px 8px;">
                                <div style="font-weight: 700; color: #1F3D2B;">{{ $r->booking_code }}</div>
                                <div style="font-size: 0.75rem; color: #6B756E;">{{ $r->customer_name }}</div>
                            </td>
                            <td style="padding: 11px 8px; font-weight: 500;">{{ $r->package_name }}</td>
                            <td style="padding: 11px 8px;">
                                {{ date('d M Y', strtotime($r->trip_date)) }}<br>
                                <span style="font-size: 0.72rem; color: #6B756E;">08.00 WIB</span>
                            </td>
                            <td style="padding: 11px 8px;">{{ $r->participant_count }} orang</td>
                            <td style="padding: 11px 8px; font-weight: 700;">Rp{{ number_format($r->total_price, 0, ',', '.') }}</td>
                            <td style="padding: 11px 8px;">
                                <span style="display: inline-block; padding: 3px 9px; border-radius: 99px; font-size: 0.72rem; font-weight: 600;
                                    {{ $r->payment_status === 'Lunas' ? 'background: #EAF0E7; color: #1F3D2B;' : '' }}
                                    {{ $r->payment_status === 'Menunggu Verifikasi' ? 'background: #FEEFE6; color: #F26B1D;' : '' }}
                                    {{ $r->payment_status === 'Menunggu Pembayaran' ? 'background: #EDEEE9; color: #6B756E;' : '' }}
                                    {{ $r->payment_status === 'Dibatalkan' ? 'background: #FFEBEE; color: #C62828;' : '' }}
                                ">
                                    {{ $r->payment_status }}
                                </span>
                            </td>
                            <td style="padding: 11px 8px; text-align: right;">
                                <a href="{{ route('admin.reservations.index', ['select' => $r->booking_code, 'status' => $status, 'q' => $search, 'page' => request('page')]) }}#detail-panel"
                                   style="padding: 5px 10px; border-radius: 6px; border: 1px solid #DFE4DC; background: {{ $isSelected ? '#1F3D2B' : '#FFFFFF' }}; color: {{ $isSelected ? '#FFFFFF' : '#1F3D2B' }}; font-size: 0.75rem; font-weight: 600; text-decoration: none;">
                                    Detail ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 32px; text-align: center; color: #6B756E;">Tidak ada data reservasi yang ditemukan.</td>
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
    <div class="stat-card" id="detail-panel">
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
                <span>{{ $selectedBooking->participant_count }} orang</span>

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
@endsection
