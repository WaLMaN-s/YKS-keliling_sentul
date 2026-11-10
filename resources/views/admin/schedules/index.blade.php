@extends('layouts.admin', ['title' => 'Jadwal & Slot'])

@section('content')
<style>
    .sched-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 24px;
        align-items: start;
    }
    @media (max-width: 1100px) {
        .sched-grid {
            grid-template-columns: 1fr;
        }
    }
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
    }
    .cal-head {
        text-align: center;
        font-size: 0.74rem;
        font-weight: 700;
        color: #6B756E;
        padding: 6px 0;
    }
    .cal-cell {
        min-height: 74px;
        border: 1px solid #ECECE6;
        border-radius: 8px;
        padding: 8px;
        background: #FFFFFF;
        text-decoration: none;
        color: #1F3D2B;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.15s;
    }
    .cal-cell:hover {
        border-color: #1F3D2B;
    }
    .cal-cell.selected {
        border: 2px solid #1F3D2B;
        background: #F8F5EF;
    }
    .cal-cell.blocked {
        background: #F5F5F2;
        border-color: #DFE4DC;
        color: #8E988E;
    }
    .cal-cell.almost {
        background: #FFF9F5;
        border-color: #FBD5BD;
    }
    .cal-cell.full {
        background: #FFEBEE;
        border-color: #FFCDD2;
    }
    .form-input {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #DFE4DC;
        border-radius: 8px;
        font-size: 0.84rem;
        color: #1F3D2B;
        background: #FFF;
    }
</style>

<!-- Header Bar (Figma: Administrasi / Jadwal & Slot + Tambah slot) -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 4px;">Jadwal & Slot</h1>
        <p style="color: #6B756E; font-size: 0.9rem;">Jaga kapasitas perjalanan tetap aman dan terencana.</p>
    </div>

    <a href="#slot-form-card" class="btn-primary-yks" style="background: #F26B1D; padding: 9px 18px;">
        <i class="bi bi-plus-lg"></i> Tambah slot
    </a>
</div>

<div class="sched-grid">
    <!-- KOLOM KIRI: Kalender Bulanan & Daftar Sesi Tanggal Terpilih -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Card 1: Kalender Bulanan (Figma) -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 10px;">
                <div>
                    <h3 style="font-size: 1.1rem; font-weight: 700;">{{ $monthCarbon->translatedFormat('F Y') }}</h3>
                    <span style="font-size: 0.78rem; color: #6B756E;">Semua paket · kapasitas dalam slot reservasi</span>
                </div>

                <div style="display: flex; align-items: center; gap: 6px;">
                    @php
                        $prevMonth = $monthCarbon->copy()->subMonth()->format('Y-m');
                        $nextMonth = $monthCarbon->copy()->addMonth()->format('Y-m');
                    @endphp
                    <a href="{{ route('admin.schedules.index', ['month' => $prevMonth]) }}" class="btn-outline-yks" style="padding: 5px 10px;">←</a>
                    <a href="{{ route('admin.schedules.index', ['month' => '2026-11', 'date' => '2026-11-14']) }}" class="btn-outline-yks" style="padding: 5px 12px; font-size: 0.78rem;">Hari ini</a>
                    <a href="{{ route('admin.schedules.index', ['month' => $nextMonth]) }}" class="btn-outline-yks" style="padding: 5px 10px;">→</a>
                </div>
            </div>

            <!-- Grid Kalender 7 Hari -->
            <div class="cal-grid">
                @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
                    <div class="cal-head">{{ $dayName }}</div>
                @endforeach

                @for($empty = 1; $empty < $startDayOfWeek; $empty++)
                    <div></div>
                @endfor

                @foreach($calendarDays as $item)
                    @php
                        $isSel = ($item['date'] === $selectedDate);
                        $cellClass = '';
                        if ($item['status'] === 'Diblokir') $cellClass = 'blocked';
                        elseif ($item['status'] === 'Hampir penuh') $cellClass = 'almost';
                        elseif ($item['status'] === 'Penuh') $cellClass = 'full';
                    @endphp
                    <a href="{{ route('admin.schedules.index', ['month' => $monthCarbon->format('Y-m'), 'date' => $item['date']]) }}"
                       class="cal-cell {{ $cellClass }} {{ $isSel ? 'selected' : '' }}">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 700; font-size: 0.85rem;">{{ $item['day'] }}</span>
                            @if($isSel)
                                <span style="color: #1F3D2B; font-size: 0.75rem; font-weight: 800;">✓</span>
                            @endif
                        </div>

                        <div>
                            <div style="font-size: 0.7rem; font-weight: 600; color: {{ $item['status'] === 'Hampir penuh' ? '#F26B1D' : ($item['status'] === 'Penuh' ? '#C62828' : '#1F3D2B') }};">
                                {{ $item['is_blocked'] ? '0 slot' : $item['remaining'] . '/' . $item['capacity'] . ' sisa' }}
                            </div>
                            <div style="font-size: 0.65rem; color: #6B756E;">
                                {{ $item['status'] }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Legenda Kalender (Figma) -->
            <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #ECECE6; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 0.75rem; color: #6B756E;">
                <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                    <span><strong style="color: #1F3D2B;">●</strong> Tersedia</span>
                    <span><strong style="color: #F26B1D;">●</strong> Hampir penuh ≤2</span>
                    <span><strong style="color: #C62828;">●</strong> Penuh</span>
                    <span><strong style="color: #8E988E;">●</strong> Diblokir</span>
                </div>
                <span>Angka menunjukkan sisa slot / kapasitas harian.</span>
            </div>
        </div>

        <!-- Card 2: Tabel Sesi pada Tanggal Terpilih (Figma) -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 700;">
                        Sesi · {{ date('d F Y', strtotime($selectedDate)) }}
                    </h3>
                    <span style="font-size: 0.78rem; color: #6B756E;">
                        {{ $dailyCapacity }} slot harian · {{ $selectedDayInfo['booked'] ?? 0 }} terisi · {{ $selectedDayInfo['remaining'] ?? $dailyCapacity }} tersedia
                    </span>
                </div>
                <a href="#slot-form-card" class="btn-outline-yks" style="font-size: 0.78rem; padding: 6px 12px;">+ Tambah sesi</a>
            </div>

            @if(!empty($selectedDayInfo['is_blocked']))
                <div style="background: #FEEFE6; border-left: 4px solid #F26B1D; padding: 14px; border-radius: 8px; font-size: 0.84rem; color: #1F3D2B;">
                    <strong>Tanggal ini diblokir:</strong> {{ $selectedDayInfo['block_reason'] }}.<br>
                    Tidak ada reservasi baru pada tanggal ini. Semua sesi ditutup untuk pemesanan baru.
                </div>
            @else
                <table style="width: 100%; border-collapse: collapse; font-size: 0.84rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid #DFE4DC; text-align: left; color: #6B756E;">
                            <th style="padding: 10px 8px;">Sesi (WIB)</th>
                            <th style="padding: 10px 8px;">Paket</th>
                            <th style="padding: 10px 8px;">Kapasitas</th>
                            <th style="padding: 10px 8px;">Terisi</th>
                            <th style="padding: 10px 8px;">Status Sisa</th>
                            <th style="padding: 10px 8px; text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $ses)
                            @php
                                $sisa = max(0, $ses['capacity'] - $ses['booked']);
                            @endphp
                            <tr style="border-bottom: 1px solid #ECECE6;">
                                <td style="padding: 11px 8px; font-weight: 700; color: #1F3D2B;">
                                    {{ $ses['time_start'] }}–{{ $ses['time_end'] }}
                                </td>
                                <td style="padding: 11px 8px;">{{ $ses['package_name'] }}</td>
                                <td style="padding: 11px 8px;">{{ $ses['capacity'] }} slot</td>
                                <td style="padding: 11px 8px;">{{ $ses['booked'] }} slot</td>
                                <td style="padding: 11px 8px;">
                                    <span style="background: {{ $sisa <= 2 ? '#FEEFE6; color: #F26B1D;' : '#EAF0E7; color: #1F3D2B;' }} padding: 3px 9px; border-radius: 99px; font-size: 0.74rem; font-weight: 600;">
                                        Sisa {{ $sisa }}
                                    </span>
                                </td>
                                <td style="padding: 11px 8px; text-align: right;">
                                    <button type="button"
                                            onclick="populateSessionForm('{{ $ses['time_start'] }}', '{{ $ses['time_end'] }}', '{{ addslashes($ses['package_name']) }}', {{ $ses['capacity'] }})"
                                            style="border: 1px solid #DFE4DC; background: #FFF; padding: 4px 10px; border-radius: 6px; font-size: 0.76rem; font-weight: 600; color: #1F3D2B; cursor: pointer;">
                                        Ubah ↗
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- KOLOM KANAN: Form Ubah/Tambah Slot & Blokir Tanggal (Figma) -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Card Form Ubah / Tambah Slot -->
        <div class="stat-card" id="slot-form-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 12px; border-bottom: 1px solid #ECECE6; margin-bottom: 16px;">
                <div>
                    <span style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.06em; color: #6B756E; font-weight: 700;">
                        Ubah / Tambah slot
                    </span>
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #1F3D2B; margin-top: 2px;">
                        {{ date('l, d F Y', strtotime($selectedDate)) }}
                    </h3>
                </div>
                <span style="background: #FEEFE6; color: #F26B1D; font-size: 0.74rem; font-weight: 600; padding: 4px 10px; border-radius: 99px;">
                    Sisa {{ $selectedDayInfo['remaining'] ?? 2 }} slot
                </span>
            </div>

            <form action="{{ route('admin.schedules.save_session') }}" method="POST" style="display: flex; flex-direction: column; gap: 14px;">
                @csrf
                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 5px;">Tanggal <span style="color: #F26B1D;">*</span></label>
                    <input type="date" name="date" value="{{ $selectedDate }}" class="form-input" required>
                </div>

                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 5px;">Paket <span style="color: #F26B1D;">*</span></label>
                    <select name="package_name" id="slotPackageInput" class="form-input" required>
                        <option value="Semua Paket (Sesi Pagi)">Semua Paket (Sesi Pagi)</option>
                        <option value="Semua Paket (Sesi Sore)">Semua Paket (Sesi Sore)</option>
                        @foreach($packages as $p)
                            <option value="{{ $p->name }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 5px;">Jam mulai (WIB)</label>
                        <input type="text" name="time_start" id="slotStartInput" value="08.00" class="form-input" required>
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 5px;">Jam selesai (WIB)</label>
                        <input type="text" name="time_end" id="slotEndInput" value="13.00" class="form-input" required>
                    </div>
                </div>

                <div>
                    <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 5px;">Kapasitas sesi (reservasi) <span style="color: #F26B1D;">*</span></label>
                    <input type="number" name="capacity" id="slotCapacityInput" value="8" min="1" max="50" class="form-input" required>
                    <span style="font-size: 0.74rem; color: #6B756E; display: block; margin-top: 4px;">
                        Kapasitas tidak boleh di bawah jumlah terisi. Kapasitas dihitung per reservasi, bukan jumlah peserta.
                    </span>
                </div>

                <button type="submit" class="btn-primary-yks" style="justify-content: center; padding: 10px;">
                    <i class="bi bi-check2-circle"></i> Simpan slot
                </button>
            </form>
        </div>

        <!-- Card Blokir Tanggal (Pengecualian Jadwal Perjalanan - Figma) -->
        <div class="stat-card">
            <h3 style="font-size: 1.05rem; font-weight: 700; margin-bottom: 4px;">Blokir tanggal</h3>
            <p style="font-size: 0.78rem; color: #6B756E; margin-bottom: 14px;">Pengecualian jadwal perjalanan (perawatan armada / pemeriksaan jalur).</p>

            <!-- Daftar Tanggal yang Diblokir -->
            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px;">
                @forelse($blockedDates as $bDate => $bReason)
                    <div style="background: #F8F5EF; border: 1px solid #DFE4DC; border-radius: 8px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                        <div>
                            <div style="font-weight: 700; font-size: 0.84rem; color: #1F3D2B;">{{ date('d F Y', strtotime($bDate)) }}</div>
                            <div style="font-size: 0.75rem; color: #6B756E;">{{ $bReason }}</div>
                        </div>
                        <form action="{{ route('admin.schedules.unblock_date', $bDate) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #FFFFFF; border: 1px solid #DFE4DC; color: #1F3D2B; padding: 5px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; cursor: pointer;">
                                Buka blokir
                            </button>
                        </form>
                    </div>
                @empty
                    <div style="font-size: 0.8rem; color: #6B756E;">Tidak ada tanggal yang sedang diblokir.</div>
                @endforelse
            </div>

            <!-- Form Tambah Blokir Tanggal -->
            <form action="{{ route('admin.schedules.block_date') }}" method="POST" style="border-top: 1px solid #ECECE6; padding-top: 14px; display: flex; flex-direction: column; gap: 10px;">
                @csrf
                <div>
                    <label style="font-size: 0.78rem; font-weight: 600; display: block; margin-bottom: 4px;">Tanggal diblokir</label>
                    <input type="date" name="block_date" value="{{ $selectedDate }}" class="form-input" required>
                </div>
                <div>
                    <label style="font-size: 0.78rem; font-weight: 600; display: block; margin-bottom: 4px;">Alasan penutupan jalur / perawatan</label>
                    <input type="text" name="reason" placeholder="Contoh: Perawatan kendaraan dan pemeriksaan jalur" class="form-input" required>
                </div>
                <button type="submit" style="background: #304F3B; color: #FFFFFF; border: none; padding: 9px 14px; border-radius: 8px; font-size: 0.82rem; font-weight: 600; cursor: pointer;">
                    <i class="bi bi-slash-circle"></i> Blokir Tanggal Ini
                </button>
            </form>
        </div>
    </div>
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
