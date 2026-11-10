@extends('layouts.admin', ['title' => 'Data Pelanggan'])

@section('content')
<style>
    .cust-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1100px) {
        .cust-grid {
            grid-template-columns: 1fr;
        }
    }
    .cust-row:hover {
        background: #FCFCFA;
    }
    .cust-row.selected {
        background: #F8F5EF;
    }
</style>

<!-- Header Bar (Figma: Administrasi / Data Pelanggan + Unduh data) -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Data Pelanggan</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Kenali pelanggan dan temukan riwayat perjalanan mereka.</p>
    </div>

    <a href="{{ route('admin.customers.export_csv') }}" class="btn-outline-yks">
        <i class="bi bi-download"></i> Unduh data
    </a>
</div>

<!-- 3 Kartu KPI Pelanggan (Figma) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Pelanggan terdaftar</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $totalRegistered }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">Seluruh periode tercatat</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Pelanggan kembali</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $repeatCustomers }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">Memiliki lebih dari 1 reservasi</p>
    </div>

    <div class="stat-card">
        <span style="font-size: 0.75rem; text-transform: uppercase; color: #6B756E; font-weight: 600;">Pelanggan baru bulan ini</span>
        <h3 style="font-size: 1.85rem; font-weight: 800; color: #1F3D2B; margin-top: 6px;">{{ $newThisMonth }}</h3>
        <p style="font-size: 0.76rem; color: #6B756E; margin-top: 4px;">Pemesan aktif bulan berjalan</p>
    </div>
</div>

<div class="cust-grid">
    <!-- KOLOM KIRI: Daftar Pelanggan -->
    <div class="stat-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700;">Daftar pelanggan</h3>
                <span style="font-size: 0.78rem; color: #6B756E;">{{ $totalRegistered }} pelanggan terdaftar</span>
            </div>

            <form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 8px; flex-wrap: wrap;">
                <div style="position: relative;">
                    <i class="bi bi-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6B756E; font-size: 0.78rem;"></i>
                    <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, surel, atau kontak…"
                           style="padding: 6px 10px 6px 28px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.8rem; width: 210px;">
                </div>
                <select name="filter" onchange="this.form.submit()" style="padding: 6px 10px; border: 1px solid #DFE4DC; border-radius: 8px; font-size: 0.8rem; color: #1F3D2B; background: #FFF;">
                    <option value="">Semua pelanggan</option>
                    <option value="repeat" {{ $filter === 'repeat' ? 'selected' : '' }}>Pelanggan kembali (>1)</option>
                </select>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.84rem;">
                <thead>
                    <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                        <th style="padding: 10px 8px;">Pelanggan</th>
                        <th style="padding: 10px 8px;">Surel & Kontak</th>
                        <th style="padding: 10px 8px;">Frekuensi</th>
                        <th style="padding: 10px 8px;">Total Belanja</th>
                        <th style="padding: 10px 8px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $idx => $c)
                        @php
                            $plgCode = 'PLG-' . str_pad((string) ($customers->firstItem() + $idx), 3, '0', STR_PAD_LEFT);
                            $isSelected = ($selectedCustomer && $selectedCustomer->customer_email === $c->customer_email);
                            $waNumber = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $c->customer_phone));
                        @endphp
                        <tr class="cust-row {{ $isSelected ? 'selected' : '' }}" style="border-bottom: 1px solid #ECECE6;">
                            <td style="padding: 12px 8px;">
                                <div style="font-weight: 700; color: #1F3D2B;">{{ $c->customer_name }}</div>
                                <div style="font-size: 0.72rem; color: #6B756E; font-weight: 600;">{{ $plgCode }}</div>
                            </td>
                            <td style="padding: 12px 8px;">
                                <div style="color: #1F3D2B;">{{ $c->customer_email }}</div>
                                <div style="font-size: 0.75rem; color: #6B756E;">{{ $c->customer_phone }}</div>
                            </td>
                            <td style="padding: 12px 8px;">
                                <span style="background: #F8F5EF; border: 1px solid #DFE4DC; padding: 3px 8px; border-radius: 99px; font-size: 0.75rem; font-weight: 600;">
                                    {{ $c->total_trip }} pesanan
                                </span>
                            </td>
                            <td style="padding: 12px 8px; font-weight: 700; color: #1F3D2B;">
                                Rp{{ number_format($c->total_spent, 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px 8px; text-align: right;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <a href="https://wa.me/{{ $waNumber }}" target="_blank"
                                       style="padding: 5px 10px; border-radius: 6px; border: 1px solid #C1D1BE; background: #EAF0E7; color: #1F3D2B; font-size: 0.75rem; font-weight: 600; text-decoration: none;">
                                        WhatsApp
                                    </a>
                                    <a href="{{ route('admin.customers.index', ['select' => $c->customer_email, 'q' => $search, 'filter' => $filter]) }}#profile-panel"
                                       style="padding: 5px 10px; border-radius: 6px; border: 1px solid #DFE4DC; background: {{ $isSelected ? '#1F3D2B' : '#FFFFFF' }}; color: {{ $isSelected ? '#FFFFFF' : '#1F3D2B' }}; font-size: 0.75rem; font-weight: 600; text-decoration: none;">
                                        Profil ↗
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 32px; text-align: center; color: #6B756E;">Belum ada data pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 14px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
            <span style="font-size: 0.75rem; color: #6B756E;">Menampilkan {{ $customers->count() }} dari {{ $customers->total() }} pelanggan</span>
            {{ $customers->links() }}
        </div>
    </div>

    <!-- KOLOM KANAN: Panel Profil Pelanggan (Figma) -->
    <div class="stat-card" id="profile-panel">
        @if($selectedCustomer)
            @php
                $parts = explode(' ', trim($selectedCustomer->customer_name));
                $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                $waSelected = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $selectedCustomer->customer_phone));
            @endphp
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 14px; border-bottom: 1px solid #ECECE6; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #6B756E; font-weight: 700;">
                        Profil pelanggan
                    </span>
                    <div style="font-size: 0.8rem; color: #1F3D2B; font-weight: 600; margin-top: 2px;">
                        Pelanggan Terdaftar YKS
                    </div>
                </div>
            </div>

            <!-- Avatar & Identitas -->
            <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 18px;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #1F3D2B; color: #FFFFFF; font-weight: 800; font-size: 1.1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    {{ $initials }}
                </div>
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #1F3D2B;">{{ $selectedCustomer->customer_name }}</h3>
                    <div style="font-size: 0.76rem; color: #6B756E;">
                        Bergabung {{ date('d F Y', strtotime($selectedCustomer->first_joined)) }}
                    </div>
                </div>
            </div>

            <!-- Kontak & Statistik Ringkas -->
            <div style="background: #F8F5EF; border-radius: 10px; padding: 14px; font-size: 0.82rem; margin-bottom: 18px;">
                <div style="display: grid; grid-template-columns: 95px 1fr; row-gap: 6px;">
                    <span style="color: #6B756E;">Surel</span>
                    <strong style="color: #1F3D2B;">{{ $selectedCustomer->customer_email }}</strong>

                    <span style="color: #6B756E;">Kontak</span>
                    <span>{{ $selectedCustomer->customer_phone }}</span>

                    <span style="color: #6B756E;">Total pesanan</span>
                    <strong>{{ $selectedCustomer->total_trip }} reservasi</strong>

                    <span style="color: #6B756E;">Total transaksi</span>
                    <strong style="color: #1F3D2B;">Rp{{ number_format($selectedCustomer->total_spent, 0, ',', '.') }}</strong>
                </div>

                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #E2E6DF;">
                    <a href="https://wa.me/{{ $waSelected }}" target="_blank" class="btn-primary-yks" style="width: 100%; justify-content: center; font-size: 0.8rem;">
                        <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                    </a>
                </div>
            </div>

            <!-- Riwayat Perjalanan Pelanggan -->
            <div>
                <h4 style="font-size: 0.9rem; font-weight: 700; color: #1F3D2B; margin-bottom: 10px;">
                    Riwayat perjalanan ({{ count($customerHistory) }})
                </h4>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    @foreach($customerHistory as $hist)
                        <div style="border: 1px solid #ECECE6; border-radius: 8px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                            <div>
                                <div style="font-weight: 700; font-size: 0.83rem; color: #1F3D2B;">
                                    {{ $hist->booking_code }} · {{ $hist->package_name }}
                                </div>
                                <div style="font-size: 0.75rem; color: #6B756E; margin-top: 2px;">
                                    Trip {{ date('d M Y', strtotime($hist->trip_date)) }} · {{ $hist->participant_count }} peserta · <strong>Rp{{ number_format($hist->total_price, 0, ',', '.') }}</strong>
                                </div>
                            </div>

                            <a href="{{ route('admin.reservations.index', ['select' => $hist->booking_code]) }}"
                               style="font-size: 0.74rem; font-weight: 600; color: #1F3D2B; text-decoration: underline; white-space: nowrap;">
                                Lihat ↗
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div style="text-align: center; padding: 48px 16px; color: #6B756E;">
                Pilih pelanggan dari daftar di sebelah kiri untuk melihat profil dan riwayat perjalanan.
            </div>
        @endif
    </div>
</div>
@endsection
