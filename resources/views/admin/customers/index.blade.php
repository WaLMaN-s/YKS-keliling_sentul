@extends('layouts.admin', ['title' => 'Pelanggan', 'breadcrumb' => 'Pelanggan'])

@section('content')
<style>
    .cust-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1120px) {
        .cust-layout {
            grid-template-columns: 1fr;
        }
    }
    .cust-panel {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .cust-row-item {
        display: grid;
        grid-template-columns: 1fr 210px 70px 100px;
        gap: 12px;
        align-items: center;
        padding: 12px;
        min-height: 67px;
        border-bottom: 1px solid #E1E4DA;
        text-decoration: none;
        color: inherit;
        transition: background 0.15s ease;
    }
    .cust-row-item:hover {
        background: #F9FAF7;
    }
    .cust-row-item.selected {
        background: #EAF0E7;
    }
    .c-kv {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        font-size: 13px;
        line-height: 1.5;
    }
    .c-kv span:first-child { color: #718074; }
    .c-kv span:last-child { color: #24382D; font-weight: 500; text-align: right; }
</style>

<!-- Identitas Halaman (Figma Frame 178:19274) -->
<div style="margin-bottom: 24px;">
    <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px; margin-bottom: 6px;">Pelanggan</h1>
    <p style="font-size: 13px; color: #718074;">Temukan data pemesan dan riwayat reservasinya dalam satu tempat.</p>
</div>

<!-- Filter Pencarian Pelanggan (Figma Frame 178:19280) -->
<form action="{{ route('admin.customers.index') }}" method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 24px;">
    <div style="position: relative;">
        <i class="bi bi-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #718074; font-size: 13px;"></i>
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau email pelanggan..."
               style="height: 42px; padding: 0 14px 0 34px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 13px; color: #24382D; width: 250px;">
    </div>

    <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: #24382D;">
        <i class="bi bi-calendar3" style="color: #718074;"></i>
        <span>Terdaftar: 1–9 Nov 2026</span>
    </div>

    @if($search)
        <a href="{{ route('admin.customers.index') }}"
           style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; font-size: 12px; color: #718074; text-decoration: none; display: inline-flex; align-items: center;">
            Reset
        </a>
    @endif
</form>

<!-- 2 Kolom: Daftar Pelanggan & Detail Pelanggan Terpilih (Figma Frame 178:19290) -->
<div class="cust-layout">
    <!-- KOLOM KIRI: Daftar Pelanggan -->
    <div class="cust-panel">
        <div>
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                Daftar pelanggan
            </h3>
            <p style="font-size: 12px; color: #718074;">
                Identitas fiktif · Kontak tidak dapat digunakan
            </p>
        </div>

        <div style="overflow-x: auto;">
            <div style="min-width: 560px;">
                <div style="display: grid; grid-template-columns: 1fr 210px 70px 100px; gap: 12px; background: #F4F6F1; padding: 12px; border-radius: 6px; font-size: 11px; font-weight: 600; color: #718074;">
                    <div>Pelanggan</div>
                    <div>Kontak contoh</div>
                    <div>Reservasi</div>
                    <div>Terdaftar</div>
                </div>

                @forelse($customers as $idx => $c)
                    @php
                        $isSelected = ($selectedCustomer && $selectedCustomer->customer_email === $c->customer_email);
                        $initial = strtoupper(substr(trim($c->customer_name ?? 'P'), 0, 1));
                    @endphp
                    <a href="{{ route('admin.customers.index', ['select' => $c->customer_email, 'q' => $search]) }}#profile-panel"
                       class="cust-row-item {{ $isSelected ? 'selected' : '' }}">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #EAF0E7; color: #24382D; font-size: 11px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ $initial }}
                            </div>
                            <span style="font-size: 13px; font-weight: 600; color: #24382D;">{{ $c->customer_name }}</span>
                        </div>

                        <div>
                            <div style="font-size: 11px; color: #24382D;">{{ $c->customer_email }}</div>
                            <div style="font-size: 10px; color: #718074; margin-top: 2px;">{{ $c->customer_phone }}</div>
                        </div>

                        <div style="font-size: 12px; color: #24382D;">
                            {{ $c->total_trip }}
                        </div>

                        <div style="font-size: 12px; color: #24382D;">
                            {{ !empty($c->first_joined) ? \Carbon\Carbon::parse($c->first_joined)->translatedFormat('j M Y') : '9 Nov 2026' }}
                        </div>
                    </a>
                @empty
                    <div style="padding: 32px; text-align: center; color: #718074; font-size: 13px;">
                        Tidak ada data pelanggan yang ditemukan.
                    </div>
                @endforelse
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <span style="font-size: 12px; color: #718074;">
                1–{{ $customers->count() }} dari {{ $customers->total() }} pelanggan
            </span>
            {{ $customers->links() }}
        </div>
    </div>

    <!-- KOLOM KANAN: Detail Pelanggan & Riwayat Reservasi (Figma Frame 178:19425) -->
    <div style="display: flex; flex-direction: column; gap: 24px;" id="profile-panel">
        @if($selectedCustomer)
            @php
                $selInitial = strtoupper(substr(trim($selectedCustomer->customer_name ?? 'N'), 0, 1));
            @endphp
            <!-- Panel 1: Detail Pelanggan -->
            <div class="cust-panel">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B;">
                        Detail pelanggan
                    </h3>
                    <span style="font-size: 14px; color: #718074;">×</span>
                </div>

                <div style="display: flex; align-items: center; gap: 14px;">
                    <div style="width: 52px; height: 52px; border-radius: 50%; background: #EAF0E7; color: #24382D; font-size: 21px; font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        {{ $selInitial }}
                    </div>
                    <div>
                        <div style="font-size: 20px; font-weight: 600; color: #24382D; margin-bottom: 4px;">
                            {{ $selectedCustomer->customer_name }}
                        </div>
                        <span style="background: #EAF0E7; color: #1F3D2B; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 100px;">
                            Pelanggan contoh
                        </span>
                    </div>
                </div>

                <div style="height: 1px; background: #E1E4DA;"></div>

                <div class="c-kv">
                    <span>ID pelanggan</span>
                    <span>PLG-008</span>
                </div>
                <div class="c-kv">
                    <span>Email</span>
                    <span>{{ $selectedCustomer->customer_email }}</span>
                </div>
                <div class="c-kv">
                    <span>Telepon</span>
                    <span>{{ $selectedCustomer->customer_phone }}</span>
                </div>
                <div class="c-kv">
                    <span>Terdaftar</span>
                    <span>{{ !empty($selectedCustomer->first_joined) ? \Carbon\Carbon::parse($selectedCustomer->first_joined)->translatedFormat('j F Y') : '9 November 2026' }}</span>
                </div>
                <div class="c-kv">
                    <span>Total reservasi</span>
                    <span>{{ $selectedCustomer->total_trip }} reservasi</span>
                </div>
            </div>

            <!-- Panel 2: Riwayat Reservasi Pelanggan -->
            <div class="cust-panel">
                <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B;">
                    Riwayat reservasi
                </h3>

                @foreach($customerHistory as $hist)
                    <div style="border: 1px solid #E1E4DA; border-radius: 8px; padding: 14px; display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; font-weight: 600; color: #24382D;">{{ $hist->booking_code }}</span>
                            <span style="background: #FFF0E4; color: #A54B10; font-size: 11px; font-weight: 600; padding: 4px 9px; border-radius: 100px;">
                                {{ $hist->reservation_status ?: $hist->payment_status }}
                            </span>
                        </div>
                        <div style="font-size: 12px; color: #718074;">
                            {{ $hist->custom_package_name ?: $hist->package_name }} · {{ \Carbon\Carbon::parse($hist->trip_date)->translatedFormat('d M Y') }}
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                            <strong style="color: #1F3D2B;">Rp{{ number_format($hist->total_price, 0, ',', '.') }}</strong>
                            <a href="{{ route('admin.reservations.index', ['select' => $hist->booking_code]) }}"
                               style="color: #1F3D2B; font-weight: 600; text-decoration: none;">
                                Buka reservasi ↗
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
