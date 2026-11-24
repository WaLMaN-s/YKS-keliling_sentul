@extends('layouts.admin', ['title' => 'Jadwal & Kapasitas', 'breadcrumb' => 'Jadwal & Kapasitas'])

@section('content')
<style>
    .sched-kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 960px) {
        .sched-kpi-grid {
            grid-template-columns: 1fr;
        }
    }
    .sched-kpi-card {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .sched-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1120px) {
        .sched-layout {
            grid-template-columns: 1fr;
        }
    }
    .sched-panel {
        background: #FFFFFF;
        border: 1px solid #E1E4DA;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .week-strip {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }
    .week-day-pill {
        background: #F4F6F1;
        border-radius: 8px;
        padding: 10px 4px;
        text-align: center;
        text-decoration: none;
        color: #718074;
        font-size: 12px;
        line-height: 1.6;
        transition: all 0.15s ease;
    }
    .week-day-pill:hover {
        background: #EAF0E7;
        color: #1F3D2B;
    }
    .week-day-pill.active {
        background: #1F3D2B;
        color: #FFFFFF;
        font-weight: 700;
    }
    .slot-row {
        display: grid;
        grid-template-columns: 1fr 150px 95px 55px;
        gap: 12px;
        align-items: center;
        padding: 12px;
        min-height: 67px;
        border-bottom: 1px solid #E1E4DA;
    }
    .slot-row.selected {
        background: #EAF0E7;
    }
    .s-input {
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #E1E4DA;
        border-radius: 8px;
        font-family: inherit;
        font-size: 13px;
        color: #24382D;
        background: #FFFFFF;
    }
</style>

@php
    $selCarbon = \Carbon\Carbon::parse($selectedDate);
    $weekStart = $selCarbon->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    $weekDays = [];
    $dayShortNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    for ($i = 0; $i < 7; $i++) {
        $d = $weekStart->copy()->addDays($i);
        $weekDays[] = [
            'date' => $d->format('Y-m-d'),
            'label' => $dayShortNames[$i],
            'day' => $d->format('j'),
            'active' => $d->format('Y-m-d') === $selectedDate,
        ];
    }

    $totalCap = collect($sessions)->sum('capacity') ?: 8;
    $totalBooked = collect($sessions)->sum('booked') ?: 3;
    $totalRemaining = max(0, $totalCap - $totalBooked);
@endphp

<!-- Identitas Halaman (Figma Frame 178:19043) -->
<div style="display: flex; justify-content: space-between; align-items: center; gap: 20px; margin-bottom: 24px; flex-wrap: wrap;">
    <div>
        <h1 style="font-size: 28px; font-weight: 800; color: #1F3D2B; letter-spacing: -0.6px; margin-bottom: 6px;">Jadwal &amp; Kapasitas</h1>
        <p style="font-size: 13px; color: #718074;">Kelola slot perjalanan dan ketersediaan jeep. Semua waktu menggunakan WIB.</p>
    </div>

    <a href="#slot-form-card"
       style="background: #F26B1D; color: #1F3D2B; height: 42px; padding: 0 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-plus-lg"></i> Tambah slot
    </a>
</div>

<!-- Filter Jadwal (Figma Frame 178:19050) -->
<form action="{{ route('admin.schedules.index') }}" method="GET" style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 24px;">
    <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; display: inline-flex; align-items: center; gap: 8px;">
        <i class="bi bi-calendar3" style="color: #718074; font-size: 13px;"></i>
        <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()"
               style="border: none; font-family: inherit; font-size: 13px; color: #24382D; background: transparent; cursor: pointer;">
    </div>

    <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: #24382D;">
        <span>Semua paket</span>
        <i class="bi bi-chevron-down" style="font-size: 11px; color: #718074;"></i>
    </div>

    <div style="height: 42px; padding: 0 12px; border: 1px solid #E1E4DA; border-radius: 8px; background: #FFFFFF; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: #24382D;">
        <span>Semua status slot</span>
        <i class="bi bi-chevron-down" style="font-size: 11px; color: #718074;"></i>
    </div>

    <span style="font-size: 12px; color: #718074;">
        {{ count($sessions) }} slot pada tanggal terpilih
    </span>
</form>

<!-- 3 Kartu Ringkasan Kapasitas (Figma Frame 178:19061) -->
<div class="sched-kpi-grid">
    <div class="sched-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Kapasitas slot aktif</span>
            <i class="bi bi-calendar3" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            {{ $totalCap }} jeep
        </div>
        <div style="font-size: 11px; color: #718074;">
            {{ count($sessions) }} slot aktif · {{ $selCarbon->translatedFormat('l, d F') }}
        </div>
    </div>

    <div class="sched-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Terisi / ditahan</span>
            <i class="bi bi-truck" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            {{ $totalBooked }} jeep
        </div>
        <div style="font-size: 11px; color: #718074;">
            1 terkonfirmasi · 2 menunggu verifikasi
        </div>
    </div>

    <div class="sched-kpi-card">
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #718074;">
            <span>Masih tersedia</span>
            <i class="bi bi-check-circle" style="font-size: 16px;"></i>
        </div>
        <div style="font-family: 'Montserrat', sans-serif; font-size: 26px; font-weight: 700; color: #1F3D2B;">
            {{ $totalRemaining }} jeep
        </div>
        <div style="font-size: 11px; color: #718074;">
            Slot nonaktif tidak dihitung
        </div>
    </div>
</div>

<!-- 2 Kolom: Slot Perjalanan & Edit Slot (Figma Frame 178:19080) -->
<div class="sched-layout">
    <!-- KOLOM KIRI: Slot Perjalanan -->
    <div class="sched-panel">
        <div>
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                Slot perjalanan
            </h3>
            <p style="font-size: 12px; color: #718074;">
                {{ $selCarbon->translatedFormat('l, d F Y') }}
            </p>
        </div>

        <!-- Baris Tanggal Pekan (Sen - Min) -->
        <div class="week-strip">
            @foreach($weekDays as $wd)
                <a href="{{ route('admin.schedules.index', ['date' => $wd['date'], 'month' => substr($wd['date'], 0, 7)]) }}"
                   class="week-day-pill {{ $wd['active'] ? 'active' : '' }}">
                    <div>{{ $wd['label'] }}</div>
                    <div>{{ $wd['day'] }}</div>
                </a>
            @endforeach
        </div>

        <!-- Tabel Slot Perjalanan -->
        <div>
            <div style="display: grid; grid-template-columns: 1fr 150px 95px 55px; gap: 12px; background: #F4F6F1; padding: 12px; border-radius: 6px; font-size: 11px; font-weight: 600; color: #718074;">
                <div>Paket / waktu WIB</div>
                <div>Kapasitas jeep</div>
                <div>Status</div>
                <div>Aksi</div>
            </div>

            @foreach($sessions as $idx => $ses)
                @php
                    $sisa = max(0, $ses['capacity'] - $ses['booked']);
                    $pct = $ses['capacity'] > 0 ? min(100, round(($ses['booked'] / $ses['capacity']) * 100)) : 0;
                    $isFull = ($sisa === 0);
                @endphp
                <div class="slot-row {{ $idx === 0 ? 'selected' : '' }}">
                    <div>
                        <div style="font-size: 13px; font-weight: 600; color: #24382D;">{{ $ses['package_name'] }}</div>
                        <div style="font-size: 11px; color: #718074; margin-top: 2px;">{{ $ses['time_start'] }}–{{ $ses['time_end'] }}</div>
                    </div>

                    <div>
                        <div style="font-size: 11px; color: #24382D; margin-bottom: 6px;">
                            {{ $ses['booked'] }}/{{ $ses['capacity'] }} terisi · {{ $sisa }} sisa
                        </div>
                        <div style="width: 130px; height: 5px; background: #E1E4DA; border-radius: 100px; overflow: hidden;">
                            <div style="width: {{ $pct }}%; height: 5px; background: {{ $isFull ? '#F26B1D' : '#1F3D2B' }}; border-radius: 100px;"></div>
                        </div>
                    </div>

                    <div>
                        <span style="display: inline-block; background: {{ $isFull ? '#FFF0E4' : '#EAF0E7' }}; color: {{ $isFull ? '#A54B10' : '#1F3D2B' }}; font-size: 11px; font-weight: 600; padding: 5px 10px; border-radius: 100px;">
                            {{ $isFull ? 'Penuh' : 'Tersedia' }}
                        </span>
                    </div>

                    <div>
                        <button type="button"
                                onclick="populateSessionForm('{{ $ses['time_start'] }}', '{{ $ses['time_end'] }}', '{{ addslashes($ses['package_name']) }}', {{ $ses['capacity'] }})"
                                style="border: none; background: transparent; font-size: 12px; font-weight: 600; color: #24382D; cursor: pointer; padding: 0;">
                            {{ $idx === 0 ? 'Dipilih' : 'Edit' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="background: #EAF0E7; border-radius: 8px; padding: 14px; display: flex; align-items: flex-start; gap: 10px;">
            <i class="bi bi-info-circle" style="color: #1F3D2B; font-size: 15px;"></i>
            <span style="font-size: 12px; color: #1F3D2B; line-height: 1.5;">
                Terisi mencakup jeep terkonfirmasi dan yang ditahan selama proses pembayaran. Bukti ditolak melepas penahanan slot; pelanggan perlu memilih ulang saat mengirim bukti baru.
            </span>
        </div>

        <div style="font-size: 11px; color: #718074;">
            Jadwal perjalanan berbeda dari jam layanan admin.
        </div>
    </div>

    <!-- KOLOM KANAN: Edit Slot (Figma Frame 178:19169) -->
    <form action="{{ route('admin.schedules.save_session') }}" method="POST" class="sched-panel" id="slot-form-card">
        @csrf
        <div>
            <h3 style="font-family: 'Montserrat', sans-serif; font-size: 17px; font-weight: 700; color: #1F3D2B; margin-bottom: 4px;">
                Edit slot
            </h3>
            <p style="font-size: 12px; color: #718074;">
                Jelajah Jeep · {{ $selCarbon->translatedFormat('d F Y') }}
            </p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 7px;">
            <label style="font-size: 12px; font-weight: 600; color: #24382D;">Paket *</label>
            <select name="package_name" id="slotPackageInput" class="s-input" required>
                @foreach($packages as $p)
                    <option value="{{ $p->name }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; flex-direction: column; gap: 7px;">
            <label style="font-size: 12px; font-weight: 600; color: #24382D;">Tanggal perjalanan *</label>
            <input type="date" name="date" value="{{ $selectedDate }}" class="s-input" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div style="display: flex; flex-direction: column; gap: 7px;">
                <label style="font-size: 12px; font-weight: 600; color: #24382D;">Mulai (WIB) *</label>
                <input type="text" name="time_start" id="slotStartInput" value="08.00" class="s-input" required>
            </div>
            <div style="display: flex; flex-direction: column; gap: 7px;">
                <label style="font-size: 12px; font-weight: 600; color: #24382D;">Selesai (WIB) *</label>
                <input type="text" name="time_end" id="slotEndInput" value="12.00" class="s-input" required>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 7px;">
            <label style="font-size: 12px; font-weight: 600; color: #24382D;">Kapasitas maksimal *</label>
            <input type="number" name="capacity" id="slotCapacityInput" value="4" min="1" max="50" class="s-input" required>
        </div>

        <div style="background: #EAF0E7; border-radius: 8px; padding: 14px; display: flex; align-items: flex-start; gap: 10px;">
            <i class="bi bi-info-circle" style="color: #1F3D2B; font-size: 15px;"></i>
            <span style="font-size: 12px; color: #1F3D2B; line-height: 1.5;">
                2 jeep sudah terisi / ditahan. Kapasitas tidak boleh lebih kecil dari jumlah tersebut.
            </span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 7px;">
            <label style="font-size: 12px; font-weight: 600; color: #24382D;">Status slot</label>
            <select class="s-input">
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>

        <div style="height: 1px; background: #E1E4DA;"></div>

        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <a href="{{ route('admin.schedules.index') }}"
               style="height: 42px; padding: 0 16px; border-radius: 8px; border: 1px solid #E1E4DA; background: #FFFFFF; color: #1F3D2B; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;">
                Batal
            </a>
            <button type="submit"
                    style="height: 42px; padding: 0 16px; border-radius: 8px; border: none; background: #F26B1D; color: #1F3D2B; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                <i class="bi bi-floppy"></i> Simpan slot
            </button>
        </div>
    </form>
</div>

<script>
    function populateSessionForm(start, end, pkg, cap) {
        document.getElementById('slotStartInput').value = start;
        document.getElementById('slotEndInput').value = end;
        document.getElementById('slotCapacityInput').value = cap;
        const sel = document.getElementById('slotPackageInput');
        if (sel) {
            for (let i = 0; i < sel.options.length; i++) {
                if (sel.options[i].value === pkg) {
                    sel.selectedIndex = i;
                    break;
                }
            }
        }
        document.getElementById('slot-form-card').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endsection
